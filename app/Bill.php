<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'name',
        'value',
        'due_date',
        'done',
        'receivable'
    ];
}
