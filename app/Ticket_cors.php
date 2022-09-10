<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket_cors extends Model
{
     protected $fillable =[
        "venta_id", "correlativo_no"
    ];
}
