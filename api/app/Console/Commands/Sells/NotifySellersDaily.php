<?php

namespace App\Console\Commands\Sells;

use App\Enums\ProfileTypesEnum;
use App\Mail\NotifySalesDaily;
use App\Models\User;
use App\Services\Sales\SaleService;
use App\Services\Users\UserService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifySellersDaily extends Command
{
    protected $signature = 'sells:notify-seller-daily';
    protected $description = 'Notify sellers daily';

    protected SaleService $saleService;
    protected UserService $userService;

    public function __construct()
    {
        parent::__construct();
        $this->saleService = new SaleService();
        $this->userService = new UserService();
    }


    /**
     * Esse command é o responsavel por enviar notificação ao final do dia para os vendedores com o resumo de suas vendas.
     * @return void
     */
    public function handle()
    {
        $this->info('Iniciando envio de notificações diárias...');

        // Trás todos os usuários por perfil
        $sellers = $this->userService->listByProfileId(ProfileTypesEnum::SELLER);

        $today = Carbon::today();
        $date = $today->format('d/m/Y');

        foreach ($sellers as $seller) {
            try {
                // Pega todas as vendas do vendedor com filtro do dia
                $sales = $this->saleService->getDailySales($seller->id, $today);

                // Envia notificação
                Mail::to($seller->email)->queue(new NotifySalesDaily($seller, $sales, $date));

                $this->info("Notificação enviada para {$seller->name} ({$seller->email})");
            } catch (\Exception $e) {
                $this->error("Erro ao enviar notificação para {$seller->name}: {$e->getMessage()}");
            }
        }

        $this->info('Processo de notificação concluído!');
    }
}
