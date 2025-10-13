<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(entrypoints: ['resources/css/app.css', 'resources/js/app.js'])

</head> 
    
<body>
    <div class="main-wrapper">
        <div class="align-center">
            <div class="card">
                <div class="col">
                    <h3>{{ $judul }}</h3>

                    <form action="{{ route('anggota.store') }}" method="post">
                        @csrf
                        <label for="nama">Nama</label><br>
                        <input type="text" placeholder="Masukan Nama" name="nama" id="nama"
                            value="{{ old('nama') }}">
                        <p></p>

                        <label for="hp">HP</label><br>
                        <input type="text" placeholder="Masukan Nomor Hp" name="hp" id="hp"
                            value="{{ old('hp') }}">
                        <p></p>

                        <div class="col-1" style="display: flex; justify-content: space-between;">
                            <button type="submit">Simpan</button>
                            <a href="{{ route('anggota.index') }}" class="btn-cancel">
                                <button type="button">Batal</button></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
