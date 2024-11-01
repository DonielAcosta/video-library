<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'views',
        'searches',
    ];

    /**
     * Get the video that owns the analytics.
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
