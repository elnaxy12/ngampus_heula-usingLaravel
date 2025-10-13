<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::orderBy('id', 'desc')->get();
        return view('v_anggota.index', [
            'judul' => 'Data Anggota',
            'index' => $anggota
        ]);
    }

    public function create()
    {
        return view('v_anggota.create', [
            'judul' => 'Tambah Anggota'
        ]);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
             'nama' => 'required|max:255',
             'hp' => 'required|min:10|max:13',
         ]);
        Anggota::create($validatedData);
        return redirect('/anggota');
    }
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $anggota = Anggota::find($id);
        return view('v_anggota.edit', [
            'judul' => 'Ubah Anggota',
            'edit' => $anggota
        ]);
    }

    public function update(Request $request, string $id)
    {
        $rules = [
            'nama' => 'required|max:100',
            'hp' => 'required|min:10|max:13',
        ];
        $validatedData = $request->validate($rules);
        Anggota::where('id', $id)->update($validatedData);
        return redirect('/anggota');
    }

    public function destroy(string $id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->delete();
        return redirect('/anggota');
    }

}
