@extends('layouts.sidebar')

@section('content')
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    <p class="w-75 m-auto"></p>
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <!-- 投稿者名 -->
      <p class="user_name"><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <!-- タイトル -->
      <p class="no_margin post_title_p"><a class="post_title" href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area d-flex">
        <!-- サブカテゴリーとコメントアイコン、いいねアイコン -->
        <div class="d-flex post_status">
          <!-- サブカテゴリーを表示 -->
          <div class="sub_category_name">
            @foreach($post->subCategories as $subCategory)
              <butto class="sub_category_name_button">{{ $subCategory->sub_category }}</butto>
            @endforeach
          </div>
          <div class="icon">
            <!-- コメントアイコン -->
            <div class="mr-5 comment_count">
              <i class="fa fa-comment comment_icon"></i><span class="count">{{ $commentCounts[$post->id] }}</span>
            </div>
            <!-- いいねアイコン -->
            <div class="like_count">
              @if(Auth::user()->is_Like($post->id))
              <p class="m-0"><i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i><span class="count like_counts{{ $post->id }}">{{ $likeCounts[$post->id] }}</span></p>
              @else
              <p class="m-0"><i class="fas fa-heart like_btn inner_like_btn" post_id="{{ $post->id }}"></i><span class="count like_counts{{ $post->id }}">{{ $likeCounts[$post->id] }}</span></p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="other_area border w-25">
    <div class="border m-4">
      <div class="side_box1">
        <!-- 投稿 -->
        <a class="post_create" href="{{ route('post.input') }}">投稿</a>
        <!-- 検索窓 -->
        <div class="search_box">
          <input class="search_area" type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
          <input class="search_button" type="submit" value="検索" form="postSearchRequest">
        </div>
        <!-- いいねした投稿・自分の投稿 -->
        <div class="post_show_box">
          <input type="submit" name="like_posts" class="category_btn like_box" value="いいねした投稿" form="postSearchRequest">
          <input type="submit" name="my_posts" class="category_btn main_box" value="自分の投稿" form="postSearchRequest">
        </div>
      </div>
      <div class="category_search">
        <label>カテゴリー検索</label>
        <div class="sub_category_box">
          <ul>
              @foreach($categories as $category)
              <div class="main_category_box">
                <li class="main_categories" category_id="{{ $category->id }}">
                  <div class="category_header">
                    <span class="category_name">{{ $category->main_category }}<span>
                    <span class="toggle_arrow"></span>
                  </div>
                    <ul class="sub_categories category_num{{ $category->id }}"> <!-- デフォルトで非表示にする -->
                    @foreach($category->subCategories as $subCategory)
                      <li>
                        <div class="sub_category">
                          <form action="{{ route('post.show') }}" method="GET" class="d-inline">
                              <input type="hidden" name="keyword" value="{{ $subCategory->sub_category }}">
                              <button type="submit" class="sub_category_button p-0 m-0">{{ $subCategory->sub_category }}</button>
                          </form>
                        </div>
                      </li>
                    @endforeach
                  </ul>
                </li>
              </div>
              @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
@endsection
