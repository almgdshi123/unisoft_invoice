<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class invoices extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'invoice_number',
        'invoice_Date',
        'Due_date',
        'product',
        'section_id',
        'Amount_collection',
       'customer_id',
        'Status',
        'Value_Status',
        'note',
        'Payment_Date',
    ];

    protected $dates = ['deleted_at'];

 public function section()
   {
   return $this->belongsTo('App\sections');
   }
   public function customer()
   {
   return $this->belongsTo('App\Customer');
   }}
