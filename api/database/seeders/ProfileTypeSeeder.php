<?php

namespace Database\Seeders;

use App\Enums\ProfileTypesEnum;
use App\Models\Common\ProfileTypesModel;
use Illuminate\Database\Seeder;

class ProfileTypeSeeder extends Seeder
{

    public function run(){
        $profileTypes = [
            [
                'id' => ProfileTypesEnum::ADMIN,
                'name' => 'Admin',
            ],
            [
                'id' => ProfileTypesEnum::SELLER,
                'name' => 'Seller',
            ]
        ];

        foreach($profileTypes as $type){
            ProfileTypesModel::create($type);
        }
    }

}
