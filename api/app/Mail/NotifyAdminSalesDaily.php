<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyAdminSalesDaily extends Mailable
{
    use Queueable, SerializesModels;

    public $sellersData;
    public $date;
    public $totalSellers;
    public $totalSales;
    public $totalValue;
    public $totalCommission;

    public function __construct($sellersData, $date)
    {
        $this->sellersData = $sellersData;
        $this->date = $date;
        $this->totalSellers = count($sellersData);
        $this->totalSales = collect($sellersData)->sum('totalSales');
        $this->totalValue = collect($sellersData)->sum('totalValue');
        $this->totalCommission = collect($sellersData)->sum('totalCommission');
    }

    public function build()
    {
        return $this->subject('Relatório de Vendas Diárias - ' . $this->date)
                    ->view('emails.admin-sales-daily');
    }
} 