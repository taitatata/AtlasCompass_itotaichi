<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;
// 追記（FormRequestを使用したバリデーションの設定）
use App\Http\Requests\MainCategoryRequest;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PostEditRequest;

class PostsController extends Controller
{
    public function show(Request $request){
        $posts = Post::with('user', 'postComments')->get();
        $categories = MainCategory::with('subCategories')->get(); //追記:with('subCategories')->
        $like = new Like;
        $post_comment = new Post;

        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            // サブカテゴリーが完全一致するかをチェック
            $matchingSubCategory = SubCategory::where('sub_category', $keyword)->first();
            if ($matchingSubCategory) {
                // サブカテゴリーが一致する場合、そのサブカテゴリーに属する投稿のみを取得
                $posts = Post::with(['user', 'postComments', 'subCategories'])
                    ->whereHas('subCategories', function ($query) use ($matchingSubCategory) {
                        $query->where('sub_category', $matchingSubCategory->sub_category);
                    })
                    ->get()
                    ->sortBy(function ($post) {
                    return $post->subCategories->first()->mainCategory->main_category;
                });
                } else {
            // キーワードが投稿タイトルまたは投稿内容に部分一致する場合
            $posts = Post::with(['user', 'postComments', 'subCategories'])
                ->where('post_title', 'like', '%' . $keyword . '%')
                ->orWhere('post', 'like', '%' . $keyword . '%')
                ->orWhereHas('subCategories', function ($query) use ($keyword) {
                    $query->where('sub_category', 'like', '%' . $keyword . '%');
                })
                ->get();
            }
        }else if($request->category_word){
            $sub_category = $request->category_word;
            $posts = Post::with('user', 'postComments')->get();
        }else if($request->like_posts){
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments')
            ->whereIn('id', $likes)->get();
        }else if($request->my_posts){
            $posts = Post::with('user', 'postComments')
            ->where('user_id', Auth::id())->get();
        }
        // 各投稿のコメント数を取得
        $commentCounts = [];
        foreach ($posts as $post) {
            $commentCounts[$post->id] = $post->commentCounts();
        }
        // 各投稿のいいね数を取得
        $likeCounts = [];
        foreach ($posts as $post) {
            $likeCounts[$post->id] = $like->likeCounts($post->id);
        }
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment','commentCounts','likeCounts'));
    }

    public function postDetail($post_id){
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::with('subCategories')->get();
        $sub_categories = SubCategory::get();
        return view('authenticated.bulletinboard/post_create', compact('main_categories','sub_categories'));
    }

    public function postSubCategory()
    {
        $posts = Post::with('subCategories')->get();
        return view('authenticated.bulletinboard/posts', compact('posts'));
    }

    public function postCreate(PostFormRequest $request){
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body,
            'sub_category_id' => $request->post_category_id
        ]);
        if ($request->has('sub_category_id')) {
            $post->subCategories()->attach($request->sub_category_id);
        }

        return redirect()->route('post.show');
    }

    public function postEdit(PostEditRequest $request){
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }

    //バリデーションを設定
    public function mainCategoryCreate(MainCategoryRequest $request){
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    //サブカテゴリーの登録処理・バリデーションを設定
    public function subCategoryCreate(SubCategoryRequest $request){
        SubCategory::create([
            'sub_category' => $request->input('sub_category_name'),
            'main_category_id' => $request->input('main_category_id'),
        ]);
        return redirect()->route('post.input');
    }

    public function commentCreate(CommentRequest $request){
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

        return response()->json();
    }
}
