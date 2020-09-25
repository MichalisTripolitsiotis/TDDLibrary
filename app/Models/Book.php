<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Author;

class Book extends Model
{
    use HasFactory;
    //protected $fillable = ['title', 'author', 'author_id'];
    protected $guarded = [];
    //a method for taking the path of each book and I don't need to use redirect to Controller
    public function path()
    {
        ///books/1-endless-game (endless game is the book title)
        //return '/books/' . $this->id . '-' . Str::slug($this->title); works if I want
        return '/books/' . $this->id;
    }

    public function checkout($user)
    {
        $this->reservations()->create([
            'user_id' => $user->id,
            'checked_out_at' => now(),
        ]);
    }

    public function checkin($user)
    {
        $reservation = $this->reservations()->where('user_id', $user->id)
            ->whereNotNull('checked_out_at')
            ->whereNull('checked_in_at')
            ->first();

        if (is_null($reservation)) {
            throw new \Exception();
        }

        $reservation->update([
            'checked_in_at' => now(),
        ]);
    }

    public function setAuthorIdAttribute($author)
    {
        $this->attributes['author_id'] = (Author::firstOrCreate([
            'name' => $author,
        ]))->id;
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
