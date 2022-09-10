<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reprocesos extends Model
{
    //
    protected $fillable =["producto_id","nombre_producto","codigo_producto",
    "total_lb_reproceso","status"];    
    
    public function supplier()
    {
    	return $this->belongsTo('App\Supplier');
    }
}
