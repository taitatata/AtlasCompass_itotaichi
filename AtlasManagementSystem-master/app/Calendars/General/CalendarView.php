<?php

// スクール予約ページ

namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView{

  private $carbon;
  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';

    $weeks = $this->getWeeks();
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';

      $days = $week->getDays();
      foreach($days as $day){
        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");

        if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
          $html[] = '<td class="calendar-td">';
        }else{
          $html[] = '<td class="calendar-td '.$day->getClassName().'">';
        }
        $html[] = $day->render();

          $reserveDate = $day->authReserveDate($day->everyDay())->first();
          if ($reserveDate) {
              $reservePart = $reserveDate->setting_part;
              if ($reservePart == 1) {
                  $reservePart = "リモ1部";
              } else if ($reservePart == 2) {
                  $reservePart = "リモ2部";
              } else if ($reservePart == 3) {
                  $reservePart = "リモ3部";
              }
              if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
                  $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px"></p>';
                  $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
              } else {
                  $html[] = '<button type="button" class="btn btn-danger p-0 w-75 cancel-reservation" data-toggle="modal" data-target="#cancelModal" data-date="'. $reserveDate->setting_reserve .'" data-part="'.$reservePart.'" style="font-size:12px">'. $reservePart .'</button>';
                  $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
              }
          } else {
              $html[] = $day->selectPart($day->everyDay());
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

    protected function renderDayPartCounts($date){
            $html = [];//結果として返すHTMLを格納する配列を初期化
            $parts = ['1部' => 1, '2部' => 2, '3部' => 3];
            foreach($parts as $partName => $partNum){ //各部についてループ処理を行う
                $count = $this->getPartCount($date, $partNum);// 部ごとの予約数を取得
                $reserveSetting = $this->getReserveId($date, $partNum); // 予約設定を取得

                if($reserveSetting !== null) {
                    $html[] = "<div><a href=\"#\" class=\"cancel-link\" data-toggle=\"modal\" data-target=\"#cancelModal\" data-id=\"{$reserveSetting->id}\" data-date=\"{$date}\" data-part=\"{$partName}\">{$partName}　　</a> {$count}人</div>";// 予約リンクを生成
                }
            }
            return implode("", $html);// HTML文字列を結合して返す
        }

        protected function getReserveId($date, $part) {
        $setting = ReserveSettings::where('setting_reserve', $date)->where('setting_part', $part)->first(); // 予約設定を取得

        // デバッグ情報の追加
        if ($setting) {
            \Log::info("Found setting: " . json_encode($setting));
            // ログインユーザーのIDを取得
            $userId = Auth::id();
            // reserve_setting_usersテーブルから該当レコードを取得
            $reservation = DB::table('reserve_setting_users')
                                ->where('reserve_setting_id', $setting->id)
                                ->where('user_id', $userId)
                                ->first();
            // デバッグ情報の追加
            if ($reservation) {
                \Log::info("Found reservation: " . json_encode($reservation));
                return $reservation->id;
            } else {
                \Log::warning("No reservation found for reserve_setting_id: " . $setting->id . " and user_id: " . $userId);
            }
        } else {
            \Log::warning("No setting found for date: " . $date . " and part: " . $part);
        }
        return null; // 予約設定が存在しない場合、または予約が見つからない場合はnullを返す
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
            return ReserveSettings::where('setting_reserve', $date)
                                    ->where('setting_part', $part)
                                    ->count();
        }
}
