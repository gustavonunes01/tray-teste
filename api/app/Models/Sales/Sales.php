<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';
    protected $fillable = [
        "external_id",
        "name",
        "price",
        "commission_value",
        "seller_id",
    ];

    protected $casts = [
        "price" => "decimal",
        "commission_value" => "decimal",
    ];

    public function seller(){
        return $this->belongsTo("App\\Models\\User", "seller_id");
    }




}
