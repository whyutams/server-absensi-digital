<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::all();

        return response()->json([
            'status' => 'success',
            'data' => $siswa,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'nullable|unique:siswas,nis',
            'nama' => 'nullable|string',
            'nomor_hp' => 'nullable|string',
            'jurusan_id' => 'nullable|exists:jurusans,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'orangtua_id' => 'nullable|exists:users,id',
        ]);

        Siswa::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        return response()->json([
            'status' => 'success',
            'data' => $siswa,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis' => 'nullable|unique:siswas,nis,' . $siswa->id,
            'nama' => 'nullable|string',
            'nomor_hp' => 'nullable|string',
            'jurusan_id' => 'nullable|exists:jurusans,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'orangtua_id' => 'nullable|exists:users,id',
        ]);

        $siswa->update([
            'nis' => $request['nis'] ?? $siswa->nis,
            'nama' => $request['nama'] ?? $siswa->nama,
            'nomor_hp' => $request['nomor_hp'] ?? $siswa->nomor_hp,
            'jurusan_id' => $request['jurusan_id'] ?? $siswa->jurusan_id,
            'kelas_id' => $request['kelas_id'] ?? $siswa->kelas_id,
            'orangtua_id' => $request['orangtua_id'] ?? $siswa->orangtua_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diupdate.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}
