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
            <div class="col-1">
                <h3>{{ $judul }}</h3>
            </div>
            <div class="col-2">
                <button type="button" class="btn-tambah" onclick="window.location='{{ route('anggota.create') }}'">
                    Tambah
                </button>
            </div>
            <div class="col-3">
                <table border="1" width="60%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($index as $row)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td style="padding-left: 5px;">{{ $row->nama }}</td>
                                <td class="text-center">{{ $row->hp }}</td>
                                <td class="evenly-space">
                                    <button onclick="window.location='{{ route('anggota.edit',$row->id )}}'" class="btn-ubah">
                                        Ubah
                                    </button>

                                    <form action="{{ route('anggota.destroy', $row->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

</html>
