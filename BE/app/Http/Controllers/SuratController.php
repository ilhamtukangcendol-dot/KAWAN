<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Warga;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role <= 2) {
            $suratList = Surat::with('user', 'warga')->latest()->paginate(15);
        } else {
            $suratList = Surat::with('user', 'warga')
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(15);
        }

        $wargaList = Warga::orderBy('nama_lengkap', 'asc')->get();

        return view('surat.index', compact('suratList', 'wargaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id'   => 'nullable|exists:warga,id',
            'jenis_surat'=> 'required|string|max:255',
            'keperluan'  => 'required|string',
        ]);

        $surat = Surat::create([
            'user_id'           => auth()->id(),
            'warga_id'          => $validated['warga_id'] ?? null,
            'jenis_surat'       => $validated['jenis_surat'],
            'keperluan'         => $validated['keperluan'],
            'status'            => 'pending',
            'tanggal_pengajuan' => now()->format('Y-m-d'),
        ]);

        ActivityLog::log('Pengajuan Surat RT', 'Permohonan surat ID #' . $surat->id . ' (' . $surat->jenis_surat . ')');

        return redirect()->route('surat.index')->with('success', 'Permohonan surat keterangan berhasil dikirimkan.');
    }

    public function approve(Request $request, Surat $surat)
    {
        $request->validate([
            'no_surat'   => 'required|string|max:100',
            'catatan_rt' => 'nullable|string',
        ]);

        $surat->update([
            'no_surat'          => $request->no_surat,
            'catatan_rt'        => $request->catatan_rt,
            'status'            => 'approved',
            'tanggal_disetujui' => now()->format('Y-m-d'),
        ]);

        ActivityLog::log('Persetujuan Surat RT', 'Menyetujui permohonan surat ID #' . $surat->id);

        return redirect()->back()->with('success', 'Surat berhasil disetujui dan ditandatangani.');
    }

    public function reject(Request $request, Surat $surat)
    {
        $request->validate(['catatan_rt' => 'required|string']);

        $surat->update([
            'catatan_rt' => $request->catatan_rt,
            'status'     => 'rejected',
        ]);

        ActivityLog::log('Penolakan Surat RT', 'Menolak permohonan surat ID #' . $surat->id);

        return redirect()->back()->with('success', 'Permohonan surat ditolak dengan alasan.');
    }

    public function destroy(Surat $surat)
    {
        $surat->delete();
        ActivityLog::log('Hapus Pengajuan Surat', 'Menghapus data permohonan surat ID #' . $surat->id);

        return redirect()->route('surat.index')->with('success', 'Data permohonan surat berhasil dihapus.');
    }
}
