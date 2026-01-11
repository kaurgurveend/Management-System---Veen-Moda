<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class SupplierShipmentsDueReminder extends Notification
{
    use Queueable;

    protected $shipments;

    public function __construct($shipments)
    {
        $this->shipments = $shipments;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $count = $this->shipments->count();
        $mail = (new MailMessage)
                    ->subject("Pengingat Pembayaran Supplier - {$count} yang mendekati jatuh tempo")
                    ->greeting('Hallo Admin,')
                    ->line("Terdapat {$count} pembayaran supplier yang masih berstatus 'Hutang' dan akan/mendekati jatuh tempo.");

        foreach ($this->shipments as $s) {
            $mail->line("- {$s->supplier_name} | {$s->product_name} | Jatuh tempo: {$s->due_date->format('d/m/Y')} | Total: Rp " . number_format($s->invoice_total ?? $s->total_cost, 0, ',', '.'));
        }

        $mail->line('Silakan buka halaman Pengingat Pembayaran pada aplikasi untuk mengirimkan WhatsApp reminder secara manual atau menandai pengingat sebagai terkirim.')
            ->action('Buka Pengingat Pembayaran', url('/supplier-shipments/reminders'))
            ->line('Pesan ini dibuat otomatis oleh sistem.');

        return $mail;
    }
}
