<?php

namespace App\Http\Middleware;

use App\Helpers\CommonHelper;
use Closure;
use App\Models\MobileRegion;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class SetApplicationCurrency
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $selectedCurrency = 'USD';
        $currencies = [
            'BDT' => 'BDT',
            'INR' => 'INR',
            'USD' => 'USD',
            'GBP' => 'GBP',
            'SAR' => 'SAR',
            'EUR' => 'EUR'
        ];

        if (!$request->session()->has('currency')) {
            $ip = CommonHelper::getUserIpAddress();
            $location_details = Location::get($ip);
            if ($location_details) {
                $isoCode = MobileRegion::where('region_code', $location_details->countryCode)->value('iso_code');
                if (!empty($isoCode)) {
                    $selectedCurrency = $currencies[$isoCode];
                }
            }
            $request->session()->put('currency', $selectedCurrency);
        }

        return $next($request);
    }
}