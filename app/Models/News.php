<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'content', 'min_age'];

    public function articles(){
        return $this->hasMany(Article::class);
    }
}
