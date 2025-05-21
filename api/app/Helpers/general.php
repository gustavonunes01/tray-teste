<?php

use App\Models\Common\ParamsGeneral;

function params_general($bind, $default = ""){
    return ParamsGeneral::where("bind", $bind)->first()?->value ?? $default;
}


