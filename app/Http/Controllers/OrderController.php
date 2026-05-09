<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    // ================================
    // CART
    // ================================

    public function addToCart(int $id)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $produk   = Produk::findOrFail($id);

        $order = Order::firstOrCreate(
            [
        'customer_id' => $customer->id,
        'status' => 'pending'
    ],
            [
        'user_id' => Auth::id(),
        'total_harga' => 0
    ]
        );

        $orderItem = OrderItem::firstOrCreate(
            ['order_id' => $order->id, 'produk_id' => $produk->id],
            ['quantity' => 1, 'harga' => $produk->harga]
        );

        if (!$orderItem->wasRecentlyCreated) {
            $orderItem->quantity++;
            $orderItem->save();
        }

        $order->total_harga += $produk->harga;
        $order->save();

        return redirect()->route('order.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function viewCart()
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if ($order) {
            $order->load('orderItems.produk');
        }

        return view('v_order.cart', compact('order'));
    }

    public function updateCart(Request $request, int $id)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $order    = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if ($order) {
            $orderItem = $order->orderItems()->where('id', $id)->first();

            if ($orderItem) {
                $quantity = $request->input('quantity');

                if ($quantity > $orderItem->produk->stok) {
                    return redirect()->route('order.cart')->with('error', 'Jumlah produk melebihi stok yang tersedia');
                }

                $order->total_harga -= $orderItem->harga * $orderItem->quantity;
                $orderItem->quantity = $quantity;
                $orderItem->save();
                $order->total_harga += $orderItem->harga * $orderItem->quantity;
                $order->save();
            }
        }

        return redirect()->route('order.cart')->with('success', 'Jumlah produk berhasil diperbarui');
    }

    public function removeFromCart(Request $request, int $id)
    {
        $customer = Customer::where('user_id', Auth::id())->first();

        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return redirect()->back();
        }

        $orderItem = OrderItem::where('order_id', $order->id)
            ->where('produk_id', $id)
            ->first();

        if (!$orderItem) {
            return redirect()->back();
        }

        $order->total_harga -= ($orderItem->harga * $orderItem->quantity);

        $orderItem->delete();

        if ($order->orderItems()->count() == 0) {
            $order->delete();
        } else {
            $order->save();
        }

        return redirect()->route('order.cart')
            ->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    // ================================
    // SHIPPING
    // ================================

    public function selectShipping()
    {
        $customer = Customer::where('user_id', Auth::id())->first();

        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();


        if (!$order || $order->orderItems->count() == 0) {
            return redirect()->route('order.cart')->with('error', 'Keranjang belanja kosong.');
        }

        return view('v_order.select_shipping', compact('order'));
    }

    public function updateOngkir(Request $request)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $order    = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return back()->with('error', 'Gagal menyimpan data ongkir');
        }

        $order->kurir           = $request->input('kurir');
        $order->layanan_ongkir  = $request->input('layanan_ongkir');
        $order->biaya_ongkir    = $request->input('biaya_ongkir');
        $order->estimasi_ongkir = $request->input('estimasi_ongkir');
        $order->total_berat     = $request->input('total_berat');
        $order->alamat          = $request->input('alamat') . ', <br>' .
                                  $request->input('city_name') . ', <br>' .
                                  $request->input('province_name');
        $order->pos             = $request->input('pos');
        $order->save();

        return redirect()->route('order.selectpayment');
    }

    // ================================
    // PAYMENT
    // ================================

    public function selectPayment()
    {
        $customer = Auth::user();
        $order    = Order::where('customer_id', $customer->customer->id)
            ->where('status', 'pending')
            ->first();

        if (!$order || !$order->kurir) {
            return redirect()->route('order.selectShipping')->with('error', 'Pilih pengiriman terlebih dahulu.');
        }

        $order->load('orderItems.produk');

        $totalHarga = 0;
        foreach ($order->orderItems as $item) {
            $totalHarga += $item->harga * $item->quantity;
        }

        $grossAmount = $totalHarga + $order->biaya_ongkir;


        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');



        $params = [
            'transaction_details' => [
                'order_id'     => $order->id . '-' . time(),
                'gross_amount' => (int) $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $customer->nama,
                'email'      => $customer->email,
                'phone'      => $customer->hp,
            ],
            'enabled_payments' => [
                'bca_va', 'bni_va', 'bri_va', 'permata_va',
                'gopay', 'qris', 'shopeepay',
                'indomaret', 'alfamart',
            ],
        ];


        $snapToken = Snap::getSnapToken($params);

        return view('v_order.select_payment', compact('order', 'snapToken'));
    }


    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed    = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $orderId = explode('-', $request->order_id)[0];
            $order   = Order::find($orderId);

            if ($order) {
                $transactionStatus = $request->transaction_status;
                $fraudStatus       = $request->fraud_status;

                if (($transactionStatus == 'capture' && $fraudStatus == 'accept') || $transactionStatus == 'settlement') {
                    $order->update(['status' => 'Paid']);
                } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $order->update(['status' => 'pending']);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function complete()
    {
        return redirect()->route('order.history')->with('success', 'Pembayaran berhasil');
    }

    public function orderHistory()
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $orders   = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['Paid', 'Kirim', 'Selesai'])
            ->orderBy('id', 'desc')
            ->get();

        return view('v_order.history', compact('orders'));
    }

    public function checkout()
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $order    = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if ($order) {
            foreach ($order->orderItems as $item) {
                $produk = $item->produk;

                if ($produk->stok >= $item->quantity) {
                    $produk->stok -= $item->quantity;
                    $produk->save();
                } else {
                    return redirect()->route('order.cart')->with('error', 'Stok produk ' . $produk->nama_produk . ' tidak mencukupi');
                }
            }

            $order->status = 'completed';
            $order->save();
        }

        return redirect()->route('order.history')->with('success', 'Checkout berhasil');
    }

    // ================================
    // BACKEND - PESANAN
    // ================================

    public function statusProses()
    {
        $order = Order::with('customer')
            ->whereIn('status', ['Paid', 'Kirim'])
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.v_pesanan.proses', [
            'judul'    => 'Data Pesanan',
            'subJudul' => 'Pesanan Proses',
            'index'    => $order,
        ]);
    }

    public function statusSelesai()
    {
        $order = Order::with('customer')
            ->where('status', 'Selesai')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.v_pesanan.selesai', [
            'judul'    => 'Data Transaksi',
            'subJudul' => 'Pesanan Selesai',
            'index'    => $order,
        ]);
    }

    public function statusDetail(int $id)
    {
        $order = Order::findOrFail($id);

        return view('backend.v_pesanan.detail', [
            'judul'    => 'Data Transaksi',
            'subJudul' => 'Detail Pesanan',
            'order'    => $order,
        ]);
    }

    public function statusUpdate(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $rules = ['alamat' => 'required'];

        if ($request->status != $order->status) {
            $rules['status'] = 'required';
        }
        if ($request->noresi != $order->noresi) {
            $rules['noresi'] = 'required';
        }
        if ($request->pos != $order->pos) {
            $rules['pos'] = 'required';
        }

        $request->validate($rules);

        Order::where('id', $id)->update($request->only(array_keys($rules)));

        return redirect()->route('pesanan.proses')->with('success', 'Data berhasil diperbaharui');
    }

    // ================================
    // BACKEND - LAPORAN
    // ================================

    public function formOrderProses()
    {
        return view('backend.v_pesanan.formproses', [
            'judul'    => 'Laporan',
            'subJudul' => 'Laporan Pesanan Proses',
        ]);
    }

    public function cetakOrderProses(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ], [
            'tanggal_awal.required'         => 'Tanggal Awal harus diisi.',
            'tanggal_akhir.required'        => 'Tanggal Akhir harus diisi.',
            'tanggal_akhir.after_or_equal'  => 'Tanggal Akhir harus lebih besar atau sama dengan Tanggal Awal.',
        ]);

        $order = Order::whereIn('status', ['Paid', 'Kirim'])
            ->whereBetween('created_at', [$request->tanggal_awal, $request->tanggal_akhir])
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.v_pesanan.cetakproses', [
            'judul'        => 'Laporan',
            'subJudul'     => 'Laporan Pesanan Proses',
            'tanggalAwal'  => $request->tanggal_awal,
            'tanggalAkhir' => $request->tanggal_akhir,
            'cetak'        => $order,
        ]);
    }

    public function formOrderSelesai()
    {
        return view('backend.v_pesanan.formselesai', [
            'judul'    => 'Laporan',
            'subJudul' => 'Laporan Pesanan Selesai',
        ]);
    }

    public function cetakOrderSelesai(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ], [
            'tanggal_awal.required'        => 'Tanggal Awal harus diisi.',
            'tanggal_akhir.required'       => 'Tanggal Akhir harus diisi.',
            'tanggal_akhir.after_or_equal' => 'Tanggal Akhir harus lebih besar atau sama dengan Tanggal Awal.',
        ]);

        $order = Order::where('status', 'Selesai')
            ->whereBetween('created_at', [$request->tanggal_awal, $request->tanggal_akhir])
            ->orderBy('id', 'desc')
            ->get();

        $totalPendapatan = 0;
        foreach ($order as $row) {
            $totalPendapatan += $row->total_harga + $row->biaya_ongkir;
        }

        return view('backend.v_pesanan.cetakselesai', [
            'judul'           => 'Laporan',
            'subJudul'        => 'Laporan Pesanan Selesai',
            'tanggalAwal'     => $request->tanggal_awal,
            'tanggalAkhir'    => $request->tanggal_akhir,
            'cetak'           => $order,
            'totalPendapatan' => $totalPendapatan,
        ]);
    }

    // ================================
    // INVOICE
    // ================================

    public function invoiceBackend(int $id)
    {
        $order = Order::findOrFail($id);

        return view('backend.v_pesanan.invoice', [
            'judul'    => 'Data Transaksi',
            'subJudul' => 'Invoice Pesanan',
            'order'    => $order,
        ]);
    }

    public function invoiceFrontend(int $id)
    {
        $order = Order::findOrFail($id);

        return view('v_order.invoice', [
            'judul'    => 'Data Transaksi',
            'subJudul' => 'Invoice Pesanan',
            'order'    => $order,
        ]);
    }
}
