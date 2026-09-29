@php
    // 1. اللغة الافتراضية تعتمد بشكل قاطع على الرابط
    $isArabic = (request()->is('ar') || request()->is('ar/*'));

    // 2. اسم الموقع
    $siteName = $isArabic ? 'جامعة السودان للعلوم والتكنولوجيا' : 'Sudan University of Science and Technology';

    // 3. عنوان الصفحة: إذا تم تمرير title نستخدمه، وإلا نستخدم اسم الموقع
    $title = trim($__env->yieldContent('title'));
    $pageTitle = $title ? "$title | $siteName" : $siteName;

    // 4. استخراج كائن الخبر أو الإعلان أو الصفحة بشكل آمن سواء كان مفرداً أو Collection
    $singleNews = (isset($data['news']) && $data['news'] instanceof \Illuminate\Database\Eloquent\Model) ? $data['news'] : null;
    $singleAd = (isset($data['ads']) && $data['ads'] instanceof \Illuminate\Database\Eloquent\Model) ? $data['ads'] : null;
    $singlePage = (isset($data['page']) && $data['page'] instanceof \Illuminate\Database\Eloquent\Model) ? $data['page'] : null;

    // 5. وصف الصفحة: نحاول أخذ الوصف المخصص أولاً، ثم تفاصيل الخبر أو الإعلان أو الكلية
    $description = trim($__env->yieldContent('description'));

    if (!$description) {
        if ($singleNews && !empty($singleNews->detail)) {
            $description = strip_tags($singleNews->detail);
        } elseif ($singleAd && !empty($singleAd->detail)) {
            $description = strip_tags($singleAd->detail);
        } elseif ($singlePage && !empty($singlePage->detail)) {
            $description = strip_tags($singlePage->detail);
        } elseif (!empty($college->name)) {
            $description = $isArabic ? "كلية " . $college->name . " - جامعة السودان" : ($college->name_en ?? $college->name) . " - SUST";
        } else {
            $description = $isArabic 
                ? "الموقع الرسمي لجامعة السودان للعلوم والتكنولوجيا - البرامج الأكاديمية والبحوث العلمية"
                : "Official website of Sudan University of Science and Technology - Programs and Research";
        }
    }

    // قص الوصف لأول 160 حرف ليتناسب مع جوجل
    $pageDescription = \Illuminate\Support\Str::limit($description, 160, '...');

    // 6. الكلمات المفتاحية
    $keywords = trim($__env->yieldContent('keywords'));
    if (!$keywords) {
        if ($singleNews && !empty($singleNews->keywords)) {
            $keywords = $singleNews->keywords;
        } elseif ($singleAd && !empty($singleAd->keywords)) {
            $keywords = $singleAd->keywords;
        } else {
            $keywords = $isArabic ? "جامعة السودان, كليات, بحوث, تعليم" : "Sudan University, SUST, colleges";
        }
    }

    // 7. صورة المشاركة لمواقع التواصل
    $shareImage = versioned_asset('images/logo.png');

    try {
        if ($singleNews && !empty($singleNews->photos) && count($singleNews->photos) > 0 && !empty($singleNews->photos[0]->img)) {
            $shareImage = url($singleNews->photos[0]->img);
        } elseif ($singleAd && !empty($singleAd->photos) && count($singleAd->photos) > 0 && !empty($singleAd->photos[0]->img)) {
            $shareImage = url($singleAd->photos[0]->img);
        } elseif ($singlePage && !empty($singlePage->img)) {
            $shareImage = url($singlePage->img);
        }
    } catch (\Exception $e) {
        \Log::error("seo-head error: " . $e->getMessage() . " | singleNews is: " . (is_object($singleNews) ? get_class($singleNews) : gettype($singleNews)));
    }

    // 7. الرابط الحالي
    $pageUrl = url()->current();

    // 8. روابط اللغات التبادلية
    $arUrl = request()->is('ar*') ? $pageUrl : url('ar/' . ltrim(request()->path(), '/'));
    $enUrl = request()->is('ar*') ? url(preg_replace('#^ar/?#', '', request()->path())) : $pageUrl;
@endphp

<!-- عنوان الصفحة -->
<title>{{ $pageTitle }}</title>

<!-- وسوم جوجل الأساسية -->
<meta name="description" content="{{ $pageDescription }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $pageUrl }}">

<!-- وسوم اللغات -->
<link rel="alternate" hreflang="ar" href="{{ $arUrl }}">
<link rel="alternate" hreflang="en" href="{{ $enUrl }}">

<!-- وسوم فيسبوك وواتساب -->
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:image" content="{{ $shareImage }}">
<meta property="og:url" content="{{ $pageUrl }}">
<meta property="og:type" content="website">

<!-- وسوم تويتر -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $shareImage }}">

<!-- بيانات منظمة بسيطة لجوجل -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "EducationalOrganization",
    "name": "{{ $siteName }}",
    "url": "{{ url('/') }}",
    "logo": "{{ versioned_asset('images/logo.png') }}"
}
</script>

@yield('meta')
@yield('schema')
