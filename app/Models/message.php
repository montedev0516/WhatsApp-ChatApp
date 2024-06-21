<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    protected $table = 'messages';
    protected $fillable = ['user', 'value'];
}
