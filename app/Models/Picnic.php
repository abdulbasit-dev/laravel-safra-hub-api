<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Picnic extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts=[
        'currency_type'=>'integer',
        'type'=>'integer',
    ];


    public function getCreatedByAtrribute($id){
        return User::findOrFail($id);
    }
}
