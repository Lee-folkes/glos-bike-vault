<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nickname',
        'mpn',
        'brand',
        'model',
        'type',
        'wheel_size',
        'colour',
        'num_gears',
        'brake_type',
        'suspension',
        'gender',
        'age_group',
        'status',
    ];
    /*
    * Get the user that owns the bike.
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
