<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LecheRecibida extends Model
{
    protected $fillable =[

        "supplier_id", "qty", "created_at"
    ];    
    
    public function supplier()
    {
    	return $this->belongsTo('App\Supplier');
    }

}
