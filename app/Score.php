<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'scores';

    protected $fillable = [
        'hash',
        'uid',
        'sco',
        'kk',
        'cc',
        'gg',
        'bb',
        'mm',       
        'maxcombo',
    ];

    protected $dates =[
        'created_at',
        'updated_at'
    ];
}
