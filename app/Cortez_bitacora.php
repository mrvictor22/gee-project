<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cortez_bitacora extends Model

{
    //
    protected $fillable =[
        "caja_id", "cortez_no","ticket_inicio","ticket_final","total_transacciones","total_productos","totalv_cortez"
    ];
}
