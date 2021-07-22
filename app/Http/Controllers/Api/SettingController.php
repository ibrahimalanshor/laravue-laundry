<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Setting;
use App\Http\Resources\SettingResource;
use App\Http\Requests\UpdateSettingRequest;

class SettingController extends Controller
{

    public function index(Setting $setting): SettingResource
    {
        $setting = $setting->first();

        return new SettingResource($setting);
    }

    public function update(UpdateSettingRequest $request, Setting $setting): SettingResource
    {
        $setting = $setting->first();
        $setting->update($request->all());

        return new SettingResource($setting);
    }

}
