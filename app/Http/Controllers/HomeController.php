<?php

namespace App\Http\Controllers;

use App\Library\MobileTransformer;
use App\Library\PhotoGallery;
use App\Models\Brand;
use App\Models\FilterTab;
use App\Models\Mobile;
use App\Models\MobilePrice;
use App\Models\MobileRegion;
use App\Models\News;
use App\Models\PopularComparison;
use App\Models\Rating;
use Cache;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Session;

class HomeController extends Controller
{
    public function media()
    {
        return view('media');
    }

    public function privacyPolicy()
    {
        return view('front_end.privacy_mobile');
    }

    public function termsConditions()
    {
        return view('front_end.terms_mobile');
    }

    public function aboutUs()
    {
        return view('front_end.about_mobile');
    }

    public function home()
    {
        $brands = Brand::where('status', 'Active')->orderBy('sorting')->limit(15)->get();
        $topBrands = $brands->take(5);

        $latestMobiles = Mobile::with('mobileImages', 'mobilePrices')
            ->where(function ($query) {
                $query->orWhere('status', 'LIKE', '%Coming soon.%');
                $query->orWhere('status', 'LIKE', '%Available%');
            })->where('published', 1)->orderBy('sorting', 'desc')->limit(25)->get();

        $popularMobiles = Mobile::with('mobileImages', 'mobilePrices')->where([
            ['published', '=', 1],
            ['updated_at', '>=', date('Y-m-d H:i:s', strtotime("-6 month"))],
        ])->orderBy('view_count', 'desc')->orderBy('mobiles.sorting', 'DESC')->limit(25)->get();

        $rumoredMobiles = Mobile::with('mobileImages', 'mobilePrices')->where([
            ['published', '=', 1],
            ['status', 'LIKE', '%Rumored%'],
        ])->orderBy('updated_at', 'desc')->limit(25)->get();

        $popularComparisons = PopularComparison::with('mobile1', 'mobile1.mobileImages', 'mobile2', 'mobile2.mobileImages')
            ->orderBy('view_count', 'DESC')
            ->limit(20)->get();

        $allNews = News::where('status', 'Active')->orderBy('id', 'DESC')->limit(10)->get();
        $currency = Session::get('currency');
        $currency_rate = MobileRegion::where('iso_code', $currency)->first();
        $maxPrice = ceil(MobilePrice::where('status', 'Active')->max('usd_price'));
        $maxPrices = (round($maxPrice, strlen($maxPrice) * -0.5)) * $currency_rate->rate;

        $length1 = ltrim(strlen($maxPrices), '.');
        $length2 = ltrim(floor($length1 / 2), '.');
        if ($length1 % 2 == 1) {
            $divider = str_split($maxPrices, $length2 + 1);
        } else {
            $divider = str_split($maxPrices, $length2);
        }
        if ($divider[1] != 0) {
            $var = $divider[0] + 1;
            $max = $var . "" . str_repeat(0, $length2);
        } else {
            $var = $divider[0];
            $max = $var . "" . $divider[1];
        }

        return view('front_end.home', compact(
            'topBrands',
            'latestMobiles',
            'popularMobiles',
            'rumoredMobiles',
            'popularComparisons',
            'allNews',
            'currency',
            'currency_rate',
            'maxPrice',
            'maxPrices',
            'brands',
            'max'
        ));
    }

