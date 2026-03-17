<?php

namespace App\Models;

use App\Events\ClearCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use HasFactory, SoftDeletes;

    protected $attributes = [
        'status' => true,
    ];

    protected $casts = [
        'status' => 'bool',
        'date' => 'date',
    ];

    protected $dispatchesEvents = [
        'saved' => ClearCache::class,
        'deleted' => ClearCache::class,
    ];
}
