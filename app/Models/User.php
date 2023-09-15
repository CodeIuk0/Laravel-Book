<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Books;
use Illuminate\Support\Facades\Auth;
use App\Models\Library;
use App\Models\Comments;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function library()
    {
        return $this->hasMany(Library::class, 'user_id');
    }

    public function books()
    {
        return $this->hasManyThrough(Books::class, Library::class, 'user_id', 'id', 'id',"book_id"); // Library::join("books","library.book_id","=","books.id")->select('library.*')->get(); // ->find(1); // $this->hasMany(Library::class,'user_id')->where("book_id");
    }

    public function comments()
    {
        return $this->hasMany(Comments::class,'user_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
