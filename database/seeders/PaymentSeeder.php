<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        if ($orders->isEmpty()) {
            $this->command?->warn('Tabel orders masih kosong. Jalankan OrderSeeder terlebih dahulu.');
            return;
        }

        $methods = [
            'QRIS',
            'Transfer BCA',
            'Transfer Mandiri',
            'GoPay',
            'ShopeePay',
            'COD (Bayar di Tempat)',
        ];

        foreach ($orders as $index => $order) {
            $method = $methods[$index % count($methods)];
            $isPaid = in_array(strtolower($order->payment_status), ['sukses', 'success', 'paid']);
            $paidAt = $isPaid ? ($order->order_at ? Carbon::parse($order->order_at)->addMinutes(12) : now()) : null;

            // Setiap order memiliki bukti pembayaran sebagai syarat validitas order
            $buktiPembayaran = 'payments/bukti_transfer_' . str_pad((string)$order->id, 4, '0', STR_PAD_LEFT) . '.jpg';

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_method'   => $method,
                    'amount'           => $order->grand_total ?? $order->subtotal,
                    'payment_status'   => $order->payment_status,
                    'bukti_pembayaran' => $buktiPembayaran,
                    'paid_at'          => $paidAt,
                ]
            );
        }
    }
}

