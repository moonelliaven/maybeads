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
                'username'       => 'trandinhtuan567',
                'product_index'  => 0,
                'quantity'       => 1,
                'subtotal'       => 32000.00,
                'order_status'   => 'Pending',
                'payment_status' => 'Pending',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::parse('2026-09-14 11:02:00'),
                'send_at'        => null,
            ],
            [
                'username'       => 'nguyenanhdao77',
                'product_index'  => 1,
                'quantity'       => 2,
                'subtotal'       => 70000.00,
                'order_status'   => 'Sukses',
                'payment_status' => 'Sukses',
                'address'        => 'Jl. Mawar Indah No. 4, Jakarta Timur',
                'order_at'       => Carbon::parse('2026-09-17 09:02:00'),
                'send_at'        => Carbon::parse('2026-09-17 11:30:00'),
            ],
            [
                'username'       => 'nguyenminhkhoa101',
                'product_index'  => 2,
                'quantity'       => 1,
                'subtotal'       => 40000.00,
                'order_status'   => 'Dibatalkan',
                'payment_status' => 'Dibatalkan',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::parse('2026-09-20 15:02:00'),
                'send_at'        => null,
            ],
            [
                'username'       => 'phamquynh88',
                'product_index'  => 3,
                'quantity'       => 1,
                'subtotal'       => 56000.00,
                'order_status'   => 'Dibatalkan',
                'payment_status' => 'Gagal',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::parse('2026-09-22 17:02:00'),
                'send_at'        => null,
            ],
            [
                'username'       => 'lequocviet101',
                'product_index'  => 4,
                'quantity'       => 2,
                'subtotal'       => 65000.00,
                'order_status'   => 'Sukses',
                'payment_status' => 'Sukses',
                'address'        => 'Jl. Kenari No. 12, Banjarbaru',
                'order_at'       => Carbon::parse('2026-09-23 18:02:00'),
                'send_at'        => Carbon::parse('2026-09-23 20:00:00'),
            ],
            [
                'username'       => 'dinhhoangyen88',
                'product_index'  => 1,
                'quantity'       => 1,
                'subtotal'       => 50000.00,
                'order_status'   => 'Proses',
                'payment_status' => 'Proses',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::parse('2026-09-24 14:52:00'),
                'send_at'        => null,
            ],
            [
                'username'       => 'lethihoa99',
                'product_index'  => 2,
                'quantity'       => 2,
                'subtotal'       => 65000.00,
                'order_status'   => 'Dibatalkan',
                'payment_status' => 'Dibatalkan',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::parse('2026-09-30 14:12:00'),
                'send_at'        => null,
            ],
            [
                'username'       => 'nguyenvinhhoa567',
                'product_index'  => 0,
                'quantity'       => 1,
                'subtotal'       => 42000.00,
                'order_status'   => 'Pending',
                'payment_status' => 'Pending',
                'address'        => 'Jl. Mistar Cokrokusumo No. 45, Banjarbaru, Kalimantan Selatan',
                'order_at'       => Carbon::now()->subDays(1)->subHours(3),
                'send_at'        => null,
            ],
            [
                'username'       => 'trantrungkien',
                'product_index'  => 1,
                'quantity'       => 1,
                'subtotal'       => 56000.00,
                'order_status'   => 'Sedang Perjalanan',
                'payment_status' => 'Sukses',
                'address'        => 'Jl. Panglima Batur Timur No. 12, Banjarbaru, Kalimantan Selatan',
                'order_at'       => Carbon::now()->subDays(2)->subHours(5),
                'send_at'        => null,
            ],
            [
                'username'       => 'hoangthanhphat88',
                'product_index'  => 4,
                'quantity'       => 1,
                'subtotal'       => 57000.00,
                'order_status'   => 'Diproses',
                'payment_status' => 'Sukses',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::now()->subDays(1)->subHours(8),
                'send_at'        => null,
            ],
            [
                'username'       => 'lehongson2023',
                'product_index'  => 1,
                'quantity'       => 1,
                'subtotal'       => 67000.00,
                'order_status'   => 'Dibatalkan',
                'payment_status' => 'Dibatalkan',
                'address'        => 'Ambil Ditempat (Store Maybeads)',
                'order_at'       => Carbon::now()->subDays(6),
                'send_at'        => null,
            ],
            [
                'username'       => 'bachdangthang',
                'product_index'  => 0,
                'quantity'       => 1,
                'subtotal'       => 67000.00,
                'order_status'   => 'Selesai',
                'payment_status' => 'Sukses',
                'address'        => 'Jl. Rahayu No. 8, Banjarbaru, Kalimantan Selatan',
                'order_at'       => Carbon::now()->subDays(3),
                'send_at'        => Carbon::now()->subDays(1),
            ],
            [
                'username'       => 'moonelliaven',
                'product_index'  => 0,
                'quantity'       => 2,
                'subtotal'       => 395000.00,
                'order_status'   => 'Sedang Perjalanan',
                'payment_status' => 'Sukses',
                'address'        => 'Jl. Mistar Cokrokusumo No. 45, Sungai Besar, Banjarbaru, Kalimantan Selatan',
                'order_at'       => Carbon::now()->subDays(1)->subHours(4),
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

        // Sinkronisasi order_count pada produk berdasarkan banyaknya pesanan nyata
        foreach ($products as $p) {
            $realCount = Order::where('product_id', $p->id)->count();
            $p->update(['order_count' => $realCount]);
        }
    }
}

