<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Studio;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $values = [];
        foreach (array_keys(Studio::DEFAULTS) as $key) $values[$key] = Studio::setting($key);
        return view('admin.settings.edit', compact('values'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'usd_rate'     => 'required|numeric|min:1',
            'hour_rate_fc' => 'required|integer|min:0',
            'open_hour'    => 'required|integer|between:0,23',
            'close_hour'   => 'required|integer|between:1,24|gt:open_hour',
            'phone'        => 'nullable|string|max:30',
            'whatsapp'     => 'nullable|string|max:30',
            'address'      => 'nullable|string|max:150',
        ]);
        foreach ($data as $key => $value) Setting::put($key, $value ?? '');
        return back()->with('success', 'Paramètres enregistrés. Les prix en FC sont recalculés avec le nouveau taux.');
    }
}
