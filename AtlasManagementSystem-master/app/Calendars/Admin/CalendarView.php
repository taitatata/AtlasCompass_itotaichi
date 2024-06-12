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
        return '<span class="title_style">' . $this->carbon->format('Y年n月') . '</span>';
    }

    public function render(){
        $html = [];
        $html[] = '<div class="text-center">';// カレンダーの外枠を作成
        $html[] = '<table class="border table_style">';// テーブルを作成
        $html[] = '<thead>';// テーブルヘッダーの開始タグ
        $html[] = '<tr>';// テーブル行の開始タグ
        $html[] = '<th class="border margin_style">月</th>';// ヘッダーセル（曜日：月）
        $html[] = '<th class="border margin_style">火</th>';// ヘッダーセル（曜日：火）
        $html[] = '<th class="border margin_style">水</th>';// ヘッダーセル（曜日：水）
        $html[] = '<th class="border margin_style">木</th>';// ヘッダーセル（曜日：木）
        $html[] = '<th class="border margin_style">金</th>';// ヘッダーセル（曜日：金）
        $html[] = '<th class="border margin_style day-sat">土</th>';// ヘッダーセル（曜日：土）
        $html[] = '<th class="border margin_style day-sun">日</th>';// ヘッダーセル（曜日：日）
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
                    $html[] = '<td class="past-day border '.$day->getClassName().'">';// 過去の日付の場合のセルの開始タグ
                }else{
                    $html[] = '<td class="future_day border '.$day->getClassName().'">';// 未来の日付の場合のセルの開始タグ
                }
                // $html[] = $day->render();// 日付情報をセルに表示
                $dayRendered = $day->render();
                $dayRenderedWithClass = str_replace('<p', '<p class="date_style '.$day->getClassName().'"', $dayRendered); // pタグにクラスを追加
                $html[] = $dayRenderedWithClass; // 日付情報をセルに表示
                $html[] = '<div class="count_style">';
                $html[] = $this->renderDayPartCounts($day->everyDay()); // 各部の予約数を表示
                $html[] = '</div>';
                // $html[] = $day->dayPartCounts($day->everyDay());
                $html[] = '</td>';// テーブルセルの終了タグ
                } else {
                    $html[] = '<td class="border date_blank"></td>'; // 日付がない場合は空のセルを表示
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

            $html[] = "<div class=\"link_container\"><a href=\"" . route('calendar.admin.detail', ['date' => $date, 'part' => $partNum]) . "\" class=\"reserve-link link_style\">{$partName}</a></div>";
            $html[] = "<div class=\"link_container\"><span class=\"count_text_style\">{$count}</span></div>"; // 予約リンクを生成
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
