<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SupplierShipment;
use App\Models\User;
use App\Notifications\SupplierShipmentsDueReminder;

class SendDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipments:send-due-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly email reminders for supplier shipments that are approaching due date and still unpaid.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Looking for due shipments...');

        $shipments = SupplierShipment::where('payment_status', 'hutang')
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addWeeks(6))
            ->where(function ($q) {
                $q->whereNull('last_reminder_sent_at')
                  ->orWhere('last_reminder_sent_at', '<=', now()->subDays(7));
            })
            ->orderBy('due_date', 'asc')
            ->get();

        if ($shipments->isEmpty()) {
            $this->info('No shipments to remind.');
            return 0;
        }

        $admins = User::where('role', 'admin')->get();
        if ($admins->isEmpty()) {
            $this->warn('No admin users found to notify.');
            return 0;
        }

        foreach ($admins as $admin) {
            $admin->notify(new SupplierShipmentsDueReminder($shipments));
        }

        // Update last_reminder_sent_at for these shipments
        foreach ($shipments as $s) {
            $s->update(['last_reminder_sent_at' => now()]);
        }

        $this->info('Reminders sent to admins.');
        return 0;
    }
}
