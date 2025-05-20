<?php

namespace Database\Seeders;

use App\Models\Common\ParamsGeneral;
use Illuminate\Database\Seeder;

class ParamsGeneralSeeder extends Seeder
{

    public function run(){
        $types = [
          [
              "bind" => "percent_sales",
              "value" => 8.5
          ]
        ];

        foreach($types as $type){
            ParamsGeneral::create($type);
        }
    }
}
