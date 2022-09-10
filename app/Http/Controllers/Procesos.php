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
use App\Procesosl;
use App\Product_Warehouse;
use App\Product;
use Illuminate\Support\Facades\DB;

class Procesos extends Controller
{
    //
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
                $lims_quotation_all = Procesosl::with('supplier')->orderBy('id', 'desc')->get();
                //$lims_quotation_all = Quotation::with('supplier')->orderBy('id', 'desc')->get();
            else
                $lims_quotation_all = Procesosl::with('supplier')->orderBy('id', 'desc')->get();
                //$lims_quotation_all = Quotation::with('supplier')->orderBy('id', 'desc')->get();
                
            return view('procesos.index', compact('lims_quotation_all', 'all_permission'));
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
          
            $lims_product_list  = Product::where([ ['category_id', '1'], ['type', 'standard'],['is_active', true] ])->get(); 
           
            return view('procesos.create', compact('lims_supplier_list','lims_product_list'));
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
       
       

        $document = $request->document;
       
       
        
              
          
        $lims_procesos_data = Procesosl::create($data);
        //$ultimoreg  = Procesosl::where('id')->first();
       $ultimoreg = $lims_procesos_data->id;
     //QUERYS SOLO PARA MOSTRAR EL PRODUCTO QUE SE ESTA PROCESANDO EN EL INDEX DE LA PAGINA
        DB::table('procesosls as c')
        ->join('products as b', 'c.producto_id', '=', 'b.id')
        ->update([ 'c.nombre_producto' => DB::raw("`b`.`name`") ]);
        DB::table('procesosls as c')
        ->join('products as b', 'c.producto_id', '=', 'b.id')
        ->update([ 'c.codigo' => DB::raw("`b`.`code`") ]);
       //ACTUALIZACION DE RESTA DE CANTIDAD EN STOCK CUANDO SE INICIA UN PROCESO
       $results = DB::update(DB::raw(" UPDATE product_warehouse des,(SELECT  b.qty-a.peso_inicial res ,a.producto_id prod 
        from procesosls a INNER JOIN product_warehouse b ON a.producto_id=b.product_id WHERE a.id='$ultimoreg') src
       set des.qty=src.res
       WHERE des.product_id=src.prod" ));
       //ACTUALIZACION PARA LA CANTINDAD EN MODULO DE PRODUCTOS
       $results2 = DB::update(DB::raw("UPDATE products des,
       (SELECT a.qty qt, a.product_id pr FROM product_warehouse a INNER JOIN products b on a.product_id=b.id)src
       SET des.qty=src.qt
       WHERE des.id=src.pr" ));
       //calculo de costo d la produccion
       $results3 = DB::update(DB::raw(" UPDATE procesosls des,
       (SELECT (a.bolsas_utilizar * a.costo_enbolsado)+ (a.etiquetas_utilizar * a.costo_etiquetas )res  FROM procesosls a WHERE a.id='$ultimoreg') src
       set des.costo_final_venta=src.res
       WHERE des.id='$ultimoreg'" ));

        $message = 'Procesos creado satisfactoriamente'  ;
        
        return redirect('procesos')->with('message', $message);
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
            
           
            $lims_product_list = Product::where([ ['category_id', '1'], ['type', 'standard'],['is_active', true] ])->get();
            $lims_product_data = Procesosl::find($id);
           
            return view('procesos.edit',compact('lims_product_data', 'lims_product_list'));
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
      //$data ['columna de la tabla']=$data['nombredelfield'];
        $data['etiquetas_utilizar']=$data['etiquetas_utilizar'];
        
        $lims_quotation_data = Procesosl::find($id);     
       
        $lims_quotation_data->update($data);
       
        if($lims_quotation_data->status == 'Terminado'){
            
           //DEVUELVE LA CANTIDAD QUE ESTABA EN PROD A BODEGA
        
       $results = DB::update(DB::raw(" UPDATE product_warehouse des,(SELECT  b.qty+a.peso_final res ,a.producto_destino_id prod 
       from procesosls a INNER JOIN product_warehouse b ON a.producto_destino_id=b.product_id WHERE a.id='$id') src
      set des.qty=src.res
      WHERE des.product_id=src.prod" ));
      //ACTUALIZACION DE CANTIDAD DE BODEGA PARA TABLA DE PRODUCTOS
      $results2 = DB::update(DB::raw("UPDATE products des,
      (SELECT a.qty qt, a.product_id pr FROM product_warehouse a INNER JOIN products b on a.product_id=b.id)src
      SET des.qty=src.qt
      WHERE des.id=src.pr" ));
      //calculo de costo de la produccion
      $results3 = DB::update(DB::raw(" UPDATE procesosls des,
      (SELECT (a.bolsas_utilizar * a.costo_enbolsado)+ (a.etiquetas_utilizar * a.costo_etiquetas )res  FROM procesosls a WHERE a.id='$id') src
      set des.costo_final_venta=src.res
      WHERE des.id='$id'" ));
            
        
           
 
          
            $message = 'Proceso actualizado correctamente';
         }
        else
         $message = 'Proceso actualizado correctamente'  ;
        
        return redirect('procesos')->with('message', $message);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lims_quotation_data = Procesosl::find($id);
       
        $lims_quotation_data->delete();
        return redirect('procesos')->with('not_permitted', 'Proceso borrado exitosamente');
    }




}
