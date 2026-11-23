<?php

namespace Database\Seeders;

use App\Models\DeliveryMethod;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Payment Methods (Hanya QRIS dan GoPay E-Wallet)
        $methods = [
            [
                'code'           => 'qris',
                'name'           => 'QRIS Dinamis Maybeads',
                'type'           => 'qris',
                'price'          => 500,
                'status'         => 'active',
                'account_number' => 'NMID: ID102030405060',
                'account_name'   => 'Maybeads Official Merchant',
                'description'    => 'Pembayaran instan QRIS lintas bank & e-wallet (+ Rp 500)',
            ],
            [
                'code'           => 'gopay',
                'name'           => 'GoPay E-Wallet',
                'type'           => 'e-wallet',
                'price'          => 500,
                'status'         => 'active',
                'account_number' => '0812-3456-7890',
                'account_name'   => 'Maybeads Official',
                'description'    => 'Pembayaran dompet digital GoPay (+ Rp 500)',
            ],
        ];

        $savedMethods = [];
        foreach ($methods as $m) {
            $savedMethods[$m['code']] = PaymentMethod::updateOrCreate(
                ['code' => $m['code']],
                $m
            );
        }

        $qris = $savedMethods['qris'];
        $gopay = $savedMethods['gopay'];

        // Hapus metode pembayaran lama selain QRIS dan GoPay agar database tetap bersih
        Payment::whereNotIn('payment_method_id', [$qris->id, $gopay->id])
            ->update([
                'payment_method_id' => $qris->id,
                'payment_method'    => $qris->name,
            ]);
        PaymentMethod::whereNotIn('code', ['qris', 'gopay'])->delete();

        // 2. Seed Delivery Methods
        $pickup = DeliveryMethod::updateOrCreate(
            ['code' => 'pickup'],
            [
                'name'        => 'Ambil Ditempat',
                'price'       => 200,
                'status'      => 'active',
                'description' => 'Ambil langsung di toko Maybeads (+ Rp 200)',
            ]
        );

        $delivery = DeliveryMethod::updateOrCreate(
            ['code' => 'delivery'],
            [
                'name'        => 'Antar ke Tempat',
                'price'       => 2000,
                'status'      => 'non-active',
                'description' => 'Khusus Banjarbaru, tarif Rp 2.000/km via Google Maps API (Sedang tidak aktif)',
            ]
        );

        // 3. Seed Payments for Existing Orders (Mapping hanya ke QRIS dan GoPay)
        $orders = Order::all();

        if ($orders->isEmpty()) {
            return;
        }

        $availableMethods = [$qris, $gopay];

        foreach ($orders as $index => $order) {
            $isPaid = in_array(strtolower($order->payment_status), ['sukses', 'success', 'paid']);
            $paidAt = $isPaid ? ($order->order_at ? Carbon::parse($order->order_at)->addMinutes(12) : now()) : null;
            $chosenMethod = $availableMethods[$index % count($availableMethods)];

            $isHomeDelivery = !empty($order->address) && !str_contains(strtolower($order->address), 'ambil') && !str_contains(strtolower($order->address), 'ditempat');
            $deliveryMethodId = $isHomeDelivery ? $delivery->id : $pickup->id;

            // Setiap order memiliki bukti pembayaran sebagai syarat validitas order
            $buktiPembayaran = 'payments/bukti_transfer_' . str_pad((string)$order->id, 4, '0', STR_PAD_LEFT) . '.jpg';

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_method_id'  => $chosenMethod->id,
                    'delivery_method_id' => $deliveryMethodId,
                    'payment_method'     => $chosenMethod->name,
                    'amount'             => $order->subtotal,
                    'payment_status'     => $order->payment_status,
                    'bukti_pembayaran'   => $buktiPembayaran,
                    'paid_at'            => $paidAt,
                    'created_at'         => $order->order_at ?? now(),
                    'updated_at'         => now(),
                ]
            );
        }
    }
}
