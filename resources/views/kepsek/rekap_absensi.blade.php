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

            {{-- Filter Bulan & Export Excel --}}
            <div class="card shadow-sm mb-4" style="border-left: 4px solid #007bff;">
              <div class="card-body">
                {{-- Form Filter Bulan --}}
                <form method="GET" action="{{ route('kepsek.rekap') }}">
                  <div class="form-group">
                    <label for="bulan" class="font-weight-bold">Pilih Bulan :</label>
                    <div class="input-group">
                      <input type="month" name="bulan" id="bulan" class="form-control"
                        value="{{ $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) }}">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                          <i class="fas fa-search mr-1"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </form>

                {{-- Rekap Bulan & Export --}}
                <div class="mt-4">
                  <label class="font-weight-bold d-block">Rekap Bulan :</label>
                  <p class="text-muted mb-2">
                    {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
                  </p>
                  <a href="{{ route('rekap-absensi.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                    class="btn btn-success">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                  </a>
                </div>
              </div>
            </div>

            <div class="card shadow-sm">
            <div class="card-header bg-light">
              <h3 class="card-title font-weight-bold">
                <i class="fas fa-table mr-2"></i>Data Kehadiran Guru
              </h3>
            </div>
            <div class="card-body">
              {{-- Tabel --}}
              <div class="table-responsive">
                <table class="table table-hover text-nowrap">
                  <thead class="thead-light">
                    <tr>
                      <th>No</th>
                      <th><i class="fas fa-user mr-1 text-secondary"></i> Nama Guru</th>
                      <th><i class="fas fa-check-circle mr-1 text-success"></i> Hadir Lengkap</th>
                      <th><i class="fas fa-clock mr-1 text-warning"></i> Belum Absen Pulang </th>
                      <th>Izin</th>
                      <th>Sakit</th>
                      <th>Alfa</th>
                      <th>Persentase</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($rekap as $index => $item)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['nama_guru'] }}</td>
                        <td><span class="badge badge-success">{{ $item['hadir_lengkap'] ?? 0 }}</span></td>
                        <td><span class="badge badge-warning">{{ $item['hadir_belum_lengkap'] ?? 0 }}</span></td>
                        <td><span class="badge badge-info">{{ $item['izin'] }}</span></td>
                        <td><span class="badge badge-primary">{{ $item['sakit'] }}</span></td>
                        <td><span class="badge badge-danger">{{ $item['alfa'] }}</span></td>
                        <td>{{ $item['persentase'] }}%</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="9" class="text-center text-muted">Tidak ada data absensi pada bulan ini.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
                <p class="text-muted mt-3">
                  Total Guru: <strong>{{ count($rekap) }}</strong> |
                  Rata-rata Kehadiran: 
                  <strong>{{ round(collect($rekap)->avg('persentase'), 2) }}%</strong>
                </p>
              </div>
            </div>
          </div>

          {{-- Filter Bulan --}}
          <!-- <form method="GET" action="{{ route('kepsek.rekap') }}">
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
                  <th>Hadir Lengkap</th>
                  <th>Hadir Belum Lengkap</th>
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
                    <td>{{ $item['hadir_lengkap'] ?? 0 }}</td>
                    <td>{{ $item['hadir_belum_lengkap'] ?? 0 }}</td>
                    <td>{{ $item['izin'] }}</td>
                    <td>{{ $item['sakit'] }}</td>
                    <td>{{ $item['alfa'] }}</td>
                    <td>{{ $item['total'] }}</td>
                    <td>{{ $item['persentase'] }}%</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="text-center text-muted">Tidak ada data absensi pada bulan ini.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div> -->
        </div>
      </div>
    </div>
  </section>
</div>
@endsection