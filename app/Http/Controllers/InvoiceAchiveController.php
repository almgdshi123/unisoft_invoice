<?php

namespace App\Http\Controllers;

use App\invoice_attachments;
use Illuminate\Http\Request;
use App\invoices;
use Illuminate\Support\Facades\Storage;
class InvoiceAchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = invoices::onlyTrashed()->get();
        return view('Invoices.Archive_Invoices',compact('invoices'));
    }


   
  public function update(Request $request)
    {
         $id = $request->invoice_id;
         $flight = Invoices::withTrashed()->where('id', $id)->restore();
         session()->flash('restore_invoice');
         return redirect('/Archive');
    }

    
    public function destroy(Request $request)
    {
         $invoices = invoices::withTrashed()->where('id',$request->invoice_id)->first();
        $Details = invoice_attachments::where('invoice_id', $request->invoice_id)->first();
         if (!empty($Details->invoice_number)) {

          Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
      }

         $invoices->forceDelete();
         session()->flash('delete_invoice');
         return redirect('/Archive');
    
    }
}