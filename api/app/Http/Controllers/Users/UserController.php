<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSellerRequest;
use App\Repositories\Users\UsersRepository;
use App\Services\Users\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service){
        $this->service = $service;
    }

    public function createSeller(CreateSellerRequest $request): JsonResponse
    {
        $data = $request->all();
        $user = $this->service->createSeller($data);
        return response()->json(["message" => "Vendedor criado com sucesso.", "user" => $user]);
    }

    public function listSellers(): JsonResponse
    {
        $sellers = $this->service->listAllSellers();
        return response()->json($sellers);
    }

    public function updateSeller(Request $request, int $id): JsonResponse
    {
        $data = $request->all();
        $user = $this->service->updateSeller($id, $data);
        return response()->json(["message" => "Vendedor atualizado com sucesso.", "user" => $user]);
    }

    public function deleteSeller(int $id): JsonResponse
    {
        $this->service->deleteSeller($id);
        return response()->json(["message" => "Vendedor excluído com sucesso."]);
    }
}
