<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $table = 'supervisor';
    protected $primaryKey = 'codigo';

    protected $visible = ['codigo', 'nome'];
}
