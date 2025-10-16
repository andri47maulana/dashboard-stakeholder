@extends('layouts.app')

@section('content')
<div class="container">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">Input Laporan Kunjungan</div>
    <div class="card-body">
      <form action="{{ route('laporan.kunjungan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Tanggal Kunjungan</label>
            <input type="date" name="tanggal_kunjungan" class="form-control" required>
          </div>
          <div class="col-md-8">
            <label class="form-label">Lokasi Kunjungan</label>
            <input type="text" name="lokasi" class="form-control" required>
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label">Nama Instansi / Lembaga</label>
          <input type="text" name="nama_instansi" class="form-control" required>
        </div>

        <div class="mt-3">
          <label class="form-label">Tujuan dan Topik Pembahasan</label>
          <textarea name="tujuan_topik" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mt-3">
          <label class="form-label">Keputusan / Hasil Kunjungan</label>
          <textarea name="hasil_keputusan" class="form-control" rows="3"></textarea>
        </div>

        <div class="mt-3">
          <label class="form-label">Rencana Tindak Lanjut</label>
          <textarea name="rencana_tindak_lanjut" class="form-control" rows="3"></textarea>
        </div>

        <div class="mt-3">
          <label class="form-label">PIC Terkait</label>
          <select name="pic_id" class="form-select">
            <option value="">-- Pilih PIC --</option>
            @foreach($users as $u)
              <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="mt-3">
          <label class="form-label">Upload Foto/Dokumen Pendukung</label>
          <input type="file" name="file_pendukung" class="form-control">
          <small class="text-muted">Format: jpg, png, pdf, docx (maks. 5MB)</small>
        </div>

        <div class="text-end mt-4">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
