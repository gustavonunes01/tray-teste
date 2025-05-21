<?php

namespace App\Services\Users;

use App\Enums\ProfileTypesEnum;
use App\Models\User;
use App\Repositories\Users\UsersRepository;
use App\Services\Sales\SaleService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected UsersRepository $repository;
    protected SaleService $saleService;

    public function __construct(){
        $this->repository = new UsersRepository();
        $this->saleService = new SaleService();
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

    public function listByProfileId(int $profileId){
        return $this->repository->allByProfileId($profileId);
    }

    public function getDailySalesSummary(Carbon $date): array
    {
        $sellers = $this->listByProfileId(ProfileTypesEnum::SELLER);
        $sellersData = [];

        foreach ($sellers as $seller) {
            $sales = $this->saleService->getDailySales($seller->id, $date);

            $sellersData[] = [
                'seller' => $seller,
                'sales' => $sales,
                'totalSales' => $sales->count(),
                'totalValue' => $sales->sum('price'),
                'totalCommission' => $sales->sum('commission_value')
            ];
        }

        return $sellersData;
    }

    public function find(int $id){
        return $this->repository->find($id);
    }
}

