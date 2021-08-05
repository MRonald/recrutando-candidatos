<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'applicant_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function getJob()
    {
        return $this->hasOne(Job::class);
    }

    public function getApplicant()
    {
        return $this->hasOne(Applicant::class);
    }
}
