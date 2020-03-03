<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VirusDataUpdate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id', 'virus_data_type', 'date', 'new', 'changes'
    ];

}
