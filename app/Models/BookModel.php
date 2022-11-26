<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookModel extends Model
{
    use HasFactory;
    protected $table = 'books';
    protected $fillable = ["name", "description", "price", "image", "category_id"];


    function category()
    {
        return $this->belongsTo(CategoryModel::class);
    }
}
