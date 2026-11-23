<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Halaman manajemen pengantaran (Delivery Tracking & Courier Monitor)
     */
    public function index(Request $request)
    {
        // Ambil orderan yang menggunakan metode antar ke alamat (bukan ambil di tempat)
        $query = Order::with(['user', 'product', 'payment'])
            ->where(function ($q) {
                $q->where('address', 'not like', '%Ambil%')
                  ->where('address', 'not like', '%ditempat%')
                  ->whereNotNull('address')
                  ->where('address', '!=', '');
            })
            ->latest('id');

        $statusFilter = strtolower($request->query('status', 'semua'));
        if ($statusFilter === 'siap_kirim') {
            $query->whereIn('order_status', ['Pending', 'pending', 'Diproses', 'diproses', 'Proses', 'proses']);
        } elseif ($statusFilter === 'dalam_perjalanan') {
            $query->whereIn('order_status', ['Sedang Perjalanan', 'sedang perjalanan']);
        } elseif ($statusFilter === 'terkirim') {
            $query->whereIn('order_status', ['Selesai', 'selesai', 'Sukses', 'sukses']);
        }

        $courierFilter = strtolower($request->query('courier', 'semua'));
        $search = trim($request->query('q', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $rawOrders = $query->get();

        // Format data pengantaran dengan informasi kurir cerdas
        $deliveries = $rawOrders->map(function ($o) {
            $addr = $o->address ?: 'Banjarbaru, Kalimantan Selatan';
            $isLuarPulau = preg_match('/jakarta|surabaya|bandung|semarang|medan|makassar|bali|jawa|sumatera|sulawesi/i', $addr);

            // Tentukan ekspedisi default jika belum ada
            if ($isLuarPulau) {
                $defaultCourier = ($o->id % 2 === 0) ? 'J&T Express' : 'JNE Regular';
                $coverage = 'Luar Pulau';
            } else {
                $defaultCourier = ($o->id % 2 === 0) ? 'Gojek Instant' : 'Kurir Toko';
                $coverage = 'Dalam Kota';
            }

            $courierCode = match ($defaultCourier) {
                'J&T Express'   => 'JT' . str_pad((string)$o->id, 8, '920410', STR_PAD_LEFT),
                'JNE Regular'   => 'JNE' . str_pad((string)$o->id, 8, '883921', STR_PAD_LEFT),
                'Gojek Instant' => 'GK-' . str_pad((string)$o->id, 6, '5510', STR_PAD_LEFT),
                default         => 'KURIR-LOKAL-' . $o->id,
            };

            $stLower = strtolower($o->order_status);
            $deliveryStatus = match ($stLower) {
                'sukses', 'selesai'                    => 'terkirim',
                'sedang perjalanan'                     => 'dalam_perjalanan',
                'dibatalkan', 'batal'                   => 'dibatalkan',
                default                                => 'siap_kirim',
            };

            return [
                'order_id'        => $o->id,
                'order_code'      => $o->order_code,
                'order'           => $o,
                'customer_name'   => $o->user->name ?? 'Pelanggan',
                'phone'           => $o->user->phone ?? '0812-3456-7890',
                'address'         => $addr,
                'coverage'        => $coverage,
                'courier_name'    => $defaultCourier,
                'tracking_code'   => $courierCode,
                'order_status'    => $o->order_status,
                'delivery_status' => $deliveryStatus,
                'order_at'        => $o->order_at ? $o->order_at->format('d M Y, H:i') : ($o->created_at ? $o->created_at->format('d M Y, H:i') : '-'),
            ];
        });

        // Filter kurir jika ada
        if ($courierFilter !== 'semua') {
            $deliveries = $deliveries->filter(function ($d) use ($courierFilter) {
                return str_contains(strtolower($d['courier_name']), $courierFilter);
            });
        }

        // Hitung statistik
        $allDeliveryOrders = Order::where('address', 'not like', '%Ambil%')
            ->where('address', 'not like', '%ditempat%')
            ->whereNotNull('address')
            ->where('address', '!=', '')
            ->get();

        $stats = [
            'total'            => $allDeliveryOrders->count(),
            'siap_kirim'       => $allDeliveryOrders->filter(fn($o) => in_array(strtolower($o->order_status), ['pending', 'diproses', 'proses']))->count(),
            'dalam_perjalanan' => $allDeliveryOrders->filter(fn($o) => in_array(strtolower($o->order_status), ['sedang perjalanan']))->count(),
            'terkirim'         => $allDeliveryOrders->filter(fn($o) => in_array(strtolower($o->order_status), ['selesai', 'sukses']))->count(),
        ];

        return view('admin.delivery.index', compact('deliveries', 'stats', 'statusFilter', 'courierFilter', 'search'));
    }

    /**
     * Update status pengiriman / kurir
     */
    public function updateCourier(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $validated = $request->validate([
            'order_status' => 'required|string|in:Diproses,Sedang Perjalanan,Selesai,Dibatalkan',
        ]);

        $order->update([
            'order_status' => $validated['order_status'],
            'send_at'      => $validated['order_status'] === 'Selesai' ? now() : ($order->send_at ?? now()),
        ]);

        if ($validated['order_status'] === 'Selesai' && $order->payment) {
            $order->payment->update(['payment_status' => 'Sukses']);
        }

        return redirect()->back()->with('success', "Status pengantaran pesanan #{$order->order_code} berhasil diperbarui.");
    }
}
