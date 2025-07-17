@extends('guru.layout')

@section('title', 'Home')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Penilaian - SIPENA</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Penilaian</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main Conten -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">

          <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center ml-auto">
                  <!-- Search box -->
                  <div style="width: 170px;">
                    <div class="input-group input-group-sm">
                      <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Spacer -->
                  <div style="width: 10px;"></div>

                  <!-- Button Tambah -->
                  <button class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#tambahNilaiModal">
                    <i class="fas fa-plus"></i> Tambah
                  </button>

                  <!-- Button Rekap -->
                  <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#rekapModal">
                    <i class="fas fa-file-csv"></i> Rekap Nilai
                  </button>

                </div>
              </div>
              <div class="card-body table-responsive p-0">

                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Siswa</th>
                      <th>Kelas</th>
                      <th>Tanggal Dinilai</th>
                      <th>Deskripsi Tugas</th>
                      <th>Nilai</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($nilaiHarian as $index => $nilai)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $nilai->siswa->nama }}</td>
                        <td>{{ $nilai->siswa->kelas->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($nilai->tanggal)->format('d-m-Y') }}</td>
                        <td style="min-width: 200px;">
                          @php
                            $mapelNama = $nilai->mapel->nama_mapel ?? '?';
                            $deskripsi = $nilai->deskripsi ?? '-';
                            $showModal = strlen($deskripsi) > 5 || strlen($mapelNama) > 5;
                          @endphp

                          <div class="d-flex justify-content-between align-items-center w-100">
                            <div class="text-truncate" style="max-width: 150px; overflow: hidden;">
                              <strong class="text-muted">[{{ Str::limit($mapelNama, 5) }}]</strong>
                              {{ Str::limit($deskripsi, 5) }}
                            </div>

                            @if($showModal)
                              <button type="button" class="btn btn-link p-0 ml-2" 
                                      onclick="$('#modalDeskripsi{{ $nilai->id }}').modal('show')" 
                                      title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                              </button>
                            @endif
                          </div>
                        </td>
                        <td>{{ $nilai->nilai }}</td>
                        <td>
                          <div class="d-flex align-items-center">
                            <form action="{{ route('guru.penilaian.destroy', $nilai->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus nilai ini?')" class="mr-1">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                              </button>
                            </form>
                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $nilai->id }}" title="Edit">
                              <i class="fas fa-pen"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>

              </div>
            </div>

            <!-- Modal Tambah -->
            <div class="modal fade" id="tambahNilaiModal" tabindex="-1" role="dialog" aria-labelledby="tambahNilaiModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <form action="{{ route('guru.penilaian.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                      <h5 class="modal-title font-weight-bold" id="tambahNilaiModalLabel">Tambah Nilai Harian Siswa</h5>
                      <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- Upload File -->
                      <div class="form-group">
                        <label for="file_nilai">Upload File Excel (opsional)</label>
                        <input type="file" class="form-control-file" id="file_nilai" name="file_nilai" accept=".xlsx, .xls">
                        <small class="form-text text-muted">Jika Anda upload file, form di bawah bisa dikosongkan.</small>
                      </div>

                      <hr>

                      <!-- Manual Input -->
                      <div class="form-group">
                        <label for="siswa_id">Pilih Siswa</label>
                        <select name="siswa_id" id="siswa_id" class="form-control">
                          <option value="">-- Pilih Siswa --</option>
                          @foreach ($siswa as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->kelas->nama }})</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="mapel_id">Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel_id" class="form-control" required>
                          <option value="">-- Pilih Mapel --</option>
                          @foreach ($mapels as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="deskripsi">Deskripsi Tugas</label>
                        <input type="text" name="deskripsi" id="deskripsi" class="form-control" placeholder="Contoh: Tugas 1 - Materi IPA">
                      </div>

                      <div class="form-group">
                        <label for="nilai">Nilai</label>
                        <input type="number" name="nilai" id="nilai" class="form-control" placeholder="Contoh: 85" min="0" max="100">
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">
                          <i class="fas fa-times mr-1"></i> Batal
                      </button>
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div> 
            <!-- Input Modal End -->

            <!-- Modal Deskripsi (dikeluarin dari table) -->
            @foreach($nilaiHarian as $nilai)
            @php
              $mapelNama = $nilai->mapel->nama_mapel ?? '?';
              $deskripsi = $nilai->deskripsi ?? '-';
              $showModal = strlen($deskripsi) > 5 || strlen($mapelNama) > 5;
            @endphp

            @if($showModal)
              <div class="modal fade" id="modalDeskripsi{{ $nilai->id }}" tabindex="-1" role="dialog" aria-labelledby="deskripsiLabel{{ $nilai->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                  <div class="modal-content border-info shadow rounded-lg">

                    {{-- Header --}}
                    <div class="modal-header bg-info text-white rounded-top">
                      <h5 class="modal-title font-weight-bold" id="deskripsiLabel{{ $nilai->id }}">
                        <i class="fas fa-book-open mr-2"></i> Detail Nilai Harian
                      </h5>
                      <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    {{-- Body --}}
                    <div class="modal-body">
                      <div class="card card-outline card-info mb-0">
                        <div class="card-body pb-2">

                          <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                              <i class="fas fa-chalkboard-teacher mr-1"></i> Mata Pelajaran
                            </small>
                            <p class="mb-0 font-weight-semibold text-dark text-left">
                              {{ $mapelNama }}
                            </p>
                          </div>

                          <div>
                            <small class="text-muted d-block mb-1">
                              <i class="fas fa-align-left mr-1"></i> Deskripsi
                            </small>
                            <p class="mb-0 font-weight-semibold text-dark text-left">
                              {{ $deskripsi }}
                            </p>
                          </div>

                        </div>
                      </div>
                    </div>

                    {{-- Divider --}}
                    <hr class="my-0">

                    {{-- Footer --}}
                    <div class="modal-footer justify-content-end px-4 py-3">
                      <button type="button" class="btn btn-outline-info" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-1"></i> Tutup
                      </button>
                    </div>

                  </div>
                </div>
              </div>
            @endif
          @endforeach

            <!-- Modal Edit (juga dipindah ke luar table) -->
            @foreach($nilaiHarian as $nilai)
              <div class="modal fade" id="editModal{{ $nilai->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $nilai->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                  <div class="modal-content shadow-lg">
                    <form action="{{ route('guru.penilaian.update', $nilai->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title font-weight-bold" id="editModalLabel{{ $nilai->id }}">
                          <i class="fas fa-pen mr-2"></i>Edit Nilai Siswa
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label class="font-weight-bold">Nama Siswa</label>
                            <input type="text" class="form-control" value="{{ $nilai->siswa->nama }}" readonly>
                          </div>
                          <div class="form-group col-md-6">
                            <label class="font-weight-bold">Kelas</label>
                            <select name="kelas_id" class="form-control" disabled>
                              @foreach($kelases as $kelas)
                                <option value="{{ $kelas->id }}" {{ $nilai->siswa->kelas_id == $kelas->id ? 'selected' : '' }}>
                                  {{ $kelas->nama }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label class="font-weight-bold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ \Carbon\Carbon::parse($nilai->tanggal)->format('Y-m-d') }}" required>
                          </div>
                          <div class="form-group col-md-6">
                            <label class="font-weight-bold">Deskripsi Tugas</label>
                            <input type="text" name="deskripsi_tugas" class="form-control" value="{{ $nilai->deskripsi }}" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label class="font-weight-bold">Nilai</label>
                            <input type="number" name="nilai" class="form-control" value="{{ $nilai->nilai }}" min="0" max="100" required>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer bg-light">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Batal</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach

            <!-- MODAL: Rekap Nilai -->
            <div class="modal fade" id="rekapModal" tabindex="-1" role="dialog" aria-labelledby="rekapModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-sm" role="document">
                <form action="{{ route('guru.penilaian.rekap') }}" method="GET">
                  <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                      <h5 class="modal-title font-weight-bold" id="rekapModalLabel">
                        <i class="fas fa-file-csv mr-2"></i>Rekap Nilai Siswa
                      </h5>
                      <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <div class="form-group">
                        <label for="kelas_id">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control" required>
                          <option value="">-- Pilih Kelas --</option>
                          @foreach ($kelases as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="mapel_id">Pilih Mapel</label>
                        <select name="mapel_id" id="mapel_id" class="form-control" required>
                          <option value="">-- Pilih Mapel --</option>
                          @foreach ($mapels as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="modal-footer bg-light">
                      <button type="submit" class="btn btn-success">
                        <i class="fas fa-download mr-1"></i> Download CSV
                      </button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <!-- Modal Alert -->
            <div class="modal fade" id="rekapKosongModal" tabindex="-1" role="dialog" aria-labelledby="rekapKosongLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white" id="rekapKosongLabel">
                      <i class="fas fa-exclamation-triangle mr-2"></i>Data Tidak Ditemukan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="callout callout-warning mb-0">
                      <h6><i class="fas fa-info-circle mr-1"></i> Informasi:</h6>
                      <p>Tidak ditemukan data nilai untuk kelas <strong>{{ session('kelas_nama') }}</strong> dan mapel <strong>{{ session('mapel_nama') }}</strong>.</p>
                      <p class="mb-0">Pastikan kamu memilih kelas dan mapel yang sesuai dengan data yang sudah diinput sebelumnya.</p>
                    </div>
                  </div>
                  <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-outline-warning" data-dismiss="modal">
                      <i class="fas fa-times-circle mr-1"></i> Tutup
                    </button>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@section('scripts')
    @if (session('rekap_kosong'))
    <script>
        $(function () {
            $('#rekapKosongModal').modal('show');
        });
    </script>
    @endif
@endsection