<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;

class CommentsController extends Controller
{
    /**
     * Display a listing of the comments.
     *
     * @return View
     */
    public function index()
    {
        $comments = Comment::with('ticket','user')->get();
        return view('comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new comment.
     *
     * @return View
     */
    public function create()
    {
        $tickets = Ticket::all()->pluck('subject','id');
		$users = User::all()->pluck('name','id');
        return view('comments.create', compact('tickets','users'));
    }

    /**
     * Store a new comment in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            Comment::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'comments.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('comments.index')
                             ->with('success_message', 'Comment was successfully added!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified comment.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $comment = Comment::with('ticket','user')->findOrFail($id);
        return view('comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified comment.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        $tickets = Ticket::all()->pluck('subject','id')->except($id);
		$users = User::all()->pluck('name','id')->except($id);
        return view('comments.edit', compact('comment','tickets','users'));
    }

    /**
     * Update the specified comment in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $comment = Comment::findOrFail($id);
            $oldData = $comment->toArray();
            $comment->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'comments.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('comments.index')
                             ->with('success_message', 'Comment was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified comment from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $oldData = $comment->toArray();
            $comment->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'comments.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('tickets.comments', $comment->ticket_id)
                             ->with('success_message', 'Comment was successfully deleted!');
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
            'ticket_id' => 'required',
            'user_id' => 'required',
            'message' => 'required',

        ]);

        return $data;
    }

}
