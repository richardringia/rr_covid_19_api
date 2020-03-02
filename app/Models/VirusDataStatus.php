<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VirusDataStatus extends Model
{
    use SoftDeletes;

    protected $casts = ['id' => 'string'];

    protected $fillable = [
        'id', 'url'
    ];
}
