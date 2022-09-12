<?php

namespace App\Http\Controllers;

use App\Models\FilterTab;
use Illuminate\Http\Request;
use App\Models\FilterSection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;

class FilterSectionsController extends Controller
{

    /**
     * Display a listing of the filter sections.
     *
     * @return View
     */
    public function index()
    {
        $filterSections = FilterSection::join('filter_tabs', 'filter_sections.filter_tab_id', '=', 'filter_tabs.id')
            ->orderBy('filter_tabs.sorting', 'asc')
            ->get(['filter_sections.*']);
        $serial = 1;

        return view('filter_sections.index', compact('filterSections', 'serial'));
    }

    /**
     * Show the form for creating a new filter section.
     *
     * @return View
     */
    public function create()
    {
        $filterTabs = FilterTab::all()->pluck('title','id');
        return view('filter_sections.create', compact('filterTabs'));
    }

    /**
     * Store a new filter section in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            FilterSection::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'filter_sections.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('filter_sections.index')
                             ->with('success_message', 'Filter Section was successfully added!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified filter section.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $filterSection = FilterSection::with('filtertab')->findOrFail($id);
        return view('filter_sections.show', compact('filterSection'));
    }

    /**
     * Show the form for editing the specified filter section.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $filterSection = FilterSection::findOrFail($id);
        $filterTabs = FilterTab::all()->pluck('title','id');
        return view('filter_sections.edit', compact('filterSection','filterTabs'));
    }

    /**
     * Update the specified filter section in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $filterSection = FilterSection::findOrFail($id);
            $oldData = $filterSection->toArray();
            $filterSection->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'filter_sections.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('filter_sections.index')
                             ->with('success_message', 'Filter Section was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified filter section from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $filterSection = FilterSection::findOrFail($id);
            $oldData = $filterSection->toArray();
            $filterSection->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'filter_sections.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('filter_sections.index')
                             ->with('success_message', 'Filter Section was successfully deleted!');
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
            'filter_tab_id' => 'required',
            'label' => 'required|string|min:1|max:100',
            'field' => 'required|string|min:1|max:100',
            'type' => 'required',
            'show_label' => 'required',
            'sorting' => 'required|numeric|min:-2147483648|max:2147483647',
            'status' => 'nullable',

        ]);


        return $data;
    }

}
