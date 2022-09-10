<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Spatie\Permission\Models\Role;
use App\Quotation;
use App\Supplier;
use App\Warehouse;
use App\Biller;
use App\Tax;
use App\Customer;
use App\LecheRecibida;

class LecherecibidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('quotes-index')){
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[] = 'dummy text';
            
            if(Auth::user()->role_id > 2 && config('staff_access') == 'own')
                $lims_quotation_all = LecheRecibida::with('supplier')->orderBy('id', 'desc')->get();
                //$lims_quotation_all = Quotation::with('supplier')->orderBy('id', 'desc')->get();
            else
                $lims_quotation_all = LecheRecibida::with('supplier')->orderBy('id', 'desc')->get();
                //$lims_quotation_all = Quotation::with('supplier')->orderBy('id', 'desc')->get();
                
            return view('lecherecibida.index', compact('lims_quotation_all', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('quotes-add')){
           
            $lims_supplier_list = Supplier::where('is_active', true)->get();
           

            return view('lecherecibida.create', compact('lims_supplier_list'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $data = $request->except('document');
         /*return dd($data);*/
        $data['user_id'] = Auth::id();
        $data['created_at']=$data['expired_date'];
        $document = $request->document;
       
       
        $lims_lecherecibida_data = LecheRecibida::create($data);           

        $message = 'Milk received created successfully';
        
        return redirect('lecherecibida')->with('message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('quotes-edit')){
            
            $lims_supplier_list = Supplier::where('is_active', true)->get();
           
            $lims_quotation_data = LecheRecibida::find($id);
           
            return view('lecherecibida.edit',compact('lims_quotation_data', 'lims_supplier_list'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('document');
        //return dd($data);
        $document = $request->document;

        $data['created_at']=$data['expired_date'];
        
        $lims_quotation_data = LecheRecibida::find($id);     
       
        $lims_quotation_data->update($data);
       
        $message = 'Milk received updated successfully';
        
        return redirect('lecherecibida')->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lims_quotation_data = LecheRecibida::find($id);
       
        $lims_quotation_data->delete();
        return redirect('lecherecibida')->with('not_permitted', 'Milk received deleted successfully');
    }
}
