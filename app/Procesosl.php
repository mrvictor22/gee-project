<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Procesosl extends Model
{
    //
    protected $fillable =[

        "producto_id",'producto_destino_id', "peso_inicial","peso_final","bolsas_utilizar" ,
        "bolsas_desperdiciadas","costo_enbolsado","etiquetas_utilizar","etiquetas_desperdiciadas","costo_etiquetas","total_sobrantes","costo_final_venta","status","created_at"
    ];    
    
    public function supplier()
    {
    	return $this->belongsTo('App\Supplier');
    }

}
