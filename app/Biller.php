<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Biller extends Model
{
    protected $fillable =[
        "name", "image", "company_name", "vat_number",
        "email", "phone_number", "address", "city",
        "state", "postal_code", "country","resolucion","n_autorizacion","fecha_autorizacion",
        "serie_autorizada","serie_inicio","serie_final","correlativo_actual", "is_active"
    ];

    public function sale()
    {
    	return $this->hasMany('App\Sale');
    }
}
