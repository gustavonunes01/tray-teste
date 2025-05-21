<?php

namespace App\Services\Users;

use App\Enums\ProfileTypesEnum;
use App\Repositories\Users\UsersRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected UsersRepository $repository;

    public function __construct(){
        $this->repository = new UsersRepository();
    }

    public function listAllSellers(){
        return $this->repository->index(["profile_id"=> ProfileTypesEnum::SELLER]);
    }

    public function index(array $data){
        return $this->repository->index($data);
    }

    public function createSeller(array $data){
        $data_create = [
            ...$data,
            "profile_id"=> ProfileTypesEnum::SELLER,
            "password"=> Hash::make("123456")
        ];
        return $this->repository->create($data_create);
    }

    public function updateSeller(int $id, array $data){
        return $this->repository->update($data, $id);
    }

    public function deleteSeller(int $id){
        return $this->repository->delete($id);
    }
}

