<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhatsappSetting\WhatsappSettingStoreRequest;
use App\Models\WhatsAppSetting;
use Illuminate\Support\Facades\Auth;

class WhatsAppSettingController extends Controller
{
    public function index()
    {
        $whatsAppSetting = WhatsAppSetting::first();
        return view('backend.whatsapp-setting.index', compact('whatsAppSetting'));
    }

    public function store(WhatsappSettingStoreRequest $request)
    {
        try {
            $whatsAppSetting = WhatsAppSetting::first();
            if (empty($whatsAppSetting)) {
                $whatsAppSetting = new WhatsAppSetting;
            }
            $whatsAppSetting->user_id = Auth::id();
            $whatsAppSetting->whatsapp_from_name = $request->whatsapp_from_name;
            $whatsAppSetting->whatsapp_number = $request->whatsapp_number;
            $whatsAppSetting->save();
            return redirect()->back()->with(['success' => 'WhatsApp setting update successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
