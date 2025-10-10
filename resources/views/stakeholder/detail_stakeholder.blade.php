@extends('layouts.app')
@section('content')
    <h1 class="h3 mb-2 text-gray-800"> DETAIL DATA STAKEHOLDER</h1>
    <p class="mb-4">PT Perkebunan Nusantara I</p>     
    <div class="card shadow mb-4">   
        <div class="card-body">    
            <!-- --------------------------------------------------------------------------------------- -->
            <form action="{{url('/dash/stakeholder')}}" method="get">              
            @csrf
                    <style>
                        .form-group {
                            margin-bottom: 0.5rem!important;
                        }
                        .form-control {
                            font-size: 1.1em!important;
                        }
                    </style>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-info text-white"><i class="fas fa-building"></i> Instansi</div>
                                <div class="card-body" style="font-size:0.9em;">                                    
                                    <p><strong>Region:</strong> {{ $datauser->region}}</p>
                                    <p><strong>Kategori:</strong> <span class="badge badge-success">{{ $datauser->kategori}}</span></p>
                                    <p><strong>Kebun/Unit:</strong> {{ $datauser->kebun}}</p>
                                    <p><strong>Instansi/Lembaga:</strong> {{ $datauser->nama_instansi}}</p>

                                    <p><strong>Daerah:</strong> Prov. {{ $datauser->prov_nama}}, Kab./Kota {{ $datauser->kab_nama}}, Kec. {{ $datauser->kec_nama}}, Desa/Kel. {{ $datauser->desa_nama}}</p>
                                    <p>
                                        <strong>Koordinat (Lat, Long):</strong>
                                        @if(!empty($datauser->latlong))
                                            {{ $datauser->latlong }}
                                            @php
                                                $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($datauser->latlong);
                                            @endphp
                                            <a href="{{ $mapsUrl }}" target="_blank" class="ml-2"><u><i>Buka di Google Maps</i></u></a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                    <p><strong>Dokumen Pendukung:</strong> <a href="{{ asset('pdf/'.$datauser->dokumenpendukung) }}" target="_blank"><u><i>Klik untuk melihat</i></u></a></p>
                                    {{-- <p><strong>Curent Condition:</strong> {{ $datauser->curent_condition ?? '-'}}</p> --}}
                                    <br>
                                    <br>
                                </div>
                            </div>
                            

                            
                        </div>

                        <div class="col-md-6">
                        <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-success text-white"><i class="fas fa-user"></i> PIC</div>
                                <div class="card-body" style="font-size:0.9em;">
                                    <p><strong>Nama PIC:</strong> {{ $datauser->nama_pic}}</p>
                                    <p><strong>Jabatan:</strong> {{ $datauser->jabatan_pic}}</p>
                                    <p><strong>Kontak:</strong> {{ $datauser->nomorkontak_pic}}</p>
                                    <p><strong>Email / Sosial Media:</strong> {{ $datauser->email ?? '-'}}</p>
                                    <p><strong>Nama PIC 2:</strong> {{ $datauser->nama_pic2 ?? '-'}}</p>
                                    <p><strong>Jabatan PIC 2:</strong> {{ $datauser->jabatan_pic2 ?? '-'}}</p>
                                    <p><strong>Kontak PIC 2:</strong> {{ $datauser->nomorkontak_pic2 ?? '-'}}</p>
                                    <p><strong>Email / Sosial Media PIC 2:</strong> {{ $datauser->email ?? '-'}}</p>
                                </div>
                            </div>

                            
                        </div>
                        <div class="col-md-12">
                            <div class="card mb-3 shadow-sm">
                                
                                <div class="card-header bg-secondary text-white"><i class="fas fa-chart-bar"></i> Data Analisis Hubungan Stakeholder</div>
                                <div class="card-body" style="font-size:0.9em;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><strong>Skala Kepentingan: </strong>{{ $datauser->skala_kepentingan ?? '-' }}</p>
                                                <p><strong>Skala Pengaruh: </strong>{{ $datauser->skala_pengaruh ?? '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><strong>Curent Condition: </strong> {{ $datauser->curent_condition ?? '-'}}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><strong>Note:  </strong>{{ $datauser->hasil_skala ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="overflow-x:auto;">
                                                <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%; min-width: 600px;">
                                                    <tr style="background-color: #007BFF; color: white; text-align: center;">
                                                        <th style="width: 33%;">Ekspektasi Stakeholder</th>
                                                        <th style="width: 33%;">Ekspektasi Perusahaan</th>
                                                        <th style="width: 33%;">Saran Bagi Manajemen</th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $datauser->ekspektasi_stakeholder ?? '-' }}</td>
                                                        <td>{{ $datauser->ekspektasi_ptpn ?? '-' }}</td>
                                                        <td>{{ $datauser->saranbagimanajemen ?? '-' }}</td>
                                                    </tr>
                                                </table>
                                            </div>


                                        </div>
                                    </div>               
                                </div>
                            </div>
                            {{-- <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-warning text-white"><i class="fas fa-file-alt"></i> Dokumen Pendukung</div>
                                <div class="card-body" style="font-size:0.9em;">
                                    @if ($datauser->dokumenpendukung)
                                        <embed src="{{ asset('pdf/'.$datauser->dokumenpendukung) }}" 
                                            type="application/pdf" 
                                            width="100%" 
                                            height="400px">
                                    @else
                                        <p>Tidak ada dokumen pendukung.</p>
                                    @endif
                                </div>
                            </div> --}}
                        </div>

                    </div>
                    
                
                <!-- /.card-body -->
                {{-- <div class="card-footer"> --}}
                  <button type="submit" name="submit" class="btn btn-success float-right">
                    Kembali
                    </button>
                {{-- </div> --}}
              </form>
        </div>
    </div>
@endsection