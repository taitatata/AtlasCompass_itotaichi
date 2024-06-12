@extends('layouts.sidebar')
@section('content')
<div class="vh-100 d-flex">
    <div class="w-50 mt-5">
        <div class="m-3 detail_container">
            <div class="p-3">
                <div class="detail_inner_head">
                    <!-- ログインユーザーと投稿者が一致する場合のみ編集・削除ボタンを表示 -->
                    @if(Auth::id() === $post->user_id)
                        <div class="sub_category_name_area">
                            @foreach($post->subCategories as $subCategory)
                            <button class="sub_category_name_button">{{ $subCategory->sub_category }}</button>
                            @endforeach
                        </div>
                        <div class="edit_delete_button">
                            <button class="edit_button">
                                <span class="edit-modal-open" post_title="{{ $post->post_title }}" post_body="{{ $post->post }}" post_id="{{ $post->id }}">編集</span>
                            </button>
                            <button class="delete_button">
                                <a href="#" class="" data-toggle="modal" data-target="#deleteModal" data-post-id="{{ $post->id }}">削除</a>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="contributor d-flex">
                    <p>
                        <span>{{ $post->user->over_name }}</span>
                        <span>{{ $post->user->under_name }}</span>
                        さん
                    </p>
                    <!-- <span class="ml-5">{{ $post->created_at }}</span> -->
                </div>
                <div class="detsail_post_title">
                    {{ $post->post_title }}
                </div>
                <div class="mt-3 detsail_post">
                    {{ $post->post }}
                </div>
            </div>
            <div class="p-3">
                <div class="comment_container">
                    <span class="">コメント</span>
                    @foreach($post->postComments as $comment)
                        <div class="comment_area border-top">
                            <p>
                            <span>{{ $comment->commentUser($comment->user_id)->over_name }}</span>
                            <span>{{ $comment->commentUser($comment->user_id)->under_name }}</span>さん
                            </p>
                            <p>{{ $comment->comment }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="w-50 p-3">
        <div class="comment_container border m-5">
            <div class="comment_area p-3">
                <p class="m-0">コメントする</p>
                @if ($errors->has("comment")) <!-- バリデーションエラーメッセージ  -->
                    <span class="error_message">{{ $errors->first('comment') }}</span>
                @endif
                <textarea class="w-100 gray_border" name="comment" form="commentRequest"></textarea>
                <input type="hidden" name="post_id" form="commentRequest" value="{{ $post->id }}">
                <div class="submit_button">
                    <input type="submit" class="btn btn-primary" form="commentRequest" value="投稿">
                </div>
                <form action="{{ route('comment.create') }}" method="post" id="commentRequest">{{ csrf_field() }}</form>
            </div>
        </div>
    </div>
</div>

@if ($errors->any())
    <input type="hidden" id="hasErrors" value="true">
    <input type="hidden" id="old_post_title" value="{{ old('post_title') }}">
    <input type="hidden" id="old_post_body" value="{{ old('post_body') }}">
    <input type="hidden" id="old_post_id" value="{{ old('post_id') }}">
@endif
<!-- 編集用モーダル -->
<div class="modal   js-modal">
    <div class="modal__bg js-modal-close">
        <div class="modal__content">
            <form action="{{ route('post.edit') }}" method="post">
                <div class="w-100">
                    <div class="modal-inner-title w-50 m-auto">
                        @if ($errors->has("post_title")) <!-- バリデーションエラーメッセージ  -->
                            <span id="error_post_title" class="error_message">{{ $errors->first('post_title') }}</span>
                        @endif
                        <input type="text" name="post_title" placeholder="タイトル" class="w-100">
                    </div>
                    <div class="modal-inner-body w-50 m-auto pt-3 pb-3">
                        @if ($errors->has("post_body")) <!-- バリデーションエラーメッセージ  -->
                            <span id="error_post_body" class="error_message">{{ $errors->first('post_body') }}</span>
                        @endif
                        <textarea placeholder="投稿内容" name="post_body" class="w-100"></textarea>
                    </div>
                    <div class="w-50 m-auto edit-modal-btn d-flex">
                        <a class="js-modal-close btn btn-danger d-inline-block" href="">閉じる</a>
                        <input type="hidden" class="edit-modal-hidden" name="post_id" value="">
                        <input type="submit" class="btn btn-primary d-block" value="編集">
                    </div>
                </div>
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</div>
<!-- 削除確認用モーダル -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">削除確認</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>本当に削除してよろしいですか？</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">削除</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
