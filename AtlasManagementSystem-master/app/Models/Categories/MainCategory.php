<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category'
    ];

    //リレーションの修正：1対多のリレーション
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'main_category_id');
        //return $this->1対多のメソッド(リレーション先のモデル, 'ローカル(自分側)の外部キー');
    }
}
