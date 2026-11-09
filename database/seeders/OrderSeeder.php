<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get()->keyBy('name');
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command?->warn('Tidak ada data produk untuk relasi OrderSeeder.');
            return;
        }

        $fallbackUser = User::first() ?? User::factory()->create();
        $defaultProduct = $products->first();

        $ordersData = [
            [
                'username'       => 'nguyenvinhhoa567',
                'product_index'  => 0,
                'quantity'       => 1,
                'subtotal'       => 42000.00,
                'order_status'   => 'Pending',
                'payment_status' => 'Proses',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::parse('2026-10-12 12:00:00'),
                'send_at'        => null,
            ],
            [
                'username'       => 'trantrungkien',
                'product_index'  => 1,
                'quantity'       => 1,
                'subtotal'       => 56000.00,
                'order_status'   => 'Proses',
                'payment_status' => 'Sukses',
                'address'        => 'Jl. Mawar Indah No. 4, Jakarta Timur',
                'order_at'       => Carbon::parse('2026-10-12 12:15:00'),
                'send_at'        => Carbon::parse('2026-10-12 14:00:00'),
            ],
            [
                'username'       => 'trandinhtuan567',
                'product_index'  => 2,
                'quantity'       => 2,
                'subtotal'       => 90000.00,
                'order_status'   => 'Sukses',
                'payment_status' => 'Sukses',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::parse('2026-10-12 12:30:00'),
                'send_at'        => Carbon::parse('2026-10-12 15:00:00'),
            ],
            [
                'username'       => 'nguyenanhdao77',
                'product_index'  => 3,
                'quantity'       => 1,
                'subtotal'       => 30000.00,
                'order_status'   => 'Dibatalkan',
                'payment_status' => 'Dibatalkan',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::parse('2026-10-12 13:00:00'),
                'send_at'        => null,
            ],
            [
                'username'       => 'hoangthanhphat88',
                'product_index'  => 4,
                'quantity'       => 1,
                'subtotal'       => 57000.00,
                'order_status'   => 'Pending',
                'payment_status' => 'Pending',
                'address'        => 'Jl. Flamboyan No. 21, Bandung',
                'order_at'       => Carbon::parse('2026-10-12 13:45:00'),
                'send_at'        => null,
            ],
            [
                'username'       => 'lehongson2023',
                'product_index'  => 5,
                'quantity'       => 1,
                'subtotal'       => 67000.00,
                'order_status'   => 'Dibatalkan',
                'payment_status' => 'Gagal',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::parse('2026-10-12 14:10:00'),
                'send_at'        => null,
            ],
            [
                'username'       => 'bachdangthang',
                'product_index'  => 6,
                'quantity'       => 1,
                'subtotal'       => 67000.00,
                'order_status'   => 'Sukses',
                'payment_status' => 'Sukses',
                'address'        => 'Jl. Kenari No. 18, Surabaya',
                'order_at'       => Carbon::parse('2026-10-12 14:30:00'),
                'send_at'        => Carbon::parse('2026-10-12 16:30:00'),
            ],
            [
                'username'       => 'moonelliaven',
                'product_index'  => 5,
                'quantity'       => 2,
                'subtotal'       => 395000.00,
                'order_status'   => 'Proses',
                'payment_status' => 'Sukses',
                'address'        => 'Jl. Anggrek No. 10, Jakarta Selatan',
                'order_at'       => Carbon::parse('2026-05-26 14:32:00'),
                'send_at'        => null,
            ],
            [
                'username'       => 'nguyenminhkhoa101',
                'product_index'  => 7,
                'quantity'       => 2,
                'subtotal'       => 105000.00,
                'order_status'   => 'Proses',
                'payment_status' => 'Sukses',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::parse('2026-10-12 15:10:00'),
                'send_at'        => null,
            ],
            [
                'username'       => 'phamquynh88',
                'product_index'  => 8,
                'quantity'       => 1,
                'subtotal'       => 48000.00,
                'order_status'   => 'Pending',
                'payment_status' => 'Pending',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::parse('2026-10-12 15:45:00'),
                'send_at'        => null,
            ],
        ];

        foreach ($ordersData as $idx => $item) {
            $user = $users->get($item['username']) ?? $fallbackUser;
            $product = $products->get($item['product_index']) ?? $defaultProduct;

            Order::updateOrCreate(
                ['id' => $idx + 1],
                [
                    'user_id'        => $user->id,
                    'product_id'     => $product->id,
                    'quantity'       => $item['quantity'],
                    'subtotal'       => $item['subtotal'],
                    'order_status'   => $item['order_status'],
                    'payment_status' => $item['payment_status'],
                    'address'        => $item['address'],
                    'order_at'       => $item['order_at'],
                    'send_at'        => $item['send_at'],
                ]
            );
        }
    }
}

