@extends('backend.v_layouts.app')

@section('content')
<div class="container mt-4">
    <h4>{{ $judul }}</h4>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($index as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->user->nama }}</td>
                <td>{{ $row->user->email }}</td>
                <td>
                    <a href="{{ route('backend.customer.show', $row->id) }}" class="btn btn-info btn-sm">Detail</a>
                    <a href="{{ route('backend.customer.edit', $row->id) }}" class="btn btn-warning btn-sm">Ubah</a>
                    <form action="{{ route('backend.customer.destroy', $row->id) }}" method="POST" style="display:inline-block"
                        onsubmit="return confirm('Yakin ingin menghapus?')">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection