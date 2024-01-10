<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{

    protected $fillable = [
        'name_customer', 'phone_customer','note_customer','Value_Status','Created_by'
        ];
}
