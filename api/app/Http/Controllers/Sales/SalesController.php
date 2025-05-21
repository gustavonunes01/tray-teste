<?php

namespace App\Http\Controllers\Sales;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SalesRequest;
use App\Mail\NotifySalesDaily;
use App\Services\Sales\SaleService;
use App\Services\Users\UserService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SalesController extends Controller
{
    protected SaleService $service;

    public function __construct(SaleService $service){
        $this->service = $service;
    }

    public function newSale(SalesRequest $request): JsonResponse
    {
        try {
            $data = $request->all();
            $sale = $this->service->newSale($data);
            return response()->json(["message" => "Venda criada com sucesso!", "sale" => $sale], 200);
        }catch (\Exception $exception){
            Log::error($exception);
            return response()->json(["message"=>"Erro inesperado ao processar a venda."], 422);
        }
    }

    public function index(PaginateRequest $request): JsonResponse{
        try {
            $data = $request->all();
            $sales = $this->service->index($data);
            return response()->json($sales);
        }catch (\Exception $exception){
            Log::error($exception);
            return response()->json(["message" => "Erro inesperado ao listar as vendas."], 422);
        }
    }

    /**
     * @param string $external_id - Hash para identificar a venda
     * @throws BusinessException
     */
    public function show(string $external_id): JsonResponse{
        $sale = $this->service->show($external_id);
        return response()->json(["data" =>$sale]);
    }

    /**
     * @param string $external_id - Hash para identificar a venda
     * @throws BusinessException
     * @return JsonResponse
     */
    public function delete(string $external_id): JsonResponse{
        $sale = $this->service->delete($external_id);
        return response()->json($sale);
    }

    public function mySales(): JsonResponse
    {
        return response()->json($this->service->mySales());
    }

    public function sendEmailNotification(int $seller_id): JsonResponse
    {
        try{
            $this->service->notifySeller($seller_id);
            return response()->json(["message" => "Notificação encaminhada para o vendedor com sucesso!"]);
        }catch (\Exception $exception){
            Log::error($exception);
            return response()->json(["message" => "Não conseguimos processar sua solicitação."], 422);
        }
    }

}
