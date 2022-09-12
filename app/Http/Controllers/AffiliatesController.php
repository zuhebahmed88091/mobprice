<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;

class AffiliatesController extends Controller
{

    /**
     * Display a listing of the affiliates.
     *
     * @return View
     */
    public function index()
    {
        $affiliates = Affiliate::get();
        return view('affiliates.index', compact('affiliates'));
    }

    /**
     * Show the form for creating a new affiliate.
     *
     * @return View
     */
    public function create()
    {
        
        return view('affiliates.create');
    }

    /**
     * Store a new affiliate in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            Affiliate::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'affiliates.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('affiliates.index')
                             ->with('success_message', 'Affiliate was successfully added!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified affiliate.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $affiliate = Affiliate::findOrFail($id);
        return view('affiliates.show', compact('affiliate'));
    }

    /**
     * Show the form for editing the specified affiliate.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $affiliate = Affiliate::findOrFail($id);
        
        return view('affiliates.edit', compact('affiliate'));
    }

    /**
     * Update the specified affiliate in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $affiliate = Affiliate::findOrFail($id);
            $oldData = $affiliate->toArray();
            $affiliate->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'affiliates.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('affiliates.index')
                             ->with('success_message', 'Affiliate was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified affiliate from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $affiliate = Affiliate::findOrFail($id);
            $oldData = $affiliate->toArray();
            $affiliate->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'affiliates.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('affiliates.index')
                             ->with('success_message', 'Affiliate was successfully deleted!');
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
            'domain' => 'required|string|min:1|max:100',
    
        ]);


        return $data;
    }

}
