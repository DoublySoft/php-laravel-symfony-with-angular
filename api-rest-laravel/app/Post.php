<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($id)
 * @method static where(string $string, $id)
 * @method static updateOrCreate(array $where, $params_array)
 * @property mixed user_id
 * @property mixed category_id
 * @property mixed title
 * @property mixed content
 * @property mixed image
 */
class Post extends Model
{
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category_id', 'title', 'content', 'image'
    ];


    // Relación de muchos a uno
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function category() {
        return $this->belongsTo('App\Category', 'category_id');
    }
}
