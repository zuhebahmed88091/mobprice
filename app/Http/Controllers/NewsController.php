<?php

namespace App\Http\Controllers;

use App\Helpers\EventHelper;
// use App\Models\mobileRam;
use App\Models\EventLog;
use App\Models\Mobile;
use App\Models\News;
use Cache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NewsController extends Controller
{

    /**
     * Display a listing of the mobile rams.
     *
     * @return View
     */
    public function index()
    {
        $allNews = News::orderBy('id', 'DESC')->get();
        return view('news.index', compact('allNews'));
    }

    /**
     * Show the form for creating a new mobile ram.
     *
     * @return View
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Store a new mobile ram in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {

        $data = $this->getData($request);

        try {

            $news = News::create($data);

            if ($request->hasFile('image')) {
                // upload photo
                $image = $request->file('image');
                $fileExtension = $image->getClientOriginalExtension();
                $filename = 'news' . time() . '.' . $fileExtension;
                $image->storeAs('news', $filename);
            }

            $news->update(['image' => $filename]);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'news.create',
                    'changes' => EventHelper::logForCreate($data),
                ]);
            }

            return redirect()->route('news.index')
                ->with('success_message', 'news was successfully added!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified mobile ram.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $news = News::findOrFail($id);
        return view('news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified mobile ram.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $news = News::findOrfail($id);
        return view('news.edit', compact('news'));
    }

    /**
     * Update the specified mobile ram in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $news = News::findOrFail($id);
            $oldData = $news->toArray();
            $news->update($data);

            // upload photo if submit
            if ($request->hasFile('image')) {
                $previousFileName = $news->image;

                // upload photo
                $image = $request->file('image');
                $fileExtension = $image->getClientOriginalExtension();
                $filename = 'news' . time() . '.' . $fileExtension;
                $image->storeAs('news', $filename);

                $news->update(['image' => $filename]);

                // remove old file when different extension
                if ($previousFileName && $previousFileName != $filename) {
                    Storage::delete('news/' . $previousFileName);
                }
            }

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'news.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data),
                ]);
            }

            return redirect()->route('news.index')
                ->with('success_message', 'News was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified mobile ram from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $news = News::findOrFail($id);
            $oldData = $news->toArray();
            Storage::delete('news/' . $news->image);
            $news->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'news.destroy',
                    'changes' => EventHelper::logForDelete($oldData),
                ]);
            }

            return redirect()->route('news.index')
                ->with('success_message', 'News was successfully deleted!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    public function newsItems()
    {
        $allNews = Cache::remember('allNews', 1800, function () {
            $allNews = News::where('status', 'Active')->orderBy('id', 'DESC')->paginate(10);
            foreach ($allNews as $news) {
                $news->image_url = asset('storage/news/' . $news->image);
                unset($news->image);
                unset($news->created_at);
                unset($news->updated_at);
                unset($news->deleted_at);
            }
            return $allNews;
        });

        return response()->json($allNews->items(), Response::HTTP_OK);
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
            'title' => 'required|max:120',
            'status' => 'required',
            'short_description' => 'required|max:300',
            'description' => 'required',
        ];

        if ($request->isMethod('POST')) {
            $rules['image'] = 'required|file|max:2048';
        } else {

            if ($request->hasFile('image')) {
                $rules['image'] = 'required|file|max:2048';
            }
        }

        $data = $request->validate($rules);

        if (!empty($data['image'])) {
            unset($data['image']);
        }

        return $data;
    }

}
