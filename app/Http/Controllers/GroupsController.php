<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;

class GroupsController extends Controller
{

    /**
     * Display a listing of the groups.
     *
     * @return View
     */
    public function index()
    {
        $groups = Group::get();
        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new group.
     *
     * @return View
     */
    public function create()
    {
        
        return view('groups.create');
    }

    /**
     * Store a new group in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            Group::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'groups.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('groups.index')
                             ->with('success_message', 'Group was successfully added!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified group.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $group = Group::findOrFail($id);
        return view('groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified group.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);
        
        return view('groups.edit', compact('group'));
    }

    /**
     * Update the specified group in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $group = Group::findOrFail($id);
            $oldData = $group->toArray();
            $group->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'groups.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('groups.index')
                             ->with('success_message', 'Group was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified group from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $group = Group::findOrFail($id);
            $oldData = $group->toArray();
            $group->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'groups.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('groups.index')
                             ->with('success_message', 'Group was successfully deleted!');
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
            'title' => 'required|string|min:1|max:255',
            'slug' => 'nullable|string|min:0|max:255',
            'fa_icon' => 'nullable|string|min:0|max:50',
            'status' => 'required',
            'short_description' => 'nullable',
    
        ]);




        return $data;
    }

}
