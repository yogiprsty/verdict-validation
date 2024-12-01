<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verdict extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'litigant',
        'defendant',
        'case_number',
        'case_type',
        'sub_case_type',
        'verdict_date',
        'url_to_valid_verdict',
        'file_verdict_path',
        'file_verdict_stamped_path'
    ];

    protected $casts = [
        'id' => 'string',
        'verdict_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
    }
}
