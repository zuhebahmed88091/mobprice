<?php

namespace App\Http\Controllers;

use App\Exports\TestimonialExport;
use App\Helpers\CommonHelper;
use App\Models\User;
use App\Models\Testimonial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use Maatwebsite\Excel\Facades\Excel;

class TestimonialsController extends Controller
{
    /**
     * Display a listing of the testimonials.
     *
     * @return View
     */
    public function index()
    {
        $testimonials = Testimonial::with('customer')->get();
        return view('testimonials.index', compact('testimonials'));
    }

    public function getQuery()
    {
        return Testimonial::query()->with('customer');
    }

    public function exportXLSX()
    {
        return Excel::download(
            new TestimonialExport($this->getQuery()),
            'testimonial-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $testimonial = Testimonial::with('customer')->findOrFail($id);
        $view = view('testimonials.print_details', compact('testimonial'));
        CommonHelper::generatePdf($view->render(), 'testimonial-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new testimonial.
     *
     * @return View
     */
    public function create()
    {
        $customers = User::whereHas('roles', function ($q) {
            $q->where('name', 'Customer');
        })->pluck('name', 'id');
        return view('testimonials.create', compact('customers'));
    }

    /**
     * Store a new testimonial in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            Testimonial::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'testimonials.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('testimonials.index')
                             ->with('success_message', 'Testimonial was successfully added!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified testimonial.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $testimonial = Testimonial::with('customer')->findOrFail($id);
        return view('testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified testimonial.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $customers = User::whereHas('roles', function ($q) {
            $q->where('name', 'Customer');
        })->pluck('name', 'id');
        return view('testimonials.edit', compact('testimonial','customers'));
    }

    /**
     * Update the specified testimonial in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $testimonial = Testimonial::findOrFail($id);
            $oldData = $testimonial->toArray();
            $testimonial->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'testimonials.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('testimonials.index')
                             ->with('success_message', 'Testimonial was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified testimonial from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws Exception
     */
    public function destroy($id)
    {
        try {
            $testimonial = Testimonial::findOrFail($id);
            $oldData = $testimonial->toArray();
            $testimonial->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'testimonials.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('testimonials.index')
                             ->with('success_message', 'Testimonial was successfully deleted!');
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
            'customer_id' => 'required',
            'rating' => 'required|numeric|min:-2147483648|max:2147483647',
            'status' => 'required',
            'message' => 'required',

        ]);

        return $data;
    }
}
