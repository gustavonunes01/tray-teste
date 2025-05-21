<?php

namespace App\Repositories\Sales;

use App\Models\Sales\Sales;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SalesRepository extends AbstractRepository
{
    protected mixed $model = Sales::class;

    public function filter(array $params):Builder
    {
        $query = $this->model->query();

        if(!empty($params["seller_id"])){
            $query = $this->model->where('seller_id', '=', $params["seller_id"]);
        }

        return $query;
    }

    public function create(array $data): Sales{
        return $this->model->create($data);
    }

    public function findByExternal(string $external_id): Sales{
        return $this->model->where('external_id', '=', $external_id)->firstOrFail();
    }

    public function deleteByExternal(string $saleExternalId)
    {
        return $this->model->where('external_id', '=', $saleExternalId)->delete();
    }

    public function listBySeller($seller_id){
        return $this->model->where('seller_id', $seller_id)->get();
    }



}
