<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Customer;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function statusProses()
    {
        $order = Order::whereIn('status', ['Paid', 'Kirim'])
            ->latest('id')
            ->get();

        return view('backend.v_pesanan.proses', [
            'judul'     => 'Pesanan',
            'subJudul'  => 'Pesanan Proses',
            'index'     => $order
        ]);
    }

    public function statusSelesai()
    {
        $order = Order::where('status', 'Selesai')
            ->latest('id')
            ->get();

        return view('backend.v_pesanan.selesai', [
            'judul'     => 'Data Transaksi', // fix duplikat
            'subJudul'  => 'Pesanan Selesai',
            'index'     => $order
        ]);
    }

    public function statusDetail($id)
    {
        $order = Order::findOrFail($id);

        return view('backend.v_pesanan.detail', [
            'judul'     => 'Data Transaksi',
            'subJudul'  => 'Detail Pesanan',
            'order'     => $order,
        ]);
    }

    public function statusUpdate(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $rules = [
            'alamat' => 'required',
        ];

        if ($request->status !== $order->status) {
            $rules['status'] = 'required';
        }

        if ($request->noresi !== $order->noresi) {
            $rules['noresi'] = 'required';
        }

        if ($request->pos !== $order->pos) {
            $rules['pos'] = 'required';
        }

        $validatedData = $request->validate($rules);

        $order->update($validatedData);

        return redirect()
            ->route('pesanan.proses')
            ->with('success', 'Data berhasil diperbaharui');
    }
    public function addToCart($id)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $produk = Produk::findOrFail($id);

        $order = Order::firstOrCreate(
            ['customer_id' => $customer->id, 'status' => 'pending'],
            ['total_harga' => 0]
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
            ->whereIn('status', ['pending', 'paid'])
            ->first();

        if ($order) {
            $order->load('orderItems.produk');
        }

        return view('v_order.cart', compact('order'));
    }

    public function updateCart(Request $request, $id)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $order = Order::where('customer_id', $customer->id)
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

    public function removeFromCart(Request $request, $id)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if ($order) {
            $orderItem = OrderItem::where('order_id', $order->id)
                ->where('produk_id', $id)
                ->first();

            if ($orderItem) {
                $order->total_harga -= $orderItem->harga * $orderItem->quantity;
                $orderItem->delete();

                if ($order->total_harga <= 0) {
                    $order->delete();
                } else {
                    $order->save();
                }
            }
        }

        return redirect()->route('order.cart')->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    public function selectShipping(Request $request)
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
        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if ($order) {
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

        return back()->with('error', 'Gagal menyimpan data ongkir');
    }

    public function getProvinces()
    {
        $response = Http::withHeaders(['key' => env('RAJAONGKIR_API_KEY')])
            ->get(env('RAJAONGKIR_BASE_URL') . 'destination/province');  // ✅

        return response()->json($response->json());
    }

    public function getCities(Request $request)
    {
        $response = Http::withHeaders(['key' => env('RAJAONGKIR_API_KEY')])
            ->get(env('RAJAONGKIR_BASE_URL') . 'destination/city', [
                'province_code' => $request->input('province_id'),  // ✅ 'province_code'
            ]);

        return response()->json($response->json());
    }

    public function getCost(Request $request)
    {
        $response = Http::withHeaders(['key' => env('RAJAONGKIR_API_KEY')])
            ->asForm()
            ->post(env('RAJAONGKIR_BASE_URL') . 'calculate/district/domestic-cost', [
                'origin'      => $request->input('origin'),
                'destination' => $request->input('destination'),
                'weight'      => $request->input('weight'),
                'courier'     => $request->input('courier'),
                'price'       => 'lowest',
            ]);

        return response()->json($response->json());
    }

    public function selectPayment()
    {
        // Mendapatkan customer yang login
        $customer = Auth::user();

        // Cari order dengan status 'pending'
        $order = Order::where('customer_id', $customer->customer->id)->where('status', 'pending')->first();

        $origin = session('origin');        // Kode kota asal
        $originName = session('originName'); // Nama kota asal

        // Jika order tidak ditemukan, tampilkan error
        if (!$order) {
            return redirect()->route('order.cart')->with('error', 'Keranjang belanja kosong.');
        }

        // Muat relasi orderItems dan produk terkait
        $order->load('orderItems.produk');

        // Hitung total harga produk
        $totalHarga = 0;
        foreach ($order->orderItems as $item) {
            $totalHarga += $item->harga * $item->quantity;
        }

        // Kirim data order dan snapToken ke view
        return view('v_order.select_payment', [
            'order' => $order,
            'origin' => $origin,
            'originName' => $originName,
            // 'snapToken' => $snapToken
        ]);
    }

}
