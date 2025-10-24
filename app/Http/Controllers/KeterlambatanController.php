<?php

namespace App\Http\Controllers;

use App\Models\Keterlambatan;
use Auth;
use Illuminate\Http\Request;

class KeterlambatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keterlambatans = Keterlambatan::all();

        return response()->json([
            'status' => 'success',
            'data' => $keterlambatans,
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
            "nis" => "required|string",
            "nomor_hp" => "required|string",
            "nama" => "required|string",
            "keterangan" => "required|string",
            "gambar" => "required|image|mimes:jpg,jpeg,png",
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/keterlambatan/'), $filename);
            $validated['gambar'] = 'storage/uploads/keterlambatan/' . $filename;
        }

        if (Auth::check()) {
            $validated['created_by'] = Auth::id();
        }

        Keterlambatan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Keterlambatan $keterlambatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Keterlambatan $keterlambatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Keterlambatan $keterlambatan)
    {
        $validated = $request->validate([
            "nis" => "string",
            "nomor_hp" => "string",
            "nama" => "string",
            "keterangan" => "string",
            "gambar" => "image|mimes:jpg,jpeg,png",
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/keterlambatan/'), $filename);
            $validated['gambar'] = 'storage/uploads/keterlambatan/' . $filename;

            // cek dan hapus gambar lama jika ada
            if ($keterlambatan->gambar && file_exists(public_path($keterlambatan->gambar))) {
                unlink(public_path($keterlambatan->gambar));
            }
        }

        // Update data (jika field tidak diisi, gunakan nilai lama)
        $keterlambatan->update([
            "nis" => $validated['nis'] ?? $keterlambatan->nis,
            "nomor_hp" => $validated['nomor_hp'] ?? $keterlambatan->nomor_hp,
            "nama" => $validated['nama'] ?? $keterlambatan->nama,
            "keterangan" => $validated['keterangan'] ?? $keterlambatan->keterangan,
            "gambar" => $validated['gambar'] ?? $keterlambatan->gambar,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keterlambatan $keterlambatan)
    {
        $keterlambatan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.',
        ]);
    }
}
