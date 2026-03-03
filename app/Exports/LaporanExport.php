<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode',
            'Nama Peminjam',
            'Nama Alat',
            'Kategori',
            'Tgl Pinjam',
            'Tgl Kembali',
            'Status',
        ];
    }

    public function map($peminjaman): array
    {
        static $no = 1;
        return [
            $no++,
            $peminjaman->kode,
            $peminjaman->peminjam->name,
            $peminjaman->alat->nama,
            $peminjaman->alat->kategoriAlat->nama_kategori_alat,
            $peminjaman->tanggal_pinjam,
            $peminjaman->tanggal_kembali ?? '-',
            $peminjaman->status,
        ];
    }
}
