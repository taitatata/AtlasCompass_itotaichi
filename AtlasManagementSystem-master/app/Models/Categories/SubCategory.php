<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];

    //リレーションの修正：多対1のリレーション
    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
        //return $this->多対1のメソッド（リレーション先のモデル::class, 'リレーション先の外部キー');
    }

    //リレーションの追加：多対多のリレーション
    public function posts(){
        return $this->belongsToMany(\App\Models\Posts\Post::class, 'post_sub_categories', 'sub_category_id', 'post_id');
        //return $this->多対多のメソッド(相手モデル::class, '中間テーブル', '現在のモデルの外部キー', '相手モデルの外部キー');
    }
}
