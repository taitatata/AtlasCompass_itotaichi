<?php

namespace App\Http\Controllers\Authenticated\Calendar\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\Admin\CalendarView;
use App\Calendars\Admin\CalendarSettingView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    //{スクール予約確認ページ}
    public function show(){
        $calendar = new CalendarView(time());
        // 現在のタイムスタンプを使用して、CalendarViewクラスのインスタンスを作成し、変数定義
        $reserveSettings = ReserveSettings::with('users')->get();
        // ReserveSettingsテーブルの全レコードを関連するusersテーブルのデータと共に取得し、変数定義
        return view('authenticated.calendar.admin.calendar', compact('calendar','reserveSettings'));
        // 'authenticated.calendar.admin.calendar'ビューを表示し、$calendarと$reserveSettingsを渡す
    }

    // public function reserveDetail($date, $part){
    //     $reservePersons = ReserveSettings::with('users')->where('setting_reserve', $date)->where('setting_part', $part)->get();
    //     // 指定された日付($date)と部($part)に対応する予約設定と関連するユーザー情報を取得し、変数定義
    //     return view('authenticated.calendar.admin.reserve_detail', compact('reservePersons', 'date', 'part'));
    //     // 'authenticated.calendar.admin.reserve_detail'ビューを表示し、$reservePersonsと$dateと$partを渡す
    // }

    //{スクール詳細ページ}
    public function reserveDetail(Request $request, $date, $part)
    {
        // クエリパラメータから日付と部を取得
        $date = $request->query('date', $date);
        $part = $request->query('part', $part);

        // 指定された日付($date)と部($part)に対応する予約設定を取得
        $reserveSetting = ReserveSettings::with('users')
            ->where('setting_reserve', $date)
            ->where('setting_part', $part)
            ->first();

        // 予約設定が見つからなかった場合の処理
        if (!$reserveSetting) {
            return redirect()->route('calendar.general.show', ['user_id' => auth()->id()])->with('error', '予約が見つかりませんでした。');
        }

        // 関連するユーザー情報を取得
        $users = $reserveSetting->users()->select('users.id', 'users.over_name', 'users.under_name')->get();

        // ビューにデータを渡して表示
        return view('authenticated.calendar.admin.reserve_detail', compact('reserveSetting', 'users'));
    }

    // {スクール枠登録ページ}
    public function reserveSettings(){
        $calendar = new CalendarSettingView(time());
        return view('authenticated.calendar.admin.reserve_setting', compact('calendar'));
    }

    // {スクール枠の更新}
    public function updateSettings(Request $request){
        $reserveDays = $request->input('reserve_day');
        // リクエストから 'reserve_day'　入力ちを取得し変数定義
        foreach($reserveDays as $day => $parts){
            // 各予約日のループ処理
            foreach($parts as $part => $frame){
                // 各部のループ処理
                ReserveSettings::updateOrCreate([
                    // レコードを作成または更新する
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                    // 'setting_reserve' カラムが$dateと一致し、'setting_part' カラムが$partと一致するレコードを更新する
                ],[
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                    'limit_users' => $frame,
                    //レコードが存在しない場合に新規作成する値の指定
                ]);
            }
        }
        return redirect()->route('calendar.admin.setting', ['user_id' => Auth::id()]);
        // 管理者の設定ページにリダイレクトし、現在のユーザーIDをパラメータとして渡す
    }

}
