<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateModel extends Model
{
    // use HasFactory;
    protected $table = 'UpdateResult';
    protected $fillable = ['user','type','value', 'phoneNum', 'time'];
}
