<?php

namespace App\Exports;

use App\Models\Proposal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProposalDiterimaExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    //     return Proposal::all();
    // }
    public function __construct(string $keyword)
    {
        $this->id_status = $keyword;
    }
    public function query()
    {
        return Proposal::when(request('search'), function($query) {
            $query->where('judul', 'like', '%' . request('search') . '%');
        })->select(
            "proposals.judul",
            "proposals.file",
            "proposals.tanggal",
            "statuses.status as status",
            "users.name as nama_dinas",
            "perusahaans.name as nama_perusahaan",
        )
        ->join("statuses", "proposals.id_status", "=", "statuses.id")
        ->join("users", "proposals.id_dinas", "=", "users.id")
        ->join("perusahaans", "proposals.id_perusahaan", "=", "perusahaans.id")
        ->where("id_status", "=", 3) ;
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
