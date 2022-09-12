<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\User;
use App\Models\FileType;
use App\Models\UploadedFile;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class MediaController extends Controller
{
    private $allowed_dir = ['products', 'articles', 'users', 'tickets', 'comments'];

    /**
     * Display a listing of the uploaded files.
     *
     * @return View
     */
    public function index()
    {
        $uploadedFiles = UploadedFile::with('filetype','user')->get();
        return view('uploaded_files.index', compact('uploadedFiles'));
    }

    /**
     * Upload image from summernote text editor.
     *
     * @param Request $request
     *
     * @return string
     */
    function summerNoteUpload(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageDir = $request->input('imageDir');

            $originalFileName = $image->getClientOriginalName();
            $fileExtension = $image->getClientOriginalExtension();

            if ($imageDir && in_array($imageDir, $this->allowed_dir)) {
                $fileDir = 'media/'. $imageDir . '/';
            } else {
                $fileDir = 'media/default/';
            }

            $fileType = FileType::where('name', $fileExtension)->first();
            if (empty($fileType)) {
                $fileTypeId = 1;
            } else {
                $fileTypeId = $fileType->id;
            }

            $uploadedFile = Media::create([
                'original_filename' => $originalFileName,
                'file_dir' => $fileDir,
                'file_type_id' => $fileTypeId,
                'user_id' => Auth::user()->id
            ]);

            $filename = $uploadedFile->id . '.' . $fileExtension;
            $image->storeAs($fileDir, $filename);

            $uploadedFile->update([
                'filename' => $filename
            ]);

            return asset('storage/' . $fileDir . $filename);
        } else {
            return 'Something goes wrong';
        }
    }

    /**
     * Show the form for creating a new uploaded file.
     *
     * @return View
     */
    public function create()
    {
        $fileTypes = FileType::pluck('name','id')->all();
        $users = User::pluck('name','id')->all();

        return view('uploaded_files.create', compact('fileTypes','users'));
    }

    /**
     * Store a new uploaded file in the storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            UploadedFile::create($data);

            return redirect()->route('uploaded_files.index')
                             ->with('success_message', 'Uploaded File was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified uploaded file.
     *
     * @param int $id
     *
     * @return View
     */
    public function show($id)
    {
        $uploadedFile = UploadedFile::with('filetype','user')->findOrFail($id);

        return view('uploaded_files.show', compact('uploadedFile'));
    }

    /**
     * Show the form for editing the specified uploaded file.
     *
     * @param int $id
     *
     * @return View
     */
    public function edit($id)
    {
        $uploadedFile = UploadedFile::findOrFail($id);
        $fileTypes = FileType::pluck('name','id')->all();
        $users = User::pluck('name','id')->all();

        return view('uploaded_files.edit', compact('uploadedFile','fileTypes','users'));
    }

    /**
     * Update the specified uploaded file in the storage.
     *
     * @param  int $id
     * @param Request $request
     *
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $uploadedFile = UploadedFile::findOrFail($id);
            $uploadedFile->update($data);

            return redirect()
                ->route('uploaded_files.index')
                ->with('success_message', 'Uploaded File was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified uploaded file from the storage.
     *
     * @param  int $id
     *
     * @return RedirectResponse | Redirector
     */
    public function destroy($id)
    {
        try {
            $uploadedFile = UploadedFile::findOrFail($id);
            $uploadedFile->delete();

            return redirect()
                ->route('uploaded_files.index')
                ->with('success_message', 'Uploaded File was successfully deleted!');

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
            'filename' => 'required|string|min:1|max:50',
            'original_filename' => 'required|string|min:1|max:255',
            'file_type_id' => 'required',
            'user_id' => 'required',
        ]);

        return $data;
    }
}