    public function compare(Request $request)
    {
        if ($request->ajax()) {
            return $this->searchList($request);
        }

        $sMobiles = Mobile::with('mobileImages')->where([
            ['published', '=', 1],
            ['status', 'like', '%Available%'],
        ])->orderBy('sorting', 'desc')->limit(4)->get();

        $serial = 1;
        $s1Mobile = null;
        $s2Mobile = null;
        $s3Mobile = null;
        $s4Mobile = null;
        foreach ($sMobiles as $sMobile) {
            if ($serial == 1) {
                $s1Mobile = $sMobile;
            } else if ($serial == 2) {
                $s2Mobile = $sMobile;
            } else if ($serial == 3) {
                $s3Mobile = $sMobile;
            } else if ($serial == 4) {
                $s4Mobile = $sMobile;
            }
            $serial++;
        }

        $c1Mobile = null;
        if (!empty($request->c1)) {
            $c1Mobile = Mobile::find($request->c1);
        }

        $c2Mobile = null;
        if (!empty($request->c2)) {
            $c2Mobile = Mobile::find($request->c2);
        }

        $c3Mobile = null;
        if (!empty($request->c3)) {
            $c3Mobile = Mobile::find($request->c3);
        }

        $c4Mobile = null;
        if (!empty($request->c4)) {
            $c4Mobile = Mobile::find($request->c4);
        }

        $userIp = $request->ip();
        $comparisonKeys = Session::get('comparisonKey', []);

        if (!empty($request->c1) && !empty($request->c2) && $request->c1 != $request->c2) {
            $this->setCompareCount($comparisonKeys, $request->c1, $request->c2, $userIp);
        }

        if (!empty($request->c1) && !empty($request->c3) && $request->c1 != $request->c3) {
            $this->setCompareCount($comparisonKeys, $request->c1, $request->c3, $userIp);
        }

        if (!empty($request->c1) && !empty($request->c4) && $request->c1 != $request->c4) {
            $this->setCompareCount($comparisonKeys, $request->c1, $request->c4, $userIp);
        }

        if (!empty($request->c2) && !empty($request->c3) && $request->c2 != $request->c3) {
            $this->setCompareCount($comparisonKeys, $request->c2, $request->c3, $userIp);
        }

        if (!empty($request->c2) && !empty($request->c4) && $request->c2 != $request->c4) {
            $this->setCompareCount($comparisonKeys, $request->c2, $request->c4, $userIp);
        }

        if (!empty($request->c3) && !empty($request->c4) && $request->c3 != $request->c4) {
            $this->setCompareCount($comparisonKeys, $request->c3, $request->c4, $userIp);
        }

        if ($request->wantsJson()) {
            return response()->json('Success', Response::HTTP_OK);
        }

        return view('front_end.compare', compact(
            'c1Mobile',
            'c2Mobile',
            'c3Mobile',
            'c4Mobile',
            's1Mobile',
            's2Mobile',
            's3Mobile',
            's4Mobile'
        ));
    }

    public function setCompareCount($comparisonKeys, $item1, $item2, $userIp)
    {
        $popularComparison = PopularComparison::updateOrCreate([
            'mobile1_id' => $item1,
            'mobile2_id' => $item2,
        ]);
        $compareKey = $userIp . '_' . $item1 . '|' . $item2;

        if (empty($comparisonKeys) || !in_array($compareKey, $comparisonKeys)) {
            $comparisonKeys[] = $compareKey;
            Session::put('comparisonKey', $comparisonKeys);
            $popularComparison->update(['view_count' => $popularComparison->view_count + 1]);
        }
    }

