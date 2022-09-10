@extends('layout.main') @section('content')
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.Entrada')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        <p class="italic"><small>Seleccione el producto a producir, al almacenar el registro le redireccionara a la pantalla
                        principal de la linea de produccion con el calculo completo de el costo de producción*</small></p>
                        {!! Form::open(['route' => 'lineaproduccion.store', 'method' => 'post', 'files' => true, 'id' => 'quotation-form']) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                                                         
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('Receta de producto')}} *</label>
                                           
                                            <select name="product_id" class="selectpicker form-control" data-live-search="true" id="product_id"  title="Select Product..." required onchange='cambioEmpresa();'>
                                                @foreach($lims_supplier_list as $supplier)
                                                
                                                <option value="{{$supplier->id}} ">{{$supplier->name . ' Cod:(' . $supplier->code . ')'}} </option>
                                                
                                                         @endforeach
                                                         <!--input type="text" name="id_producto" class="form-control" step="any" id="showId"-->
                                                     </select>
                                                     

                                                 </div>
                                             </div>
                                             <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('Producto final para venta')}} *</label>
                                           
                                            <select name="product_id_venta" class="selectpicker form-control" data-live-search="true" id="product_id"  title="Select Product..." required onchange='cambioEmpresa();'>
                                                @foreach($lims_supplier_list2 as $supplier2)
                                                
                                                <option value="{{$supplier2->id}} ">{{$supplier2->name . ' Cod:(' . $supplier2->code . ')'}} </option>
                                                
                                                         @endforeach
                                                         <!--input type="text" name="id_producto" class="form-control" step="any" id="showId"-->
                                                     </select>
                                                     

                                                 </div>
                                             </div>
                                               
                                                     

                                                     
                                         <div class="col-md-4">
                                             <div class="form-group">
                                        <label>{{'Libras a producir'}}*</strong> </label>
                                        <input type="number" name="libras_a_prod" class="form-control" step='0.01'>                                                                              
                                             </div>
                                                      </div>


                                </div>
                            
                               
		                       
		                        <div class="row">
                                <div class="col-md-4">
                                                     <div class="form-group">
                                                     <label>{{'Leche usar'}} </strong> </label>
                                                         <input type="number" name="leche_usar"  class="form-control" step='0.01'>                                                                              
                                                     </div>
                                                 </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{"Acidez de la leche"}} </strong> </label>
                                        <input type="number" name="acidez" class="form-control" step='0.01'>                                                                              
                                    </div>
                                     </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{'Peso de la leche'}}</label>
                                            <input type="number" name="peso" class="form-control" step='0.01'>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{'Temperatura de la leche'}}</label>
                                            <input type="number" name="temperatura" class="form-control" step='0.01'>
                                        </div>
                                    </div>
                                    

                                     
                               
                                </div>
                                <div class="card-header d-flex align-items-center">
                                        <h5>{{trans('file.Costo')}} </h5>
                                        </div>
                                
                              
                                <div class="row">
                               
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.ntrabajadores')}} </strong> </label>
                                        <input type="number" name="ntrabajadores" class="form-control" step="any">
                                                                             
                                    </div>
                                </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Otros Costos</label>
                                            <input type="number" name="otros_costos" class="form-control" step="any">
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                    <div class="form-group">
                                            <label>Nota de la produccion</label>
                                            <textarea rows="3" class="form-control" name="nota_prod"></textarea>
                                        </div>
                                    </div>

                                                                 
                                        
                                    <hr/>
                                   
                                </div>       
                                <div class="card-header d-flex align-items-center">
                                     <h5>{{trans('file.Estado')}} </h5>
                                    </div>                          
                              
                               
                                
                                <div class="row">
                                	<div class="col-md-4">
                                		<div class="form-group">
                                			<label>{{trans('file.Estado')}}</label> 
                                			<select class="form-control" name="status">
                                				<option value="1">{{trans('file.Espera')}}</option>
                                				<option value="2">{{trans('file.Terminado')}}</option>
                                				<option value="3">{{trans('En producción')}}</option>
                                                <option value="4">{{trans('file.NoPasa')}}</option>
                                			</select>   
                                		</div>
                                	</div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('file.Pesof')}}</label>
                                            <input type="number" name="pesof" class="form-control" step="any">
                                        </div>
                                    </div>

                                   
                                	
                                </div>
                           
                                <div class="form-group">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary" id="submit-button">
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    
<script type="text/javascript">

    $("ul#produccion").siblings('a').attr('aria-expanded','true');
    $("ul#produccion").addClass("show");
    $("ul#produccion #lineaproduccion-menu").addClass("active"); 

	$('.selectpicker').selectpicker({
    	style: 'btn-link',
	});
    

    $('[data-toggle="tooltip"]').tooltip();

    function cambioEmpresa()

{
    
    var id_producto = document.getElementById('showId').value=document.getElementById('product_id').value;
    


}






</script>
@endsection