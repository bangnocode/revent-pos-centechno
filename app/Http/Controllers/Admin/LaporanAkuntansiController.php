<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rekening;
use App\Models\DetailJurnal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanAkuntansiController extends Controller
{
    public function bukuBesar(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $koderId = $request->input('rekening_id'); // Using rekening_id for consistency with existing system while aligning with template logic

        $rekeningList = Rekening::orderBy('kode_rekening')->get();

        // Fetch DetailJurnals
        $query = DetailJurnal::with(['jurnal', 'rekening'])
            ->whereHas('jurnal', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            });

        if ($koderId) {
            $query->where('rekening_id', $koderId);
        }

        $items = $query->get()->sortBy(function ($item) {
            return $item->jurnal->tanggal . ' ' . $item->jurnal->no_jurnal;
        });

        // Pre-calculate starting balances for involved accounts
        $involvedIds = $koderId ? [$koderId] : $items->pluck('rekening_id')->unique()->toArray();
        $baseBalances = [];

        foreach ($involvedIds as $id) {
            $rek = Rekening::find($id);
            if (!$rek) continue;

            $sums = DetailJurnal::where('rekening_id', $id)
                ->whereHas('jurnal', function ($q) use ($startDate) {
                    $q->where('tanggal', '<', $startDate);
                })
                ->select(DB::raw('SUM(debit) as d, SUM(kredit) as k'))
                ->first();

            if ($rek->posisi_rekening == 'A') {
                $baseBalances[$id] = ($sums->d ?? 0) - ($sums->k ?? 0);
            } else {
                $baseBalances[$id] = ($sums->k ?? 0) - ($sums->d ?? 0);
            }
        }

        $laporan = [];
        $currentBalances = $baseBalances;

        foreach ($items as $item) {
            $rid = $item->rekening_id;
            $saldoAwal = $currentBalances[$rid] ?? 0;

            if ($item->rekening->posisi_rekening == 'A') {
                $saldoAkhir = $saldoAwal + ($item->debit - $item->kredit);
            } else {
                $saldoAkhir = $saldoAwal + ($item->kredit - $item->debit);
            }

            $laporan[] = (object)[
                'TGL' => $item->jurnal->tanggal,
                'NOSLIP' => $item->jurnal->no_jurnal,
                'KODER' => $item->rekening->kode_rekening,
                'NAMA_REKENING' => $item->rekening->nama_rekening,
                'KET' => $item->keterangan ?? $item->jurnal->keterangan,
                'DEBIT' => $item->debit,
                'KREDIT' => $item->kredit,
                'SALDOAWAL' => $saldoAwal,
                'SALDO' => $saldoAkhir
            ];

            $currentBalances[$rid] = $saldoAkhir;
        }

        $laporan = collect($laporan);

        return view('admin.akuntansi.laporan.buku_besar', [
            'rekeningList' => $rekeningList,
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'koder' => $koderId
        ]);
    }

    public function neraca(Request $request)
    {
        $perTanggal = $request->input('per_tanggal', date('Y-m-d'));

        // Get all accounts
        $rekenings = Rekening::orderBy('kode_rekening')->get();

        // Pre-calculate balances for leaf nodes (transaksi)
        $balances = DetailJurnal::select('rekening_id', DB::raw('SUM(debit) as total_debit'), DB::raw('SUM(kredit) as total_kredit'))
            ->whereHas('jurnal', function ($q) use ($perTanggal) {
                $q->where('tanggal', '<=', $perTanggal);
            })
            ->groupBy('rekening_id')
            ->get()
            ->keyBy('rekening_id');

        // Assign balances to rekening objects
        foreach ($rekenings as $rek) {
            $rec = $balances->get($rek->id);
            $debit = $rec ? $rec->total_debit : 0;
            $kredit = $rec ? $rec->total_kredit : 0;

            if ($rek->posisi_rekening == 'A') {
                $rek->saldo_akhir = $debit - $kredit;
            } else {
                $rek->saldo_akhir = $kredit - $debit;
            }
        }

        $keyedRekenings = $rekenings->keyBy('kode_rekening');

        // Accumulate from Children to Parent
        foreach ($rekenings as $rek) {
            $parts = explode('-', $rek->kode_rekening);
            if (count($parts) == 2) {
                $prefix = $parts[0];
                $suffix = $parts[1];
                if (strlen($suffix) == 5 && !str_ends_with($suffix, '0000')) {
                    $parentSuffix = substr($suffix, 0, 1) . '0000';
                    $parentCode = $prefix . '-' . $parentSuffix;

                    if (isset($keyedRekenings[$parentCode]) && $parentCode !== $rek->kode_rekening) {
                        $keyedRekenings[$parentCode]->saldo_akhir += $rek->saldo_akhir;
                    }
                }
            }
        }

        // Now update Grandparents from Parents
        foreach ($rekenings as $rek) {
            $parts = explode('-', $rek->kode_rekening);
            if (count($parts) == 2) {
                $prefix = $parts[0];
                $suffix = $parts[1];
                if (str_ends_with($suffix, '0000') && !str_ends_with($suffix, '00000')) {
                    $gpCode = $prefix . '-00000';
                    if (isset($keyedRekenings[$gpCode])) {
                        $keyedRekenings[$gpCode]->saldo_akhir += $rek->saldo_akhir;
                    }
                }
            }
        }

        // Map and format for template
        $formatted = $rekenings->map(function ($rek) {
            $parts = explode('-', $rek->kode_rekening);
            $suffix = $parts[1] ?? '';

            $class = 'level-3';
            if (str_ends_with($suffix, '00000')) {
                $class = 'level-1';
            } elseif (str_ends_with($suffix, '0000')) {
                $class = 'level-2';
            }

            return (object)[
                'ID' => $rek->id,
                'KODER' => $rek->kode_rekening,
                'NAMA' => $rek->nama_rekening,
                'SALDO' => $rek->saldo_akhir,
                'class' => $class,
                'posisi' => $rek->posisi_rekening,
                'tipe' => $rek->tipe_rekening
            ];
        });

        // Filter Aktiva (Assets) and Pasiva (Liabilities + Equity)
        $aktiva = $formatted->where('posisi', 'A');

        // Sum actual transactions for totals
        $totalAktiva = DetailJurnal::whereHas('rekening', function ($q) {
            $q->where('posisi_rekening', 'A');
        })->whereHas('jurnal', function ($q) use ($perTanggal) {
            $q->where('tanggal', '<=', $perTanggal);
        })->select(DB::raw('SUM(debit - kredit) as total'))->first()->total ?? 0;

        $pasiva = $formatted->where('posisi', 'P');
        $totalPasivaReal = DetailJurnal::whereHas('rekening', function ($q) {
            $q->where('posisi_rekening', 'P');
        })->whereHas('jurnal', function ($q) use ($perTanggal) {
            $q->where('tanggal', '<=', $perTanggal);
        })->select(DB::raw('SUM(kredit - debit) as total'))->first()->total ?? 0;

        // The balancing figure
        $totalPasiva = $totalAktiva;

        return view('admin.akuntansi.laporan.neraca', [
            'aktiva' => $aktiva,
            'pasiva' => $pasiva,
            'totalAktiva' => $totalAktiva,
            'totalPasiva' => $totalPasiva,
            'totalPasivaReal' => $totalPasivaReal,
            'perTanggal' => $perTanggal
        ]);
    }
}
