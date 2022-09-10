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
use App\Reprocesos;
use App\Product_Warehouse;
use App\Product;
use Illuminate\Support\Facades\DB;

class ReprocesosController extends Controller
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
                $lims_quotation_all = Reprocesos::with('supplier')->orderBy('id', 'desc')->get();
                //$lims_quotation_all = Quotation::with('supplier')->orderBy('id', 'desc')->get();
            else
                $lims_quotation_all = Reprocesos::with('supplier')->orderBy('id', 'desc')->get();
                //$lims_quotation_all = Quotation::with('supplier')->orderBy('id', 'desc')->get();
                
            return view('reprocesos.index', compact('lims_quotation_all', 'all_permission'));
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
           // $lims_product_list = Product::where('is_active', true)->get();
            $lims_product_list  = Product::where([ ['category_id', '1'], ['type', 'standard'],['is_active', true] ])->get(); 
           
            return view('reprocesos.create', compact('lims_supplier_list','lims_product_list'));
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
       
       
        
              
          
        $lims_procesos_data = Reprocesos::create($data);
    
        DB::table('reprocesos as c')
        ->join('products as b', 'c.producto_id', '=', 'b.id')
        ->update([ 'c.nombre_producto' => DB::raw("`b`.`name`") ]);
       
        DB::table('reprocesos as c')
        ->join('products as b', 'c.producto_id', '=', 'b.id')
        ->update([ 'c.codigo_producto' => DB::raw("`b`.`code`") ]);

        $message = 'Reprocesos creado satisfactoriamente';
        
        return redirect('reprocesos')->with('message', $message);
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
            
            $lims_product_list = Product::where([ ['category_id', '1'], ['type', 'standard'] ])->get(); 
            //$lims_product_list = Product::where('is_active', true)->get();
            $lims_product_data = Reprocesos::find($id);
           
            return view('reprocesos.edit',compact('lims_product_data', 'lims_product_list'));
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
        $data['total_lb_reproceso']=$data['total_lb_reproceso'];
        
        $lims_quotation_data = Reprocesos::find($id);     
       
        $lims_quotation_data->update($data);
       
        if($lims_quotation_data->status == 'Terminado'){
            /*CALCULO DE CUANTAS LIBRAS VAN A BODEGA*/ 
         
           /* $suma = DB::select("SELECT  a.pesof+ b.qty FROM linea_produccions a INNER JOIN
              product_warehouse b ON a.product_id_venta=b.product_id
            WHERE a.id =".$id);*/
             //TIENE QUE HABER HABIDO 1 COMPRA DE EL PRODUCTO A VENDER PARA PODERSE REGISTRAR
             //ACTUALIZACION DE CANTIDAD EN STOCK CUANDO TERMINA UNA PRODUCCION
            $results = DB::update(DB::raw("UPDATE product_warehouse des,(SELECT a.total_lb_reproceso+b.qty su,a.producto_id prod  FROM reprocesos a  INNER JOIN  product_warehouse b ON a.producto_id=b.product_id
            WHERE a.id = '$id') src
            set des.qty=src.su
            WHERE des.product_id=src.prod" ));
             //ACTUALIZACION DE LA TABLA DE LA CANTIDAD EN STOCK QUE SE LE MUESTRA AL USUARIO EN MODULO DE PRODUCTOS
            $results2 = DB::update(DB::raw("UPDATE products des,
            (SELECT a.qty qt, a.product_id pr FROM product_warehouse a INNER JOIN products b on a.product_id=b.id)src
            SET des.qty=src.qt
            WHERE des.id=src.pr" ));
            
            
        
           
 
          
            $message = 'Reproceso actualizado correctamente';
         }
        else
         $message = 'Reproceso actualizado correctamente'  ;
        
        return redirect('reprocesos')->with('message', $message);
    }


    public function getProduct($id)
    {
        $lims_product_warehouse_data = Product_Warehouse::where([
                                        ['warehouse_id', $id],
                                        ['qty', '>', 0]
                                    ])->whereNull('variant_id')->get();
        $lims_product_with_variant_warehouse_data = Product_Warehouse::where([
                ['warehouse_id', $id],
                ['qty', '>', 0]
            ])->whereNotNull('variant_id')->get();
        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_data = [];
        //product without variant
        foreach ($lims_product_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $lims_product_data = Product::find($product_warehouse->product_id);
            $product_code[] =  $lims_product_data->code;
            $product_name[] = $lims_product_data->name;
            $product_type[] = $lims_product_data->type;
            $product_id[] = $lims_product_data->id;
            $product_list[] = $lims_product_data->product_list;
            $qty_list[] = $lims_product_data->qty_list;
        }
        //product with variant
        foreach ($lims_product_with_variant_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $lims_product_data = Product::find($product_warehouse->product_id);
            $lims_product_variant_data = ProductVariant::select('item_code')->FindExactProduct($product_warehouse->product_id, $product_warehouse->variant_id)->first();
            $product_code[] =  $lims_product_variant_data->item_code;
            $product_name[] = $lims_product_data->name;
            $product_type[] = $lims_product_data->type;
            $product_id[] = $lims_product_data->id;
            $product_list[] = $lims_product_data->product_list;
            $qty_list[] = $lims_product_data->qty_list;
        }
        //retrieve product with type of digital and combo
        $lims_product_data = Product::whereNotIn('type', ['standard'])->where('is_active', true)->get();
        foreach ($lims_product_data as $product) 
        {
            $product_qty[] = $product->qty;
            $product_code[] =  $product->code;
            $product_name[] = $product->name;
            $product_type[] = $product->type;
            $product_id[] = $product->id;
            $product_list[] = $product->product_list;
            $qty_list[] = $product->qty_list;
        }
        $product_data = [$product_code, $product_name, $product_qty, $product_type, $product_id, $product_list, $qty_list];
        return $product_data;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lims_quotation_data = Reprocesos::find($id);
       
        $lims_quotation_data->delete();
        return redirect('reprocesos')->with('not_permitted', 'Reproceso borrado exitosamente');
    }




}
