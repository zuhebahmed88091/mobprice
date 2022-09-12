<?php

namespace App\Http\Controllers;

use App\Exports\TagExport;
use App\Helpers\CommonHelper;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use Maatwebsite\Excel\Facades\Excel;

class TagsController extends Controller
{
    /**
     * Display a listing of the tags.
     *
     * @return View
     */
    public function index()
    {
        $tags = Tag::with('creator')->get();
        return view('tags.index', compact('tags'));
    }

    public function getQuery()
    {
        return Tag::query()->with('creator');
    }

    public function exportXLSX()
    {
        return Excel::download(
            new TagExport($this->getQuery()),
            'tag-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $tag = Tag::with('creator')->findOrFail($id);
        $view = view('tags.print_details', compact('tag'));
        CommonHelper::generatePdf($view->render(), 'tag-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new tag.
     *
     * @return View
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Generate option list for tags
     *
     * @param array $selectedTags
     * @return string
     */
    function getOptionListForTags($selectedTags = [])
    {
        $optionHtml = '';
        $tags = Tag::where('status', 'Active')->get();
        if ($tags->isNotEmpty()) {
            foreach ($tags as $tag) {
                $selected = in_array($tag->id, $selectedTags) ? ' selected' : '';
                $optionHtml .= '<option value="' . $tag->id . '" ' . $selected . '>' . $tag->title . '</option>';
            }
        }

        return $optionHtml;
    }

    /**
     * Store a new tag in the storage.
     *
     * @param Request $request
     * @param boolean $isAjax
     * @return RedirectResponse | Redirector | array
     */
    public function store(Request $request, $isAjax = false)
    {
        try {
            $data = $this->getData($request);
            $data['created_by'] = Auth::user()->id;
            $tag = Tag::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'tags.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            if ($isAjax) {
                $selectedTags = [];
                if (!empty($data['tags'])) {
                    $selectedTags = $data['tags'];
                }
                $selectedTags[] = $tag->id;

                $tagsDropdown = $this->getOptionListForTags($selectedTags);
                return ['status' => 'OK', 'html' => $tagsDropdown];
            } else {
                return redirect()->route('tags.index')->with('success_message', 'Tag was successfully added!');
            }
        } catch (Exception $exception) {
            if ($isAjax) {
                return ['status' => 'FAILED', 'message' => 'Something goes wrong!!!'];
            } else {
                return back()->withInput()->withErrors(['unexpected_error' => 'Unexpected error occurred!']);
            }
        }
    }

    /**
     * Display the specified tag.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $tag = Tag::with('creator')->findOrFail($id);
        return view('tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified tag.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);

        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $tag = Tag::findOrFail($id);
            $oldData = $tag->toArray();
            $tag->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'tags.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('tags.index')->with('success_message', 'Tag was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()->withErrors(['unexpected_error' => 'Unexpected error occurred!']);
        }
    }

    /**
     * Remove the specified tag from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $oldData = $tag->toArray();
            $tag->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'tags.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('tags.index')
                             ->with('success_message', 'Tag was successfully deleted!');
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
            'status' => 'required',
            'tags' => "nullable|array",
            'tags.*'  => "required|integer|distinct"
        ]);

        return $data;
    }
}