    public function filters()
    {
        $filters = Cache::remember('filters', 1800, function () {
            $filters = [];
            $filterTabs = FilterTab::with('filterSections', 'filterSections.filterOptions')
                ->where('status', 'Active')
                ->orderBy('sorting', 'ASC')
                ->get();

            foreach ($filterTabs as $filterTab) {
                $filterSections = $filterTab->filterSections;
                $sections = [];
                foreach ($filterSections as $filterSection) {

                    $filterOptions = $filterSection->filterOptions;
                    $options = [];
                    if ($filterSection->type == 'TableBrands') {
                        $brands = Brand::where([
                            ['total_item', '>', 0],
                            ['status', 'Active'],
                        ])->orderBy('sorting', 'ASC')->get();
                        foreach ($brands as $brand) {
                            $options[] = [
                                'id' => $brand->id,
                                'name' => $brand->title,
                                'value' => $brand->id,
                                'sorting' => $brand->sorting,
                            ];
                        }
                        $filterSection->type = 'Checkbox';
                    } else if ($filterSection->type == 'Slider') {
                        foreach ($filterOptions as $filterOption) {
                            list($min, $max, $step) = explode('|', $filterOption->value);
                            if ($filterSection->field == 'weight_range') {
                                $max = Mobile::max('gm_weight');
                            }
                            if ($filterSection->field == 'battery_capacity') {
                                $max = Mobile::max('mah_battery');
                            }
                            $options = [
                                'name' => $filterOption->name,
                                'min' => $min,
                                'max' => $max,
                                'step' => $step,
                                'sorting' => $filterOption->sorting,
                            ];
                        }

                    } else {
                        foreach ($filterOptions as $filterOption) {
                            $options[] = [
                                'id' => $filterOption->id,
                                'name' => $filterOption->name,
                                'value' => $filterOption->value,
                                'sorting' => $filterOption->sorting,
                            ];
                        }
                    }

                    $sections[] = [
                        'header' => $filterSection->show_label == 'Yes' ? $filterSection->label : '',
                        'type' => $filterSection->type,
                        'field' => $filterSection->field,
                        'options' => $options,
                    ];
                }

                $tab = [
                    'tab' => $filterTab->title,
                    'sorting' => $filterTab->sorting,
                    'sections' => $sections,
                ];

                $filters[] = $tab;
            }
            return $filters;
        });

        if (request()->wantsJson()) {
            return response()->json($filters, Response::HTTP_OK);
        }

        return $filters;
    }

    public function setUsdMinPrice()
    {
        $mobiles = Mobile::whereNull('price_url')->where('usd_min_price', '=', 0)->get();
        foreach ($mobiles as $mobile) {
            $lowestPrice = $mobile->prices->min('usd_price');
            if ($lowestPrice) {
                $mobile->update([
                    'usd_min_price' => $lowestPrice,
                ]);
            }
        }
    }

    public function filteredMobiles(Request $request)
    {
        $limit = 25;
        if ($request->input('limit')) {
            $limit = $request->input('limit');
        }
        $query = $this->getQuery($request);
        $mobiles = $query->select('mobiles.*')
            ->with('mobileImages', 'cameras', 'rams', 'mobileStorage')
            ->paginate($limit);

        if ($request->wantsJson()) {
            return response()->json($mobiles->items(), Response::HTTP_OK);
        }

        if ($request->ajax()) {
            return view('front_end.data_list', compact('mobiles'));
        }

        $filters = $this->filters();
        $currency = Session::get('currency');
        $currency_rate = MobileRegion::where('iso_code', $currency)->first();
        $maxPrice = ceil(MobilePrice::where('status', 'Active')->max('usd_price'));
        $maxPrices = (round($maxPrice, strlen($maxPrice) * -0.5)) * $currency_rate->rate;
        $length1 = ltrim(strlen($maxPrices), '.');
        $length2 = ltrim(floor($length1 / 2), '.');
        if ($length1 % 2 == 1) {
            $divider = str_split($maxPrices, $length2 + 1);
        } else {
            $divider = str_split($maxPrices, $length2);
        }
        if ($divider[1] != 0) {
            $var = $divider[0] + 1;
            $max = $var . "" . str_repeat(0, $length2);
        } else {
            $var = $divider[0];
            $max = $var . "" . $divider[1];
        }

        return view('front_end.mobile_list', compact('filters', 'mobiles', 'currency_rate', 'maxPrices', 'max'));
    }

