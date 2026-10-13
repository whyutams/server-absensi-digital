<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jurusan::with(['created_by', 'updated_by']);

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alias', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        $perPage = $request->query('per_page', 15);
        if ($perPage === 'all') {
            $data = $query->get();
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'total' => $data->count(),
            ]);
        }

        $perPage = max(1, (int)$perPage);
        $paginated = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $paginated->items(),
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'per_page' => $paginated->perPage(),
            'total' => $paginated->total(),
            'has_more' => $paginated->hasMorePages(),
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
            'alias' => 'nullable|string|unique:jurusans,alias',
            'deskripsi' => 'nullable|string',
        ]);

        if (isset($validated['alias'])) {
            $validated['alias'] = trim($validated['alias']) !== '' ? trim($validated['alias']) : null;
        } else {
            $validated['alias'] = null;
        }

        if (Auth::check()) {
            $validated['created_by'] = Auth::id();
        }

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
        return response()->json([
            'status' => 'success',
            'data' => $jurusan->load(['created_by', 'updated_by']),
        ]);
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
            'nama' => 'nullable|string|unique:jurusans,nama,' . $jurusan->id,
            'alias' => 'nullable|string|unique:jurusans,alias,' . $jurusan->id,
            'deskripsi' => 'nullable|string',
        ]);

        $updateData = [
            'nama' => $request['nama'] ?? $jurusan->nama,
            'deskripsi' => $request['deskripsi'] ?? $jurusan->deskripsi,
        ];

        if ($request->has('alias')) {
            $aliasVal = trim($request['alias'] ?? '');
            $updateData['alias'] = $aliasVal !== '' ? $aliasVal : null;
        }

        if (Auth::check()) {
            $updateData['updated_by'] = Auth::id();
        }

        $jurusan->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
            'data' => $jurusan->load(['created_by', 'updated_by']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurusan $jurusan)
    {
        try {
            $jurusan->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data jurusan tidak dapat dihapus karena masih terkait dengan data lain.'
            ], 422);
        }
    }
}
