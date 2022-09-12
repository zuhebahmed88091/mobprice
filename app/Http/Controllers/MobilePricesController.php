<?php

namespace App\Http\Controllers;

use App\Models\Mobile;
use App\Models\MobileRegion;
use App\Models\MobilePrice;
use Illuminate\Http\Request;
use App\Models\MobileStorage;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;

class MobilePricesController extends Controller
{

    /**
     * Display a listing of the mobile prices.
     *
     * @return View
     */
    public function index()
    {
        $mobilePrices = MobilePrice::with('mobile','region','mobilestorage')->get();
        return view('mobile_prices.index', compact('mobilePrices'));
    }

    /**
     * Show the form for creating a new mobile price.
     *
     * @return View
     */
    public function create()
    {
        $mobiles = Mobile::all()->pluck('title','id');
		$regions = MobileRegion::all()->pluck('title','id');
		// $mobileRams = MobileRam::all()->pluck('title','id');
		// $mobileStorages = MobileStorage::all()->pluck('title','id');
        return view('mobile_prices.create', compact('mobiles','regions'));
    }

    /**
     * Store a new mobile price in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        // $mobileId = $request->input('mobile_id');
        
        try {

            $data = $this->getData($request);
            
            $regions = MobileRegion::where('id', $request->region_id)->first();

            if( $regions ){
                $data['usd_price'] = $request->price/$regions->rate;
            }
           
            MobilePrice::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'mobile_prices.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            $html = '';
            if (!empty($request->mobile_id)) {

                $variationPrices = MobilePrice::where('mobile_id', $request->mobile_id)->get();

                $html = view('mobiles.pricees_table', compact('variationPrices'))->render();
            }

            return ['status' => 'OK', 'html' => $html, 'message' => 'Mobile Price was successfully added!'];

            // return redirect()->route('mobiles.mobile.edit', $mobileId)
            //                  ->with('success_message', 'Mobile Price was successfully added!');

        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified mobile price.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $mobilePrice = MobilePrice::with('mobile','region','mobilestorage')->findOrFail($id);
        return view('mobile_prices.show', compact('mobilePrice'));
    }

    /**
     * Show the form for editing the specified mobile price.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {   
        $mobilePrice = MobilePrice::findOrFail($id);
        $mobiles = Mobile::all()->pluck('title','id')->except($id);
		$regions = MobileRegion::all()->pluck('title','id')->except($id);
		// $mobileRams = MobileRam::all()->pluck('title','id')->except($id);
		// $mobileStorages = MobileStorage::all()->pluck('title','id')->except($id);
        return view('mobile_prices.edit', compact('mobilePrice','mobiles','regions'));
    }

    /**
     * Update the specified mobile price in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {
            
            $data = $this->getData($request);
            $regions = MobileRegion::where('id', $request->region_id)->first();
            if( $regions ){
                $data['usd_price'] = $request->price/$regions->rate;
            }
            
            $mobilePrice = MobilePrice::findOrFail($id);
            $oldData = $mobilePrice->toArray();
            $mobilePrice->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'mobile_prices.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }
            $html = '';

            if (!empty($request->mobile_id)) {

                $variationPrices = MobilePrice::where('mobile_id', $request->mobile_id)
                    ->get();

                $html = view('mobiles.pricees_table', compact('variationPrices'))->render();
            }

            return ['status' => 'OK', 'html' => $html, 'message' => 'Mobile price was successfully updated!'];

            // return redirect()->route('mobile_prices.index')
            //                  ->with('success_message', 'Mobile Price was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified mobile price from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {   
        // $mobileId = $request->input('mobile_id');
        try {
            $mobilePrice = MobilePrice::findOrFail($id);
            $oldData = $mobilePrice->toArray();
            $mobilePrice->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'mobile_prices.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            $variationPrices = MobilePrice::where('mobile_id', $mobilePrice->mobile_id)->get();
            $html = view('mobiles.pricees_table', compact('variationPrices'))->render();

            return ['status' => 'OK', 'html' => $html, 'message' => 'Mobile price was successfully deleted!'];
            // return redirect()->route('mobiles.mobile.edit', $mobileId)
            //                  ->with('success_message', 'Mobile Price was successfully deleted!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    
    /**
     * Get the request's data from the request.
     *
     * @param Request $request 
     * @return array
     */
    protected function getData(Request $request)
    {
        $data = $request->validate([
            'mobile_id' => 'required',
            'region_id' => 'required',
            // 'mobile_ram_id' => 'required',
            // 'mobile_storage_id' => 'required',
            'variation' => 'required',
            'store' => 'required',
            'price' => 'required|numeric|min:-1.0E+18|max:1.0E+18',
            // 'usd_price' => 'required|numeric|min:-1.0E+18|max:1.0E+18',
            'status' => 'required',
            'affiliate_url' => 'nullable',
    
        ]);


        return $data;
    }

}
