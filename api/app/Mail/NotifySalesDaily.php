<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifySalesDaily extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $sales;
    public $date;
    public $totalSales;
    public $totalValue;
    public $totalCommission;

    public function __construct(User $seller, $sales, $date)
    {
        $this->seller = $seller;
        $this->sales = $sales;
        $this->date = $date;
        $this->totalSales = $sales->count();
        $this->totalValue = $sales->sum('price');
        $this->totalCommission = $sales->sum('commission_value');
    }

    public function build()
    {
        return $this->subject('Relatório de Vendas Diárias - ' . $this->date)
                    ->view('emails.sales-daily');
    }
}
