<?php

namespace App\Services\Sales;

use App\Enums\ProfileTypesEnum;
use App\Exceptions\BusinessException;
use App\Mail\NotifySalesDaily;
use App\Models\Sales\Sales;
use App\Repositories\Sales\SalesRepository;
use App\Services\Users\UserService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

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

    public function getDailySales(int $sellerId, Carbon $date)
    {
        return $this->repository->getDailySales($sellerId, $date);
    }

    /**
     * Função usada para notificar um especifico vendedor de suas vendas.
     * @param string $sellerId
     * @return bool
     * @throws BusinessException
     */
    public function notifySeller(string $sellerId): bool
    {
        $authUser = auth()->user();

        if(!empty($authUser) && $authUser->profile_id !== ProfileTypesEnum::ADMIN)
            throw new BusinessException("Permissão insuficiente para continuar.");

        $userService = new UserService();
        $seller = $userService->find($sellerId);

        if($seller){
            $today = Carbon::today();
            $date = $today->format('d/m/Y');
            $sales = $this->getDailySales($sellerId, $today);

            if(!empty($sales)){
                // Envia notificação
                Mail::to($seller->email)->queue(new NotifySalesDaily($seller, $sales, $date));
                return true;
            }

            throw new BusinessException("Não houve nenhuma movimentação hoje.");
        }

        return false;
    }
}
