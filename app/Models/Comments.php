<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Models\Books;

class Comments extends Model
{
    use  HasFactory, Notifiable;

    protected $table ="comments";

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'comment',
        'note',
        'user_id',
        'book_id',
        'comment_updated_at',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'comment_updated_at' => 'datetime'
    ];

    public function scopeForBook($query, $id) {
        return $query->where('book_id', $id);
    }
}
