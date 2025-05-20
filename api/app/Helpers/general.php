<?php

function params_general($bind, $default = ""){
    return ParamsGeneral::where("bind", $bind)->find() ?? $default;
}


