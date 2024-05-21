<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AtlasBulletinBoard</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous"> -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300&family=Oswald:wght@200&display=swap" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body class="register_page">
  <form action="{{ route('registerPost') }}" method="POST">
    <div class="register_container">
      <div class="register_box">
        <div class="register_form">
          @if ($errors->has("over_name")) <!-- バリデーションエラーメッセージ  -->
                <span class="error_message">{{ $errors->first('over_name') }}</span>
          @endif
          <br>
          @if ($errors->has("under_name")) <!-- バリデーションエラーメッセージ  -->
                <span class="error_message">{{ $errors->first('under_name') }}</span>
          @endif
          <div class="username_area">
            <div class="username1">
              <label>姓</label>
              <input type="text" name="over_name">
            </div>
            <div class="username2">
              <label>名</label>
              <input type="text" name="under_name">
            </div>
          </div>
          @if ($errors->has("over_name_kana")) <!-- バリデーションエラーメッセージ  -->
            <span class="error_message">{{ $errors->first('over_name_kana') }}</span>
          @endif
          <br>
          @if ($errors->has("under_name_kana")) <!-- バリデーションエラーメッセージ  -->
            <span class="error_message">{{ $errors->first('under_name_kana') }}</span>
          @endif
          <div class="username_area_kana">
            <div class="username1_kana">
              <label>セイ</label>
              <input type="text"name="over_name_kana">
            </div>
            <div class="username2_kana">
              <label>メイ</label>
              <input type="text" name="under_name_kana">
            </div>
          </div>
          @if ($errors->has("mail_address")) <!-- バリデーションエラーメッセージ  -->
            <span class="error_message">{{ $errors->first('mail_address') }}</span>
          @endif
          <div class="register_mail_area">
            <label >メールアドレス</label>
            <input type="mail" class="mail_address" name="mail_address">
          </div>
        </div>
        @if ($errors->has("sex")) <!-- バリデーションエラーメッセージ  -->
          <span class="error_message">{{ $errors->first('sex') }}</span>
        @endif
        <div class="radio_btn_area1">
          <div class="radio1">
            <input type="radio" name="sex" class="sex" value="1">
            <label>男性</label>
          </div>
          <div class="radio2">
            <input type="radio" name="sex" class="sex" value="2">
            <label>女性</label>
          </div>
          <div class="radio3">
            <input type="radio" name="sex" class="sex" value="3">
            <label>その他</label>
          </div>
        </div>
        @if ($errors->has('old_year'))
          <span class="error_message">{{ $errors->first('old_year') }}</span>
        @endif
        @if ($errors->has('old_month'))
          <span class="error_message">{{ $errors->first('old_month') }}</span>
        @endif
        @if ($errors->has('old_day'))
          <span class="error_message">{{ $errors->first('old_day') }}</span>
        @endif
        <div class="old_area">
          <label>生年月日</label>
          <div class="old_area_box">
            <div class="old_year_area">
              <select class="old_year" name="old_year">
                <option value="none">-----</option>
                <option value="2000">2000</option>
                <option value="2001">2001</option>
                <option value="2002">2002</option>
                <option value="2003">2003</option>
                <option value="2004">2004</option>
                <option value="2005">2005</option>
                <option value="2006">2006</option>
                <option value="2007">2007</option>
                <option value="2008">2008</option>
                <option value="2009">2009</option>
                <option value="2010">2010</option>
                <option value="2011">2011</option>
                <option value="2012">2012</option>
                <option value="2013">2013</option>
                <option value="2014">2014</option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
              </select>
              <label>年</label>
            </div>
            <div class="old_month_area">
              <select class="old_month" name="old_month">
                <option value="none">-----</option>
                <option value="01">1</option>
                <option value="02">2</option>
                <option value="03">3</option>
                <option value="04">4</option>
                <option value="05">5</option>
                <option value="06">6</option>
                <option value="07">7</option>
                <option value="08">8</option>
                <option value="09">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
              </select>
              <label>月</label>
            </div>
            <div class="old_day_area">
              <select class="old_day" name="old_day">
                <option value="none">-----</option>
                <option value="01">1</option>
                <option value="02">2</option>
                <option value="03">3</option>
                <option value="04">4</option>
                <option value="05">5</option>
                <option value="06">6</option>
                <option value="07">7</option>
                <option value="08">8</option>
                <option value="09">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
                <option value="24">24</option>
                <option value="25">25</option>
                <option value="26">26</option>
                <option value="27">27</option>
                <option value="28">28</option>
                <option value="29">29</option>
                <option value="30">30</option>
                <option value="31">31</option>
              </select>
              <label>日</label>
            </div>
          </div>
        </div>
        @if ($errors->has("role")) <!-- バリデーションエラーメッセージ  -->
          <span class="error_message">{{ $errors->first('role') }}</span>
        @endif
        <div class="radio_btn_area2">
          <label>役職</label>
          <div class="radio_btn_box">
            <div class="radio4">
              <input type="radio" name="role" class="admin_role role" value="1">
              <label>教師(国語)</label>
            </div>
            <div class="radio4">
              <input type="radio" name="role" class="admin_role role" value="2">
              <label>教師(数学)</label>
            </div>
            <div class="radio4">
              <input type="radio" name="role" class="admin_role role" value="3">
              <label>教師(英語)</label>
            </div>
            <div class="radio4">
              <input type="radio" name="role" class="other_role role" value="4">
              <label class="other_role">生徒</label>
            </div>
          </div>
        </div>
        <div class="select_teacher_area">
          <label class="d-block m-0">選択科目</label>
          <div class="select_teacher_box">
            @foreach($subjects as $subject)
              <div class="select_teacher">
                <input type="checkbox" name="subject[]" value="{{ $subject->id }}">
                <label>{{ $subject->subject }}</label>
              </div>
            @endforeach
          </div>
        </div>
        @if ($errors->has("password")) <!-- バリデーションエラーメッセージ  -->
          <span class="error_message">{{ $errors->first('password') }}</span>
        @endif
        <div class="register_password_area">
          <label>パスワード</label>
          <input type="password" name="password">
        </div>
        <div class="password_check_area">
          <label>確認用パスワード</label>
          <!-- name="password"からname="password_confirmation"に修正 -->
          <input type="password" class="password_confirmation" name="password_confirmation">
        </div>
        <div class="submit_btn">
          <input type="submit" class="register_btn" value="新規登録" onclick="return confirm('登録してよろしいですか？')">
        </div>
        <div class="login_page_link">
          <a href="{{ route('loginView') }}">ログインはこちら</a>
        </div>
      </div>
    </div>
    {{ csrf_field() }}
  </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/register.js') }}" rel="stylesheet"></script>
</body>
</html>
