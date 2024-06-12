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
        // 現在のタイムスタンプを使用して、CalendarViewのインスタンスを作成し、変数定義
        return view('authenticated.calendar.general.calendar', compact('calendar'));
        // 'authenticated.calendar.general.calendar' ビューを表示し、$calendar 変数をビューに渡す
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        // トランザクションの開始
        try{
            $getPart = $request->getPart;
            $getDate = $request->getData;
            // リクエストからデータを取得して変数定義

            $reserveDays = array_filter(array_combine($getDate, $getPart));
            // 日付と部の配列を結合し、空の値を取り除く
            foreach($reserveDays as $key => $value){
            //各予約日と部についてループ処理を行う
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)
                                                ->where('setting_part', $value)
                                                ->first();
                                                // 指定された日付と部に対応する予約設定を取得
                $reserve_settings->decrement('limit_users');
                // 予約可能な人数を1減らす
                $reserve_settings->users()->attach(Auth::id());
                // 現在のユーザーを予約設定に関連づける
            }
            DB::commit();
            // トランザクションをコミット（保存）
        }catch(\Exception $e){
            DB::rollback();
            // エラーが発生した場合、トランザクションをロールバック（取り消し）
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
        // ユーザーのカレンダー表示ページにリダイレクト
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        // トランザクションを開始する
        try {
            // フォームから送信された予約設定IDを取得
            $reserveSettingId = $request->input('reserve_setting_id');

            // reserve_setting_users テーブルから指定された ID のレコードを削除
            DB::table('reserve_setting_users')
                ->where('id', $reserveSettingId)
                ->delete();

            DB::commit();// トランザクションをコミットする
            return response()->json(['success' => true]);// 成功メッセージをJSON形式で出す
        } catch (\Exception $e) {
            DB::rollback();// トランザクションをロールバックする
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            // 失敗メッセージをJSON形式で出す
        }
    }

}
