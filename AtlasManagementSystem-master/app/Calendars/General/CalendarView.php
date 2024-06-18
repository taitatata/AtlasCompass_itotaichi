<?php

// スクール予約ページ

namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Calendars\ReserveSettings;

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
        $html[] = '<div class="text-center">';
        $html[] = '<table class="border table_style">';
        $html[] = '<thead>';
        $html[] = '<tr>';
        $html[] = '<th class="border margin_style">月</th>';
        $html[] = '<th class="border margin_style">火</th>';
        $html[] = '<th class="border margin_style">水</th>';
        $html[] = '<th class="border margin_style">木</th>';
        $html[] = '<th class="border margin_style">金</th>';
        $html[] = '<th class="border margin_style day-sat">土</th>';
        $html[] = '<th class="border margin_style day-sun">日</th>';
        $html[] = '</tr>';
        $html[] = '</thead>';
        $html[] = '<tbody>';

        $weeks = $this->getWeeks();
        foreach($weeks as $week){
            $html[] = '<tr class="'.$week->getClassName().'">';

            $days = $week->getDays();
            foreach($days as $day){
                $startDay = $this->carbon->format("Y-m-01"); // 月初の日付を取得
                $toDay = $this->carbon->format("Y-m-d"); // 当日の日付を取得

                if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
                    $html[] = '<td class="past-day border '.$day->getClassName().'">'; // 過去の日付の場合のセルの開始タグ
                } else {
                    $html[] = '<td class="future_day border '.$day->getClassName().'">'; // 未来の日付の場合のセルの開始タグ
                }
                $dayRendered = $day->render();
                $dayRenderedWithClass = str_replace('<p', '<p class=" '.$day->getClassName().'"', $dayRendered); // pタグにクラスを追加
                $html[] = $dayRenderedWithClass; // 日付情報をセルに表示

                $reserveDate = $day->authReserveDate($day->everyDay())->first();
                if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
                    // 過去日の場合
                    $userReservation = $day->authReserveDate($day->everyDay())->first();
                    //ユーザーの予約情報の取得
                    if ($userReservation) {
                        $reservePart = $userReservation->setting_part;
                        if ($reservePart == 1) {
                            $reservePartText = "1部参加";
                            //テキスト表示の設定
                        } else if ($reservePart == 2) {
                            $reservePartText = "2部参加";
                            //テキスト表示の設定
                        } else if ($reservePart == 3) {
                            $reservePartText = "3部参加";
                            //テキスト表示の設定
                        }
                        $html[] = '<p class="m-auto p-0 w-75 text_color" style="font-size:12px">' . $reservePartText . '</p>';
                        //予約していた場合の表示
                    } else {
                        $html[] = '<p class="m-auto p-0 w-75 text_color" style="font-size:12px">受付終了</p>';
                        //予約していなかった場合の表示
                    }
                    $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
                    $html[] = '<input type="hidden" name="getDate[]" value="' . $day->everyDay() . '" form="reserveParts">';
                } else {
                    // 未来日または現在の日付の場合
                if ($reserveDate) {
                    $reservePart = $reserveDate->setting_part;
                    if ($reservePart == 1) {
                        $reservePart = "リモ1部";
                    } else if ($reservePart == 2) {
                        $reservePart = "リモ2部";
                    } else if ($reservePart == 3) {
                        $reservePart = "リモ3部";
                    }

                    $reserveSettingUserPivotId = $this->getUserReserveId($reserveDate->setting_reserve, $reserveDate->setting_part); // ログインユーザーの予約設定を取得
                    if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
                        $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px"></p>';
                        $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
                    } else {
                        $html[] = '<button type="button" class="btn btn-danger p-0 w-75 cancel-reservation" data-toggle="modal" data-target="#cancelModal" data-id="'. $reserveSettingUserPivotId .'" data-date="'. $reserveDate->setting_reserve .'" data-part="'.$reservePart.'" style="font-size:12px">'. $reservePart .'</button>';
                        $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
                    }
                } else {
                $html[] = $day->selectPart($day->everyDay());
                }
            }
                $html[] = $day->getDate();
                $html[] = '</div>';
                $html[] = '</td>';
            }
            $html[] = '</tr>';
        }
            $html[] = '</tbody>';
            $html[] = '</table>';
            $html[] = '</div>';
            $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
            $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';
        return implode('', $html);
    }

    protected function getWeeks(){
        $weeks = [];
        $firstDay = $this->carbon->copy()->firstOfMonth();
        $lastDay = $this->carbon->copy()->lastOfMonth();
        $week = new CalendarWeek($firstDay->copy());
        $weeks[] = $week;
        $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
        while($tmpDay->lte($lastDay)){
            $week = new CalendarWeek($tmpDay, count($weeks));
            $weeks[] = $week;
            $tmpDay->addDay(7);
        }
        return $weeks;
    }

    protected function getUserReserveId($date, $partNum)
{
    $user = Auth::user();
    $reserveSetting = ReserveSettings::where('setting_reserve', $date)
                                        ->where('setting_part', $partNum)
                                        ->first();

    if ($reserveSetting) {
        $reserveSettingUser = $user->reserveSettings()
                                    ->where('reserve_settings.id', $reserveSetting->id)
                                    ->first();

        \Log::info("Retrieved Reserve Setting: " . json_encode($reserveSetting));
        \Log::info("Retrieved Reserve Setting User: " . json_encode($reserveSettingUser));
        return $reserveSettingUser ? $reserveSettingUser->pivot->id : null;
    }
    \Log::info("No Reserve Setting found for date: " . $date . " and part: " . $partNum);
    return null;
}

        protected function getReserveUserId($reserveSettingId, $userId){
            $reserveSettingUser = DB::table('reserve_setting_users')
                                    ->where('reserve_setting_id', $reserveSettingId)
                                    ->where('user_id', $userId)
                                    ->first(); // ユーザーの予約データを取得
            return $reserveSettingUser ? $reserveSettingUser->id : null; // 予約データが存在すればそのIDを返す
        }

        public function getPartCount($date, $part)
        {
            return DB::table('reserve_setting_users')
                ->join('reserve_settings', 'reserve_setting_users.reserve_setting_id', '=', 'reserve_settings.id')
                ->where('reserve_settings.setting_reserve', $date)
                ->where('reserve_settings.setting_part', $partNum)
                ->count();
        }
}
