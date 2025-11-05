<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PembayaranExport implements FromCollection, WithHeadings, WithMapping
{
  protected $filters;

  public function __construct($filters = [])
  {
    $this->filters = $filters;
  }

  public function collection()
  {
    $query = Pembayaran::with(['santri', 'admin']);

    if (isset($this->filters['dari'])) {
      $query->whereDate('tanggal_bayar', '>=', $this->filters['dari']);
    }

    if (isset($this->filters['sampai'])) {
      $query->whereDate('tanggal_bayar', '<=', $this->filters['sampai']);
    }

    if (isset($this->filters['jenis'])) {
      $query->where('jenis_pembayaran', $this->filters['jenis']);
    }

    return $query->orderBy('tanggal_bayar', 'desc')->get();
  }

  public function headings(): array
  {
    return [
      'Tanggal',
      'NIS',
      'Nama Santri',
      'Jenis Pembayaran',
      'Bulan',
      'Jumlah',
      'Metode',
      'Status',
      'Admin',
    ];
  }

  public function map($pembayaran): array
  {
    return [
      $pembayaran->tanggal_bayar->format('d/m/Y'),
      $pembayaran->santri->nomor_induk,
      $pembayaran->santri->nama_lengkap,
      $pembayaran->jenis_pembayaran_label,
      $pembayaran->bulan_bayar ? \Carbon\Carbon::parse($pembayaran->bulan_bayar)->format('M Y') : '-',
      $pembayaran->jumlah,
      ucfirst($pembayaran->metode_pembayaran),
      ucfirst($pembayaran->status),
      $pembayaran->admin->username ?? '-',
    ];
  }
}
