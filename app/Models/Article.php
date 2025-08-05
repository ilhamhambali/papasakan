<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- PASTIKAN BARIS INI ADA
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory; // <-- DAN PASTIKAN BARIS INI JUGA ADA

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
