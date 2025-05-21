<?php

namespace App\Services\Sales;

use App\Exceptions\BusinessException;
use App\Models\Sales\Sales;
use App\Repositories\Sales\SalesRepository;
use Illuminate\Database\Eloquent\Builder;

class SaleService
{
    protected SalesRepository $repository;

    public function __construct(){
        $this->repository = new SalesRepository();
    }

    public function newSale(array $data): Sales
    {

        $percent = params_general("percent_sales", 8.5);

        $price = floatval($data["price"]);
        $commission = ($price * $percent) / 100;

        $create = [
            "name" => $data["name"] ?? "Venda sem nome",
            "price" => $price,
            "commission_value" => $commission,
            "seller_id" => $data["seller_id"],
        ];

        return $this->repository->create($create);
    }

    public function index(array $data = []){
        return $this->repository->index($data);
    }

    public function show(string $saleExternalId){
        if(!empty($saleExternalId))
            throw new BusinessException("Obrigatorio informar a venda.");

        return $this->repository->findByExternal($saleExternalId);
    }

    public function delete(string $saleExternalId){
        if(!empty($saleExternalId))
            throw new BusinessException("Obrigatorio informar a venda para ser deletada.");

        return $this->repository->deleteByExternal($saleExternalId);
    }

    public function mySales(){
        $user = auth()->user();

        if(!empty($user) && $user?->id){
            return $this->repository->listBySeller($user->id);
        }

        return [];
    }


}
