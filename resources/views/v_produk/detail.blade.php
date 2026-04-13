@extends('v_layouts.app')

@section('content')
    <div class="row">

        {{-- Judul --}}
        <div class="col-md-12">
            <div class="billing-details">
                <div class="section-title">
                    <h3 class="title">{{ $judul }}</h3>
                </div>
            </div>
        </div>

        {{-- Product Details --}}
        <div class="product product-details clearfix">

            {{-- Kolom Foto --}}
            <div class="col-md-6">

                {{-- Foto Utama (Large) --}}
                <div id="product-main-view">
                    <div class="product-view">
                        <img src="{{ asset('storage/img-produk/thumb_lg_' . $row->foto) }}" alt="">
                    </div>

                    @foreach ($fotoProdukTambahan as $item)
                        @if ($item->produk_id == $row->id)
                            <div class="product-view">
                                <img src="{{ asset('storage/img-produk/' . $item->foto) }}" alt="">
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Foto Thumbnail (Small) --}}
                <div id="product-view">
                    <div class="product-view">
                        <img src="{{ asset('storage/img-produk/thumb_sm_' . $row->foto) }}" alt="">
                    </div>

                    @foreach ($fotoProdukTambahan as $item)
                        @if ($item->produk_id == $row->id)
                            <div class="product-view">
                                <img src="{{ asset('storage/img-produk/' . $item->foto) }}" alt="">
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>

            {{-- Kolom Informasi Produk --}}
            <div class="col-md-6">
                <div class="product-body">

                    <div class="product-label">
                        <span>Kategori</span>
                        <span class="sale">{{ $row->kategori->nama_kategori }}</span>
                    </div>

                    <h2 class="product-name">{{ $row->nama_produk }}</h2>
                    <h3 class="product-price">Rp. {{ number_format($row->harga, 0, ',', '.') }}</h3>

                    <p>{!! $row->detail !!}</p>

                    <div class="product-options">
                        <ul class="size-option">
                            <li><span class="text-uppercase">Berat:</span></li>
                            <li>{{ $row->berat }} Gram</li>
                        </ul>
                        <ul class="size-option">
                            <li><span class="text-uppercase">Stok:</span></li>
                            <li>{{ $row->stok }}</li>
                        </ul>
                    </div>

                    <div class="product-btns">
                        <form action="#" method="post" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="primary-btn add-to-cart">
                                <i class="fa fa-shopping-cart"></i> Pesan
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
        {{-- /Product Details --}}
    </div>
@endsection