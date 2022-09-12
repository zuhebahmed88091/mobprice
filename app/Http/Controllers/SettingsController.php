<?php

namespace App\Http\Controllers;

use App\Models\FileType;
use App\Models\Group;
use App\Models\Setting;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Auth;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display a listing of the settings.
     *
     * @return View
     */
    public function index()
    {
        $settings = Setting::get();
        return view('settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new setting.
     *
     * @return View
     */
    public function create()
    {
        return view('settings.create');
    }

    /**
     * Store a new setting in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            Setting::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'settings.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('settings.index')
                             ->with('success_message', 'Setting was successfully added!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified setting.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $setting = Setting::findOrFail($id);
        return view('settings.show', compact('setting'));
    }

    /**
     * Show the form for editing the specified setting.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $setting = Setting::findOrFail($id);

        return view('settings.edit', compact('setting'));
    }

    /**
     * Update the specified setting in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $setting = Setting::findOrFail($id);
            $oldData = $setting->toArray();
            $setting->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'settings.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('settings.index')
                             ->with('success_message', 'Setting was successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified setting from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     */
    public function destroy($id)
    {
        try {
            $setting = Setting::findOrFail($id);
            $oldData = $setting->toArray();
            $setting->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'settings.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('settings.index')
                             ->with('success_message', 'Setting was successfully deleted!');
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
            'constant' => 'required|string|min:1|max:255',
            'value' => 'required|string|min:1|max:100',
            'field_type' => 'required',
            'options' => 'required|string|min:1|max:255',
            'status' => 'required',

        ]);

        return $data;
    }

    /**
     * Show the form for updating all setting.
     *
     * @param  int $groupId
     * @return View
     */
    public function group($groupId = 1)
    {
        $settings = Setting::where('group_id', $groupId)->orderBy('sorting', 'ASC')->get();
        if (!empty($settings)) {
            foreach ($settings as $setting) {
                $options = array();
                if ($setting->field_type == 'Select' || $setting->field_type == 'MultiSelect') {
                    $optionList = explode(',', $setting->options);
                    foreach ($optionList as $singleOption) {
                        $parts = explode('|', $singleOption);
                        $options[$parts[1]] = $parts[0];
                    }
                    $setting->options = $options;
                } else if ($setting->field_type == 'DateFormat') {
                    $setting->options = explode('|', $setting->options);
                } else if ($setting->field_type == 'Boolean') {
                    list($setting->dataOn, $setting->dataOff) = explode('|', $setting->options);
                }
            }
        }

        $groups = Group::where('status', 'Active')->orderBy('sorting', 'ASC')->get();
        $selectedGroup = Group::findOrFail($groupId);
        $products = [];

        return view('settings.group', compact('settings', 'groups', 'selectedGroup', 'products'));
    }

    /**
     * Show the form for updating all setting.
     *
     * @return View
     */
    public function all()
    {
        $settings = Setting::orderBy('sorting', 'ASC')->get();
        if (!empty($settings)) {
            foreach ($settings as $setting) {
                $options = array();
                if ($setting->field_type == 'Options') {
                    $optionList = explode(',', $setting->options);
                    foreach ($optionList as $singleOption) {
                        $parts = explode('|', $singleOption);
                        $options[$parts[1]] = $parts[0];
                    }
                    $setting->options = $options;
                }
            }
        }

        return view('settings.all', compact('settings'));
    }

    /**
     * Update the specified setting in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function updateBatch(Request $request)
    {
        try {
            $inputList = $request->all();
            $settings = Setting::orderBy('sorting', 'ASC')->get();
            if ($settings->isNotEmpty()) {
                foreach ($settings as $setting) {
                    if (isset($inputList[$setting->constant])) {
                        if ($setting->field_type == 'File') {
                            if ($request->hasFile($setting->constant)) {
                                // upload photo
                                $photo = $request->file($setting->constant);
                                $fileExtension = $photo->getClientOriginalExtension();

                                // get file type from database
                                $fileType = FileType::where('name', $fileExtension)->first();
                                if (empty($fileType)) {
                                    return back()->withInput()
                                        ->withErrors(['unexpected_error' => 'Wrong file type! Please provide valid files']);
                                }

                                // Store file in site folder
                                $filename = 'logo-' . time() . '.' . $fileExtension;
                                $photo->storeAs('sites', $filename);

                                // update logo settings in db
                                $settingLine = Setting::findOrFail($setting->id);
                                $settingLine->update(array(
                                    'value' => $filename
                                ));
                            }
                        } else if ($setting->field_type == 'MultiSelect') {
                            $settingLine = Setting::findOrFail($setting->id);
                            $settingLine->update([
                                'value' => implode('|', $inputList[$setting->constant])
                            ]);
                        } else {
                            $settingLine = Setting::findOrFail($setting->id);
                            $settingLine->update([
                                'value' => $inputList[$setting->constant]
                            ]);
                        }

                        // Update variable in .env file
                        if ($setting->applied_env) {
                            $this->setEnvironmentValue($setting->constant, $inputList[$setting->constant]);
                        }
                    }
                }
            }

            return redirect()->route('settings.group', $request->groupId)
                ->with('success_message', 'Settings were successfully updated!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    private function setEnvironmentValue($envKey, $newValue)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $envFileString = file_get_contents($path);
            $envFileString .= "\n"; // In case the searched variable is in the last line without \n

            $keyPosition = strpos($envFileString, "$envKey=");
            $endOfLinePosition = strpos($envFileString, "\n", $keyPosition);
            $oldLine = substr($envFileString, $keyPosition, $endOfLinePosition - $keyPosition);
            $newLine = "$envKey=$newValue";

            if ($oldLine !== $newLine) {
                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $envFileString .= $newLine . "\n";
                } else {
                    $envFileString = str_replace($oldLine, $newLine, $envFileString);
                }

                $envFileString = substr($envFileString, 0, -1);
                if (file_put_contents($path, $envFileString)) {
                    session(['isConfigCache'=> true]);
                    return true;
                }
            }
        }

        return false;
    }
}
