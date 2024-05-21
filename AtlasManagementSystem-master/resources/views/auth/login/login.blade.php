<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AtlasBulletinBoard</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300&family=Oswald:wght@200&display=swap" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body class="login_page">
  <form action="{{ route('loginPost') }}" method="POST">
    <div class="login_container">
      <img src="/image/atlas-black.png" alt='Atlasロゴ画像'>
        <div class="login_box">
          <div class="mail_box">
            <label>メールアドレス</label>
            <div class="mail_area">
              <input type="text" name="mail_address">
            </div>
          </div>
          <div class="password_box">
            <label>パスワード</label>
            <div class="password_area">
              <input type="password" name="password">
            </div>
          </div>
          <div class="submit_btn">
            <input type="submit" class="btn btn-primary" value="ログイン">
          </div>
          <div class="sign_up">
            <a href="{{ route('registerView') }}">新規登録はこちら</a>
          </div>
        </div>
        {{ csrf_field() }}
    </div>
  </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/register.js') }}" rel="stylesheet"></script>
</body>
</html>
