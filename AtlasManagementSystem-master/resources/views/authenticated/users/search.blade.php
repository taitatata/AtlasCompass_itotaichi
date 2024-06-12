@extends('layouts.sidebar')

@section('content')
<div class="search_content w-100 d-flex">
  <!-- ユーザー一覧 -->
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="one_person">
      <div>
        <span class="name_title">ID : </span><span>{{ $user->id }}</span>
      </div>
      <div class="user_name_container">
        <span class="name_title">名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
          <span>{{ $user->over_name }}</span>
          <span>{{ $user->under_name }}</span>
        </a>
      </div>
      <div>
        <span class="name_title">カナ : </span>
        <span>({{ $user->over_name_kana }}</span>
        <span>{{ $user->under_name_kana }})</span>
      </div>
      <div>
        @if($user->sex == 1)
        <span class="name_title">性別 : </span><span>男</span>
        @elseif($user->sex == 2)
        <span class="name_title">性別 : </span><span>女</span>
        @else
        <span class="name_title">性別 : </span><span>その他</span>
        @endif
      </div>
      <div>
        <span class="name_title">生年月日 : </span><span>{{ $user->birth_day }}</span>
      </div>
      <div>
        @if($user->role == 1)
        <span class="name_title">権限 : </span><span>教師(国語)</span>
        @elseif($user->role == 2)
        <span class="name_title">権限 : </span><span>教師(数学)</span>
        @elseif($user->role == 3)
        <span class="name_title">権限 : </span><span>講師(英語)</span>
        @else
        <span class="name_title">権限 : </span><span>生徒</span>
        @endif
      </div>
      <div>
        @if($user->role == 4)
        <span class="name_title">選択科目 :</span>
          @foreach($user->subjects as $subject)
          <span>{{ $subject->subject }}</span>
          @endforeach
        @endif
      </div>
    </div>
    @endforeach
  </div>
  <!-- 検索用サイドバー -->
  <div class="search_sidebar w-25">
    <div class="">
      <p class="search_title no_margin">検索</p>
      <div class="search_box2">
        <input type="text" class="free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
      </div>
      <div class="search_container">
        <label>カテゴリ</label>
        <div class="select_container">
          <select form="userSearchRequest" name="category" class="engineer">
            <option value="name">名前</option>
            <option value="id">社員ID</option>
          </select>
          <span class="toggle_arrow3"></span>
        </div>
      </div>
      <div class="search_container margin_more select_container">
        <label>並び替え</label>
        <div class="select_container">
          <select name="updown" form="userSearchRequest" class="engineer">
            <option value="ASC">昇順</option>
            <option value="DESC">降順</option>
          </select>
          <span class="toggle_arrow3"></span>
        </div>
      </div>
      <div class="user_search_container">
          <div class="user_search_box">
            <p class="m-0 search_conditions">
              <span class="title_name title_color">検索条件の追加</span>
              <span class="toggle_arrow"></span>
            </p>
          </div>
        <div class="search_conditions_inner">
          <div class="search_container">
            <label class="no_margin">性別</label>
            <div class="search_items">
              <span>男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
              <span>女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
              <span>その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
            </div>
          </div>
          <div class="search_container">
            <label class="no_margin">権限</label>
            <div class="search_items select_container">
              <select name="role" form="userSearchRequest" class="engineer">
                <option selected disabled>----</option>
                <option value="1">教師(国語)</option>
                <option value="2">教師(数学)</option>
                <option value="3">教師(英語)</option>
                <option value="4" class="">生徒</option>
              </select>
              <span class="toggle_arrow3"></span>
            </div>
          </div class="search_container">
          <div class="selected_engineer margin_bottom">
            <label class="no_margin">選択科目</label>
            <div class="search_items">
              <span>国語</span><input type="checkbox" id="checkbox1" name="subject[]" value="1" form="userSearchRequest">
              <span>数学</span><input type="checkbox" id="checkbox2" name="subject[]" value="2" form="userSearchRequest">
              <span>英語</span><input type="checkbox" id="checkbox3" name="subject[]" value="3" form="userSearchRequest">
            </div>
          </div>
        </div>
      </div>
      <div class="search_button2">
        <input type="submit" name="search_btn" value="検索" form="userSearchRequest">
      </div>
      <div class="reset_button">
        <input type="reset" value="リセット" form="userSearchRequest">
      </div>
    </div>
    <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
  </div>
</div>
@endsection
