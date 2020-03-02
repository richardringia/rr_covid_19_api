<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VirusData extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id', 'date', 'count', 'type', 'status', 'state'
    ];
}
