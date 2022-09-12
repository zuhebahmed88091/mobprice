<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\FilterOption;
use App\Models\FilterSection;
use App\Models\FilterTab;
use App\Models\Mobile;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;

class FilterTabsController extends Controller
{
    /**
     * Display a listing of the filter tabs.
     *
     * @return View
     */
    public function index()
    {
        $filterTabs = FilterTab::get();
        return view('filter_tabs.index', compact('filterTabs'));
    }

    /**
     * Show the form for creating a new filter tab.
     *
     * @return View
     */
    public function create()
    {

        return view('filter_tabs.create');
    }

    /**
     * Store a new filter tab in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            FilterTab::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'filter_tabs.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('filter_tabs.index')
                ->with('success_message', 'Filter Tab was successfully added!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified filter tab.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $filterTab = FilterTab::findOrFail($id);
        return view('filter_tabs.show', compact('filterTab'));
    }

    /**
     * Show the form for editing the specified filter tab.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $filterTab = FilterTab::findOrFail($id);

        return view('filter_tabs.edit', compact('filterTab'));
    }

    /**
     * Update the specified filter tab in the storage.
     *
     * @param int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $filterTab = FilterTab::findOrFail($id);
            $oldData = $filterTab->toArray();
            $filterTab->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'filter_tabs.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('filter_tabs.index')
                ->with('success_message', 'Filter Tab was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified filter tab from the storage.
     *
     * @param int $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $filterTab = FilterTab::findOrFail($id);
            $oldData = $filterTab->toArray();
            $filterTab->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'filter_tabs.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('filter_tabs.index')
                ->with('success_message', 'Filter Tab was successfully deleted!');
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
            'sorting' => 'required|numeric|min:-2147483648|max:2147483647',
            'status' => 'nullable',

        ]);

        return $data;
    }

}
