<?php   

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Biller;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; 
use App\Sale;
use App\Product_Sale;
use App\Warehouse;
use App\Payment;
use Carbon\Carbon;
use DB;
use App\Ticket_cors;
use App\Cortez_bitacora;
use NumberToWords\NumberToWords;

use Auth;

class BillerController extends Controller
{
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('billers-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[] = 'dummy text';
            $lims_biller_all = biller::where('is_active', true)->get();
            return view('biller.index',compact('lims_biller_all', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('billers-add'))
            return view('biller.create');
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
            'company_name' => [
                'max:255',
                    Rule::unique('billers')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
            'email' => [
                'email',
                'max:255',
                    Rule::unique('billers')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:10000',
        ]);

        $lims_biller_data = $request->except('image');
        $lims_biller_data['is_active'] = true;
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['company_name']);
            /*Image::make($image)
                ->resize(250, null, function ($constraints) {
                    $constraints->aspectRatio();
                })->save('public/images/biller/' . $imageName.'-resize.'.$ext);*/
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/biller', $imageName);
            
            $lims_biller_data['image'] = $imageName;
        }
        Biller::create($lims_biller_data);
        $message = 'Data inserted successfully';
        try{
            Mail::send( 'mail.biller_create', $lims_biller_data, function( $message ) use ($lims_biller_data)
            {
                $message->to( $lims_biller_data['email'] )->subject( 'New Biller' );
            });
        }
        catch(\Exception $e){
            $message = 'Data inserted successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
        }  
        return redirect('biller')->with('message', $message);
    }

    public function edit($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('billers-edit')) {
            $lims_biller_data = Biller::where('id',$id)->first();
            return view('biller.edit',compact('lims_biller_data'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'company_name' => [
                'max:255',
                    Rule::unique('billers')->ignore($id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
            'email' => [
                'email',
                'max:255',
                    Rule::unique('billers')->ignore($id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],

            'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        $input = $request->except('image');
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['company_name']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/biller', $imageName);
            $input['image'] = $imageName;
        }

        $lims_biller_data = Biller::findOrFail($id);
        $lims_biller_data->update($input);
        return redirect('biller')->with('message','Data updated successfully');
    }

    public function importBiller(Request $request)
    {
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext != 'csv')
            return redirect()->back()->with('not_permitted', 'Please upload a CSV file');
        $filename =  $upload->getClientOriginalName();
        $filePath=$upload->getRealPath();
        //open and read
        $file=fopen($filePath, 'r');
        $header= fgetcsv($file);
        $escapedHeader=[];
        //validate
        foreach ($header as $key => $value) {
            $lheader=strtolower($value);
            $escapedItem=preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }
        //looping through othe columns
        while($columns=fgetcsv($file))
        {
            if($columns[0]=="")
                continue;
            foreach ($columns as $key => $value) {
                $value=preg_replace('/\D/','',$value);
            }
           $data= array_combine($escapedHeader, $columns);

           $biller = Biller::firstOrNew(['company_name'=>$data['companyname']]);
           $biller->name = $data['name'];
           $biller->image = $data['image'];
           $biller->vat_number = $data['vatnumber'];
           $biller->email = $data['email'];
           $biller->phone_number = $data['phonenumber'];
           $biller->address = $data['address'];
           $biller->city = $data['city'];
           $biller->state = $data['state'];
           $biller->postal_code = $data['postalcode'];
           $biller->country = $data['country'];
           $biller->is_active = true;
           $biller->save();
           $message = 'Biller Imported successfully';
           if($data['email']){
                try{
                    Mail::send( 'mail.biller_create', $data, function( $message ) use ($data)
                    {
                        $message->to( $data['email'] )->subject( 'New Biller' );
                    });
                }
                catch(\Exception $e){
                    $message = 'Biller Imported successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
                }
            }
        }
        return redirect('biller')->with('message', $message);
        
    }

    public function deleteBySelection(Request $request)
    {
        $biller_id = $request['billerIdArray'];
        foreach ($biller_id as $id) {
            $lims_biller_data = Biller::find($id);
            $lims_biller_data->is_active = false;
            $lims_biller_data->save();
        }
        return 'Biller deleted successfully!';
    }

    public function destroy($id)
    {
        $lims_biller_data = Biller::find($id);
        $lims_biller_data->is_active = false;
        $lims_biller_data->save();
        return redirect('biller')->with('not_permitted','Data deleted successfully');
    }

    public function invoice($id)
    {   //BUSCO EL ID DE LA CAJA
        $lims_caja_data = Biller::find($id);
    
         $tipo_decortez="Del dia";
        //instancio la tabla bitacora
        $bitacora_save = new Cortez_bitacora;
        //BUSCO SI HAY UN CORTEZ EN MI BITACORA TABLA
        $bitacora_no = Cortez_bitacora::where('caja_id',$id)->latest()->first();
        $idcort=$bitacora_no->cortez_no;
       
        $fechahoy=Carbon::now();
     
       // $numerodecor++;
        //SACO TODOS LOS REGISTROS APROBADOS DEL DIA CON LA LIBRERIA CARBON
        $posts = Ticket_cors::where([['fecha_venta', '>=', date('Y-m-d').' 00:00:00'],['caja_id',$id],['estado_ticket','Aprobado']])->get();
        $postsanulado = Ticket_cors::where([['fecha_venta', '>=', date('Y-m-d').' 00:00:00'],['caja_id',$id],['estado_ticket','Anulado']])->get();
                //SACO EL TOTAL DE PRODUCTOS VENDIDOS EN EL CORTE Z
        $sum = $posts->sum('cantidad_productos');
        //saco el inicio de los correlativos y el final
        $ticket_inicio= $posts->min('correlativo_no');
        $ticket_fin= $posts->max('correlativo_no');
        //saco el total de tickets emitidos
        $emitidos = $posts->count('id');
        //saco el monto total de los tickets
        $total_venta_ticket = $posts->sum('total_venta');
        //hago la suma del numero de corte
        $cortesum= $idcort+1;
        //SACO EL TOTAL DE PRODUCTOS ANULADOS EN EL CORTE Z
        $sumn = $postsanulado->sum('cantidad_productos');
        //saco el inicio de los correlativos y el final
        $ticket_inicion= $postsanulado->min('correlativo_no');
        $ticket_fin_n= $postsanulado->max('correlativo_no');
        //saco el total de transacciones anuladas
        $emitidos_nulos = $postsanulado->count('id');
        //saco el monto total de los tickets
        $total_venta_ticket_nulo = $postsanulado->sum('total_venta');
       
        //funcion a futuro pero lo mejor es siempre guardar todo: verifico si hay un corte en la tabla si no hay que ingrese datos
       
            $bitacora_save->caja_id=$id;
            $bitacora_save->cortez_no=$cortesum;
            $bitacora_save->tipo_cortez=$tipo_decortez;
            $bitacora_save->ticket_inicio=$ticket_inicio;
            $bitacora_save->ticket_final=$ticket_fin;
            $bitacora_save->total_transacciones=$emitidos;
            $bitacora_save->total_productos=$sum;
            $bitacora_save->totalv_cortez=$total_venta_ticket;
            //////save de anulados
            $bitacora_save->tot_anulados=$sumn;
            $bitacora_save->ticket_inicio_anulado=$ticket_inicion;
            $bitacora_save->ticket_fin_anulado=$ticket_fin_n;
            $bitacora_save->transacciones_anuladas=$emitidos_nulos;
            $bitacora_save->totalv_nulo=$total_venta_ticket_nulo;
            
            $bitacora_save->save();
           
        
        

        //ALMACENO LOS DATOS EN MI TABLA BITACORA DE CORTEZ SI ES QUE NO SE HA EMITIDO EL CORTE
        
  
        
        

        return view('biller.invoice', compact('total_venta_ticket_nulo','sumn','emitidos_nulos','fechahoy','idcort','cortesum','lims_caja_data','sum','ticket_inicio','ticket_fin','emitidos','total_venta_ticket'));
    }
    public function invoicemes($id)
    {   //BUSCO EL ID DE LA CAJA
        $lims_caja_data = Biller::find($id);
        $tipo_decortez="Del mes";
        
        //instancio la tabla bitacora
        $bitacora_save = new Cortez_bitacora;
        //BUSCO SI HAY UN CORTEZ EN MI BITACORA TABLA
        $bitacora_no = Cortez_bitacora::where('caja_id',$id)->latest()->first();
        $idcort=$bitacora_no->cortez_no;
       
        $fechahoy=Carbon::now();
     
       // $numerodecor++;
        //SACO TODOS LOS REGISTROS APROBADOS DEL Mes 
        $posts = Ticket_cors::where([['fecha_venta', '>=', date('Y-m').' 00:00:00'],['caja_id',$id],['estado_ticket','Aprobado']])->get();
        $postsanulado = Ticket_cors::where([['fecha_venta', '>=', date('Y-m').' 00:00:00'],['caja_id',$id],['estado_ticket','Anulado']])->get();
                //SACO EL TOTAL DE PRODUCTOS VENDIDOS EN EL CORTE Z
        $sum = $posts->sum('cantidad_productos');
        //saco el inicio de los correlativos y el final
        $ticket_inicio= $posts->min('correlativo_no');
        $ticket_fin= $posts->max('correlativo_no');
        //saco el total de tickets emitidos
        $emitidos = $posts->count('id');
        //saco el monto total de los tickets
        $total_venta_ticket = $posts->sum('total_venta');
        //hago la suma del numero de corte
        $cortesum= $idcort+1;
        //SACO EL TOTAL DE PRODUCTOS ANULADOS EN EL CORTE Z
        $sumn = $postsanulado->sum('cantidad_productos');
        //saco el inicio de los correlativos y el final
        $ticket_inicion= $postsanulado->min('correlativo_no');
        $ticket_fin_n= $postsanulado->max('correlativo_no');
        //saco el total de transacciones anuladas
        $emitidos_nulos = $postsanulado->count('id');
        //saco el monto total de los tickets
        $total_venta_ticket_nulo = $postsanulado->sum('total_venta');
       
        //funcion a futuro pero lo mejor es siempre guardar todo: verifico si hay un corte en la tabla si no hay que ingrese datos
       
            $bitacora_save->caja_id=$id;
            $bitacora_save->cortez_no=$cortesum;
            $bitacora_save->tipo_cortez=$tipo_decortez;
            $bitacora_save->ticket_inicio=$ticket_inicio;
            $bitacora_save->ticket_final=$ticket_fin;
            $bitacora_save->total_transacciones=$emitidos;
            $bitacora_save->total_productos=$sum;
            $bitacora_save->totalv_cortez=$total_venta_ticket;
            //////save de anulados
            $bitacora_save->tot_anulados=$sumn;
            $bitacora_save->ticket_inicio_anulado=$ticket_inicion;
            $bitacora_save->ticket_fin_anulado=$ticket_fin_n;
            $bitacora_save->transacciones_anuladas=$emitidos_nulos;
            $bitacora_save->totalv_nulo=$total_venta_ticket_nulo;
            
            $bitacora_save->save();
           
        
        

        //ALMACENO LOS DATOS EN MI TABLA BITACORA DE CORTEZ SI ES QUE NO SE HA EMITIDO EL CORTE
        
  
        
        

        return view('biller.invoicecortezmes', compact('total_venta_ticket_nulo','sumn','emitidos_nulos','fechahoy','idcort','cortesum','lims_caja_data','sum','ticket_inicio','ticket_fin','emitidos','total_venta_ticket'));
    }
    public function tiraje($id)
    {
        $ticket_stado="Aprobado";
       // $lims_sale_data = Sale::find($id);
       // $lims_product_sale_data = Product_Sale::where('sale_id', $id)->get();
       //saco la consulta de todas la ventas de esta caja hechas en el dia

        // $posts = Sale::where([['created_at', '>=', date('Y-m-d').' 00:00:00'],['biller_id',$id]])->get();
     
         $posts = DB::table('ticket_cors')
         ->join('sales', 'venta_id', '=', 'sales.id')
         ->where([['fecha_venta', '>=', date('Y-m-d').' 00:00:00'],['ticket_cors.estado_ticket',$ticket_stado],['caja_id',$id]])
         ->get();
     
                   $numberToWords = new NumberToWords();
       
        


        $lims_biller_data = Biller::find($id);
       // $lims_warehouse_data = Warehouse::find($lims_sale_data->warehouse_id);
      //  $lims_customer_data = Customer::find($lims_sale_data->customer_id);
        //$lims_payment_data = Payment::where('sale_id', $id)->get();  

        return view('biller.invoicetiraje', compact('lims_biller_data','posts','numberToWords'));
    }
}
