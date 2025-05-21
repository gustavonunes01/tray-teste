<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class UsersRepository extends AbstractRepository
{
    protected mixed $model = User::class;

    public function filter(array $params): Builder
    {
        $query = $this->model->query();

        if (!empty($params["profile_id"])) {
            $query->where('profile_id', $params["profile_id"]);
        }

        return $query;
    }

}
