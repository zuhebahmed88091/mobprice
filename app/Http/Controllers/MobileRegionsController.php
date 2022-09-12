<?php

namespace App\Http\Controllers;

use App\Helpers\EventHelper;
use App\Models\EventLog;
use App\Models\MobileRegion;
use Cache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MobileRegionsController extends Controller
{

    /**
     * Display a listing of the mobile regions.
     *
     * @return View
     */
    public function index()
    {
        $mobileRegions = MobileRegion::get();
        return view('mobile_regions.index', compact('mobileRegions'));
    }

    public function items()
    {
        $mobileRegions = Cache::remember('mobileRegions', 1800, function () {
            $mobileRegions = MobileRegion::select(['id', 'title', 'region_code', 'currency', 'iso_code', 'symbol'])
                ->where('status', 'Active')
                ->whereNotNull('region_code')
                ->get();
            foreach ($mobileRegions as $mobileRegion) {
                $mobileRegion->flag_url = asset('storage/flags/' . $mobileRegion->region_code . '.png');
            }
            return $mobileRegions;
        });
        return response()->json($mobileRegions, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new mobile region.
     *
     * @return View
     */
    public function create()
    {
        return view('mobile_regions.create');
    }

    /**
     * Store a new mobile region in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            MobileRegion::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'mobile_regions.create',
                    'changes' => EventHelper::logForCreate($data),
                ]);
            }

            return redirect()->route('mobile_regions.index')
                ->with('success_message', 'Mobile Region was successfully added!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified mobile region.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $mobileRegion = MobileRegion::findOrFail($id);
        return view('mobile_regions.show', compact('mobileRegion'));
    }

    /**
     * Show the form for editing the specified mobile region.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $mobileRegion = MobileRegion::findOrFail($id);

        return view('mobile_regions.edit', compact('mobileRegion'));
    }

    /**
     * Update the specified mobile region in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $mobileRegion = MobileRegion::findOrFail($id);
            $oldData = $mobileRegion->toArray();
            $mobileRegion->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'mobile_regions.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data),
                ]);
            }

            return redirect()->route('mobile_regions.index')
                ->with('success_message', 'Mobile Region was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified mobile region from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $mobileRegion = MobileRegion::findOrFail($id);
            $oldData = $mobileRegion->toArray();
            $mobileRegion->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'mobile_regions.destroy',
                    'changes' => EventHelper::logForDelete($oldData),
                ]);
            }

            return redirect()->route('mobile_regions.index')
                ->with('success_message', 'Mobile Region was successfully deleted!');
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
            'title' => 'required|string|min:1|max:100',
            'currency' => 'required|string|min:1|max:100',
            'iso_code' => 'required|string|min:1|max:5',
            'symbol' => 'required|string|min:1|max:10',
            'rate' => 'required|numeric|min:-99999999.99|max:99999999.99',
            'status' => 'required',

        ]);

        return $data;
    }

}
