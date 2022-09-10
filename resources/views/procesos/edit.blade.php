@extends('layout.main') @section('content')
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible number-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif
<section class="forms">
<div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('Editar ficha de proceso')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => ['procesos.update', $lims_product_data->id], 'method' => 'put', 'files' => true, 'id' => 'payment-form']) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    
                                    
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label>Producto a procesar *</label>
                                            <input type="hidden" name="producto_id_hidden" value="{{$lims_product_data->producto_id }}" />
                                            <select name="producto_id" class="selectpicker form-control" data-live-search="true" id="producto-id" >
                                                @foreach($lims_product_list as $producto)
                                                <option value="{{$producto->id}}">{{($producto->name)}}({{$producto->code}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        </div>                                   
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label>Producto destino para venta*</label>
                                            <input type="hidden" name="producto_destino_id_hidden" value="{{$lims_product_data->producto_destino_id }}" />
                                            <select name="producto_destino_id" class="selectpicker form-control" data-live-search="true" id="producto-destino-id" >
                                                @foreach($lims_product_list as $producto)
                                                <option value="{{$producto->id}}">{{($producto->name)}}({{$producto->code}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        </div>                                   

                                         <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{'Peso inicial'}} *</label>
                                            <input type="number" name="peso_inicial" class="form-control" value="{{$lims_product_data->peso_inicial}}" step="any" required>
                                             </div>
                                          </div>
                                
                                          <div class="col-md-4 form-group">
                                         <label>{{trans('Peso final')}} *</label>
                                         <input type="number" name="peso_final" class="form-control" value="{{$lims_product_data->peso_final}}" >
                                              </div>   
                                              <div class="col-md-12">
                                    
                                    <br>
                                 
                                    <h4>{{'Embolsado'}}</h4>
                                    
                                 </div>
                                              <div class="col-md-4 form-group">
                                              <label>{{'N° bolsas a utilizar'}} </label>
                                         <input type="number" name="bolsas_utilizar" class="form-control" value="{{$lims_product_data->bolsas_utilizar}}" required>
                                              </div>   
                                              <div class="col-md-4 form-group">
                                              <label>{{'N° bolsas desperdiciadas'}}</label>
                                         <input type="number" name="bolsas_desperdiciadas" class="form-control" value="{{$lims_product_data->bolsas_desperdiciadas}}" required>
                                              </div>   
                                              <div class="col-md-4 form-group">
                                              <label>{{'Costo por bolsa'}}</label>
                                         <input type="number" name="costo_enbolsado" class="form-control" value="{{$lims_product_data->costo_enbolsado}}" step='0.01' onchange="sumar(this.value);" >
                                              </div>   

                                              <div class="col-md-12">
                                    
                                                <br>
                                 
                                            <h4>{{'Etiquetado'}}</h4>
                                    
                                            </div>
                                              <div class="col-md-4 form-group">
                                              <label>{{'N° etiquetas a utilizar'}} </label>
                                         <input type="number" name="etiquetas_utilizar" class="form-control" value="{{$lims_product_data->etiquetas_utilizar}}" required>
                                              </div>   
                                              <div class="col-md-4 form-group">
                                              <label>{{'N° etiquetas desperdiciadas'}}</label>
                                         <input type="number" name="etiquetas_desperdiciadas" class="form-control" value="{{$lims_product_data->etiquetas_desperdiciadas}}" required>
                                              </div>   
                                              <div class="col-md-4 form-group">
                                              <label>{{'Costo por etiqueta'}}</label>
                                         <input type="number" name="costo_etiquetas" class="form-control" value="{{$lims_product_data->costo_etiquetas}}" step='0.01' onchange="sumar(this.value);">
                                              </div>   

                                               <div class="col-md-12">
                                    
                                                <br>
                                 
                                            <h4>{{'Sobrantes'}}</h4>
                                    
                                            </div>
                                              <div class="col-md-4 form-group">
                                              <label>{{'Total de sobrantes(Lb)'}}</label>
                                         <input type="number" name="total_sobrantes" class="form-control" value="{{$lims_product_data->total_sobrantes}}" step='0.01'>
                                              </div>   
                                              <div class="col-md-4 form-group">
                                              <label>{{'Costo total del proceso'}}</label>
                                         <input type="number" name="costo_final_venta" class="form-control" value="{{$lims_product_data->costo_final_venta}}" step='0.01' id="spTotal">
                                              </div>   
                                           
                                         <div class="form-group">
                                            <label>{{'Estado del proceso'}} </label>
                                            <input type="hidden" name="status_hidden" value="{{$lims_product_data->status }}" />
                                            <select required name="status" id="status-proceso" class="selectpicker form-control"   >
                                          
                                                <option value="En proceso">En proceso</option>
                                                <option value="En espera">En espera</option>
                                                <option value="Terminado">Terminado</option>
                                              
                                              </select>
                                        </div>
                                     


                                </div>
                                

                                                       
                                
                                    
                                </div>                    
                               
 
                            </div>
                        </div>
                        <div class="form-group">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary" id="submit-button">
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
      $("ul#produccion #lecherecibida-list-menu1").addClass("active");   
  
  var rownumber = $('table.order-list tbody tr:last').index();
  
  var expired_date = $('.expired_date');
      expired_date.datepicker({
       format: "yyyy-mm-dd",
       startDate: "<?php echo date('Y-m-d'); ?>",
       autoclose: true,
       todayHighlight: true     
       });
  
  $('.selectpicker').selectpicker({
      style: 'btn-link',
  });
  
  $('[data-toggle="tooltip"]').tooltip();
  
  //assigning value
  $('select[name="producto_id"]').val($('input[name="producto_id_hidden"]').val());
  $('select[name="producto_destino_id"]').val($('input[name="producto_destino_id_hidden"]').val());
  $('select[name="status"]').val($('input[name="status_hidden"]').val());

 /* function sumar (valor) {
    var total = 0;	
  valor = parseFloat(valor); // Convertir el valor a un entero (número).
	
    total = document.getElementById('spTotal').value;
	
    // Aquí valido si hay un valor previo, si no hay datos, le pongo un cero "0".
    total = (total == null || total == undefined || total == "") ? 0 : total;
	
    /* Esta es la suma. */
  /*  total = (parseFloat(total) +parseFloat(valor));
	
    // Colocar el resultado de la suma en el control "span".
    document.getElementById('spTotal').value = total;
}
*/
  
  $('.selectpicker').selectpicker('refresh');
  
  </script>

@endsection