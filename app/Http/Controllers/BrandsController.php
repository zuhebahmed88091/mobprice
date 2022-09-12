<?php

namespace App\Http\Controllers;

use App\Helpers\EventHelper;
use App\Models\Brand;
use App\Models\EventLog;
use Auth;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BrandsController extends Controller
{
    /**
     * Display a listing of the brands.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $brands = Brand::get();

        return view('brands.index', compact('brands'));
    }

    public function items()
    {
        $brands = Cache::remember('brands', 1800, function () {
            $brands = Brand::where('status', 'Active')->orderBy('sorting')->get();
            foreach ($brands as $brand) {
                $brand->image_url = asset('storage/brands/' . $brand->image);
                unset($brand->image);
                unset($brand->detail_url);
                unset($brand->created_at);
                unset($brand->updated_at);
                unset($brand->deleted_at);
            }
            return $brands;
        });

        return response()->json($brands, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new brand.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a new brand in the storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            Brand::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'brands.create',
                    'changes' => EventHelper::logForCreate($data),
                ]);
            }

            return redirect()->route('brands.index')
                ->with('success_message', 'Brand was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified brand.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return view('brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified brand.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('brands.edit', compact('brand'));
    }

    /**
     * Update the specified brand in the storage.
     *
     * @param  int $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $brand = Brand::findOrFail($id);
            $oldData = $brand->toArray();
            $brand->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'brands.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data),
                ]);
            }

            return redirect()->route('brands.index')
                ->with('success_message', 'Brand was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified brand from the storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $oldData = $brand->toArray();
            $brand->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'brands.delete',
                    'changes' => EventHelper::logForDelete($oldData),
                ]);
            }

            return redirect()->route('brands.index')
                ->with('success_message', 'Brand was successfully deleted!');

        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|min:1|max:255',
            'total_item' => 'required|numeric|min:-32768|max:32767',
            'sorting' => 'required',
            'revision' => 'required|numeric|min:-2147483648|max:2147483647',

        ]);

        return $data;
    }

}
