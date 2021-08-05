<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'occupation',
        'localization',
        'level'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
