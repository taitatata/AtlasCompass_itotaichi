<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    //リレーションの追加：多対多のリレーション
    public function subCategories()
    {
        return $this->belongsToMany(\App\Models\Categories\SubCategory::class, 'post_sub_categories', 'post_id', 'sub_category_id');
        //return $this->多対多のメソッド(相手モデル::class, '中間テーブル', '現在のモデルの外部キー', '相手モデルの外部キー');
    }

    // // コメント数
    // public function commentCounts($post_id){
    //     return Post::with('postComments')->find($post_id)->postComments();
    // }

    // コメント数をカウントするメソッド
    public function commentCounts()
    {
        return $this->postComments()->count();
    }
}
