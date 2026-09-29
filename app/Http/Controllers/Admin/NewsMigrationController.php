<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlbumPhoto;
use App\Models\News;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class NewsMigrationController extends Controller
{
    private const COLLEGE_ID = 1; // الإدارة
    private const ALBUM_ID = 2;   // أخبار و أحداث الجامعة

    /**
     * Parse arbitrary date string into Y-m-d.
     * Supports "August 24, 2025", "24 August 2025", "2025-08-24", etc.
     */
    private function parseDate(?string $dateStr): string
    {
        if (empty($dateStr)) {
            return Carbon::today()->format('Y-m-d');
        }

        try {
            return Carbon::parse(trim($dateStr))->format('Y-m-d');
        } catch (Exception $e) {
            return Carbon::today()->format('Y-m-d');
        }
    }

    /**
     * Download and process image from URL, storing original and thumbnail.
     * Returns an AlbumPhoto model instance on success.
     */
    private function processImageUrl(string $url, string $title, int $userId): ?AlbumPhoto
    {
        $url = trim($url);
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            // Fetch the image with timeout and SSL verify relaxed for legacy servers
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
                ])
                ->get($url);

            if (!$response->successful()) {
                return null;
            }

            $contents = $response->body();
            if (empty($contents)) {
                return null;
            }

            // Determine extension from URL or content
            $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH) ?? '');
            $extension = strtolower($pathInfo['extension'] ?? '');
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $extension = 'jpg';
            }

            $rand = hexdec(uniqid());
            $filename = $rand . '.' . $extension;
            $thumbname = $rand . '_thumb.' . $extension;

            // Ensure directories exist
            $storageTargetDir = storage_path('app/public/images/albums');
            $publicTargetDir = public_path('images/albums');
            $thumbDir = public_path('images/albums_thumbnail');

            if (!File::isDirectory($storageTargetDir)) {
                File::makeDirectory($storageTargetDir, 0775, true, true);
            }
            if (!File::isDirectory($publicTargetDir)) {
                File::makeDirectory($publicTargetDir, 0775, true, true);
            }
            if (!File::isDirectory($thumbDir)) {
                File::makeDirectory($thumbDir, 0775, true, true);
            }

            // Save original image in both storage and public
            File::put($storageTargetDir . '/' . $filename, $contents);
            File::put($publicTargetDir . '/' . $filename, $contents);

            // Generate thumbnail using Intervention Image
            try {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($contents);
                $image->scale(width: 300)->save($thumbDir . '/' . $thumbname);
            } catch (Exception $e) {
                // If Intervention fails to scale, copy original content as fallback thumbnail
                File::put($thumbDir . '/' . $thumbname, $contents);
            }

            $imgPath = 'images/albums/' . $filename;
            $thumbPath = 'images/albums_thumbnail/' . $thumbname;

            return AlbumPhoto::create([
                'user_id' => $userId,
                'album_id' => self::ALBUM_ID,
                'title' => Str::limit($title, 150),
                'title_en' => '',
                'img' => $imgPath,
                'thumb_img' => $thumbPath,
            ]);
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Import a single news item with one or more photo URLs.
     */
    public function migrateSingle(Request $request)
    {
        $request->validate([
            'college_id' => 'required|integer',
            'lang_mode' => 'required|in:ar,en,both',
            'title_ar' => 'required_if:lang_mode,ar,both|nullable|string|max:500',
            'detail_ar' => 'required_if:lang_mode,ar,both|nullable|string',
            'title_en' => 'required_if:lang_mode,en,both|nullable|string|max:500',
            'detail_en' => 'required_if:lang_mode,en,both|nullable|string',
            'news_date' => 'nullable|string',
            'keywords_ar' => 'nullable|string',
            'keywords_en' => 'nullable|string',
            'active' => 'nullable|boolean',
            'image_urls' => 'nullable',
        ]);

        try {
            $userId = Auth::id() ?? 1;
            $langMode = $request->input('lang_mode');
            $collegeId = $request->input('college_id');
            
            // Check if titles already exist in the database
            if ($langMode === 'ar' || $langMode === 'both') {
                $titleAr = trim($request->input('title_ar'));
                $existingNewsAr = News::where('title', $titleAr)->first();
                if ($existingNewsAr) {
                    return response()->json([
                        'status' => 409,
                        'message' => 'هذا الخبر (العربي) موجود مسبقاً بنفس العنوان.',
                        'existing_id' => $existingNewsAr->id
                    ], 409);
                }
            }
            if ($langMode === 'en' || $langMode === 'both') {
                $titleEn = trim($request->input('title_en'));
                $existingNewsEn = News::where('title', $titleEn)->first();
                if ($existingNewsEn) {
                    return response()->json([
                        'status' => 409,
                        'message' => 'هذا الخبر (الإنجليزي) موجود مسبقاً بنفس العنوان.',
                        'existing_id' => $existingNewsEn->id
                    ], 409);
                }
            }
            $formattedDate = $this->parseDate($request->input('news_date'));

            $formattedDate = $this->parseDate($request->input('news_date'));

            // Prepare photo URLs
            $urls = [];
            $rawUrls = $request->input('image_urls');
            if (is_array($rawUrls)) {
                foreach ($rawUrls as $item) {
                    if (is_string($item)) {
                        preg_match_all('#https?://[^\s,;"\'<>]+#i', $item, $matches);
                        if (!empty($matches[0])) {
                            foreach ($matches[0] as $m) {
                                $urls[] = $m;
                            }
                        } else {
                            $trimmed = trim($item);
                            if ($trimmed !== '') {
                                $urls[] = $trimmed;
                            }
                        }
                    }
                }
            } elseif (is_string($rawUrls) && trim($rawUrls) !== '') {
                preg_match_all('#https?://[^\s,;"\'<>]+#i', $rawUrls, $matches);
                if (!empty($matches[0])) {
                    $urls = $matches[0];
                } else {
                    $urls = preg_split('/[\r\n\s,]+/', trim($rawUrls));
                }
            }
            $urls = array_values(array_unique(array_filter($urls)));

            $active = $request->boolean('active', true) ? 1 : 0;
            $createdNews = [];

            // Title used for album naming if needed
            $mainTitle = $langMode === 'en' ? trim($request->input('title_en')) : trim($request->input('title_ar'));

            // Download photos and save to album
            $photoIds = [];
            foreach ($urls as $url) {
                $photo = $this->processImageUrl(trim($url), $mainTitle, $userId);
                if ($photo) {
                    $photoIds[] = $photo->id;
                }
            }

            // Create Arabic News
            if ($langMode === 'ar' || $langMode === 'both') {
                $titleAr = trim($request->input('title_ar'));
                $detailAr = $request->input('detail_ar');
                $keywordsAr = trim($request->input('keywords_ar') ?? '') ?: 'جامعة السودان للعلوم والتكنولوجيا';
                
                $newsAr = News::create([
                    'college_id' => $collegeId,
                    'title' => $titleAr,
                    'slug' => Str::slug($titleAr, '-', null),
                    'lang' => 1,
                    'news_date' => $formattedDate,
                    'detail_portion' => Str::limit(strip_tags($detailAr), 200) ?: '',
                    'detail' => $detailAr,
                    'keywords' => $keywordsAr,
                    'active' => $active,
                    'auth_id' => $userId,
                ]);

                if (!empty($photoIds)) {
                    $newsAr->photos()->sync($photoIds);
                }
                $createdNews[] = $newsAr;
            }

            // Create English News
            if ($langMode === 'en' || $langMode === 'both') {
                $titleEn = trim($request->input('title_en'));
                $detailEn = $request->input('detail_en');
                $keywordsEn = trim($request->input('keywords_en') ?? '') ?: 'Sudan University Of Science & Technology';
                
                $newsEn = News::create([
                    'college_id' => $collegeId,
                    'title' => $titleEn,
                    'slug' => Str::slug($titleEn, '-', null),
                    'lang' => 2,
                    'news_date' => $formattedDate,
                    'detail_portion' => Str::limit(strip_tags($detailEn), 200) ?: '',
                    'detail' => $detailEn,
                    'keywords' => $keywordsEn,
                    'active' => $active,
                    'auth_id' => $userId,
                ]);

                if (!empty($photoIds)) {
                    $newsEn->photos()->sync($photoIds);
                }
                $createdNews[] = $newsEn;
            }

            \App\Services\HomeCacheService::clearHomeCache();

            return response()->json([
                'status' => 201,
                'message' => 'تم نقل الخبر بنجاح!',
                'news' => $createdNews,
                'photos_count' => count($photoIds)
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'حدث خطأ أثناء نقل الخبر: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batch / Bulk import multiple news items.
     */
    public function migrateBatch(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string',
            'items.*.detail' => 'required|string',
            'items.*.news_date' => 'nullable|string',
            'items.*.detail_portion' => 'nullable|string',
            'items.*.image_urls' => 'nullable',
        ]);

        $userId = Auth::id() ?? 1;
        $items = $request->input('items', []);
        $successCount = 0;
        $skippedCount = 0;
        $failedCount = 0;
        $errors = [];
        $skippedTitles = [];

        foreach ($items as $index => $itemData) {
            try {
                $title = trim($itemData['title'] ?? '');
                $detail = $itemData['detail'] ?? '';
                if (empty($title) || empty($detail)) {
                    $failedCount++;
                    $errors[] = "الخبر رقم " . ($index + 1) . ": العنوان أو التفاصيل فارغة.";
                    continue;
                }

                // Check for duplicate title
                if (News::where('title', $title)->exists()) {
                    $skippedCount++;
                    $skippedTitles[] = $title;
                    continue;
                }

                $formattedDate = $this->parseDate($itemData['news_date'] ?? null);
                $lang = (int) ($itemData['lang'] ?? $request->input('lang', 1));

                // Handle images
                $urls = [];
                $rawUrls = $itemData['image_urls'] ?? [];
                if (is_array($rawUrls)) {
                    foreach ($rawUrls as $item) {
                        if (is_string($item)) {
                            preg_match_all('#https?://[^\s,;"\'<>]+#i', $item, $matches);
                            if (!empty($matches[0])) {
                                foreach ($matches[0] as $m) {
                                    $urls[] = $m;
                                }
                            } else {
                                $trimmed = trim($item);
                                if ($trimmed !== '') {
                                    $urls[] = $trimmed;
                                }
                            }
                        }
                    }
                } elseif (is_string($rawUrls) && trim($rawUrls) !== '') {
                    preg_match_all('#https?://[^\s,;"\'<>]+#i', $rawUrls, $matches);
                    if (!empty($matches[0])) {
                        $urls = $matches[0];
                    } else {
                        $urls = preg_split('/[\r\n\s,]+/', trim($rawUrls));
                    }
                }
                $urls = array_values(array_unique(array_filter($urls)));

                $photoIds = [];
                foreach ($urls as $url) {
                    $photo = $this->processImageUrl(trim($url), $title, $userId);
                    if ($photo) {
                        $photoIds[] = $photo->id;
                    }
                }

                $detailPortion = !empty($itemData['detail_portion'])
                    ? $itemData['detail_portion']
                    : Str::limit(strip_tags($detail), 200);

                $defaultKeywords = ($lang === 2)
                    ? 'Sudan University Of Science & Technology'
                    : 'جامعة السودان للعلوم والتكنولوجيا';
                $keywords = trim($itemData['keywords'] ?? '') ?: $defaultKeywords;

                $news = News::create([
                    'college_id' => self::COLLEGE_ID,
                    'title' => $title,
                    'slug' => Str::slug($title, '-', null),
                    'lang' => $lang,
                    'news_date' => $formattedDate,
                    'detail_portion' => $detailPortion ?: '',
                    'detail' => $detail,
                    'keywords' => $keywords,
                    'active' => 1,
                    'auth_id' => $userId,
                ]);

                if (!empty($photoIds)) {
                    $news->photos()->sync($photoIds);
                }

                $successCount++;
            } catch (Exception $e) {
                $failedCount++;
                $errors[] = "الخبر رقم " . ($index + 1) . " ({$itemData['title']}): " . $e->getMessage();
            }
        }

        if ($successCount > 0) {
            \App\Services\HomeCacheService::clearHomeCache();
        }

        $summary = "اكتمل النقل: تم استيراد {$successCount} خبر بنجاح.";
        if ($skippedCount > 0) {
            $summary .= " وتم تخطي {$skippedCount} خبر مكرر مسبقاً.";
        }
        if ($failedCount > 0) {
            $summary .= " وفشل {$failedCount} خبر.";
        }

        return response()->json([
            'status' => 200,
            'message' => $summary,
            'success_count' => $successCount,
            'skipped_count' => $skippedCount,
            'failed_count' => $failedCount,
            'skipped_titles' => $skippedTitles,
            'errors' => $errors,
        ]);
    }

    /**
     * Check if a news title already exists.
     */
    public function checkTitle(Request $request)
    {
        $langMode = $request->input('lang_mode');
        $titleAr = trim($request->input('title_ar') ?? '');
        $titleEn = trim($request->input('title_en') ?? '');
        
        $messages = [];
        $exists = false;
        
        if ($langMode === 'ar' || $langMode === 'both') {
            if (empty($titleAr)) {
                return response()->json(['status' => 400, 'exists' => false, 'message' => 'العنوان العربي فارغ'], 400);
            }
            $newsAr = News::where('title', $titleAr)->first();
            if ($newsAr) {
                $exists = true;
                $messages[] = 'العنوان العربي موجود مسبقاً (' . ($newsAr->news_date ?? 'غير محدد') . ')';
            }
        }
        
        if ($langMode === 'en' || $langMode === 'both') {
            if (empty($titleEn)) {
                return response()->json(['status' => 400, 'exists' => false, 'message' => 'العنوان الإنجليزي فارغ'], 400);
            }
            $newsEn = News::where('title', $titleEn)->first();
            if ($newsEn) {
                $exists = true;
                $messages[] = 'العنوان الإنجليزي موجود مسبقاً (' . ($newsEn->news_date ?? 'غير محدد') . ')';
            }
        }

        if ($exists) {
            return response()->json([
                'status' => 200,
                'exists' => true,
                'message' => implode(' و ', $messages),
            ]);
        }

        return response()->json([
            'status' => 200,
            'exists' => false,
            'message' => 'العناوين غير موجودة ومتاحة للإضافة.'
        ]);
    }
}

