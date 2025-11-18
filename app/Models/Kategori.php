<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    public $timestamps = false;
    protected $table = "kategori";
    protected $guarded = ['id'];
}
