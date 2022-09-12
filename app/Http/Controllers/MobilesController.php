<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Brand;
use App\Models\Mobile;
use App\Models\Rating;
use App\Models\EventLog;
use App\Models\MobileImage;
use App\Models\MobilePrice;
use App\Helpers\EventHelper;
use App\Models\MobileRegion;
use App\Library\MobileTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Library\PhotoGallery;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MobilesController extends Controller
{
    /**
     * Display a listing of the mobiles.
     *
     * @return View
     */
    public function index()
    {
        $mobiles = [];
        $Brands = Brand::pluck('title', 'id')->all();

        return view('mobiles.index', compact('mobiles', 'Brands'));
    }

    public function mobileList(Request $request)
    {
        $query = Mobile::query();

        if (!empty($request->mobileId)) {
            $query->where('id', $request->mobileId);
        }

        if (!empty($request->title)) {
            $query->where('title', $request->title);
        }

        if (!empty($request->brandId)) {
            $query->where('brand_id', $request->brandId);
        }

        if (!empty($request->announced)) {
            $query->where('announced', $request->announced);
        }
        if (!empty($request->status)) {
            if ($request->status == 'published') {
                $query->where('published', 1);
            }
            elseif ($request->status == 'not_published') {
                $query->where('published', 0);
            }
            elseif ($request->status == 'published_not_in_store') {
                $query->where('published', 1)->where('completed', 0);
            }
            else {
                $query->where('status', 'like', '%' . $request->status . '%');
            }
        }
        $mobiles = $query->orderBy('id', 'DESC')->paginate(15);
        return view('mobiles.data_list', compact('mobiles'));

    }

    /**
     * Show the form for creating a new mobile.
     *
     * @return View
     */
    public function create()
    {
        $Brands = Brand::pluck('title', 'id')->all();

        return view('mobiles.create', compact('Brands'));
    }

    /**
     * Store a new mobile in the storage.
     *
     * @param $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            Mobile::create($data);

            return redirect()->route('mobiles.index')
                ->with('success_message', 'Mobile was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified mobile.
     *
     * @param int $id
     *
     * @return View
     */
    public function show($id)
    {
        $mobile = Mobile::with('brand')->findOrFail($id);

        $photoGallery = new PhotoGallery();
        $fileList = $photoGallery->getFileListFromDir($id);
        $mobileInitialPreviewImages = $photoGallery->getInitialPreview($fileList, $id);
        $mobile->initialPreviewImages = $mobileInitialPreviewImages;
        $mobile->default_image = $photoGallery->getDefaultImage($id, count($mobile->mobileImages));

        $allMobiles = Mobile::where('origin_id', $mobile->origin_id)->get();
        if (count($allMobiles) > 1) {
            $mobile->is_duplicate = true;
        } else {
            $mobile->is_duplicate = false;
        }
         $mobile = MobileTransformer::transform($mobile);

        return view('mobiles.show', compact('mobile'));
    }

    /**
     * Show the form for editing the specified mobile.
     *
     * @param int $id
     *
     * @return View
     */
    public function edit($id)
    {

        $mobile = Mobile::findOrFail($id);
        $Brands = Brand::pluck('title', 'id')->all();

        $variationPrices = MobilePrice::where('mobile_id', $id)->get();
        $regions = MobileRegion::where('status', 'Active')->pluck('title','id');
        $mobilePrice = [];

        $allUserOpinions  = Rating::where('mobile_id', $id)->get();

    //    for show page
        $photoGallery = new PhotoGallery();
        $fileList = $photoGallery->getFileListFromDir($id);
        $mobileInitialPreviewImages = $photoGallery->getInitialPreview($fileList, $id);
        $mobile->initialPreviewImages = $mobileInitialPreviewImages;
        $mobile->default_image = $photoGallery->getDefaultImage($id, count($mobile->mobileImages));

        $allMobiles = Mobile::where('origin_id', $mobile->origin_id)->get();
        if (count($allMobiles) > 1) {
            $mobile->is_duplicate = true;
        } else {
            $mobile->is_duplicate = false;
        }
         $mobile = MobileTransformer::transform($mobile);

        // end show page
        //quick update
        $defaultImage = MobileImage::where('mobile_id', $mobile->id)->where('sorting', 1)->first();

        if($defaultImage){
            $mobile->default_image = $defaultImage->filename;
        } else {
            $mobile->default_image = '';
        }
        return view('mobiles.edit', compact('mobile', 'Brands', 'variationPrices', 'regions', 'mobilePrice', 'allUserOpinions'));
    }

    /**
     * Update the specified mobile in the storage.
     *
     * @param int $id
     * @param  $request
     *
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);

        try {
            $mobile = Mobile::findOrFail($id);
            $mobile->update($data);

            return redirect()->route('mobiles.index')
                ->with('success_message', 'Mobile was successfully updated.');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }


    /**
     * Update the specified mobile in the storage.
     *
     * @param int $id
     * @param  $request
     *
     * @return string
     */
    public function updatePrice($id, Request $request)
    {
        $data = $request->validate([
            'price' => 'required|numeric|min:-2147483648|max:2147483647'
        ]);

        try {
            $mobile = Mobile::findOrFail($id);
            $mobile->update($data);

            return response()->json(['message' => 'Price updated successfully'], 200
            );
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Unexpected error occurred while trying to process your request'
            ], 400);
        }
    }

    function importPrice($id)
    {
        $mobile = Mobile::with('brand')->findOrFail($id);

        $result = app('App\Http\Controllers\PriceSourceController')->getPriceTableHtml($id, 'INR');

        return view('price_sources.import_price', compact('mobile', 'result'));
    }

    /**
     * Remove the specified mobile from the storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $mobile = Mobile::findOrFail($id);
            $mobile->delete();

            return redirect()->route('mobiles.index')
                ->with('success_message', 'Mobile was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    //For mobile price modal

    public function variationPrice($mobileId)
    {
        $mobile = Mobile::findOrFail($mobileId);

        $variationPrices = VariationPrice::with('region', 'ram', 'storage')->where('mobile_id', $mobileId)->get();

        $regions = Region::all()->pluck('title', 'id');
        $rams = Ram::all()->pluck('title', 'id');
        $storages = Storage::all()->pluck('title', 'id');

        return view('mobiles.modal_prices', compact('mobile', 'variationPrices', 'regions', 'rams', 'storages'));

    }

    public function storeVariationPrice(Request $request)
    {
        try {

            $data = $this->getPriceData($request);


            VariationPrice::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'mobile.storeVariationPrices',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }
            $html = '';
            if (!empty($request->mobile_id)) {

                $variationPrices = VariationPrice::where('mobile_id', $request->mobile_id)
                    ->get();

                $html = view('mobiles.table_prices', compact('variationPrices'))->render();
            }

            return ['status' => 'OK', 'html' => $html, 'message' => 'Mobile data was successfully added!'];

        } catch (Exception $exception) {
            return ['status' => 'FAILED', 'message' => 'Something goes wrong!!!'];
        }

    }

    public function updateVariationPrice($Id, Request $request)
    {
        //$data = $this->getData($request);
        try {

            $data = $this->getPriceData($request);

            $variationPrice = VariationPrice::findOrFail($Id);

            $oldData = $variationPrice->toArray();

            $variationPrice->update($data);


            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'variation_prices.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            $html = '';

            if (!empty($request->mobile_id)) {

                $variationPrices = VariationPrice::where('mobile_id', $request->mobile_id)
                    ->get();

                $html = view('mobiles.table_prices', compact('variationPrices'))->render();
            }

            return ['status' => 'OK', 'html' => $html, 'message' => 'Mobile price was successfully updated!'];
        } catch (Exception $exception) {
            return ['status' => 'FAILED', 'message' => 'Something goes wrong!!!'];
        }
    }

    protected function getPriceData(Request $request)
    {
        $data = $request->validate([
            'mobile_id' => 'required',
            'region_id' => 'required',
            'ram_id' => 'required',
            'storage_id' => 'required',
            'price' => 'required|numeric|min:-1.0E+18|max:1.0E+18',
            'usd_price' => 'required|numeric|min:-1.0E+18|max:1.0E+18',
            'affiliate_url' => 'nullable',

        ]);

        return $data;
    }

    public function destroyVariationPrice($id)
    {
        try {
            $variationPrice = VariationPrice::findOrFail($id);
            $oldData = $variationPrice->toArray();
            $variationPrice->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'mobile.destroyVariationPrices',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            $variationPrices = VariationPrice::where('mobile_id', $variationPrice->mobile_id)->get();
            $html = view('mobiles.table_prices', compact('variationPrices'))->render();

            return ['status' => 'OK', 'html' => $html, 'message' => 'Mobile price was successfully deleted!'];
        } catch (Exception $exception) {
            return ['status' => 'FAILED', 'message' => 'Something goes wrong!!!'];
        }
    }

    public function quickUpdate($id)
    {

        $mobile = Mobile::findOrFail($id);
        $defaultImage = MobileImage::where('mobile_id', $mobile->id)->where('sorting', 1)->first();

        if($defaultImage){
            $mobile->default_image = $defaultImage->filename;
        } else {
            $mobile->default_image = '';
        }

        return view('mobiles.quick_update', compact('mobile'));

    }

    public function quickUpdateStore($id, Request $request)
    {
        $data = $this->quickUpdateData($request);

        try {
            $mobile = Mobile::findOrFail($id);
            $mobile->update($data);

            // upload photo if submit
            $previousImage = MobileImage::where('mobile_id', $mobile->id)->where('sorting', 1)->first();

            if ($request->hasFile('default_image')) {

                // upload photo
                $image = $request->file('default_image');
                $fileExtension = $image->getClientOriginalExtension();
                $filename = '1' . '.' . $fileExtension;
                $image->storeAs('mobiles/'. $mobile->id  , $filename);


                // $previousImage->update(['filename'=>$filename]);

                    $defaultImage = MobileImage::updateOrCreate(
                        ['mobile_id'=>$mobile->id,
                        'sorting'=> 1],
                        ['filename'=>$filename]
                    );

                if($previousImage){
                    $previousFileName = $previousImage->filename;
                     // remove old file when different extension
                    if ($previousFileName && $previousFileName != $filename) {
                        Storage::delete('mobiles/'.$mobile->id . '/' . $previousFileName);
                    }
                }

            }

            return redirect()->route('mobiles.mobile.edit', [$mobile->id, 'tab'=> "quick-update"] )
                ->with('success_message', 'Mobile was successfully updated.');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    protected function quickUpdateData(Request $request)
    {
        $rules = [
            'official_link' => 'nullable',
            'expert_score' => 'required|numeric|between:0,100',
            'published' => 'required|numeric',
            'completed' => 'required|numeric'
        ];

        if ($request->isMethod('POST')) {
            $rules['default_image'] = 'required|file|max:2048';
        } else {

            if ($request->hasFile('image')) {
                $rules['default_image'] = 'required|file|max:2048';
            }
        }

        $data = $request->validate($rules);

        if (!empty($data['default_image'])) {
            unset($data['default_image']);
        }

        return $data;
    }

    public function opinionsStatusUpdate(Request $request){

        try {
            $opinionsId = $request->opinionsId;
            $status = $request->status;
            $opinions  = Rating::where('id', $opinionsId)->first();
            $mobile = Mobile::where('id', $opinions->mobile_id)->first();

            if( $opinions ) {
                if( $status == 1){
                    $opinions['status'] = 'Approved';
                } else {
                    $opinions['status'] = 'Pending';
                }
                $opinions->update();
            }

            if($mobile){
                $allOpinionsSum  = Rating::where('mobile_id', $mobile->id)->where('status', 'Approved')->get()->sum('rating');
                $allOpinions  = Rating::where('mobile_id', $mobile->id)->where('status', 'Approved')->count();

                if($allOpinions !=0 ){
                    $avgRating = $allOpinionsSum/$allOpinions;
                } else{
                    $avgRating = 0;
                }
                $mobile->update(['avg_rating'=>$avgRating]);
            }

            return ['status' => 'OK', 'message' => 'Status was successfully updated!'];

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
        $rules = [
            'brand_id' => 'required',
            'title' => 'required|string|min:1|max:255',
            'price' => 'required|numeric|min:-2147483648|max:2147483647',
            'technology' => 'required|string|min:1|max:255',
            'announced' => 'required|string|min:1|max:255',
            'status' => 'required|string|min:1|max:255',
            'dimensions' => 'required|string|min:1|max:255',
            'weight' => 'required|string|min:1|max:255',
            'sim' => 'required|string|min:1|max:255',
            'type' => 'required|string|min:1|max:255',
            'size' => 'required|string|min:1|max:255',
            'resolution' => 'required|string|min:1|max:255',
            'os' => 'required|string|min:1|max:255',
            'chipset' => 'required|string|min:1|max:255',
            'cpu' => 'required|string|min:1|max:255',
            'card_slot' => 'required|string|min:1|max:255',
            'ram' => 'required|string|min:1|max:25',
            'storage' => 'required|string|min:1|max:100',
            'main_camera' => 'required|string|min:1|max:255',
            'mc_filter' => 'required|numeric|min:-2147483648|max:2147483647',
            'mc_features' => 'required|string|min:1|max:255',
            'mc_video' => 'required|string|min:1|max:255',
            'selfie_camera' => 'required|string|min:1|max:255',
            'sc_filter' => 'required|numeric|min:-2147483648|max:2147483647',
            'sc_features' => 'required|string|min:1|max:255',
            'sc_video' => 'required|string|min:1|max:255',
            'loudspeaker' => 'required|string|min:1|max:255',
            'jack_3_5mm' => 'required|string|min:1|max:255',
            'wlan' => 'required|string|min:1|max:255',
            'bluetooth' => 'required|string|min:1|max:255',
            'gps' => 'required|string|min:1|max:255',
            'radio' => 'required|string|min:1|max:255',
            'usb' => 'required|string|min:1|max:255',
            'sensors' => 'required|string|min:1|max:255',
            'battery' => 'required|string|min:1|max:255',
            'battery_filter' => 'required|numeric|min:-2147483648|max:2147483647',
            'colors' => 'required|string|min:1|max:255',
            'ext' => 'required|string|min:1|max:20',
            'images' => 'required|numeric|min:-2147483648|max:2147483647',
            'revision' => 'required|numeric|min:-2147483648|max:2147483647',
            'origin_id' => 'required|numeric|min:0|max:4294967295',
            'sorting' => 'required|numeric|min:-2147483648|max:2147483647'
        ];

        return $request->validate($rules);
    }
}
