<?php

namespace App\Http\Controllers;

use App\Exports\TicketExport;
use App\Helpers\CommonHelper;
use App\Models\Comment;
use App\Models\User;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use Maatwebsite\Excel\Facades\Excel;

class TicketsController extends Controller
{
    /**
     * Display a listing of the tickets.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataTable($request);
        }

        $departments = [];
        $users = User::where('status', 'Active')->orderBy('name', 'ASC')->pluck('name', 'id');
        $tickets = Ticket::with('creator', 'assignto')->get();

        $assignTos = User::whereHas('roles', function ($q) {
            $q->where('name', 'Agent');
        })->orderBy('name', 'ASC')->get();

        return view('tickets.index', compact('tickets', 'departments', 'users', 'assignTos'));
    }

    public function getQuery($request)
    {
        $query = Ticket::query()->with('creator', 'department', 'assignto');
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $query->whereBetween('created_at', [
                $request->startDate . ' 00:00:00',
                $request->endDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->departmentId)) {
            $query->where('department_id', $request->departmentId);
        }

        if (!empty($request->ticketId)) {
            $query->where('id', ($request->ticketId - 10000));
        }

        if (!empty($request->agentAction)) {
            $query->where('agent_action', $request->agentAction);
        }

        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }

        if (!empty($request->priority)) {
            $query->where('priority', $request->priority);
        }

        if (!empty($request->userId)) {
            $query->where('created_by', $request->userId);
        }

        if (Auth::user()->hasRole('Agent')) {
            $departmentId = User::where('id', Auth::user()->id)->value('department_id');
            $query->where(function ($q) use ($departmentId) {
                $q->where('assign_to', Auth::user()->id);
                $q->orWhere(function ($q1) use ($departmentId) {
                    $q1->where('department_id', $departmentId);
                });
            });
        } else if (!empty($request->assignTo)) {
            $query->where('assign_to', $request->assignTo);
        }

        return $query;
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new TicketExport($this->getQuery($request)),
            'ticket-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $ticket = Ticket::with('creator', 'department', 'assignto')->findOrFail($id);
        $view = view('tickets.print_details', compact('ticket'));
        CommonHelper::generatePdf($view->render(), 'ticket-details-' . date('Ymd'));
    }

    /**
     * Display a json listing for table body.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    private function getDataTable($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addColumn('action', function ($ticket) {
                return view('tickets.action', compact('ticket'));
            })
            ->addIndexColumn()
            ->addColumn('checkbox', function ($ticket) {
                return view('tickets.checkbox', compact('ticket'));
            })
            ->editColumn('id', function ($ticket) {
                return ($ticket->id + 10000);
            })
            ->editColumn('subject', function ($ticket) {
                $agentActionTag = '';
                if ($ticket->agent_action == 'New') {
                    $agentActionTag = ' <small class="label label-warning">' . $ticket->agent_action . '</small>';
                } else if ($ticket->agent_action == 'Not Answered') {
                    $agentActionTag = ' <small class="label label-danger">' . $ticket->agent_action . '</small>';
                }

                return '<a href="' . route('tickets.comments', $ticket->id) . '">
                            ' . $ticket->subject . $agentActionTag . '
                        </a>';
            })
            ->editColumn('department', function ($ticket) {
                return optional($ticket->department)->name;
            })
            ->editColumn('assignTo', function ($ticket) {
                return optional($ticket->assignTo)->name;
            })
            ->editColumn('created_by', function ($ticket) {
                return optional($ticket->creator)->name;
            })
            ->editColumn('status', function ($ticket) {
                if ($ticket->status == 'Spam') {
                    return '<span class="label label-danger">Spam</span>';
                } else if ($ticket->status == 'Closed') {
                    return '<span class="label label-warning">Closed</span>';
                } else {
                    return '<span class="label label-success">Open</span>';
                }
            })
            ->rawColumns(['action', 'checkbox', 'subject', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new ticket.
     *
     * @return View
     */
    public function create()
    {
        $departments = Department::all()->pluck('name', 'id');
        $assignTos = User::all()->pluck('name', 'id');
        $products = Product::where('status', 'Active')->pluck('title', 'id');
        return view('tickets.create', compact('departments', 'assignTos', 'products'));
    }

