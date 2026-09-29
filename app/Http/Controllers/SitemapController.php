<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Ad;
use App\Models\College;
use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * 1. الفهرس الرئيسي لكل الخرائط (Sitemap Index)
     */
    public function index(): Response
    {
        $baseUrl = url('/');
        $today = date('Y-m-d');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // خريطة الصفحات الثابتة
        $xml .= '<sitemap>';
        $xml .= '<loc>' . $baseUrl . '/sitemap-pages.xml</loc>';
        $xml .= '<lastmod>' . $today . '</lastmod>';
        $xml .= '</sitemap>';

        // خريطة الأخبار
        $xml .= '<sitemap>';
        $xml .= '<loc>' . $baseUrl . '/sitemap-news.xml</loc>';
        $xml .= '<lastmod>' . $today . '</lastmod>';
        $xml .= '</sitemap>';

        // خريطة الكليات
        $xml .= '<sitemap>';
        $xml .= '<loc>' . $baseUrl . '/sitemap-colleges.xml</loc>';
        $xml .= '<lastmod>' . $today . '</lastmod>';
        $xml .= '</sitemap>';

        $xml .= '</sitemapindex>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    /**
     * 2. خريطة الصفحات الأساسية (Pages Sitemap)
     */
    public function pages(): Response
    {
        $today = date('Y-m-d');

        // قائمة الصفحات المهمة في الموقع (عربي وإنجليزي)
        $links = [
            url('/'),
            url('/ar'),
            url('/about_sust'),
            url('/ar/about_sust'),
            url('/leadership'),
            url('/ar/leadership'),
            url('/sust_leaders'),
            url('/ar/sust_leaders'),
            url('/vice_chancellor_message'),
            url('/ar/vice_chancellor_message'),
            url('/news'),
            url('/ar/news'),
            url('/ads'),
            url('/ar/ads'),
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // إضافة الروابط الثابتة
        foreach ($links as $link) {
            $xml .= '<url>';
            $xml .= '<loc>' . $link . '</loc>';
            $xml .= '<lastmod>' . $today . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // إضافة الصفحات الديناميكية من قاعدة البيانات
        $pages = Page::select('slug', 'lang')->get();
        foreach ($pages as $p) {
            if (!empty($p->slug)) {
                $pageUrl = ($p->lang == 'ar') ? url('/ar/' . $p->slug) : url('/' . $p->slug);
                $xml .= '<url>';
                $xml .= '<loc>' . $pageUrl . '</loc>';
                $xml .= '<lastmod>' . $today . '</lastmod>';
                $xml .= '<changefreq>monthly</changefreq>';
                $xml .= '<priority>0.7</priority>';
                $xml .= '</url>';
            }
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    /**
     * 3. خريطة الأخبار والإعلانات (News & Ads Sitemap)
     */
    public function news(): Response
    {
        $today = date('Y-m-d');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // جلب آخر الأخبار الفعالة
        $allNews = News::where('active', 1)->latest()->limit(500)->get();
        foreach ($allNews as $item) {
            if (!empty($item->slug)) {
                $newsUrl = ($item->lang == 'ar') ? url('/ar/news/details/' . $item->slug) : url('/news/details/' . $item->slug);
                $xml .= '<url>';
                $xml .= '<loc>' . $newsUrl . '</loc>';
                $xml .= '<lastmod>' . ($item->updated_at ? $item->updated_at->format('Y-m-d') : $today) . '</lastmod>';
                $xml .= '<changefreq>weekly</changefreq>';
                $xml .= '<priority>0.9</priority>';
                $xml .= '</url>';
            }
        }

        // جلب آخر الإعلانات الفعالة
        $allAds = Ad::where('active', 1)->latest()->limit(200)->get();
        foreach ($allAds as $ad) {
            if (!empty($ad->slug)) {
                $adUrl = ($ad->lang == 'ar') ? url('/ar/ads/details/' . $ad->slug) : url('/ads/details/' . $ad->slug);
                $xml .= '<url>';
                $xml .= '<loc>' . $adUrl . '</loc>';
                $xml .= '<lastmod>' . ($ad->updated_at ? $ad->updated_at->format('Y-m-d') : $today) . '</lastmod>';
                $xml .= '<changefreq>weekly</changefreq>';
                $xml .= '<priority>0.8</priority>';
                $xml .= '</url>';
            }
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    /**
     * 4. خريطة الكليات والمعاهد (Colleges Sitemap)
     */
    public function colleges(): Response
    {
        $today = date('Y-m-d');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $colleges = College::where('active', 1)->get();
        foreach ($colleges as $college) {
            if (!empty($college->slug)) {
                // الرابط الإنجليزي
                $xml .= '<url>';
                $xml .= '<loc>' . url('/college/' . $college->slug) . '</loc>';
                $xml .= '<lastmod>' . $today . '</lastmod>';
                $xml .= '<priority>0.9</priority>';
                $xml .= '</url>';

                // الرابط العربي
                $xml .= '<url>';
                $xml .= '<loc>' . url('/ar/college/' . $college->slug) . '</loc>';
                $xml .= '<lastmod>' . $today . '</lastmod>';
                $xml .= '<priority>0.9</priority>';
                $xml .= '</url>';
            }
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }
}
