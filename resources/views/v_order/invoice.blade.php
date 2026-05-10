@extends('v_layouts.app')

@section('title', 'Invoice')
@push('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            background: #fff;
        }

        .page {
            width: 100%;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 24px;
            border-bottom: 2px solid #111;
            margin-bottom: 32px;
        }

        .brand {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #111;
        }

        .brand span {
            font-weight: 300;
            color: #888;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta .invoice-title {
            font-size: 18px;
            font-weight: 600;
            color: #111;
            margin-bottom: 4px;
        }

        .invoice-meta p {
            color: #666;
            font-size: 12px;
            line-height: 1.6;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            margin-bottom: 32px;
        }

        .info-block h4 {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #999;
            margin-bottom: 8px;
        }

        .info-block p {
            font-size: 13px;
            color: #333;
            line-height: 1.7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        thead tr {
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
        }

        thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            font-weight: 600;
        }

        thead th:last-child,
        tbody td:last-child {
            text-align: right;
        }

        tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        tbody td {
            padding: 12px;
            color: #333;
        }

        .item-name {
            font-weight: 500;
            color: #111;
        }

        .totals {
            display: flex;
            justify-content: flex-end;
        }

        .totals-table {
            width: 280px;
        }

        .totals-table tr td {
            padding: 5px 0;
            font-size: 13px;
            color: #555;
            border: none;
        }

        .totals-table tr td:last-child {
            text-align: right;
        }

        .totals-table .divider td {
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
        }

        .totals-table .grand-total td {
            font-size: 15px;
            font-weight: 700;
            color: #111;
            padding-top: 8px;
        }

        .shipping-info {
            background: #f9f9f9;
            border-left: 3px solid #111;
            padding: 14px 16px;
            margin-bottom: 32px;
        }

        .shipping-info h4 {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #999;
            margin-bottom: 8px;
        }

        .shipping-info p {
            font-size: 12px;
            color: #444;
            line-height: 1.7;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-paid {
            background: #e6f4ea;
            color: #2e7d32;
        }

        .badge-pending {
            background: #fff8e1;
            color: #f57f17;
        }

        .badge-kirim {
            background: #e3f2fd;
            color: #1565c0;
        }

        .badge-selesai {
            background: #ede7f6;
            color: #4527a0;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
        }

        .footer p {
            font-size: 11px;
            color: #aaa;
        }

        .print-btn {
            max-width: 720px;
            margin: 0 auto 16px;
            text-align: right;
        }

        .print-btn button {
            background: #111;
            color: #fff;
            border: none;
            padding: 10px 24px;
            font-size: 13px;
            cursor: pointer;
        }

        .print-btn button:hover {
            background: #333;
        }

        @media print {

            header,
            #navigation,
            #aside,
            footer {
                display: none !important;
            }

            .content-wrapper {
                margin: 0 !important;
                padding: 0 !important;
            }

            .print-btn {
                display: none;
            }
        }
    </style>
@endpush

@section('content')

    <div class="print-btn">
        <button onclick="window.print()">&#128438; Cetak Invoice</button>
    </div>

    <div class="page">

        {{-- HEADER --}}
        <div class="header">
            <div>
                <div class="brand">Toko<span>Online</span></div>
                <p style="font-size:12px; color:#888; margin-top:4px;">ngampusheula.com</p>
            </div>
            <div class="invoice-meta">
                <div class="invoice-title">INVOICE</div>
                <p>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p>{{ $order->created_at->format('d F Y') }}</p>
                <p style="margin-top:6px;">
                    @php
                        $statusClass = match (strtolower($order->status)) {
                            'paid' => 'badge-paid',
                            'kirim' => 'badge-kirim',
                            'selesai' => 'badge-selesai',
                            default => 'badge-pending',
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ $order->status }}</span>
                </p>
            </div>
        </div>

        {{-- INFO --}}
        <div class="info-grid">
            <div class="info-block">
                <h4>Pelanggan</h4>
                <p>
                    <strong>{{ $order->customer->nama ?? '-' }}</strong><br>
                    {{ $order->customer->email ?? '-' }}<br>
                    {{ $order->customer->hp ?? '-' }}
                </p>
            </div>
            <div class="info-block">
                <h4>Alamat Pengiriman</h4>
                <p>{!! $order->alamat ?? '-' !!}</p>
                @if($order->pos)
                    <p style="margin-top:4px; color:#888;">Kode Pos: {{ $order->pos }}</p>
                @endif
            </div>
        </div>

        {{-- ITEMS --}}
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th style="text-align:center;">Qty</th>
                    <th style="text-align:right;">Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td><span class="item-name">{{ $item->produk->nama_produk ?? '-' }}</span></td>
                        <td style="text-align:center;">{{ $item->quantity }}</td>
                        <td style="text-align:right;">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td style="text-align:right;">Rp {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- SHIPPING --}}
        @if($order->kurir)
            <div class="shipping-info">
                <h4>Info Pengiriman</h4>
                <p>
                    <strong>{{ strtoupper($order->kurir) }} - {{ $order->layanan_ongkir }}</strong>
                    &nbsp;|&nbsp; Estimasi: {{ $order->estimasi_ongkir }}
                    &nbsp;|&nbsp;
                    @if($order->noresi)
                        No. Resi: <strong>{{ $order->noresi }}</strong>
                    @else
                        No. Resi: <em style="color:#bbb;">Belum tersedia</em>
                    @endif
                </p>
            </div>
        @endif

        {{-- TOTALS --}}
        <div class="totals">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ongkos Kirim</td>
                    <td>Rp {{ number_format($order->biaya_ongkir ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr class="divider">
                    <td></td>
                    <td></td>
                </tr>
                <tr class="grand-total">
                    <td>Total</td>
                    <td>Rp {{ number_format($order->total_harga + ($order->biaya_ongkir ?? 0), 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            <p>Terima kasih telah berbelanja di NgampusHeula.</p>
            <p>Dicetak: {{ now()->format('d M Y, H:i') }}</p>
        </div>

    </div>
@endsection