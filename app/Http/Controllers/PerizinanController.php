<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validated= $request->validate([
          "nis" => "required|string|max:255",
          "nomor_hp" => "required|string|max:15",
          "nama" => "required|string|max:255",
          "gambar" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
        ]);

        if($request->hasFile('gambar')){
          $file = $request->file('gambar');
          $filename = time().'_'.$file->getClientOriginalName();
          $file->move(public_path('storage/uploads/perizinan/'), $filename);
          $validated['gambar'] = 'storage/uploads/perizinan/'.$filename;
        }

        Perizinan::create($validated);

        return response()->json([
          'status' => 'success',
          'message' => 'Data Berhasil DiTambahkan.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
