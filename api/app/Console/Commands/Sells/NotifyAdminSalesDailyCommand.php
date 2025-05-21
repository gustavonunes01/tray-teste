<?php

namespace App\Console\Commands\Sells;

use App\Enums\ProfileTypesEnum;
use App\Mail\NotifyAdminSalesDaily;
use App\Models\User;
use App\Services\Users\UserService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyAdminSalesDailyCommand extends Command
{
    protected $signature = 'sells:notify-admin-daily';
    protected $description = 'Notify administrators with daily sales summary';

    protected UserService $userService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    /**
     * Esse command é responsavel por notifcar todos os administradores do sistema com um resumo de vendas diarias.
     * @return void
     */
    public function handle()
    {
        $this->info('Iniciando envio de notificações administrativas...');

        // Lista todos os adminitradores
        $admins = $this->userService->listByProfileId(ProfileTypesEnum::ADMIN);

        if ($admins->isEmpty()) {
            $this->error('Nenhum administrador encontrado no sistema.');
            return;
        }

        $today = Carbon::today();
        $date = $today->format('d/m/Y');

        try {
            // Retorna um resumo das vendas por data (dia)
            $sellersData = $this->userService->getDailySalesSummary($today);

            // Noticiando todos os adminitradores
            foreach ($admins as $admin) {
                Mail::to($admin->email)->queue(new NotifyAdminSalesDaily($sellersData, $date));
                $this->info("Notificação enviada para {$admin->name} ({$admin->email})");
            }

            $this->info('Processo de notificação administrativa concluído!');
        } catch (\Exception $e) {
            $this->error("Erro ao enviar notificações administrativas: {$e->getMessage()}");
        }
    }
}
