<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id', 'name', 'custom_name'
    ];

    public function states() {
        return $this->hasMany(State::class, 'country', 'id');
    }

    public function name() {
        return $this->custom_name != null ? $this->custom_name : ltrim($this->name);
    }
}
