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
            "keterangan" => "required|string",
            "gambar" => "required|image|mimes:jpg,jpeg,png",
            "siswa_id" => "required|exists:siswas,id",
            "date" => "required"
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
        $data = Keterlambatan::with(['created_by', 'siswa.jurusan', 'siswa.orangtua'])->where('id', $keterlambatan->id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keterlambatan $keterlambatan)
    {
        $keterlambatan->delete();

        if ($keterlambatan->gambar && file_exists(public_path($keterlambatan->gambar))) {
            unlink(public_path($keterlambatan->gambar));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.',
        ]);
    }


    

    public function export_excel(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Keterlambatan::query();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $keterlambatans = $query->with(['siswa', 'created_by'])->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\KeterlambatanExport($keterlambatans),
            'Keterlambatan_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}
