<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class ParamsGeneral extends Model{
    protected $table = 'params_general';

    protected $fillable = [
        "bind",
        "value"
    ];
}
