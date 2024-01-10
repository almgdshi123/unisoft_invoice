<?php

namespace App\Http\Controllers;

use App\Box_account;
use App\Customer;
use App\Dep_accounts;
use App\Notifications\Add_invoice_new;
use App\products;
use Illuminate\Support\Facades\Notification;
use App\invoices;
use App\sections;
use App\User;
use App\invoices_details;
use App\invoice_attachments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Notifications\AddInvoice;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $invoices = invoices::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $table = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'invoices' AND TABLE_NAME = 'invoices'");
        if (!empty($table)) {
            $invoice_id = $table[0]->AUTO_INCREMENT;
        }
        $sections = sections::all();
        $custmoers = Customer::where('Value_Status', 1)->get();

        return view('invoices.add_invoice', compact('sections', 'invoice_id', 'custmoers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'invoice_number' => 'required|unique:invoices|max:50',
            'Custmoer' => 'required',
            'invoice_Date' => 'required',
            'Amount_collection' => 'required|max:10',
            'Total' => 'max:8',
        ], [
            'invoice_number.required' => ' الرجاء الدخال  رقم الفاتورة',
            'Custmoer.required' => ' الرجاء الدخال  اسم العميل ',
            'invoice_number.unique' => 'رقم الفاتورة مسجل مسبقا',
            'invoice_number.max' => '  رقم الفاتورة يتجوز 50 حرف',
            'invoice_Date.required' => ' الرجاء الدخال  تاريخ الفاتورة',
            'Amount_collection.required' => ' الرجاء الدخال  مبلغ التحصيل',
            'Amount_collection.max' => '  مبلغ التحصيل يتجوز طول 10 ارقام',

        ]);
        $box = Box_account::find($request->Custmoer);
        $total = ($box->Total + $request->Amount_collection);

        Dep_accounts::create([
            'id_Invoice' => $request->invoice_number, 'id_box' => $box->id, 'Value_VAT' => $request->Amount_collection, 'Total' => $box->Total,
            'Created_by' => (Auth::user()->name),
            'Value_Status'=>0,
        ]);
        $box->update([
            'Total' => $total
        ]);
        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'customer_id' => $request->Custmoer,

            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);
        $invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'Value_VAT' => 0,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);
        if ($request->hasFile('pic')) {

            $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }
        //    $user = User::first();
        $user = User::where('id', '!=', Auth::user()->id)->get();
        $invoices = invoices::latest()->first();
        Notification::send($user, new Add_invoice_new($invoices));


        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $id_page = $request->id_page;
        $invoices = invoices::where('id', $id)->first();

        return view('invoices.status_update', compact('invoices', 'id_page'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = invoices::where('id', $id)->first();
        $sections = sections::all();
        $custmoers = Customer::where('Value_Status', 1)->get();

        return view('invoices.edit_invoice', compact('sections', 'invoices','custmoers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices $invoices)
    {
        $id = $request->invoice_id;
        $validatedData = $request->validate([
            'invoice_number' => 'required|max:50|unique:invoices,invoice_number,' . $id,
            'Custmoer' => 'required',
            'invoice_Date' => 'required',
            'Amount_collection' => 'required|max:10',
            'Total' => 'max:8',
        ], [
            'invoice_number.required' => ' الرجاء الدخال  رقم الفاتورة',
            'Custmoer.required' => ' الرجاء الدخال  اسم العميل ',
            'invoice_number.unique' => 'رقم الفاتورة مسجل مسبقا',
            'invoice_number.max' => '  رقم الفاتورة يتجوز 50 حرف',
            'invoice_Date.required' => ' الرجاء الدخال  تاريخ الفاتورة',
            'Amount_collection.required' => ' الرجاء الدخال  مبلغ التحصيل',
            'Amount_collection.max' => '  مبلغ التحصيل يتجوز طول 10 ارقام',

        ]);
        $invoices = invoices::findOrFail($request->invoice_id);

         $box = Box_account::find($request->Custmoer);
         $total = ($box->Total - $invoices->Amount_collection);

        $total = ( $total+ $request->Amount_collection);
        $dep_acc=Dep_accounts::where('id_Invoice',$invoices->invoice_number )->where('Value_Status',0)->first();
        $dep_acc->update([
          'id_Invoice' =>$request->invoice_number ,
            'Value_VAT'=> $request->Amount_collection

        ]);
        $box->update([
           'Total' => $total]
        );
    

     
        $invoices->update([
          'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'customer_id' => $request->Custmoer,

            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        session()->flash('edit');
        return redirect('/invoices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoices::where('id', $id)->first();
        $Details = invoice_attachments::where('invoice_id', $id)->first();

        $id_page = $request->id_page;


        if (!$id_page == 2) {

            if (!empty($Details->invoice_number)) {

                Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
            }

            $invoices->forceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');
        } else {

            $invoices->delete();
            session()->flash('archive_invoice');

            return redirect('/invoices');
        }
    }
    public function getproducts($id)
    {
        $products = products::where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }

    public function Status_Update($id, Request $request)
    {
        $invoices = invoices::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            $box = Box_account::find($request->Custmoer);
            $total = ($box->Total - $request->Value_VAT);
    
            Dep_accounts::create([
                'id_Invoice' => $request->invoice_number, 'id_box' => $box->id, 'Value_VAT' => $request->Value_VAT, 'Total' => $box->Total,
                'Created_by' => (Auth::user()->name),
                'Value_Status'=>1
            ]);
            $box->update([
                'Total' => $total
            ]);

            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'Value_VAT'=>$request->Value_VAT,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            $box = Box_account::find($request->Custmoer);
            $total = ($box->Total - $request->Value_VAT);
    
            Dep_accounts::create([
                'id_Invoice' => $request->invoice_number, 'id_box' => $box->id, 'Value_VAT' => $request->Value_VAT, 'Total' => $box->Total,
                'Created_by' => (Auth::user()->name),
                'Value_Status'=>1
            ]);
            $box->update([
                'Total' => $total
            ]);

            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'Value_VAT' => $request->Value_VAT,

                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        if ($request->id_page == 1) {
            return redirect('/invoices');
        } elseif ($request->id_page == 2) {
            return redirect('/Invoice_Paid');
        } elseif ($request->id_page == 3) {
            return redirect('/Invoice_UnPaid');
        } elseif ($request->id_page == 4) {
            return redirect('/Invoice_Partial');
        }
    }
    public function Invoice_Paid()
    {
        $invoices = Invoices::where('Value_Status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    public function Invoice_unPaid()
    {
        $invoices = Invoices::where('Value_Status', 2)->get();
        return view('invoices.invoices_unpaid', compact('invoices'));
    }

    public function Invoice_Partial()
    {
        $invoices = Invoices::where('Value_Status', 3)->get();
        return view('invoices.invoices_Partial', compact('invoices'));
    }
    public function Print_invoice($id)
    {
        $invoices = invoices::where('id', $id)->first();
        $box=Box_account::where('customer_id',$invoices->customer_id)->first();
        $dep_acc=Dep_accounts::where('id_Invoice',$invoices->invoice_number )->where('Value_Status',0)->first();

        return view('invoices.Print_invoice', compact('invoices','dep_acc','box'));
    }
    public function export($id)
    {

        return Excel::download(new InvoicesExport($id), 'invoices.xlsx');
    }
    public function MarkAsRead_all(Request $request)
    {

        $userUnreadNotification = auth()->user()->unreadNotifications;

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }
    }


    public function unreadNotifications_count()

    {
        return auth()->user()->unreadNotifications->count();
    }

    public function unreadNotifications()

    {
        foreach (auth()->user()->unreadNotifications as $notification) {

            return $notification->data['title'];
        }
    }
}
