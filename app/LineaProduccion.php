<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LineaProduccion extends Model
{

    protected $fillable =[

        "product_id" ,"product_id_venta","leche_usar","libras_a_prod", "acidez", "peso", "temperatura", "ntrabajadores", "costo" , "otros_costos", "nota_prod", "costoin", "status", "pesof" ];    


    public function product()
    {
            return $this->belongsTo('App\Product');
    }
   
}
