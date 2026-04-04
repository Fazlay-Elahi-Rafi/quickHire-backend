<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'key_responsibilities'      => 'array',
        'benefits'                  => 'array',
        'categories'                => 'array',
        'development_tools'         => 'array',
        'bonus_skills'              => 'array',
        'required_technical_skills' => 'array',
        'database_knowledge'        => 'array',
        'qualifications'            => 'array',
        'status'                    => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('job_type', $type);
    }

    public function scopeWorkplace($query, $workplace)
    {
        return $query->where('workplace', $workplace);
    }
}
