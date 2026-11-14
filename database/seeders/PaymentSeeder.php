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
        // 1. Seed Payment Methods (Bank, Auto-Link, QRIS, E-Wallet)
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
                'code'           => 'bca',
                'name'           => 'Transfer Bank BCA',
                'type'           => 'bank',
                'price'          => 1000,
                'status'         => 'active',
                'account_number' => '7820192837',
                'account_name'   => 'CV Maybeads Indonesia',
                'description'    => 'Transfer ke rekening BCA resmi toko (+ Rp 1.000)',
            ],
            [
                'code'           => 'mandiri',
                'name'           => 'Transfer Bank Mandiri',
                'type'           => 'bank',
                'price'          => 1000,
                'status'         => 'active',
                'account_number' => '1420019283746',
                'account_name'   => 'CV Maybeads Indonesia',
                'description'    => 'Transfer ke rekening Mandiri resmi toko (+ Rp 1.000)',
            ],
            [
                'code'           => 'bri',
                'name'           => 'Transfer Bank BRI',
                'type'           => 'bank',
                'price'          => 1000,
                'status'         => 'non-active',
                'account_number' => '0129018273645',
                'account_name'   => 'CV Maybeads Indonesia',
                'description'    => 'Transfer ke rekening BRI resmi toko (+ Rp 1.000)',
            ],
            [
                'code'           => 'autolink_bca',
                'name'           => 'Auto-Link BCA OneKlik',
                'type'           => 'auto-link',
                'price'          => 500,
                'status'         => 'active',
                'account_number' => 'DEBIT-BCA-MBDS',
                'account_name'   => 'Direct Debit BCA Terhubung',
                'description'    => 'Pembayaran otomatis debit kartu BCA OneKlik (+ Rp 500)',
            ],
            [
                'code'           => 'autolink_mandiri',
                'name'           => 'Auto-Link Mandiri Debit',
                'type'           => 'auto-link',
                'price'          => 500,
                'status'         => 'non-active',
                'account_number' => 'DEBIT-MDR-MBDS',
                'account_name'   => 'Power Debit Mandiri Terhubung',
                'description'    => 'Pembayaran otomatis debit Mandiri terhubung (+ Rp 500)',
            ],
            [
                'code'           => 'gopay',
                'name'           => 'GoPay E-Wallet',
                'type'           => 'e-wallet',
                'price'          => 500,
                'status'         => 'non-active',
                'account_number' => '0812-3456-7890',
                'account_name'   => 'Maybeads Official',
                'description'    => 'Pembayaran dompet digital GoPay (+ Rp 500)',
            ],
            [
                'code'           => 'ovo',
                'name'           => 'OVO E-Wallet',
                'type'           => 'e-wallet',
                'price'          => 500,
                'status'         => 'active',
                'account_number' => '0812-3456-7890',
                'account_name'   => 'Maybeads Official',
                'description'    => 'Pembayaran dompet digital OVO (+ Rp 500)',
            ],
            [
                'code'           => 'dana',
                'name'           => 'DANA E-Wallet',
                'type'           => 'e-wallet',
                'price'          => 500,
                'status'         => 'active',
                'account_number' => '0812-3456-7890',
                'account_name'   => 'Maybeads Official',
                'description'    => 'Pembayaran dompet digital DANA (+ Rp 500)',
            ],
            [
                'code'           => 'shopeepay',
                'name'           => 'ShopeePay E-Wallet',
                'type'           => 'e-wallet',
                'price'          => 500,
                'status'         => 'non-active',
                'account_number' => '0812-3456-7890',
                'account_name'   => 'Maybeads Official',
                'description'    => 'Pembayaran dompet digital ShopeePay (+ Rp 500)',
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
        $bca = $savedMethods['bca'];
        $mandiri = $savedMethods['mandiri'];
        $dana = $savedMethods['dana'];
        $ovo = $savedMethods['ovo'];
        $autolinkBca = $savedMethods['autolink_bca'];

        // 2. Seed Delivery Methods
        // Ambil Ditempat (active, 200 rupiah), Antar ke Tempat (khusus Banjarbaru, non-active, Rp 2.000/km via Google Maps)
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

        // 3. Seed Payments for Existing Orders
        $orders = Order::all();

        if ($orders->isEmpty()) {
            return;
        }

        $methodObjMapping = [
            0 => $qris,
            1 => $bca,
            2 => $dana,
            3 => $qris,
            4 => $ovo,
            5 => $qris,
            6 => $mandiri,
            7 => $qris,
            8 => $bca,
            9 => $qris,
            10 => $dana,
            11 => $autolinkBca,
            12 => $qris,
        ];

        foreach ($orders as $index => $order) {
            $isPaid = in_array(strtolower($order->payment_status), ['sukses', 'success', 'paid']);
            $paidAt = $isPaid ? ($order->order_at ? Carbon::parse($order->order_at)->addMinutes(12) : now()) : null;
            $chosenMethod = $methodObjMapping[$index] ?? $qris;

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
