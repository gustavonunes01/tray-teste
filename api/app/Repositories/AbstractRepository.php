<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    protected mixed $model;

    public function __construct(){
        /** @var Model $model */
        $this->model = app($this->model);
    }


    public function create(array $data){
        return $this->model->create($data);
    }

    public function update(array $data, $id){
        return $this->model->find($id)->update($data);
    }

    public function delete(int $id){
        return $this->model->find($id)->delete();
    }

    public function find(int $id, $columns = array('*')){
        return $this->model->find($id, $columns);
    }

    public function paginate(Builder $filter, $params){

        $orderBy = $params['order_by'] ?? '';
        $order = $params['order'] ?? 'asc';
        $page = $params['page'] ?? 1;
        $per_page = $params['per_page'] ?? 50;

        if(strlen($orderBy) > 0)
            $filter->orderBy($orderBy, $order);

        return $filter->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @param array $params - Request data para filtrar da forma que for necessária no repository.
     * @return Builder
     */
    public function filter(array $params):Builder
    {
        return $this->model->query();
    }

    public function index($params)
    {
        $filter = $this->filter($params);
        return $this->paginate($filter, $params);
    }

}
