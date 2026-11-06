<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        $orderData = [
            [
                'user_index'     => 1, // Hilya
                'product_index'  => 0, // Star Hairclip Y2K
                'quantity'       => 2,
                'subtotal'       => 70000,
                'order_status'   => 'proses',
                'payment_status' => 'lunas',
                'address'        => 'Jl. Merpati No. 12, Bandung, Jawa Barat',
                'method'         => 'QRIS',
                'payment_status_pay' => 'sukses',
            ],
            [
                'user_index'     => 0, // Hanzen
                'product_index'  => 1, // Cyber Cross Ring
                'quantity'       => 1,
                'subtotal'       => 50000,
                'order_status'   => 'selesai',
                'payment_status' => 'lunas',
                'address'        => 'Jl. Sudirman Kav 45, Jakarta Selatan',
                'method'         => 'Transfer Bank (BCA)',
                'payment_status_pay' => 'sukses',
            ],
            [
                'user_index'     => 1, // Hilya
                'product_index'  => 2, // Butterfly Beads Bracelet
                'quantity'       => 3,
                'subtotal'       => 135000,
                'order_status'   => 'pending',
                'payment_status' => 'menunggu_pembayaran',
                'address'        => 'Jl. Diponegoro No. 8, Surabaya, Jawa Timur',
                'method'         => 'QRIS',
                'payment_status_pay' => 'pending',
            ],
            [
                'user_index'     => 2, // Zaki
                'product_index'  => 3, // Metallic Star Keychain
                'quantity'       => 2,
                'subtotal'       => 76000,
                'order_status'   => 'selesai',
                'payment_status' => 'lunas',
                'address'        => 'Jl. Gejayan No. 19, Sleman, D.I. Yogyakarta',
                'method'         => 'E-Wallet (GoPay)',
                'payment_status_pay' => 'sukses',
            ],
            [
                'user_index'     => 0, // Hanzen
                'product_index'  => 4, // Cyber Heart Keychain
                'quantity'       => 1,
                'subtotal'       => 42000,
                'order_status'   => 'dibatalkan',
                'payment_status' => 'gagal',
                'address'        => 'Jl. Veteran No. 5, Malang, Jawa Timur',
                'method'         => 'Transfer Bank (Mandiri)',
                'payment_status_pay' => 'gagal',
            ],
        ];

        foreach ($orderData as $i => $item) {
            $user = $users[$item['user_index'] % $users->count()];
            $product = $products[$item['product_index'] % $products->count()];

            $order = Order::create([
                'user_id'        => $user->id,
                'product_id'     => $product->id,
                'quantity'       => $item['quantity'],
                'subtotal'       => $item['subtotal'],
                'order_status'   => $item['order_status'],
                'payment_status' => $item['payment_status'],
                'address'        => $item['address'],
                'order_at'       => now()->subDays(5 - $i)->subHours($i * 3),
                'send_at'        => in_array($item['order_status'], ['selesai', 'proses']) ? now()->subDays(4 - $i) : null,
            ]);

            Payment::create([
                'order_id'       => $order->id,
                'payment_method' => $item['method'],
                'amount'         => $item['subtotal'],
                'payment_status' => $item['payment_status_pay'],
                'paid_at'        => $item['payment_status_pay'] === 'sukses' ? now()->subDays(5 - $i) : null,
            ]);
        }
    }
}
