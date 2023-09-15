<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Models\Library;
use App\Models\Comments;

class Books extends Model
{
    use  HasFactory, Notifiable;

    protected $table ="books";

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'pages',
        'summary',
        'tags',
        'editors',
    ];

    protected $hidden = [
        "laravel_through_key"
    ];

    public function scopeWhereNotInUser($query) {
        $userBooksIds = Library::where('user_id', auth()->id())->select('book_id');
        return $query->whereNotIn('id', $userBooksIds)->orderBy('title');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
}
