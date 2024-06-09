<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;
            $getDate = $request->getData;
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    // 予約キャンセル処理
    public function cancelReserve($id) {
    try {
        \Log::info("Trying to cancel reservation with ID: " . $id);
        // 予約設定を取得
        $reserveSetting = ReserveSettings::findOrFail($id);
        if (!$reserveSetting) {
            \Log::error("Reserve setting with ID: $id not found.");
            return response()->json(['success' => false, 'message' => 'Reserve setting not found.'], 404);
        }
        // 予約設定からユーザーを削除
        $reserveSetting->users()->detach(Auth::id());
        // 予約可能数をインクリメント
        $reserveSetting->increment('limit_users');
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function testDebugbar()
    {
        \Log::info('Debugbar is working.');
        return view('welcome');
    }
}
