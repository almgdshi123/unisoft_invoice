<?php

namespace App\Exports;

use App\invoices;
use Maatwebsite\Excel\Concerns\FromCollection;

class InvoicesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public   $id;

    public function __construct($no)
    {
        $this->id = $no;
    }
    
    public function collection()
    {
        if($this->id == 1) {
            return invoices::all();
        }
       else if($this->id == 2) {
            return Invoices::where('Value_Status', 1)->get();
        } else if($this->id == 3) {
            return Invoices::where('Value_Status', 2)->get();
        } else if($this->id == 4) {
            return Invoices::where('Value_Status', 3)->get();
        }
        if($this->id == 5) {
            return invoices::onlyTrashed()->get();
        }
        return null;
        //return invoices::select('invoice_number', 'invoice_Date', 'Due_date','Section', 'product', 'Amount_collection','Amount_Commission', 'Rate_VAT', 'Value_VAT','Total', 'Status', 'Payment_Date','note')->get();

    }
}