<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Media;
use App\Models\Ticket;
use Illuminate\View\View;

class DownloadsController extends Controller
{
    /**
     * Display a listing of the departments.
     *
     * @return View
     */
    public function index()
    {
        $departments = Department::get();
        return view('departments.index', compact('departments'));
    }

    public function download($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $file_path = public_path('storage/' . $ticket->attachment_image);
        return response()->download($file_path);
    }

    public function downloadAttachment($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        $file_path = public_path('storage/' . $media->file_dir . $media->filename);
        return response()->download($file_path);
    }
}