    /**
     * Return finalize query builder.
     *
     * @param Request $request
     *
     * @return Builder
     */
    public function getQuery(Request $request): Builder
    {
        $query = Mobile::query();

        if (!empty($request->name)) {
            $query->where('mobiles.title', 'like', '%' . $request->name . '%');
        }

        if (!empty($request->brands)) {
            $mobileIds = explode('|', $request->brands);
            $query->whereIn('mobiles.brand_id', $mobileIds);
        }

        if (!empty($request->price_range)) {
            list($min, $max) = explode('|', $request->price_range, 2);
            $currency = Session::get('currency');
            $currencyRate = MobileRegion::where('iso_code', $currency)->value('rate');
            $min = $min / $currencyRate;
            $max = $max / $currencyRate;
            $query->where('usd_min_price', '>=', $min)->where('usd_min_price', '<=', $max);
        }

        if (!empty($request->market_status)) {
            $statuses = explode('|', $request->market_status);
            $query->where(function ($subQuery) use ($statuses) {
                foreach ($statuses as $status) {
                    $subQuery->orWhere('mobiles.status', 'like', '%' . $status . '%');
                }
            });
        }

        if (!empty($request->launched_within)) {
            if (strpos($request->launched_within, '-') !== false) {
                $startDate = date('Y-m-d', strtotime($request->launched_within));
                $endDate = date('Y-m-d');
            } else {
                $startDate = date('Y-m-d');
                $endDate = date('Y-m-d', strtotime($request->launched_within));
            }
            $query->whereBetween('mobiles.created_at', [$startDate, $endDate]);
        }

        if (!empty($request->ram_range)) {
            list($min, $max) = explode('-', $request->ram_range);
            $query->join('mobile_rams', function ($join) {
                $join->on('mobiles.id', '=', 'mobile_rams.mobile_id');
            })->where('mobile_rams.mb_amount', '>=', $min)->where('mobile_rams.mb_amount', '<=', $max);
        }

        if (!empty($request->storage_range)) {
            list($min, $max) = explode('-', $request->storage_range);
            $query->join('mobile_storages', function ($join) {
                $join->on('mobiles.id', '=', 'mobile_storages.mobile_id');
            })->where('mobile_storages.mb_amount', '>=', $min)
                ->where('mobile_storages.mb_amount', '<=', $max);
        }

        if (!empty($request->weight_range)) {
            list($min, $max) = explode('|', $request->weight_range, 2);
            $query->where('mobiles.gm_weight', '>=', $min)
                ->where('mobiles.gm_weight', '<=', $max);
        }

        if (!empty($request->network_type)) {
            $networkTypes = explode('|', $request->network_type);
            $query->where(function ($subQuery) use ($networkTypes) {
                foreach ($networkTypes as $networkType) {
                    $subQuery->orWhere('mobiles.technology', 'like', '%' . $networkType . '%');
                }
            });
        }

        if (!empty($request->sim_size)) {
            $simSizes = explode('|', $request->sim_size);
            $query->where(function ($subQuery) use ($simSizes) {
                foreach ($simSizes as $simSize) {
                    $subQuery->orWhere('mobiles.sim', 'like', '%' . $simSize . '%');
                }
            });
        }

        if (!empty($request->sim_support)) {
            $simSupports = explode('|', $request->sim_support);
            $query->where(function ($subQuery) use ($simSupports) {
                foreach ($simSupports as $simSupport) {
                    $subQuery->orWhere('mobiles.sim', 'like', '%' . $simSupport . '%');
                }
            });
        }

        if (!empty($request->battery_capacity)) {
            list($min, $max) = explode('|', $request->battery_capacity, 2);
            $query->where('mobiles.mah_battery', '>=', $min)
                ->where('mobiles.mah_battery', '<=', $max);
        }

        if (!empty($request->battery_others)) {
            $batteryOthers = explode('|', $request->battery_others);
            $query->where(function ($subQuery) use ($batteryOthers) {
                foreach ($batteryOthers as $batteryOther) {
                    $subQuery->orWhere('mobiles.charging', 'like', '%' . $batteryOther . '%');
                }
            });
        }

        if (!empty($request->screen_size)) {
            $screenSizes = explode('|', $request->screen_size);
            foreach ($screenSizes as $screenSize) {
                $query->orWhere(function ($subQuery) use ($screenSize) {
                    list($min, $max) = explode('-', $screenSize);
                    $subQuery->where('mobiles.inch_size', '>=', $min)
                        ->where('mobiles.inch_size', '<=', $max);
                });
            }
        }

        if (!empty($request->pixel_density)) {
            $query->where('mobiles.px_density', '>=', $request->pixel_density);
        }

        if (!empty($request->screen_features)) {
            $screenFeatures = explode('|', $request->screen_features);
            $query->where(function ($subQuery) use ($screenFeatures) {
                foreach ($screenFeatures as $screenFeature) {
                    if ($screenFeature == 'Gorilla Glass') {
                        $subQuery->orWhere('mobiles.protection', 'like', '%' . $screenFeature . '%');
                    } else {
                        $subQuery->orWhere('mobiles.display_type', 'like', '%' . $screenFeature . '%');
                    }
                }
            });
        }

        if (!empty($request->screen_resolution)) {
            $screenResolutions = explode('|', $request->screen_resolution);
            $query->where(function ($subQuery) use ($screenResolutions) {
                foreach ($screenResolutions as $screenResolution) {
                    $subQuery->orWhere(function ($subQuery1) use ($screenResolution) {
                        list($min, $max) = explode('-', $screenResolution);
                        $subQuery1->where('mobiles.px_resolution', '>=', $min)
                            ->where('mobiles.px_resolution', '<=', $max);
                    });
                }
            });
        }

        if (!empty($request->refresh_rate)) {
            $refreshRates = explode('|', $request->refresh_rate);
            $query->where(function ($subQuery) use ($refreshRates) {
                foreach ($refreshRates as $refreshRate) {
                    $subQuery->orWhere(function ($subQuery1) use ($refreshRate) {
                        list($min, $max) = explode('-', $refreshRate);
                        $subQuery1->where('mobiles.hz_refresh_rate', '>=', $min)
                            ->where('mobiles.hz_refresh_rate', '<=', $max);
                    });
                }
            });
        }

        if (!empty($request->processor_speed)) {
            list($min, $max) = explode('-', $request->processor_speed);
            $query->where('mobiles.mhz_processor_speed', '>=', $min)
                ->where('mobiles.mhz_processor_speed', '<=', $max);
        }

        if (!empty($request->system_on_chip)) {
            $systemOnChips = explode('|', $request->system_on_chip);
            $query->where(function ($subQuery) use ($systemOnChips) {
                foreach ($systemOnChips as $systemOnChip) {
                    $subQuery->orWhere('mobiles.chipset', 'like', '%' . $systemOnChip . '%');
                }
            });
        }

        if (!empty($request->processor_cores)) {
            $processorCores = explode('|', $request->processor_cores);
            $query->where(function ($subQuery) use ($processorCores) {
                foreach ($processorCores as $processorCore) {
                    $subQuery->orWhere('mobiles.cpu', 'like', '%' . $processorCore . '%');
                }
            });
        }

        if (!empty($request->mc_resolution) || !empty($request->sc_resolution)) {
            $query->join('mobile_cameras', function ($join) {
                $join->on('mobiles.id', '=', 'mobile_cameras.mobile_id');
            });
        }

        if (!empty($request->mc_resolution)) {
            list($min, $max) = explode('-', $request->mc_resolution);
            $query->where('mobile_cameras.mp_resolutions', '>=', $min)
                ->where('mobile_cameras.mp_resolutions', '<=', $max)
                ->where('mobile_cameras.type', '=', 'Main');
        }

        if (!empty($request->mc_number)) {
            $mcNumbers = explode('|', $request->mc_number);
            $query->where(function ($subQuery) use ($mcNumbers) {
                foreach ($mcNumbers as $mcNumber) {
                    $subQuery->orWhere('mobiles.mc_numbers', 'like', '%' . $mcNumber . '%');
                }
            });
        }

        if (!empty($request->mc_features)) {
            $mcFeatures = explode('|', $request->mc_features);
            $query->where(function ($subQuery) use ($mcFeatures) {
                foreach ($mcFeatures as $mcFeature) {
                    if (in_array($mcFeature, ['HDR', 'panorama', 'flash'])) {
                        $subQuery->orWhere('mobiles.mc_features', 'like', '%' . $mcFeature . '%');
                    } else {
                        $subQuery->orWhere('mobiles.mc_resolutions', 'like', '%' . $mcFeature . '%');
                    }
                }
            });
        }

        if (!empty($request->mc_video)) {
            $mcVideos = explode('|', $request->mc_video);
            $query->where(function ($subQuery) use ($mcVideos) {
                foreach ($mcVideos as $mcVideo) {
                    $subQuery->orWhere('mobiles.mc_video', 'like', '%' . $mcVideo . '%');
                }
            });
        }

        if (!empty($request->sc_resolution)) {
            list($min, $max) = explode('-', $request->sc_resolution);
            $query->orwhere('mobile_cameras.mp_resolutions', '>=', $min)
                ->where('mobile_cameras.mp_resolutions', '<=', $max)
                ->where('mobile_cameras.type', '=', 'Front');
        }

        if (!empty($request->sc_number)) {
            $scNumbers = explode('|', $request->sc_number);
            $query->where(function ($subQuery) use ($scNumbers) {
                foreach ($scNumbers as $scNumber) {
                    $subQuery->orWhere('mobiles.sc_numbers', 'like', '%' . $scNumber . '%');
                }
            });
        }

        if (!empty($request->sc_features)) {
            $scFeatures = explode('|', $request->sc_features);
            $query->where(function ($subQuery) use ($scFeatures) {
                foreach ($scFeatures as $scFeature) {
                    if (in_array($scFeature, ['HDR', 'panorama', 'flash'])) {
                        $subQuery->orWhere('mobiles.sc_features', 'like', '%' . $scFeature . '%');
                    } else {
                        $subQuery->orWhere('mobiles.sc_resolutions', 'like', '%' . $scFeature . '%');
                    }
                }
            });
        }

        if (!empty($request->connectivity)) {
            $connectivityList = explode('|', $request->connectivity);
            $query->where(function ($subQuery) use ($connectivityList) {
                foreach ($connectivityList as $connectivity) {
                    if ($connectivity == 'Wi-Fi') {
                        $subQuery->orWhere('mobiles.wlan', 'like', '%' . $connectivity . '%');
                    } else if ($connectivity == 'NFC') {
                        $subQuery->orWhere('mobiles.nfc', 'like', '%Yes%');
                    } else if ($connectivity == 'GPS') {
                        $subQuery->orWhere('mobiles.gps', 'like', '%Yes%');
                    } else if ($connectivity == 'Radio') {
                        $subQuery->orWhere('mobiles.radio', 'like', '%Yes%');
                    } else if ($connectivity == 'Bluetooth') {
                        $subQuery->orWhere('mobiles.bluetooth', '!=', 'No');
                    } else if ($connectivity == 'USB Type-C') {
                        $subQuery->orWhere('mobiles.usb', 'like', '%' . $connectivity . '%');
                    } else if ($connectivity == 'Infrared') {
                        $subQuery->orWhere('mobiles.infrared_port', 'like', '%Yes%');
                    }
                }
            });
        }

        if (!empty($request->sensors)) {
            $sensors = explode('|', $request->sensors);
            $query->where(function ($subQuery) use ($sensors) {
                foreach ($sensors as $sensor) {
                    $subQuery->orWhere('mobiles.sensors', 'like', '%' . $sensor . '%');
                }
            });
        }

        if (!empty($request->android)) {
            $osVersions = explode('|', $request->android);
            $query->where(function ($subQuery) use ($osVersions) {
                foreach ($osVersions as $osVersion) {
                    $subQuery->orWhere('mobiles.os', 'like', '%' . $osVersion . '%');
                }
            });
        }

        if (!empty($request->ios)) {
            $osVersions = explode('|', $request->ios);
            $query->where(function ($subQuery) use ($osVersions) {
                foreach ($osVersions as $osVersion) {
                    $subQuery->orWhere('mobiles.os', 'like', '%' . $osVersion . '%');
                }
            });
        }

        if (!empty($request->os_others)) {
            $osVersions = explode('|', $request->os_others);
            $query->where(function ($subQuery) use ($osVersions) {
                foreach ($osVersions as $osVersion) {
                    $subQuery->orWhere('mobiles.os', 'like', '%' . $osVersion . '%');
                }
            });
        }

        $query->where('published', 1);

        if (!empty($request->sort_by) && $request->sort_by == 'high-low') {
            $query->orderBy('mobiles.usd_min_price', 'DESC');
        } elseif (!empty($request->sort_by) && $request->sort_by == 'low-high') {
            $query->orderBy('mobiles.usd_min_price', 'ASC');
        } elseif (!empty($request->sort_by) && $request->sort_by == 'latest') {
            $query->orderBy('mobiles.sorting', 'DESC');
        } else {
            $query->orderBy('mobiles.view_count', 'DESC')->orderBy('mobiles.sorting', 'DESC');
        }

        return $query;
    }

