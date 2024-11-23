<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = ['user_id' , 'requested_title'];
    
    public function users(){
        return $this->belongsTo(users::class);
    }
}
