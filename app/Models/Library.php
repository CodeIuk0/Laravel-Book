<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use phpDocumentor\Reflection\Types\Null_;
use App\Models\User;
use App\Models\Books;
use App\Models\Comments;

class Library extends Model
{
    use  HasFactory, Notifiable;

    protected $table ="library";

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */

    public function book()
    {
        return $this->hasOne(Books::class, 'id') ;
    }

    public function comment()
    {
        return $this->hasOne(Comments::class, 'id') ;
    }


    protected $fillable = [
        'comment_id',
        'user_id',
        'book_id',
        'advancement',
        'user_take_book_when'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'user_take_book_when' => 'datetime'
    ];
}
