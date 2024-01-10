<?php

namespace App\Http\Controllers;

use App\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
{

$this->middleware('permission:الاقسام', ['only' => ['index']]);
$this->middleware('permission:اضافة قسم', ['only' => ['create','store']]);
$this->middleware('permission:تعديل قسم', ['only' => ['edit','update']]);
$this->middleware('permission:حذف قسم', ['only' => ['destroy']]);

}
   
    public function index()
    {
        $sections = sections::all();
        return view('sections.sections',compact('sections'));

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
            'section_name' => 'required|unique:sections|max:50',
        ],[

            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
            'section_name.max' =>'  اسم القسم يتجوز 50 حرف',



        ]);
        

            sections::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'Created_by' => (Auth::user()->name),

            ]);
            session()->flash('Add', 'تم اضافة القسم بنجاح ');
            return redirect('/sections');

    }




   
    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [

            'section_name' => 'required|max:50|unique:sections,section_name,'.$id,
            
        ],[

            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
            'section_name.max' =>'  اسم القسم يتجوز 50 حرف',

          

        ]);

        $sections = sections::findOrFail($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('edit','تم تعديل القسم بنجاج');
        return redirect('/sections');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        sections::find($id)->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}
