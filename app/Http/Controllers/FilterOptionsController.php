<?php

namespace App\Http\Controllers;

use App\Models\FilterOption;
use App\Models\MobilePrice;
use App\Models\Mobile;
use Illuminate\Http\Request;
use App\Models\FilterSection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;

class FilterOptionsController extends Controller
{
    /**
     * Display a listing of the filter options.
     *
     * @return View
     */
    public function index()
    {
        $maxPrice = ceil(MobilePrice::where('status', 'Active')->max('usd_price'));
        $maxPrice = round($maxPrice, strlen($maxPrice) * -0.5);
        $priceRange = FilterOption::where('name','Price Range')->first();
        list($min, $max, $step) = explode('|', $priceRange->value, 3);
        $priceString = $min . "|" . $maxPrice . "|" . $step;
        $priceRange->value = $priceString;
        $priceRange->save();
        //
        $maxWeight = ceil(Mobile::max('gm_weight'));
        $weightRange = FilterOption::where('name','Weight Range')->first();
        list($min, $max, $step) = explode('|', $weightRange->value, 3);

        $weightString = $min . "|" . $maxWeight . "|" . $step;
        $weightRange->value = $weightString;
        $weightRange->save();

        $filterOptions = FilterOption::join('filter_sections', 'filter_options.filter_section_id', '=', 'filter_sections.id')
            ->orderBy('filter_sections.sorting', 'ASC')
            ->orderBy('filter_options.sorting', 'ASC')
            ->get(['filter_options.*']);
        $serial = 1;
        return view('filter_options.index', compact('filterOptions', 'serial',));
    }

    /**
     * Show the form for creating a new filter option.
     *
     * @return View
     */
    public function create()
    {
        $filterSections = FilterSection::all()->pluck('label','id');
        return view('filter_options.create', compact('filterSections'));
    }

    /**
     * Store a new filter option in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            FilterOption::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'filter_options.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('filter_options.index')
                             ->with('success_message', 'Filter Option was successfully added!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified filter option.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $filterOption = FilterOption::with('filtersection')->findOrFail($id);
        return view('filter_options.show', compact('filterOption'));
    }

    /**
     * Show the form for editing the specified filter option.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $filterOption = FilterOption::findOrFail($id);
        $filterSections = FilterSection::all()->pluck('label','id');
        return view('filter_options.edit', compact('filterOption','filterSections'));
    }

    /**
     * Update the specified filter option in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $filterOption = FilterOption::findOrFail($id);
            $oldData = $filterOption->toArray();
            $filterOption->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'filter_options.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('filter_options.index')
                             ->with('success_message', 'Filter Option was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified filter option from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $filterOption = FilterOption::findOrFail($id);
            $oldData = $filterOption->toArray();
            $filterOption->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'filter_options.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('filter_options.index')
                             ->with('success_message', 'Filter Option was successfully deleted!');
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
            'filter_section_id' => 'required',
            'name' => 'required|string|min:1|max:100',
            'value' => 'required|string|min:1|max:100',
            'status' => 'required',
            'sorting' => 'required|numeric|min:-2147483648|max:2147483647',

        ]);


        return $data;
    }

}
