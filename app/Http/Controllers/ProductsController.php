<?php

namespace App\Http\Controllers;

use App\products;
use App\sections;
use Illuminate\Http\Request;
use Whoops\RunInterface;

class ProductsController extends Controller
{
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
    
    $this->middleware('permission:المنتجات', ['only' => ['index']]);
    $this->middleware('permission:اضافة منتج', ['only' => ['create','store']]);
    $this->middleware('permission:تعديل منتج', ['only' => ['edit','update']]);
    $this->middleware('permission:حذف منتج', ['only' => ['destroy']]);
    
    }
    public function index()
    {
        $sections = sections::all();
        $products = products::all();
        return view('products.products', compact('sections','products'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Product_name' => 'required|max:50',
            'section_id' => 'required',

        ],[

            'Product_name.required' =>'يرجي ادخال اسم المنتج',
            'section_id.required' =>'يرجي ادخال اسم القسم',
            'Product_name.max' =>'  طول المنتج يتجاوز خمسين 50 حرف',



        ]);
        Products::create([
            'Product_name' => $request->Product_name,
            'section_id' => $request->section_id,
            'description' => $request->description,
        ]);
        session()->flash('Add', 'تم اضافة المنتج بنجاح ');
        return redirect('/products');    }

    public function update(Request $request)
    {

        $validatedData = $request->validate([
            'Product_name' => 'required|max:50',
            'section_name' => 'required',

        ],[

            'Product_name.required' =>'يرجي ادخال اسم المنتج',
            'section_name.required' =>'يرجي ادخال اسم القسم',
            'Product_name.max' =>'  طول المنتج يتجاوز خمسين 50 حرف',



        ]);
        $id = sections::where('section_name', $request->section_name)->first()->id;

        $Products = Products::findOrFail($request->pro_id);
 
        $Products->update([
        'Product_name' => $request->Product_name,
        'description' => $request->description,
        'section_id' => $id,
        ]);
 
        session()->flash('Edit', 'تم تعديل المنتج بنجاح');
        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $Products = Products::findOrFail($request->pro_id);
        $Products->delete();
        session()->flash('delete', 'تم حذف المنتج بنجاح');
        return redirect('/products');
    }
}