    /**
     * Display a listing of the articles.
     *
     * @param Request $request
     * @return false|View|string
     */
    public function searchList(Request $request)
    {
        $query = Mobile::query();
        if (!empty($request->q)) {
            $query->orWhere('title', 'like', '%' . $request->q . '%');
        }

        $query->where('published', 1);

        $mobiles = $query->orderBy('id', 'DESC')->offset(0)->limit(7)->get();

        $queryData = [];

        if (!empty($request->c1)) {
            $queryData['c1'] = $request->c1;
        }

        if (!empty($request->c2)) {
            $queryData['c2'] = $request->c2;
        }

        if (!empty($request->c3)) {
            $queryData['c3'] = $request->c3;
        }

        if (!empty($request->c4)) {
            $queryData['c4'] = $request->c4;
        }

        foreach ($mobiles as $mobile) {
            if (!empty($request->field)) {
                $queryData[$request->field] = $mobile->id;
            }

            if ($request->input('homeSearchBox') == 'yes') {
                $mobile->href = route('mobiledetail', $mobile->id, $queryData);
            } else {
                $mobile->href = route('compare', $queryData);
            }
            $mobile->imageUrl = $mobile->featured_image . '?no-cache=' . time();

            if (stripos($mobile->status, 'coming') !== false) {
                $mobile->status = 'Upcoming';
            } else if (stripos($mobile->status, 'rumoured') !== false) {
                $mobile->status = 'Rumoured';
            } else if (stripos($mobile->status, 'available') !== false) {
                $mobile->status = 'Available';
            } else {
                $mobile->status = '';
            }
        }

        if ($request->isHtml) {
            return view('front_end.mobile_list_item2', compact('mobiles'));
        } else {
            return json_encode([
                "status" => true,
                "error" => null,
                "data" => [
                    "mobiles" => $mobiles->toArray(),
                ],
            ]);
        }
    }

