<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Siswa;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Auth;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Perizinan::with(['created_by', 'updated_by', 'siswa.jurusan', 'siswa.orangtua']);

        if ($user) {
            if ($user->role === User::ROLE_GURU) {
                $query->whereHas('siswa', function ($q) use ($user) {
                    $q->where('jurusan_id', $user->jurusan_id);
                });
            } elseif ($user->role === User::ROLE_ORANGTUA) {
                $query->whereHas('siswa', function ($q) use ($user) {
                    $q->where('orangtua_id', $user->id);
                });
            }
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('date', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function ($sq) use ($search) {
                      $sq->where('nama', 'like', "%{$search}%")
                         ->orWhere('nis', 'like', "%{$search}%");
                  });
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
            "siswa_id" => "required|exists:siswas,id",
            "keterangan" => "required|string",
            "date" => "required",
            "gambar" => "required|image|mimes:jpeg,png,jpg",
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/perizinan/'), $filename);
            $validated['gambar'] = 'storage/uploads/perizinan/' . $filename;
        }

        if (Auth::check()) {
            $validated['created_by'] = Auth::id();
        }

        $perizinan = Perizinan::create($validated);

        // Kirim notifikasi HANYA ke Petugas Piket
        try {
            $siswa = Siswa::find($validated['siswa_id']);
            $namaSiswa = $siswa ? $siswa->nama : 'Siswa';
            $piketUserIds = User::whereIn('role', [User::ROLE_GURU_PIKET])
                ->orWhere('role', 'piket')
                ->pluck('id')
                ->toArray();

            if (!empty($piketUserIds)) {
                $fcmService = app(FirebaseNotificationService::class);
                $title = 'Pengajuan Perizinan Baru';
                $body = "Pengajuan izin baru untuk {$namaSiswa}. Silakan lakukan verifikasi.";
                $fcmService->sendToUsers(
                    $piketUserIds,
                    $title,
                    $body,
                    [
                        'type' => 'perizinan',
                        'reference_id' => (string) $perizinan->id,
                    ]
                );
            }
        } catch (\Throwable $e) {
            \Log::error('Gagal mengirim notifikasi perizinan ke piket: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Perizinan $perizinan)
    {
        $data = Perizinan::with(['created_by', 'updated_by', 'siswa.jurusan', 'siswa.orangtua'])->where('id', $perizinan->id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perizinan $perizinan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perizinan $perizinan)
    {
        if ($perizinan->status !== Perizinan::STATUS_PENDING) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data perizinan hanya dapat diedit jika status masih menunggu persetujuan.',
            ], 422);
        }

        $validated = $request->validate([
            "siswa_id" => "required|exists:siswas,id",
            "keterangan" => "required|string",
            "date" => "required",
            "gambar" => "nullable|image|mimes:jpeg,png,jpg",
        ]);

        if ($request->hasFile('gambar')) {
            if ($perizinan->gambar && file_exists(public_path($perizinan->gambar))) {
                unlink(public_path($perizinan->gambar));
            }
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/perizinan/'), $filename);
            $validated['gambar'] = 'storage/uploads/perizinan/' . $filename;
        }

        if (Auth::check()) {
            $validated['updated_by'] = Auth::id();
        }

        $perizinan->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
            'data' => $perizinan->load(['created_by', 'updated_by', 'siswa.jurusan', 'siswa.orangtua']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perizinan $perizinan)
    {
        $perizinan->delete();

        if ($perizinan->gambar && file_exists(public_path($perizinan->gambar))) {
            unlink(public_path($perizinan->gambar));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.',
        ]);
    }

    public function status(Request $request, Perizinan $perizinan)
    {
        $validated = $request->validate([
            "status" => "required|in:" . Perizinan::STATUS_REJECTED . "," . Perizinan::STATUS_APPROVED,
        ]);

        if ($perizinan->status != Perizinan::STATUS_PENDING) {
            return response()->json([
                'status' => 'error',
                'message' => 'Status perizinan sudah diperbarui sebelumnya.',
            ], 400);
        };

        $updateData = [
            "status" => $validated['status'],
        ];

        if (Auth::check()) {
            $updateData['updated_by'] = Auth::id();
        }

        $perizinan->update($updateData);

        // Kirim notifikasi HANYA ke Orang Tua Siswa
        try {
            $perizinan->loadMissing('siswa');
            $siswa = $perizinan->siswa;
            if ($siswa && $siswa->orangtua_id) {
                $statusText = $validated['status'] === Perizinan::STATUS_APPROVED ? 'disetujui' : 'ditolak';
                $fcmService = app(FirebaseNotificationService::class);
                $title = 'Status Perizinan Siswa';
                $body = "Pengajuan izin untuk {$siswa->nama} telah {$statusText}.";
                $fcmService->sendToUser(
                    (int) $siswa->orangtua_id,
                    $title,
                    $body,
                    [
                        'type' => 'perizinan',
                        'reference_id' => (string) $perizinan->id,
                    ]
                );
            }
        } catch (\Throwable $e) {
            \Log::error('Gagal mengirim notifikasi status perizinan ke orang tua: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    public function export_excel(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Perizinan::query();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $perizinans = $query->with(['siswa', 'created_by'])->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PerizinanExport($perizinans),
            'perizinan_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}
