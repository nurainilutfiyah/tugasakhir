<?php

namespace App\Exports;

use App\Models\Laporan;
use App\Models\Proposal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    //     return Proposal::all();
    // }
    public function query()
    {
        return Laporan::when(request('search'), function($query) {
            $query->where('judul', 'like', '%' . request('search') . '%');
        })->select(
            "laporans.judul",
            "laporans.file",
            "laporans.tanggal",
            "statuses.status as status",
            "users.name as nama_dinas",
            // "perusahaans.name as nama_perusahaan"
            "perusahaans.name as nama_perusahaan",
        )
        ->join("statuses", "laporans.id_status", "=", "statuses.id")
        ->join("users", "laporans.id_dinas", "=", "users.id")
        ->join("perusahaans", "laporans.id_perusahaan", "=", "perusahaans.id");
    }

    public function headings(): array
    {
        return [
            'judul',
            'file',
            'tanggal',
            'status',
            'nama_dinas',
            'nama_perusahaan',
        ];
    }
}
