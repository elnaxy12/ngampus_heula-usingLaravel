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
                    <h3 style=""> {{ $judul }} </h3>
                    <form action="{{ route('anggota.update', $edit->id) }}" method="post">
                        @method('put')
                        @csrf
                        <label>Nama</label><br>
                        <input type="text" name="nama" id="" value="{{ old('nama', $edit->nama) }}"
                            placeholder="Masukkan Nama Lengkap">
                        <p></p>

                        <label>HP</label><br>
                        <input type="text" name="hp" id="" value="{{ old('hp', $edit->hp) }}"
                            placeholder="Masukkan Nomor HP">
                        <p></p>

                        <div class="col-1" style="display: flex; justify-content: space-between;">
                            <button type="submit">Perbaharui</button>
                            <a href="{{ route('anggota.index') }}">
                                <button type="button">Batal</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
