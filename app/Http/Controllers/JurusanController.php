<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusan = Jurusan::all();

        return response()->json([
            'status' => 'success',
            'data' => $jurusan,
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
            'nama' => 'required|string|unique:jurusans,nama',
            'alias' => 'required|string|unique:jurusans,alias',
            'deskripsi' => 'required|string',
        ]);

        Jurusan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Jurusan $jurusan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurusan $jurusan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'nama' => 'nullable|string|unique:jurusans,nama',
            'alias' => 'nullable|string|unique:jurusans,alias',
            'deskripsi' => 'nullable|string',
        ]);

        $jurusan->update([
            'nama' => $request['nama'] ?? $jurusan->nama,
            'alias' => $request['alias'] ?? $jurusan->alias,
            'deskripsi' => $request['deskripsi'] ?? $jurusan->deskripsi,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}