    /**
     * Store a new ticket in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            $data['created_by'] = Auth::user()->id;
            $ticket = Ticket::create($data);

            // Sync all media inside summer note content
            CommonHelper::syncAllMediaInHtmlContent($ticket->id, 'tickets.message', $data['message']);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'tickets.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()
                ->route('tickets.index')
                ->with('success_message', 'Ticket was successfully added!');
        } catch (Exception $exception) {
            return back()
                ->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified ticket.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $ticket = Ticket::with('creator', 'department', 'assignto')->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $departments = Department::all()->pluck('name', 'id');
        $assignTos = User::all()->pluck('name', 'id');
        $products = Product::where('status', 'Active')->pluck('title', 'id');
        return view('tickets.edit', compact('ticket', 'departments', 'assignTos', 'products'));
    }

    /**
     * Update the specified ticket in the storage.
     *
     * @param int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $ticket = Ticket::findOrFail($id);
            $oldData = $ticket->toArray();
            $ticket->update($data);

            // Sync all media inside summer note content
            CommonHelper::syncAllMediaInHtmlContent($id, 'tickets.message', $data['message']);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'tickets.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('tickets.index')
                ->with('success_message', 'Ticket was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    public function comments($ticketId)
    {
        $ticket = Ticket::with('department', 'creator')->findOrFail($ticketId);
        $comments = Comment::where('ticket_id', $ticketId)->orderBy('created_at', 'DESC')->get();

        return view('tickets.comments', compact('ticket', 'comments'));
    }

    /**
     * Store a new comment in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function storeComment(Request $request)
    {
        try {

            $data = $this->getCommentData($request);
            $data['user_id'] = Auth::user()->id;

            $ticketId = $data['ticket_id'];

            $comment = Comment::create($data);

            // Sync all media inside summer note content
            CommonHelper::syncAllMediaInHtmlContent($comment->id, 'comments.message', $data['message']);

            $ticket = Ticket::findOrFail($ticketId);
            $ticket->update([
                'agent_action' => 'Answered',
                'customer_action' => 'Unread'
            ]);

            return redirect()->route('tickets.comments', $ticketId)
                ->with('success_message', 'Your reply is added successfully!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Assign tickets for a agent
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function storeAssignTo(Request $request)
    {
        $data = $this->getAssignToData($request);

        try {

            if (empty($data['department_id'])) {
                $user = User::with('department')->find($data['assign_to']);
                $data['department_id'] = optional($user)->department_id;
            }

            Ticket::whereIn('id', $data['ticketIds'])->update([
                'assign_to' => $data['assign_to'],
                'department_id' => $data['department_id']
            ]);

            return redirect()->route('tickets.index')
                ->with('success_message', 'Tickets are assigned to agent successfully!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Please select at least a ticket and assign to!']);
        }
    }

    /**
     * Assign tickets for a agent
     *
     * @param int $ticketId
     * @return RedirectResponse | Redirector
     */
    public function storePickMe($ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
            if (empty($ticket->assign_to)) {
                $ticket->update(['assign_to' => Auth::user()->id]);
                return redirect()->route('tickets.index')
                    ->with('success_message', 'Tickets are picked for me successfully!');
            } else {
                return back()->withInput()
                    ->withErrors(['unexpected_error' => 'Somethings already picked the ticket meanwhile!']);
            }
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Somethings goes to wrong!']);
        }
    }

    /**
     * Remove the specified ticket from the storage.
     *
     * @param int $id
     * @return RedirectResponse | Redirector
     * @throws Exception
     */
    public function destroy($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $oldData = $ticket->toArray();
            $ticket->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'tickets.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('tickets.index')
                ->with('success_message', 'Ticket was successfully deleted!');
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
            'subject' => 'required|string|min:1|max:255',
            'department_id' => 'required',
            'priority' => 'required',
            'status' => 'required',
            'assign_to' => 'nullable|numeric|min:-2147483648|max:2147483647',
            'product_id' => 'required|numeric|min:-2147483648|max:2147483647',
            'message' => 'required'
        ]);

        return $data;
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getCommentData(Request $request)
    {
        $data = $request->validate([
            'ticket_id' => 'required',
            'message' => 'required',
        ]);

        return $data;
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getAssignToData(Request $request)
    {
        $data = $request->validate([
            'ticketIds' => 'required|array',
            'ticketIds.*' => 'integer',
            'assign_to' => 'required_without:department_id',
            'department_id' => 'required_without:assign_to',
        ], [
            'ticketIds.required' => 'You have to choose at least a ticket'
        ]);

        return $data;
    }

    /**
     * Moves the attached file to the server.
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string
     */
    protected function moveFile($file)
    {
        if (!$file->isValid()) {
            return '';
        }

        return $file->store(config('codegenerator.files_upload_path'), config('filesystems.default'));
    }
}
