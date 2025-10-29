@extends('backend.v_layouts.app')

@section('content')
    <!-- contentAwal -->

    <div class="container-fluid">
        {{-- ✅ Tampilkan pesan sukses setelah update --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="{{ route('backend.user.update', $edit->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <div class="card-body">
                            <h4 class="card-title">{{ $judul }}</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Foto</label>
                                        {{-- view image --}}
                                        @if ($edit->foto)
                                            <img src="{{ asset('storage/img-user/' . $edit->foto) }}"
                                                class="foto-preview mb-2" width="100%">
                                        @else
                                            <img src="{{ asset('storage/img-user/img-default.jpg') }}"
                                                class="foto-preview mb-2" width="100%">
                                        @endif
                                        {{-- file foto --}}
                                        <input type="file" name="foto"
                                            class="form-control @error('foto') is-invalid @enderror"
                                            onchange="previewFoto(event)">
                                        @error('foto')
                                            <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Hak Akses</label>
                                        <select name="role" class="form-control @error('role') is-invalid @enderror">
                                            <option value="">- Pilih Hak Akses -</option>
                                            <option value="1" {{ old('role', $edit->role) == '1' ? 'selected' : '' }}>
                                                Super Admin</option>
                                            <option value="0" {{ old('role', $edit->role) == '0' ? 'selected' : '' }}>
                                                Admin</option>
                                        </select>
                                        @error('role')
                                            <span class="invalid-feedback alert-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="">- Pilih Status -</option>
                                            <option value="1"
                                                {{ old('status', $edit->status) == '1' ? 'selected' : '' }}>Aktif</option>
                                            <option value="0"
                                                {{ old('status', $edit->status) == '0' ? 'selected' : '' }}>NonAktif
                                            </option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback alert-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" name="nama" value="{{ old('nama', $edit->nama) }}"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            placeholder="Masukkan Nama">
                                        @error('nama')
                                            <span class="invalid-feedback alert-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="email" value="{{ old('email', $edit->email) }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Masukkan Email">
                                        @error('email')
                                            <span class="invalid-feedback alert-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>HP</label>
                                        <input type="text" onkeypress="return hanyaAngka(event)" name="hp"
                                            value="{{ old('hp', $edit->hp) }}"
                                            class="form-control @error('hp') is-invalid @enderror"
                                            placeholder="Masukkan Nomor HP">
                                        @error('hp')
                                            <span class="invalid-feedback alert-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">Perbarui</button>
                                <a href="{{ route('backend.user.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- contentAkhir -->

    {{-- Script Preview Foto --}}
    <script>
        function previewFoto(event) {
            const foto = event.target.files[0];
            const preview = document.querySelector('.foto-preview');

            if (foto) {
                preview.src = URL.createObjectURL(foto);
            }
        }

        function hanyaAngka(evt) {
            var charCode = evt.which ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
            return true;
        }
    </script>
@endsection
