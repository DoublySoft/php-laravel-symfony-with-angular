<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($id)
 * @method static where(string $string, $id)
 * @property mixed name
 */
class Category extends Model
{
    protected $table = 'categories';

    // Relación de uno a muchos
    public function posts() {
        return $this->hasMany('App\Post');
    }
}
