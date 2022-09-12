<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use App\Notifications\contactusReply;


class ContactUsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the contact uses.
     *
     * @return View
     */
    public function index()
    {
        $contactUses = ContactUs::whereNull('replied_id')->get();
        return view('contact_us.index', compact('contactUses'));
    }

    /**
     * Show the form for creating a new contact us.
     *
     * @return View
     */
    public function create()
    {
        return view('contact_us.create');
    }

    /**
     * Store a new contact us in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector | string
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            ContactUs::create($data);

            if ($request->ajax()) {
                return 'success';
            } else {
                return back()->with('success_message', 'Contact Us was successfully added!');
            }
        } catch (Exception $exception) {
            if ($request->ajax()) {
                return 'Unexpected error occurred while trying to process!';
            } else {
                return back()->withInput()
                    ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
            }
        }
    }

    /**
     * Store a new contact us in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function storeReply(Request $request)
    {
        $contactUs = ContactUs::findOrFail($request->replied_id);
        $data = $this->getData($request);
        try {
            ContactUs::create($data);

            $contactUs->update(['status' => 'Replied']);

            // Send simple reply email to respective user
            $contactUs->notify(new contactusReply($contactUs->full_name,$request->message));
            // email sending code ended

            return redirect()->route('contact_us.show', $request->replied_id)
                ->with('success_message', 'Your reply was successfully sent!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified contact us.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $contactUs = ContactUs::findOrFail($id);
        if ($contactUs->status == 'Not Seen') {
            $contactUs->update(['status' => 'Viewed']);
        }

        $replies = ContactUs::where('replied_id', $id)->get();

        return view('contact_us.show', compact('contactUs', 'replies'));
    }

    /**
     * Update the specified contact us in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $contactUs = ContactUs::findOrFail($id);
            $oldData = $contactUs->toArray();
            $contactUs->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'contact_us.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('contact_us.index')
                             ->with('success_message', 'Contact Us was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified contact us from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $contactUs = ContactUs::findOrFail($id);
            $oldData = $contactUs->toArray();

            ContactUs::where('replied_id', $id)->delete();
            $contactUs->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'contact_us.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('contact_us.index')
                             ->with('success_message', 'Contact us message was successfully deleted!');
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
            'full_name' => 'required|string|min:1|max:100',
            'email' => 'required|string|min:1|max:100',
            'subject' => 'nullable|string|min:1|max:255',
            'message' => 'required',
            'replied_id' => 'nullable'
        ]);
        
        return $data;
    }
}
