@extends('v_layouts.app')

@section('title', 'Pilih Pengiriman')
@section('content')
                        <!-- template -->

                        <div class="col-md-12" hidden>
                            <div class="order-summary clearfix">
                                <div class="section-title">
                                    <h3 class="title">Produk</h3>
                                </div>
                                @if($order && $order->orderItems->count() > 0)
                                    <table class="shopping-cart-table table">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th></th>
                                                <th class="text-center">Harga</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
        $totalHarga = 0;
        $totalBerat = 0;
                                            @endphp
                                            @foreach($order->orderItems as $item)
                                                @php
            $totalHarga += $item->harga * $item->quantity;
            $totalBerat += $item->produk->berat * $item->quantity;
                                                @endphp
                                                <tr>
                                                    <td class="thumb"><img src="{{ asset('storage/img-produk/thumb_sm_' . $item->produk->foto) }}"
                                                            alt=""></td>
                                                    <td class="details">
                                                        <a>{{ $item->produk->nama_produk }}</a>
                                                        <ul>
                                                            <li><span>Berat: {{ $item->produk->berat }} Gram</span></li>
                                                        </ul>
                                                        <ul>
                                                            <li><span>Stok: {{ $item->produk->stok }} Gram</span></li>
                                                        </ul>
                                                    </td>
                                                    <td class="price text-center"><strong>Rp. {{ number_format($item->harga, 0, ',', '.') }}</strong>
                                                    </td>
                                                    <td class="qty text-center">
                                                        <a> {{ $item->quantity }} </a>
                                                    </td>
                                                    <td class="total text-center"><strong class="primary-color">Rp.
                                                            {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</strong></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>Keranjang belanja kosong.</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="order-summary clearfix">
                                <div class="section-title">
                                    <h3 class="title">Pilih Pengiriman</h3>
                                </div>
                                <form id="shippingForm">
                                    <!-- Kota Asal -->
                                    <input type="hidden" id="city_origin" name="city_origin" value="">
                                    <input type="hidden" id="city_origin_name" name="city_origin_name" value="">
                                    <!-- /Kota Asal -->

                                    <div class="form-group">
                                        <label for="province">Provinsi Tujuan:</label>
                                        <select name="province" id="province" class="input">
                                            <option value="">Pilih Provinsi Tujuan</option>
                                            <!-- Data Provinsi Tujuan akan dimuat dengan JavaScript -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="city">Kota Tujuan:</label>
                                        <select name="city" id="city" class="input">
                                            <option value="">Pilih Kota Tujuan</option>
                                            <!-- Data Kota Tujuan akan dimuat dengan JavaScript -->
                                        </select>
                                    </div>
                                    <input type="hidden" name="weight" id="weight" value="{{ $totalBerat }}">
                                    <input type="hidden" name="province_name" id="province_name">
                                    <input type="hidden" name="city_name" id="city_name">
                                    <div class="form-group">
                                        <label for="courier">Kurir:</label>
                                        <select name="courier" id="courier" class="input">
                                            <option value="">Pilih Kurir</option>
                                            <option value="jne">JNE</option>
                                            <option value="tiki">TIKI</option>
                                            <option value="pos">POS Indonesia</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Alamat</label>
                                        <textarea class="input" name="alamat" id="alamat">{{ Auth::user()->alamat }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Kode Pos</label>
                                        <input type="text" class="input" name="kode_pos" id="kode_pos" value="{{ Auth::user()->pos }}">
                                    </div>
                                    <button type="submit" class="primary-btn">Cek Ongkir</button>
                                </form>

                                <br>
                                <div id="result">
                                    <table class="shopping-cart-table table">
                                        <thead>
                                            <tr>
                                                <th>Layanan</th>
                                                <th>Biaya</th>
                                                <th>Estimasi Pengiriman</th>
                                                <th>Total Berat</th>
                                                <th>Total Harga</th>
                                                <th>Bayar</th>
                                            </tr>
                                        </thead>
                                        <tbody id="shippingResults">
                                            <!-- Hasil dari pencarian akan dimuat di sini -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const originCityCode = 115;
            document.getElementById('city_origin').value = originCityCode;

            // LOAD PROVINCES
            fetch('/provinces')
                .then(res => res.json())
                .then(data => {
                    const provinces = data?.rajaongkir?.results || data?.data || [];
                    const select = document.getElementById('province');
                    provinces.forEach(p => {
                        let opt = new Option(p.province || p.name, p.province_id || p.id);
                        select.appendChild(opt);
                    });
                });

            // LOAD CITIES
            document.getElementById('province').addEventListener('change', function () {
                const selectedText = this.options[this.selectedIndex].text;
                document.getElementById('province_name').value = selectedText;

                fetch(`/cities?province_id=${this.value}`)
                    .then(res => res.json())
                    .then(data => {
                        const cities = data?.rajaongkir?.results || data?.data || [];
                        const select = document.getElementById('city');
                        select.innerHTML = '<option value="">Pilih Kota</option>';
                        cities.forEach(c => {
                            let opt = new Option(c.city_name || c.name, c.city_id || c.id);
                            select.appendChild(opt);
                        });
                    });
            });

            // CITY CHANGE — di luar province listener
            document.getElementById('city').addEventListener('change', function () {
                const selectedText = this.options[this.selectedIndex].text;
                document.getElementById('city_name').value = selectedText;
            });

            // CEK ONGKIR
            document.getElementById('shippingForm').addEventListener('submit', function (e) {
                e.preventDefault();

                let origin = document.getElementById('city_origin').value;
                let destination = document.getElementById('city').value;
                let weight = document.getElementById('weight').value;
                let courier = document.getElementById('courier').value;
                let alamat = document.getElementById('alamat').value;
                let kodePos = document.getElementById('kode_pos').value;
                let provinceName = document.getElementById('province_name').value;
                let cityName = document.getElementById('city_name').value;

                if (!destination) {
                    alert('Pilih kota tujuan terlebih dahulu.');
                    return;
                }
                if (!courier) {
                    alert('Pilih kurir terlebih dahulu.');
                    return;
                }

                fetch('/cost', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ origin, destination, weight, courier }),
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log('Raw response:', JSON.stringify(data, null, 2));

                        // ✅ Struktur Komerce V2 — data langsung array
                        const results = data?.data || [];

                        const table = document.getElementById('shippingResults');
                        table.innerHTML = '';

                        if (!results.length) {
                            table.innerHTML = `<tr><td colspan="6">Tidak ada data ongkir. Cek console untuk detail.</td></tr>`;
                            return;
                        }

                        results.forEach(cost => {
                            // ✅ cost langsung angka, bukan cost[0].value
                            let price = cost.cost ?? 0;
                            let etd = cost.etd ?? '-';
                            let total = parseInt(price) + parseInt({{ $order->total_harga ?? 0 }});

                            let row = `
                                <tr>
                                    <td>${cost.name} - ${cost.service}</td>
                                    <td>Rp. ${Number(price).toLocaleString('id-ID')}</td>
                                    <td>${etd}</td>
                                    <td>${weight} Gram</td>
                                    <td>Rp. ${Number(total).toLocaleString('id-ID')}</td>
                                    <td>
                                        <form method="POST" action="{{ route('order.update-ongkir') }}">
                                            @csrf
                                            <input type="hidden" name="layanan_ongkir"  value="${cost.service}">
                                            <input type="hidden" name="biaya_ongkir"    value="${price}">
                                            <input type="hidden" name="estimasi_ongkir" value="${etd}">
                                            <input type="hidden" name="total_berat"     value="${weight}">
                                            <input type="hidden" name="kurir"           value="${cost.name}">
                                            <input type="hidden" name="alamat"          value="${alamat}">
                                            <input type="hidden" name="pos"             value="${kodePos}">
                                            <input type="hidden" name="province_name"   value="${provinceName}">
                                            <input type="hidden" name="city_name"       value="${cityName}">
                                            <button type="submit" class="primary-btn">Pilih Pengiriman</button>
                                        </form>
                                    </td>
                                </tr>
                            `;
                            table.innerHTML += row;
                        });
                    })
                    .catch(err => console.error('Fetch error:', err));
            });

        });
    </script>
                        <!-- end template-->
@endsection