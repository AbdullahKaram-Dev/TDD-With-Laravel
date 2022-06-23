<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    protected $guarded = [];
    /* example property casts */
    protected $casts = [
        'dob' => 'date',
    ];

    /* example property casts dates to date object */
    protected $dates = [
        'dob',
    ];

    /* another example to set attribute  dob */
    public function setDobAttribute($dob)
    {
        $this->attributes['dob'] = Carbon::parse($dob);
    }


}
