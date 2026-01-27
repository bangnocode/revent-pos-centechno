<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\DetailJurnal;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JurnalController extends Controller
{
    public function index()
    {
        // When opening Jurnal Umum index, show the Create form as requested
        return $this->create();
    }

    public function create()
    {
        // Fetch all rekenings so the user can see their data is connected
        $rekenings = Rekening::orderBy('kode_rekening')->get();

        return view('admin.akuntansi.jurnal.create', compact('rekenings'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'keterangan_jurnal' => 'required|string',
            'details' => 'required|array|min:2', // At least debit and credit
            'details.*.rekening_id' => 'required|exists:rekenings,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.kredit' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validate Balance
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($request->details as $detail) {
            $totalDebit += $detail['debit'];
            $totalKredit += $detail['kredit'];
        }

        if (abs($totalDebit - $totalKredit) > 0.01) { // Float tolerance
            return response()->json(['message' => 'Jurnal tidak balance. Debit: ' . $totalDebit . ', Kredit: ' . $totalKredit], 422);
        }

        DB::beginTransaction();
        try {
            // Generate No Jurnal: J-YYYYMMDD-XXXX
            $dateClean = str_replace('-', '', $request->tanggal); // YYYYMMDD
            $prefix = 'J-' . $dateClean . '-';
            $lastJurnal = Jurnal::where('no_jurnal', 'like', $prefix . '%')
                ->orderBy('no_jurnal', 'desc')
                ->first();

            if ($lastJurnal) {
                $lastNumber = intval(substr($lastJurnal->no_jurnal, -4));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $noJurnal = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            $jurnal = Jurnal::create([
                'no_jurnal' => $noJurnal,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan_jurnal,
            ]);

            foreach ($request->details as $detail) {
                // Ignore if 0? No, user might input 0 for some reason but usually no. 
                // But cart logic might allow adding empty lines.
                // We will save if either debit or credit > 0.
                if ($detail['debit'] > 0 || $detail['kredit'] > 0) {
                    DetailJurnal::create([
                        'jurnal_id' => $jurnal->id,
                        'rekening_id' => $detail['rekening_id'],
                        'debit' => $detail['debit'],
                        'kredit' => $detail['kredit'],
                        'keterangan' => $detail['keterangan'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Jurnal berhasil disimpan',
                'redirect_url' => route('admin.jurnal.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
