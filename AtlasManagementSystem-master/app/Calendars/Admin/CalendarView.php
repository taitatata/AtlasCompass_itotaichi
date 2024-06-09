<?php

// スクール予約確認ページ

namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Users\User;
use App\Models\Calendars\ReserveSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalendarView{
    private $carbon;

    function __construct($date){
        $this->carbon = new Carbon($date);
    }

    public function getTitle(){
        return $this->carbon->format('Y年n月');
    }

    public function render(){
        $html = [];
        $html[] = '<div class="calendar text-center">';// カレンダーの外枠を作成
        $html[] = '<table class="table m-auto border">';// テーブルを作成
        $html[] = '<thead>';// テーブルヘッダーの開始タグ
        $html[] = '<tr>';// テーブル行の開始タグ
        $html[] = '<th class="border">月</th>';// ヘッダーセル（曜日：月）
        $html[] = '<th class="border">火</th>';// ヘッダーセル（曜日：火）
        $html[] = '<th class="border">水</th>';// ヘッダーセル（曜日：水）
        $html[] = '<th class="border">木</th>';// ヘッダーセル（曜日：木）
        $html[] = '<th class="border">金</th>';// ヘッダーセル（曜日：金）
        $html[] = '<th class="border">土</th>';// ヘッダーセル（曜日：土）
        $html[] = '<th class="border">日</th>';// ヘッダーセル（曜日：日）
        $html[] = '</tr>';// テーブル行の終了タグ
        $html[] = '</thead>';// テーブルヘッダーの終了タグ
        $html[] = '<tbody>';// テーブルボディの開始タグ

        $weeks = $this->getWeeks();// 月ごとの週情報を取得

        foreach($weeks as $week){
            $html[] = '<tr class="'.$week->getClassName().'">';// 週ごとのクラスを持つテーブル行の開始タグ
            $days = $week->getDays();// 週ごとの日付情報を取得
            foreach($days as $day){
            if ($day->everyDay() !== null && $day->everyDay() !== '') {
                $startDay = $this->carbon->format("Y-m-01");// 月初の日付を取得
                $toDay = $this->carbon->format("Y-m-d");// 当日の日付を取得
                if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
                    $html[] = '<td class="past-day border">';// 過去の日付の場合のセルの開始タグ
                }else{
                    $html[] = '<td class="border '.$day->getClassName().'">';// 未来の日付の場合のセルの開始タグ
                }
                $html[] = $day->render();// 日付情報をセルに表示
                $html[] = $this->renderDayPartCounts($day->everyDay()); // 追記：各部の予約数を表示
                // $html[] = $day->dayPartCounts($day->everyDay());
                $html[] = '</td>';// テーブルセルの終了タグ
                } else {
                    $html[] = '<td class="border"></td>'; // 日付がない場合は空のセルを表示
                }
            }
            $html[] = '</tr>';// テーブル行の終了タグ
        }
        $html[] = '</tbody>';// テーブルボディの終了タグ
        $html[] = '</table>';// テーブルの終了タグ
        $html[] = '</div>';// カレンダーの外枠の終了タグ

        return implode("", $html);// HTML文字列を結合して返す
    }

    protected function renderDayPartCounts($date){
        $html = [];//結果として返すHTMLを格納する配列を初期化
        $parts = ['1部' => 1, '2部' => 2, '3部' => 3];
        foreach($parts as $partName => $partNum){ //各部についてループ処理を行う
            $count = $this->getPartCount($date, $partNum);// 部ごとの予約数を取得
            $reserveSetting = $this->getReserveId($date, $partNum); // 予約設定を取得

            if ($reserveSetting) {
            $html[] = "<div><a href=\"" . route('calendar.admin.detail', ['date' => $date, 'part' => $partNum]) . "\" class=\"reserve-link\">{$partName}</a>　　　{$count}</div>"; // 予約リンクを生成
            } else {
                // 予約がない場合
                $html[] = "<div>{$partName}　　　{$count}人</div>"; // 予約リンクなし
            }
        }
        return implode("", $html);// HTML文字列を結合して返す
    }

    protected function getPartCount($date, $partNum)
{
    // reserve_setting_usersテーブルで、指定された日付と部に対応する予約数をカウント
    return DB::table('reserve_setting_users') // reserve_setting_users テーブルをクエリの開始点として選択
    ->join('reserve_settings', 'reserve_setting_users.reserve_setting_id', '=', 'reserve_settings.id') // reserve_setting_users テーブルと reserve_settings テーブルを reserve_setting_id で結合
    ->where('reserve_settings.setting_reserve', $date) // reserve_settings テーブルの setting_reserve カラムが指定された日付と一致するレコードを選択
    ->where('reserve_settings.setting_part', $partNum) // reserve_settings テーブルの setting_part カラムが指定された部と一致するレコードを選択
    ->count(); // 条件に一致するレコードの数をカウント
}

protected function getReserveId($date, $partNum)
{
    return ReserveSettings::where('setting_reserve', $date)
                            ->where('setting_part', $partNum)
                            ->first();
}

//     protected function getReserveId($date, $part) {
//     $setting = ReserveSettings::where('setting_reserve', $date)->where('setting_part', $part)->first(); // 予約設定を取得

//     // デバッグ情報の追加
//     if ($setting) {
//         \Log::info("Found setting: " . json_encode($setting));
//         // ログインユーザーのIDを取得
//         $userId = Auth::id();
//         // reserve_setting_usersテーブルから該当レコードを取得
//         $reservation = DB::table('reserve_setting_users')
//                             ->where('reserve_setting_id', $setting->id)
//                             ->where('user_id', $userId)
//                             ->first();
//         // デバッグ情報の追加
//         if ($reservation) {
//             \Log::info("Found reservation: " . json_encode($reservation));
//             return $reservation->id;
//         } else {
//             \Log::warning("No reservation found for reserve_setting_id: " . $setting->id . " and user_id: " . $userId);
//         }
//     } else {
//         \Log::warning("No setting found for date: " . $date . " and part: " . $part);
//     }
//     return null; // 予約設定が存在しない場合、または予約が見つからない場合はnullを返す
// }

//     protected function getReserveUserId($reserveSettingId, $userId){
//         $reserveSettingUser = DB::table('reserve_setting_users')
//                                 ->where('reserve_setting_id', $reserveSettingId)
//                                 ->where('user_id', $userId)
//                                 ->first(); // ユーザーの予約データを取得
//         return $reserveSettingUser ? $reserveSettingUser->id : null; // 予約データが存在すればそのIDを返す
//     }

//     public function getPartCount($date, $part)
//     {
//         return ReserveSettings::where('setting_reserve', $date)
//                                 ->where('setting_part', $part)
//                                 ->count();
//     }

    protected function getWeeks(){
        $weeks = [];
        $firstDay = $this->carbon->copy()->firstOfMonth();// 月初の日付を取得
        $lastDay = $this->carbon->copy()->lastOfMonth();// 月末の日付を取得
        $week = new CalendarWeek($firstDay->copy());// 最初の週の情報を取得
        $weeks[] = $week;// 週の情報を配列に追加
        $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();// 次の週の開始日を取得
        while($tmpDay->lte($lastDay)){// 月末まで週情報を取得
            $week = new CalendarWeek($tmpDay, count($weeks));// 次の週の情報を取得
            $weeks[] = $week;// 週の情報を配列に追加
            $tmpDay->addDay(7);// 次の週に進む
        }
        return $weeks;// 週情報の配列を返す
    }
}
