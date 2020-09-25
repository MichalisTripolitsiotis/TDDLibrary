<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Author extends Model
{
    //protected $fillable = ['name', 'dob'];
    protected $guarded = [];
    //cast carbon date(s)
    protected $dates = ['dob'];

    public function setDobAttribute($dob)
    {
        $this->attributes['dob'] = Carbon::parse($dob);
    }
}
