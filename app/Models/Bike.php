<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class Bike extends Model
{
    use HasFactory;
    use Searchable;

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
        'img_path',
        'last_location',
        'stolen_at',
    ];

    protected $casts = [
        'stolen_at' => 'datetime',
    ];
    /*
    * Get the user that owns the bike.
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* Override the toSearchableArray method to specify which attributes should be indexed by Scout.
     * In this case, we only want to index the MPN for searching.
     */
    public function toSearchableArray(): array
    {
        return [
            'mpn' => $this->mpn,
        ];
    }


}
