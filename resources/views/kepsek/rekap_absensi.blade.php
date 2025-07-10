@extends('kepsek.layout')

@section('title', 'Rekap Absensi Guru')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>Rekap Absensi Guru</h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-body">

          {{-- Filter Bulan --}}
          <form method="GET" action="{{ route('kepsek.rekap') }}">
            <div class="row align-items-end mb-4">
                <div class="col-md-4 col-sm-6">
                <label for="bulan" class="font-weight-bold">Pilih Bulan:</label>
                <input type="month" name="bulan" id="bulan" class="form-control"
                    value="{{ $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) }}">
                </div>
                <div class="col-md-auto col-sm-12 mt-sm-3 mt-md-0">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search mr-1"></i> Tampilkan
                </button>
                </div>
            </div>
            </form>

            <a href="{{ route('rekap-absensi.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-success mb-3">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>

          {{-- Tabel --}}
          <div class="table-responsive">
            <table class="table table-hover text-nowrap">
              <thead class="thead-light">
                <tr>
                  <th>No</th>
                  <th>Nama Guru</th>
                  <th>Hadir</th>
                  <th>Izin</th>
                  <th>Sakit</th>
                  <th>Alfa</th>
                  <th>Total Kehadiran</th>
                  <th>Persentase</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($rekap as $index => $item)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $item['nama_guru'] }}</td>
                  <td>{{ $item['hadir'] }}</td>
                  <td>{{ $item['izin'] }}</td>
                  <td>{{ $item['sakit'] }}</td>
                  <td>{{ $item['alfa'] }}</td>
                  <td>{{ $item['total'] }}</td>
                  <td>{{ $item['persentase'] }}%</td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="text-center text-muted">Tidak ada data absensi pada bulan ini.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </section>
</div>
@endsection
