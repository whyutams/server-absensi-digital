<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Keterlambatan;
use App\Models\Perizinan;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller
{
    public function info()
    {
        $kelas = Kelas::with('jurusan')->latest()->get();
        $jurusan = Jurusan::latest()->get();
        $keterlambatan = Keterlambatan::with(['created_by', 'siswa.jurusan', 'siswa.orangtua'])->latest()->get();
        $perizinan = Perizinan::with(['created_by', 'siswa.jurusan', 'siswa.orangtua'])->latest()->get();
        $siswa = Siswa::with(['jurusan', 'orangtua'])->latest()->get();
        $user = User::with(['jurusan'])->where('role', '!=', User::ROLE_SUPERADMIN)->where('role', '!=', User::ROLE_KEPSEK);

        $data = [];

        if (Auth::user()->role == User::ROLE_GURU_PIKET || Auth::user()->role == User::ROLE_KESISWAAN || Auth::user()->role == User::ROLE_GURU) {
            $keterlambatan = [];
            $perizinan = [];

            if (Auth::user()->role == User::ROLE_GURU_PIKET || Auth::user()->role == User::ROLE_KESISWAAN) {
                $keterlambatan = Keterlambatan::with(['created_by', 'siswa.jurusan', 'siswa.orangtua'])->latest()->get();
                $perizinan = Perizinan::with(['created_by', 'siswa.jurusan', 'siswa.orangtua'])->latest()->get();
            } else {
                $keterlambatan = Keterlambatan::with(['created_by', 'siswa.jurusan', 'siswa.orangtua'])
                    ->whereHas('siswa', function ($query) {
                        $query->where('jurusan_id', Auth::user()->jurusan_id);
                    })
                    ->latest()
                    ->get();

                $perizinan = Perizinan::with(['created_by', 'siswa.jurusan', 'siswa.orangtua'])
                    ->whereHas('siswa', function ($query) {
                        $query->where('jurusan_id', Auth::user()->jurusan_id);
                    })
                    ->latest()
                    ->get();

                $siswa = Siswa::with(['jurusan', 'orangtua'])->where('jurusan_id', Auth::user()->jurusan_id)->latest()->get();
            }

            $data = [
                'data' => [
                    'kelas' => $kelas,
                    'jurusan' => $jurusan,
                    'keterlambatan' => $keterlambatan,
                    'perizinan' => $perizinan,
                    'siswa' => $siswa,
                ],
                'counts' => [
                    'total_kelas' => count($kelas),
                    'total_jurusan' => count($jurusan),
                    'total_keterlambatan' => count($keterlambatan),
                    'total_perizinan' => count($perizinan),
                    'total_siswa' => count($siswa),
                ],
            ];
        } else if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_KEPSEK) {
            $data = [
                'data' => [
                    'kelas' => $kelas,
                    'jurusan' => $jurusan,
                    'keterlambatan' => $keterlambatan,
                    'perizinan' => $perizinan,
                    'siswa' => $siswa,
                    'user' => $user->latest()->get(),
                    'user_orangtua' => $user->where('role', User::ROLE_ORANGTUA)->latest()->get(),
                    'user_guru' => $user->where('role', User::ROLE_GURU)->latest()->get(),
                ],
                'counts' => [
                    'total_kelas' => count($kelas),
                    'total_jurusan' => count($jurusan),
                    'total_keterlambatan' => count($keterlambatan),
                    'total_perizinan' => count($perizinan),
                    'total_siswa' => count($siswa),
                    'total_user' => count($user->latest()->get()),
                    'total_user_guru' => count($user->where('role', User::ROLE_GURU)->latest()->get()),
                    'total_user_orangtua' => count($user->where('role', User::ROLE_ORANGTUA)->latest()->get()),
                ],
            ];
        } else {
            $keterlambatan = Keterlambatan::with(['created_by', 'siswa.jurusan', 'siswa.orangtua'])
                ->whereHas('siswa', function ($query) {
                    $query->where('orangtua_id', Auth::user()->id);
                })
                ->latest()
                ->get();
            $perizinan = Perizinan::with(['created_by', 'siswa.jurusan', 'siswa.orangtua'])
                ->whereHas('siswa', function ($query) {
                    $query->where('orangtua_id', Auth::user()->id);
                })
                ->latest()
                ->get();

            $data = [
                'data' => [
                    'keterlambatan' => $keterlambatan,
                    'perizinan' => $perizinan,
                ],
                'counts' => [
                    'total_keterlambatan' => count($keterlambatan),
                    'total_perizinan' => count($perizinan),
                ],
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
