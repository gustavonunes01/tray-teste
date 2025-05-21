<?php

namespace App\Models\Sales;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes, UuidTrait;

    protected $table = 'sales';
    protected $fillable = [
        "external_id",
        "name",
        "price",
        "commission_value",
        "seller_id",
    ];

    protected $casts = [
        "price" => "decimal:5",
        "commission_value" => "decimal:5",
    ];

    public function seller(){
        return $this->belongsTo("App\\Models\\User", "seller_id");
    }




}
