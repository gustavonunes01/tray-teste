<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class ProfileTypesModel extends Model
{
    protected $table = "profile_types";

    protected $fillable = [
        "name",
    ];

}
