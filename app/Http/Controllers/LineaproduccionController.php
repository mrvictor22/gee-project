<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Spatie\Permission\Models\Role;
use App\Quotation;
use App\Warehouse;
use App\Biller;
use App\Customer;
use App\Tax;
use App\Supplier;
use App\Product;
use App\CustomerGroup;
use App\Product_Warehouse;
use App\ProductVariant;
use App\LineaProduccion;
use Illuminate\Support\Facades\DB;

class LineaproduccionController extends Controller
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
                $lims_quotation_all = LineaProduccion::with('product')->orderBy('id', 'desc')->get();
                //$lims_quotation_all = Quotation::with('supplier')->orderBy('id', 'desc')->get();
            else
                $lims_quotation_all = LineaProduccion::with('product')->orderBy('id', 'desc')->get();
               // $lims_quotation_all = Quotation::with('supplier')->orderBy('id', 'desc')->get();
            return view('lineaproduccion.index', compact('lims_quotation_all', 'all_permission'));
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
            $lims_biller_list = Biller::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_customer_list = Customer::where('is_active', true)->get();
            $lims_supplier_list = Product::where([['type', 'combo'],['is_active','1']])->get();   
            $lims_supplier_list2 = Product::where([ ['category_id', '43'], ['type', 'standard'] , ['is_active','1'] ])->get();      
            
          
            $lims_tax_list = Tax::where('is_active', true)->get();

            return view('lineaproduccion.create', compact('lims_biller_list', 'lims_warehouse_list', 'lims_customer_list', 'lims_supplier_list','lims_supplier_list2', 'lims_tax_list'));
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
              
         $lims_lineaproduccion_data = new LineaProduccion(); 
         
       $lims_lineaproduccion_data->product_id = $request->product_id;                               
       $lims_lineaproduccion_data->product_id_venta = $request->product_id_venta;                               
       $lims_lineaproduccion_data->leche_usar = $request->leche_usar;                               
       $lims_lineaproduccion_data->libras_a_prod = $request->libras_a_prod;                               
       $lims_lineaproduccion_data->acidez = $request->acidez;                                 
       $lims_lineaproduccion_data->peso = $request->peso;                              
       $lims_lineaproduccion_data->temperatura = $request->temperatura;                              
       $lims_lineaproduccion_data->ntrabajadores = $request->ntrabajadores;                                
                                      
       $lims_lineaproduccion_data->status = $request->status;                                 
       $lims_lineaproduccion_data->pesof = $request->pesof;                                 
       $lims_lineaproduccion_data->otros_costos = $request->otros_costos;                                 
       $lims_lineaproduccion_data->nota_prod = $request->nota_prod;              
        //Busco el producto para sacar el precio de la receta
        $producto_data = Product::find($request->product_id);     
        $costoreceta = $producto_data->price;
        $librasprod = $request->libras_a_prod;
        //Hago el calculo de el precio de la receta por las libras a producir
        $costo_xlibra =$librasprod * $costoreceta;
        $lims_lineaproduccion_data->costoin = number_format((float)$costo_xlibra, 2, '.', '');    
       
     
       
         //CALCULO DE LOS COSTOS EXTRAS 
        $otro_costo = $request->input('otros_costos');
        $costo=  $costo_xlibra + $otro_costo ;
       
        $lims_lineaproduccion_data->costo = number_format((float)$costo, 2, '.', '');        
        $lims_lineaproduccion_data->save();   




        $prodid = $request->input('product_id');
        $cant_lib = $request->input('libras_a_prod');
        $status = $request->input('status');
        $obtener_producto  = Product::find($prodid);
           $lista_combo = $obtener_producto->product_list;
           $cantidad_combo = $obtener_producto->qty_list;
           $listaprd = explode(",", $lista_combo);
           $listaqty = explode(",", $cantidad_combo);
           $longitud = count($listaprd);
          
           if ($status == 3) {
            for($i=0; $i<$longitud; $i++)
            {
 
            //actualizo a cada elemento de la lista de productos el dato que quiero
             $prd = Product::find($listaprd[$i]);
             
             $old_qty[$i] = $prd->qty;
                    //calculo de cantidades de la receta por la cantidad de libras a producir 
                     //ahora este dato tiene que ser restado de la cantidad existente en bodega del producto de tipo insumo que se 
                    //esta tomando en el array
                    $cal[$i] =  $old_qty[$i] - ($listaqty[$i] * $cant_lib) ;
                   
                    
             $prd->qty = number_format((float)$cal[$i], 2, '.', '');
             Product_Warehouse::where('product_id',$listaprd[$i])->update(['qty' => number_format((float)$cal[$i], 2, '.', '')]);
             $prd->save();
          
            }
           }
           
          
       
       
       $message = 'Production line created successfully ' ;
       
       return redirect('lineaproduccion')->with('message', $message);
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
            
            /*$lims_supplier_list = Supplier::where('is_active', true)->get();*/

            $lims_supplier_list = Product::where([['type', 'combo'],['is_active','1']])->get();   
            $lims_supplier_list2 = Product::where([ ['category_id', '43'], ['type', 'standard'], ['is_active','1'] ])->get(); 
           
            $lims_quotation_data = LineaProduccion::find($id);

           
              

            
           
            return view('lineaproduccion.edit',compact('lims_quotation_data', 'lims_supplier_list','lims_supplier_list2'));
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
        $data = $request->all();
        //return dd($data);
        //$document = $request->document;       
        
        $lims_quotation_data = LineaProduccion::find($id);     
        $lims_quotation_data->product_id = $request->product_id;                               
        $lims_quotation_data->product_id_venta = $request->product_id_venta;                               
        $lims_quotation_data->leche_usar = $request->leche_usar;                               
        $lims_quotation_data->libras_a_prod = $request->libras_a_prod;                               
        $lims_quotation_data->acidez = $request->acidez;                                 
        $lims_quotation_data->peso = $request->peso;                              
        $lims_quotation_data->temperatura = $request->temperatura;                              
        $lims_quotation_data->ntrabajadores = $request->ntrabajadores;                                
        
        $lims_quotation_data->status = $request->status;                                 
        $lims_quotation_data->pesof = $request->pesof;                                 
        $lims_quotation_data->otros_costos = $request->otros_costos;                                 
        $lims_quotation_data->nota_prod = $request->nota_prod;           
        
        //CALCULO DE COSTO DE PRODUCION POR LIBRAS
        //Busco el producto para sacar el precio de la receta
        $producto_data = Product::find($request->product_id);     
        $costoreceta = $producto_data->price;
        $librasprod = $request->libras_a_prod;
        //Hago el calculo de el precio de la receta por las libras a producir
        $costo_xlibra =$librasprod * $costoreceta;
        $lims_quotation_data->costoin = number_format((float)$costo_xlibra, 2, '.', '');    

         $otro_costo = $request->input('otros_costos');
         $costo=  $lims_quotation_data->costoin + $otro_costo ;
       
         $lims_quotation_data->costo = number_format((float)$costo, 2, '.', '');    
        
         $lims_quotation_data->save();
        if($lims_quotation_data->status == 2){
           /*CALCULO DE CUANTAS LIBRAS VAN A BODEGA*/ 
        
          /* $suma = DB::select("SELECT  a.pesof+ b.qty FROM linea_produccions a INNER JOIN
             product_warehouse b ON a.product_id_venta=b.product_id
           WHERE a.id =".$id);*/
           
            //TIENE QUE HABER HABIDO 1 COMPRA DE EL PRODUCTO A VENDER PARA PODERSE REGISTRAR
            //ACTUALIZACION DE CANTIDAD EN STOCK CUANDO TERMINA UNA PRODUCCION
           $results = DB::update(DB::raw("UPDATE product_warehouse des,(SELECT a.pesof+ b.qty su,a.product_id_venta prod  
           FROM linea_produccions a INNER JOIN  product_warehouse b ON a.product_id_venta=b.product_id
           WHERE a.id = '$id') src
           set des.qty=src.su
           WHERE des.product_id=src.prod" ));
            //ACTUALIZACION DE LA TABLA DE LA CANTIDAD EN STOCK QUE SE LE MUESTRA AL USUARIO EN MODULO DE PRODUCTOS
           $results2 = DB::update(DB::raw("UPDATE products des,
           (SELECT a.qty qt, a.product_id pr FROM product_warehouse a INNER JOIN products b on a.product_id=b.id)src
           SET des.qty=src.qt
           WHERE des.id=src.pr" ));
           
           
       
          

         
           $message = 'Linea de produccion actualizada' ;
        }
       else
        $message = 'Production line updated successfully' ;
        
        return redirect('lineaproduccion')->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lims_quotation_data = LineaProduccion::find($id);
       
        $lims_quotation_data->delete();
        return redirect('lineaproduccion')->with('not_permitted', 'Production line deleted successfully');
    }    

    public function deleteBySelection(Request $request)
    {
        $quotation_id = $request['quotationIdArray'];
        foreach ($quotation_id as $id) {
            $lims_quotation_data = LineaProduccion::find($id);
           
            
            $lims_quotation_data->delete();
        }
        return 'Quotation deleted successfully!';
    }

    public function limsProductSearch(Request $request){    
      
        
       
        $lims_product_data = Product::all()->first();
        
        $product[] = $lims_product_data->name;
        $product[] = $lims_product_data->code;
      
        
            $product[] = $lims_product_data->price;
       
     
            $product[] = 'No Tax';
        
        $product[] = $lims_product_data->tax_method;
        
        $product[] = $lims_product_data->id;
        $product[] = $product_variant_id;
        return $product;
    }
}
