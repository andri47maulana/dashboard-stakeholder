@extends('layouts.app')

@section('content')
<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">📋 Daftar Laporan Kunjungan</h5>
    <a href="{{ route('laporan.kunjungan.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-circle me-1"></i> Tambah Laporan
    </a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
          <thead class="table-primary">
            <tr>
              <th width="5%">No</th>
              <th>Tanggal</th>
              <th>Lokasi</th>
              <th>Instansi / Lembaga</th>
              <th>Tujuan & Topik</th>
              <th>Hasil / Keputusan</th>
              <th>Tindak Lanjut</th>
              <th>Dokumen</th>
              <th width="10%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($laporans as $index => $laporan)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($laporan->tanggal_kunjungan)->format('d M Y') }}</td>
                <td>{{ $laporan->lokasi }}</td>
                <td>{{ $laporan->nama_instansi }}</td>
                <td>{{ Str::limit($laporan->tujuan_topik, 50) }}</td>
                <td>{{ Str::limit($laporan->hasil_keputusan, 50) }}</td>
                <td>{{ Str::limit($laporan->rencana_tindak_lanjut, 50) }}</td>
                <td>
                  @if ($laporan->file_pendukung)
                    <a href="{{ asset('storage/laporan_kunjungan/' . $laporan->file_pendukung) }}" 
                       target="_blank" class="btn btn-sm btn-outline-info">
                      <i class="bi bi-file-earmark-text"></i> Lihat
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td class="text-center">
                  <a href="{{ route('laporan.kunjungan.reminder', $laporan->id) }}" 
                     class="btn btn-sm btn-warning">
                    <i class="bi bi-bell"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center text-muted py-3">
                  Belum ada laporan kunjungan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