    /**
     * Moves the attached file to the server.
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string
     */
    protected function moveFile($file)
    {
        if (!$file->isValid()) {
            return '';
        }

        return $file->store(config('codegenerator.files_upload_path'), config('filesystems.default'));
    }

    public function news(Request $request)
    {
        $allNews = News::where('status', 'Active')->orderBy('id', 'DESC')->paginate(6);
        return view('front_end.news', compact('allNews'));
    }

    public function newsView(Request $request, $id)
    {
        $news = News::findOrFail($id);

        return view('front_end.news_view', compact('news'));
    }

    public function allBrands()
    {
        $allBrands = Brand::where('status', 'Active')->orderBy('sorting')->get();

        return view('front_end.all_brands', compact('allBrands'));
    }

    public function userReviewStore(Request $request, $id)
    {
        $data = $this->getDataReviewData($request);

        try {
            $data['user_id'] = Auth::user()->id;
            $data['mobile_id'] = $id;
            $data['status'] = 'Pending';
            $review = Rating::create($data);

            return redirect()->route('mobiledetail', $id)
                ->with('success_message', 'Your Review was successfully submited!');

        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    protected function getDataReviewData(Request $request)
    {
        $data = $request->validate([
            'review_summary' => 'required|max:500',
            'rating' => 'required',
        ]);

        return $data;
    }

    public function viewMobile($mobileId, Request $request)
    {

        $mobile = Mobile::with('brand')->findOrFail($mobileId);

        $countTotalRating = Rating::where([
            ['mobile_id', $mobileId],
            ['status', 'Approved'],
        ])->get()->count();

        $countFiveRatings = Rating::where([
            ['mobile_id', $mobileId],
            ['rating', 5],
            ['status', 'Approved'],
        ])->get()->count();

        $countFourRatings = Rating::where([
            ['mobile_id', $mobileId],
            ['rating', 4],
            ['status', 'Approved'],
        ])->get()->count();

        $countThreeRatings = Rating::where([
            ['mobile_id', $mobileId],
            ['rating', 3],
            ['status', 'Approved'],
        ])->get()->count();

        $countTwoRatings = Rating::where([
            ['mobile_id', $mobileId],
            ['rating', 2],
            ['status', 'Approved'],
        ])->get()->count();

        $countOneRatings = Rating::where([
            ['mobile_id', $mobileId],
            ['rating', 1],
            ['status', 'Approved'],
        ])->get()->count();

        $ratings = Rating::where([
            ['mobile_id', $mobileId],
            ['status', 'Approved'],
        ])->orderBy('created_at', 'DESC')->limit(25)->get();

        $userReview = [];
        if (Auth::user()) {
            $userReview = Rating::where([
                ['mobile_id', $mobileId],
                ['user_id', Auth::user()->id],
            ])->first();
        }

        $regions = MobilePrice::where('mobile_id', $mobileId)->pluck('region_id');
        $mobileRegions = [];

        if (!empty($regions)) {
            $regions = array_values($regions->toArray());
            $regions = array_unique($regions);
            foreach ($regions as $regionId) {
                $region = MobileRegion::find($regionId);
                if ($region) {
                    $mobileRegions[] = $region;
                }
            }

            foreach ($mobileRegions as $item) {
                $mobilePrices = MobilePrice::where([
                    ['mobile_id', $mobileId],
                    ['region_id', $item->id],
                ])->get();

                if ($mobilePrices) {
                    $item->prices = $mobilePrices;
                } else {
                    $item->prices = null;
                }
            }
        }

        $userIp = $request->ip();

        $mobileIdWithIp = $userIp . '_' . $mobile->id;
        $userViewInfo = Session::get('userViewInfo', []);

        if ($userViewInfo) {
            if (!in_array($mobileIdWithIp, $userViewInfo)) {
                $userViewInfo[] = $mobileIdWithIp;
                Session::put('userViewInfo', $userViewInfo);
                $mobile->update(['view_count' => $mobile->view_count + 1]);
            }
        } else {
            $userViewInfo[] = $mobileIdWithIp;
            Session::put('userViewInfo', $userViewInfo);
            $mobile->update(['view_count' => $mobile->view_count + 1]);
        }

        $mobile = MobileTransformer::transform($mobile);

        $photoGallery = new PhotoGallery();
        $fileList = $photoGallery->getFileListFromDir($mobileId);
        $mobileInitialPreviewImages = $photoGallery->getInitialPreview($fileList, $mobileId);
        $mobile->initialPreviewImages = $mobileInitialPreviewImages;

        return view('front_end.mobile_details', compact(
            'mobile',
            'mobileRegions',
            'userReview',
            'countTotalRating',
            'countFiveRatings',
            'countFourRatings',
            'countThreeRatings',
            'countTwoRatings',
            'countOneRatings',
            'ratings'
        ));
    }

    public function getpopularcomparisons(Request $request)
    {
        $Comparisonmobiles = Cache::remember('Comparisonmobiles', 600, function () {
            $popularComparisons = PopularComparison::orderBy('view_count', 'DESC')->limit(7)->get();
            $Comparisonmobiles = [];
            foreach ($popularComparisons as $popularComparison) {

                $item1 = Mobile::findOrFail($popularComparison->mobile1_id);
                $item2 = Mobile::findOrFail($popularComparison->mobile2_id);
                $mobile1 = MobileTransformer::transform($item1);
                $mobile2 = MobileTransformer::transform($item2);

                $Comparisonmobiles[] = [

                    "c1Mobile" => $mobile1,
                    "c2Mobile" => $mobile2,
                ];
            }
            return $Comparisonmobiles;
        });

        return response()->json($Comparisonmobiles, Response::HTTP_OK);
    }

    public function changeCurrency(Request $request)
    {

        if (isset($request->currency)) {
            $request->session()->put('currency', $request->currency);
        }
        return redirect()->back();
    }
}
