<?php

namespace App\Http\Controllers;

use App\Box_account;
use App\Customer;
use App\Dep_accounts;
use App\sections;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $data = Customer::orderBy('id', 'DESC')->paginate(5);
        return view('customers.customers', compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);;
    }
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('customers.Add_customer', compact('roles'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name_customer' => 'required|unique:customers|max:59',
            'phone_customer' => 'required|max:12'
        ], [

            'name_customer.required' => 'يرجي ادخال اسم االعميل',
            'name_customer.unique' => 'اسم العميل مسجل مسبقا',
            'name_customer.max' => '  اسم العميل يتجوز 60 حرف',
            'phone_customer.required' => 'يرجي ادخال رقم العميل',



        ]);
        Customer::create([
            'name_customer' => $request->name_customer,
            'phone_customer' => $request->phone_customer,
            'note_customer' => $request->note_customer,
            'Value_Status' => $request->Value_Status,
            'Created_by' => (Auth::user()->name),

        ]);
        $cutm_id = Customer::latest()->first()->id;
        Box_account::create([
            'customer_id' => $cutm_id,
            'Total' => 0
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'تم اضافة العميل بنجاح');
    }
    public function edit($id)
    {
        $cust = Customer::find($id);

        return view('customers.edit_customer', compact('cust'));
    }
    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'name_customer' => 'required|max:59|unique:customers,name_customer,' . $id,
            'phone_customer' => 'required|max:12'
        ], [

            'name_customer.required' => 'يرجي ادخال اسم االعميل',
            'name_customer.unique' => 'اسم العميل مسجل مسبقا',
            'name_customer.max' => '  اسم العميل يتجوز 60 حرف',
            'phone_customer.required' => 'يرجي ادخال رقم العميل',



        ]);
        $cust = Customer::findOrFail($id);

        $cust->update([
            'name_customer' => $request->name_customer,
            'phone_customer' => $request->phone_customer,
            'note_customer' => $request->note_customer,
            'Value_Status' => $request->Value_Status,
            'Created_by' => (Auth::user()->name),

        ]);
        return redirect()->route('customers.index')
            ->with('success', 'تم تعديل العميل بنجاح');
    }
    public function destroy(Request $request)
    {

        Customer::find($request->id)->delete();
        return redirect()->route('customers.index')->with('success', 'تم حذف العميل بنجاح');
    }
    public function show($id)
    {

        $customers =    Customer::find($id);
        $box =    Box_account::where('customer_id', $customers->id)->first();
        $dep =    Dep_accounts::where('id_box', $box->id)->where('Value_Status', 0)->get();
        $dep1 =   Dep_accounts::where('id_box', $box->id)->where('Value_Status', 1)->get();


        return view('customers.details_customers', compact('customers', 'box', 'dep', 'dep1'));
    }
}
