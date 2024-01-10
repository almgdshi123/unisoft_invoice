<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dep_accounts extends Model
{
    protected $fillable = [
         'id_Invoice','id_box','Value_VAT','Total','Created_by','Value_Status'
        ];
}
