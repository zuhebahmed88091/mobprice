<?php

namespace App\Http\Controllers;

use App\Helpers\ScrapHelper;
use App\Models\Brand;
use App\Models\Mobile;
use App\Models\MobileCamera;
use App\Models\MobileImage;
use App\Models\MobilePrice;
// use App\Models\MobileRam;
use App\Models\MobileRegion;
// use App\Models\MobileStorage;

class ScraperController extends Controller
{
    public function scrapBrandUrls()
    {
        $url = 'https://www.gsmarena.com/makers.php3';
        $content = ScrapHelper::getContent($url);
        $content = ScrapHelper::reformatContent($content);
//        ScrapHelper::tr($content);exit;

        $segment = ScrapHelper::getSegment($content, '<div class="st-text">', '</table>');

        if ($segment && preg_match_all('/<a href=(.*?)>(.*?)<br>/i', $segment, $res)) {
            if (isset($res[1])) {
                for ($k = 0, $m = count($res[1]); $k < $m; $k++) {
                    $detailUrl = 'https://www.gsmarena.com/' . $res[1][$k];
                    $title = ScrapHelper::cleanText($res[2][$k]);

                    if ($title && $detailUrl) {
                        Brand::insertOrIgnore([
                            'title' => $title,
                            'detail_url' => $detailUrl,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
        }
    }

    function scrapMobileUrls()
    {
        $brands = Brand::where('status', 'Inactive')->orderBy('id')->get();
        if ($brands->isNotEmpty()) {
            foreach ($brands as $brand) {
                $brandId = $brand->id;
                $url = $brand->detail_url;

                while ($url) {
                    $content = ScrapHelper::getContent($url);
                    $content = ScrapHelper::reformatContent($content);

                    $segment = ScrapHelper::getSegment($content, '<div class="makers">', '</ul>');

                    if ($segment && preg_match_all('/<a href="(.*?)">/i', $segment, $res)) {
                        if (isset($res[1])) {
                            for ($k = 0, $m = count($res[1]); $k < $m; $k++) {
                                $detailUrl = 'https://www.gsmarena.com/' . $res[1][$k];

                                if ($detailUrl) {
                                    $originId = ScrapHelper::getOriginId($detailUrl);
                                    Mobile::insertOrIgnore([
                                        'detail_url' => $detailUrl,
                                        'origin_id' => $originId,
                                        'brand_id' => $brandId,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);
                                }
                            }
                        }
                    }

                    if (preg_match('/<a class="pages-next" href="(.*?)" title="Next page"><\/a>/i', $content, $res)) {
                        if (isset($res[1])) {
                            $url = 'https://www.gsmarena.com/' . trim($res[1]);
                        }
                    } else {
                        break;
                    }
                }

                $brand = Brand::find($brandId);
                $brand->update(['status' => 'Active']);
            }
        }
    }

    function extractDetailInformation($mobileId, $url, $oldStatus)
    {
        $dataSet = array();

        $content = ScrapHelper::getContent($url);
        $content = ScrapHelper::reformatContent($content);

        $segment = ScrapHelper::getSegment($content, '<div id="specs-list">', '<p class="note">');
//        ScrapHelper::tr($segment);exit;

        if (!empty($segment) && stripos($segment, 'scope="row">Tests</th>') !== false) {
            $segment = ScrapHelper::getSegment($content, '<div id="specs-list">', 'scope="row">Tests</th>');
        }

//        ScrapHelper::tr($segment);exit;

        $segment = preg_replace("/<tr(.*?)>/", "<tr>", $segment);
        $segment = preg_replace("/<td(.*?)>/", "<td>", $segment);
        $segment = preg_replace("/<th(.*?)>/", "<th>", $segment);
        $segment = str_replace('</a>', '', $segment);
        $segment = preg_replace('/<a[^>]+href[^>]+>/', '', $segment);

        if ($segment && preg_match_all('/<tr(.*?)>(.*?)<\/tr>/i', $segment, $res)) {
            if (isset($res[0])) {
                //ScrapHelper::tr($res);
                $blockTitle = '';
                for ($k = 0, $m = count($res[0]); $k < $m; $k++) {
                    $line = trim($res[0][$k]); //ScrapHelper::tr($line);exit;

                    // extract block title
                    if (preg_match('/<th>(.*?)<\/th>/i', $line, $lineData)) {
                        $blockTitle = trim($lineData[1]);
                    }

                    if (preg_match('/<td>(.*?)<\/td>\s*<td>(.*?)<\/td>/i', $line, $data)) {
                        if (isset($data[2])) {
                            $title = trim($data[1]);
                            $value = ScrapHelper::cleanText($data[2]);

                            if ($blockTitle == 'Display' && $title == 'Type') {
                                $dataSet['display_type'] = $value;
                            } else if ($title == '3.5mm jack') {
                                $dataSet['jack_3_5mm'] = $value;
                            } else if ($title == 'Price') {
                                $dataSet['price'] = html_entity_decode($value);
                            } else if ($blockTitle == 'Battery' && $title == 'Type') {
                                $dataSet['battery_type'] = $value;
                            } else if ($blockTitle == 'Main Camera') {
                                if ($title == 'Features') {
                                    $dataSet['mc_features'] = $value;
                                } else if ($title == 'Video') {
                                    $dataSet['mc_video'] = $value;
                                } else if (in_array($title, ['Single', 'Dual', 'Triple', 'Quad', 'Five', 'Six'])) {
                                    $dataSet['mc_numbers'] = $title;
                                    $dataSet['mc_resolutions'] = html_entity_decode($value);
                                } else {
                                    $dataSet[ScrapHelper::getKey($title)] = $value;
                                }
                            } else if ($blockTitle == 'Selfie camera') {
                                if ($title == 'Features') {
                                    $dataSet['sc_features'] = $value;
                                } else if ($title == 'Video') {
                                    $dataSet['sc_video'] = $value;
                                } else if (in_array($title, ['Single', 'Dual', 'Triple', 'Quad', 'Five', 'Six'])) {
                                    $dataSet['sc_numbers'] = $title;
                                    $dataSet['sc_resolutions'] = html_entity_decode($value);
                                } else {
                                    $dataSet[ScrapHelper::getKey($title)] = $value;
                                }
                            } else {
                                $dataSet[ScrapHelper::getKey($title)] = trim($value);
                            }
                        }
                    }
                }
            }
        } else {
            ScrapHelper::logData('FAIL PARSE MOBILE FULL SPEC', [
                'detail_url' => $url,
                'mobile_id' => $mobileId
            ]);
            exit('FAIL PARSE MOBILE FULL SPEC');
        }

         //ScrapHelper::tr($dataSet);exit;

        if (!empty($dataSet['status']) && $oldStatus != $dataSet['status']) {
            $image_url = '';
            $segment = ScrapHelper::getSegment($content, '<div class="specs-photo-main">', '</div>'); //ScrapHelper::tr($segment);exit;
            $segment = preg_replace('/alt="(.*?)"/', '', $segment);
            if (preg_match('/src=(.*?)>/i', $segment, $res)) {
                if (isset($res[1])) {
                    $image_url = trim($res[1]);
                }
            }

            if (preg_match('/<h1 class="specs-phone-name-title"(.*?)>(.*?)<\/h1>/i', $content, $res)) {
                if (isset($res[2])) {
                    $dataSet['title'] = trim($res[2]);
                }
            }

            $segment = ScrapHelper::getSegment($content, '<li class="article-info-meta-link light">', '</li>'); //Utility::tr($segment);exit;
            if (preg_match('/href=(.*?)><i class="head-icon icon-price">/i', $segment, $res)) {
                if (isset($res[1])) {
                    $dataSet['price_url'] = 'https://www.gsmarena.com/' . trim($res[1]);
                }
            }

            if (!empty($image_url) && !empty($dataSet['title']) && !empty($dataSet)) {
                if ($image_url) {
                    $ext = ScrapHelper::getFileExtension($image_url);
                    $folder = public_path('storage/mobiles') . '/' . $mobileId;
                    $fileName = '1' . $ext;
                    if (ScrapHelper::upload($image_url, $folder, $fileName)) {
                        // remove default image
                        MobileImage::where([
                            ['sorting', 1],
                            ['mobile_id', $mobileId]
                        ])->delete();

                        // insert default image
                        MobileImage::create([
                            'mobile_id' => $mobileId,
                            'filename' => $fileName,
                            'sorting' => 1
                        ]);
                    }
                }

                $dataSet['full_spec'] = 1;
                $dataSet['analyzed'] = 0;
                $mobile = Mobile::find($mobileId);
                $mobile->update($dataSet);
            }
        }
    }

    function scrapMobileFullSpec()
    {
        $mobiles = Mobile::where('full_spec', 0)->orderBy('id')->offset(0)->limit(1500)->get();
        if ($mobiles->isNotEmpty()) {
            foreach ($mobiles as $mobile) {
                $mobileId = $mobile->id;
                $detailUrl = $mobile->detail_url;
                $status = $mobile->status;
                if ($mobile->full_spec == 0) {
                    $status = null;
                }

                $this->extractDetailInformation($mobileId, $detailUrl, $status);
            }
        }
    }

    function analyzeSpec()
    {
        $mobiles = Mobile::where([['analyzed', 0], ['full_spec', 1]])->orderBy('id')->get();
        if ($mobiles->isNotEmpty()) {
            foreach ($mobiles as $mobile) {
                $data = ScrapHelper::prepareDataSet($mobile->toArray(), false);
                //ScrapHelper::tr($data);exit;

                // save main camera resolution
                if (!empty($data['mc_resolutions'])) {
                    MobileCamera::where([
                        ['mobile_id', $mobile->id],
                        ['type', 'Main']
                    ])->delete();

                    foreach ($data['mc_resolutions'] as $value) {
                        if (stripos($value, 'MP') !== false) {
                            MobileCamera::create([
                                'mobile_id' => $mobile->id,
                                'title' => $value,
                                'type' => 'Main',
                                'mp_resolutions' => floatval($value)
                            ]);
                        }
                    }
                }
                unset($data['mc_resolutions']);

                // save front camera resolution
                if (!empty($data['sc_resolutions'])) {
                    MobileCamera::where([
                        ['mobile_id', $mobile->id],
                        ['type', 'Front']
                    ])->delete();
                    foreach ($data['sc_resolutions'] as $value) {
                        if (stripos($value, 'MP') !== false) {
                            MobileCamera::create([
                                'mobile_id' => $mobile->id,
                                'title' => $value,
                                'type' => 'Front',
                                'mp_resolutions' => floatval($value)
                            ]);
                        }
                    }
                }
                unset($data['sc_resolutions']);

                // save rams
                // if (!empty($data['ram'])) {
                //     MobileRam::where('mobile_id', $mobile->id)->delete();

                //     foreach ($data['ram'] as $value) {
                //         $mbAmount = 0;
                //         if (stripos($value, 'GB') !== false) {
                //             $mbAmount = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT) * 1000;
                //         } else if (stripos($value, 'MB') !== false) {
                //             $mbAmount = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                //         }
                //         if ($mbAmount > 0) {
                //             MobileRam::create([
                //                 'mobile_id' => $mobile->id,
                //                 'title' => $value,
                //                 'mb_amount' => $mbAmount
                //             ]);
                //         }
                //     }
                // }
                // unset($data['ram']);

                // save storages
                // if (!empty($data['storage'])) {
                //     MobileStorage::where('mobile_id', $mobile->id)->delete();
                //     foreach ($data['storage'] as $value) {
                //         $mbAmount = 0;
                //         if (stripos($value, 'GB') !== false) {
                //             $mbAmount = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT) * 1000;
                //         } else if (stripos($value, 'MB') !== false) {
                //             $mbAmount = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                //         }
                //         if ($mbAmount > 0) {
                //             MobileStorage::create([
                //                 'mobile_id' => $mobile->id,
                //                 'title' => $value,
                //                 'mb_amount' => $mbAmount
                //             ]);
                //         }
                //     }
                // }
                // unset($data['storage']);

                // update analyzed fields
                $data['analyzed'] = 1;
                $mobile = Mobile::find($mobile->id);
                $mobile->update($data);
            }
        }
    }

    function getReleasedTime($time)
    {
        $releasedTime = 0;

        $time = str_replace("Q1", "March", $time);
        $time = str_replace("Q2", "June", $time);
        $time = str_replace("Q3", "September", $time);
        $time = str_replace("Q4", "December", $time);

        if (stripos($time, 'Exp. release') !== false) {
            $parts = explode("Exp. release", $time);
        } else {
            $parts = explode("Released", $time);
        }

        if (!empty($parts[1])) {
            $time = trim($parts[1]);
        } else {
            $parts = explode("announcement", $time);
            if (!empty($parts[1])) {
                $time = trim($parts[1]);
            }
        }

        $dateParts = array_map('trim', explode(',', $time));
        if (count($dateParts) > 1 && strtotime($dateParts[1] . ' ' . $dateParts[0]) != false) {
            $releasedTime = strtotime($dateParts[1] . ' ' . $dateParts[0]);
        } else if (strtotime('15.01.' . trim($time))) {
            $releasedTime = strtotime('15.01.' . trim($time));
        }

        return $releasedTime;
    }

    function updateSorting()
    {
        $mobiles = Mobile::where([
            ['full_spec', 1],
            ['sorting', 0]
        ])->orderBy('id')->get();

        if ($mobiles->isNotEmpty()) {
            foreach ($mobiles as $mobile) {
                $released_time = $this->getReleasedTime($mobile->status);
                $mobile->update(['sorting' => $released_time]);
            }
        }
    }

    function setAnnounceDateToReleasedTime()
    {
        $mobiles = Mobile::where([['full_spec', 1], ['sorting', 0]])->orderBy('id')->get();
        if ($mobiles->isNotEmpty()) {
            foreach ($mobiles as $mobile) {
                $released_time = $this->getReleasedTime($mobile->announced);
                $mobile->update(['sorting' => $released_time]);
            }
        }
    }

    function checkUpcomingMobileStatus()
    {
        $mobiles = Mobile::where(function ($query) {
            $query->orWhere('status', 'LIKE', '%Coming soon.%');
            $query->orWhere('status', 'LIKE', '%Rumored%');
        })->orderBy('id')->get();
        if ($mobiles->isNotEmpty()) {
            foreach ($mobiles as $mobile) {
                $this->extractDetailInformation($mobile->id, $mobile->detail_url, $mobile->status);
            }
        }
    }

    function extractLatestMobileUrl()
    {
        $url = 'https://www.gsmarena.com/';
        $content = ScrapHelper::getContent($url);
        $content = ScrapHelper::reformatContent($content);
        //ScrapHelper::tr($content);exit;

        $segment = ScrapHelper::getSegment($content, '<h4 class="section-heading">Latest devices</h4>', '</div>');
        //ScrapHelper::tr($segment);exit;

        if ($segment && preg_match_all('/<a href="(.*?)"(.*?)>(.*?)<br>(.*?)<\/a>/i', $segment, $res)) {
            if (isset($res[1])) {
                for ($k = 0, $m = count($res[1]); $k < $m; $k++) {
                    $detailUrl = 'https://www.gsmarena.com/' . $res[1][$k];
                    $title = ScrapHelper::cleanText($res[4][$k]);
                    $brandId = $this->getBrandIdFromTitle($title);
                    if ($detailUrl && $brandId) {
                        $originId = ScrapHelper::getOriginId($detailUrl);
                        Mobile::insertOrIgnore([
                            'detail_url' => $detailUrl,
                            'origin_id' => $originId,
                            'brand_id' => $brandId,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    } else {
                        ScrapHelper::logData('FAIL PARSE IN LATEST MOBILE', [
                            'detail_url' => $detailUrl,
                            'title' => $title,
                            'brand_id' => $brandId
                        ]);
                    }
                }
            }
        } else {
            ScrapHelper::logData('FAIL PARSE IN LATEST MOBILE', ['segment' => $segment]);
        }

        $segment = ScrapHelper::getSegment($content, '<h4 class="section-heading">In stores now</h4>', '</div>');
        //ScrapHelper::tr($segment);exit;

        if ($segment && preg_match_all('/<a href="(.*?)"(.*?)>(.*?)<br>(.*?)<\/a>/i', $segment, $res)) {
            if (isset($res[1])) {
                for ($k = 0, $m = count($res[1]); $k < $m; $k++) {
                    $detailUrl = 'https://www.gsmarena.com/' . $res[1][$k];
                    $title = ScrapHelper::cleanText($res[4][$k]);
                    $brandId = $this->getBrandIdFromTitle($title);
                    if ($detailUrl && $brandId) {
                        $originId = ScrapHelper::getOriginId($detailUrl);
                        Mobile::insertOrIgnore([
                            'detail_url' => $detailUrl,
                            'origin_id' => $originId,
                            'brand_id' => $brandId,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    } else {
                        ScrapHelper::logData('FAIL PARSE IN STORE MOBILE', [
                            'detail_url' => $detailUrl,
                            'title' => $title,
                            'brand_id' => $brandId
                        ]);
                    }
                }
            }
        } else {
            ScrapHelper::logData('FAIL PARSE IN STORE MOBILE', ['segment' => $segment]);
        }
    }

    function getBrandIdFromTitle($title)
    {
        $brandId = 0;
        $parts = explode(' ', $title);
        if (!empty($parts[0])) {
            $brandName = trim($parts[0]);

            $brand = Brand::where('title', $brandName)->first();
            if (!empty($brand)) {
                $brandId = $brand->id;
            }
        }
        return $brandId;
    }

    function scrapPrices()
    {
        $mobiles = Mobile::whereNotNull('price_url')->whereNull('price_updated')
            ->offset(0)->limit(100)->orderBy('id')->get();

        foreach ($mobiles as $mobile) {
            $mobileId = $mobile->id;
            $url = $mobile->price_url;
            //$url = 'https://www.gsmarena.com/samsung_galaxy_s21_ultra_5g-price-10596.php';

            $content = ScrapHelper::getContent($url);
            $content = ScrapHelper::reformatContent($content);

            $segment = ScrapHelper::getSegment($content, '<div class="pricing-container">', '<p class="note">');
            //ScrapHelper::tr($segment);exit;

            if ($segment && preg_match_all('/<table class="(.*?)">(.*?)<\/table>/i', $segment, $res)) {
                // Clean previous prices
                MobilePrice::where([['mobile_id', $mobileId], ['source', 1],])->delete();

                if (isset($res[2])) {
                    // ScrapHelper::tr($res[2]);exit;
                    for ($i = 0, $n = count($res[2]); $i < $n; $i++) {
                        $tblSegment = $res[2][$i];

                        $regionId = 0;
                        $conversionRate = 1;
                        if (preg_match('/<caption>(.*?)<\/caption>/i', $tblSegment, $tblRes)) {
                            if (isset($tblRes[1])) {
                                $region = trim($tblRes[1]);
                                $mobileRegion = MobileRegion::where('title', $region)->first();
                                if (!empty($mobileRegion)) {
                                    $regionId = $mobileRegion->id;
                                    $conversionRate = $mobileRegion->rate;
                                }
                            }
                        }

                        $variations = [];
                        $theadSegment = ScrapHelper::getSegment($tblSegment, '<thead>', '</thead>');
                        if ($theadSegment && preg_match_all('/<th>(.*?)<\/th>/i', $theadSegment, $theadRes)) {
                            for ($k = 0, $m = count($theadRes[1]); $k < $m; $k++) {
                                $variations[] = trim($theadRes[1][$k]);
                            }
                        }

                        $prices = [];
                        $tbodySegment = ScrapHelper::getSegment($tblSegment, '<tbody>', '</tbody>');
                        if ($tbodySegment && preg_match_all('/<tr>(.*?)<\/tr>/i', $tbodySegment, $tbodyRes)) {
                            for ($k = 0, $m = count($tbodyRes[1]); $k < $m; $k++) {

                                $tRow = trim($tbodyRes[1][$k]);

                                $store = '';
                                if (preg_match('/<th><img alt="(.*?)"/i', $tRow, $tRowRes)) {
                                    if (isset($tRowRes[1])) {
                                        $store = trim($tRowRes[1]);
                                    }
                                }

                                $storePrice = [
                                    'store' => $store
                                ];

                                if (preg_match_all('/<td>(.*?)<\/td>/i', $tRow, $tdRes)) {
                                    for ($j = 0, $l = count($tdRes[1]); $j < $l; $j++) {
                                        $price = '';
                                        if (preg_match('/<a href="(.*?)">(.*?)<\/a>/i', $tdRes[1][$j], $anchorRes)) {
                                            $price = trim($anchorRes[2]);
                                        }

                                        if (!empty($variations[$j + 1]) && !empty($price)) {
                                            $storePrice[$variations[$j + 1]] = $price;
                                        }
                                    }
                                    $prices[] = $storePrice;
                                }
                            }
                        }

                        foreach ($variations as $variation) {
                            $minPrice = -1;
                            $store = '';
                            foreach ($prices as $price) {
                                if (!empty($price[$variation])) {
                                    $floatPrice = preg_replace("/[^-0-9\.]/", '', html_entity_decode($price[$variation]));
                                    if ($minPrice == -1 || $floatPrice < $minPrice) {
                                        $minPrice = $floatPrice;
                                        $store = $price['store'];
                                    }
                                }
                            }

                            if ($minPrice != -1) {
                                MobilePrice::create([
                                    'mobile_id' => $mobileId,
                                    'region_id' => $regionId,
                                    'variation' => $variation,
                                    'price' => $minPrice,
                                    'usd_price' => ($conversionRate > 0 ? $minPrice / $conversionRate : 0),
                                    'store' => $store,
                                    'source' => 1
                                ]);
                            }
                        }
                    }
                }

                $minUsdPrice = MobilePrice::where('mobile_id', $mobileId)->min('usd_price');

                // Update price update time
                $mobile->update([
                    'price_updated' => date('Y-m-d H:i:s'),
                    'usd_min_price' => $minUsdPrice
                ]);
            } else {
                ScrapHelper::logData('FAIL PARSE IN PRICE URL', [
                    'mobile_id' => $mobileId,
                    'price_url' => $url,
                ]);
            }
        }
    }

    function saveMobileUrl($res) {
        if (isset($res[1])) {
            for ($k = 0, $m = count($res[1]); $k < $m; $k++) {
                $detailUrl = 'https://www.gsmarena.com/' . $res[1][$k];
                $title = ScrapHelper::cleanText($res[4][$k]);
                $brandId = $this->getBrandIdFromTitle($title);
                if ($detailUrl && $brandId) {
                    $originId = ScrapHelper::getOriginId($detailUrl);
                    Mobile::insertOrIgnore([
                        'detail_url' => $detailUrl,
                        'origin_id' => $originId,
                        'brand_id' => $brandId,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    ScrapHelper::logData('FAIL PARSE IN LATEST MOBILE', [
                        'detail_url' => $detailUrl,
                        'title' => $title,
                        'brand_id' => $brandId
                    ]);
                }
            }
        }
    }

    function scrapRumouredMobileUrl()
    {
        $url = 'https://www.gsmarena.com/rumored.php3';
        $content = ScrapHelper::getContent($url);
        $content = ScrapHelper::reformatContent($content);
        //ScrapHelper::tr($content);exit;

        $segment = ScrapHelper::getSegment($content, '<div class="makers">', '</ul>');
        //ScrapHelper::tr($segment);exit;

        if ($segment && preg_match_all('/<a href="(.*?)"(.*?)>(.*?)<strong><span>(.*?)<\/span><\/strong><\/a>/i', $segment, $res)) {
            $this->saveMobileUrl($res);
        } else {
            ScrapHelper::logData('FAIL PARSE IN LATEST MOBILE', ['segment' => $segment]);
        }
    }

    public function collectAllMobiles()
    {
        set_time_limit(0);
        $startTime = time();
        echo 'Script start on: ' . date('Y-m-d H:i:s', $startTime) . '<br>';

//        $this->scrapBrandUrls();
//        $this->scrapMobileUrls();
//        $this->scrapMobileFullSpec();
//        $this->scrapPrices();
//        $this->analyzeSpec();
//        $this->updateSorting();
//        $this->setAnnounceDateToReleasedTime();

        $endTime = time();
        echo 'Completed on: ' . date('Y-m-d H:i:s', $endTime) . '<br>';
        echo 'Taken Time: ' . ($endTime - $startTime) . ' Seconds';
    }

    public function rumoredMobiles()
    {
        set_time_limit(600);
        $startTime = time();

        $this->scrapRumouredMobileUrl();
        $this->scrapMobileFullSpec();
        $this->scrapPrices();
        $this->analyzeSpec();
        $this->updateSorting();
        $this->setAnnounceDateToReleasedTime();

        $endTime = time();
        ScrapHelper::logData('Rumored Mobile Crawling', [
            'method' => 'rumoredMobiles()',
            'start_on' => date('Y-m-d H:i:s', $startTime),
            'completed_on' => date('Y-m-d H:i:s', $endTime),
            'taken_time' => ($endTime - $startTime) . ' Seconds'
        ]);
    }

    public function latestMobiles()
    {
        set_time_limit(600);
        $startTime = time();

        $this->checkUpcomingMobileStatus();
        $this->analyzeSpec();
        $this->extractLatestMobileUrl();
        $this->scrapMobileFullSpec();
        $this->updateSorting();
        $this->setAnnounceDateToReleasedTime();

        $endTime = time();
        ScrapHelper::logData('Latest Mobile Crawling', [
            'method' => 'latestMobiles()',
            'start_on' => date('Y-m-d H:i:s', $startTime),
            'completed_on' => date('Y-m-d H:i:s', $endTime),
            'taken_time' => ($endTime - $startTime) . ' Seconds'
        ]);
    }
}
