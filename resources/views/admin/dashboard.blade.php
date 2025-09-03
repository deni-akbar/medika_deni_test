@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h1 class="h3 mb-4">Dashboard Admin</h1>
<div class="row">
    <div class="col-lg-12">
        <div class="col-xl-3 col-md-6 mb-4 mx-auto" id="headerAntrian">
            <div class="row mb-3">
                <div class="col text-center">
                    <button type="button" class="btn btn-success btn-block mx-auto" style="max-width:200px;" data-toggle="modal" data-target="#modalTambahAntrian">Antrian Baru</button>
                </div>

                <!-- Modal Tambah Antrian -->
                <div class="modal fade" id="modalTambahAntrian" tabindex="-1" role="dialog" aria-labelledby="modalTambahAntrianLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahAntrianLabel">Tambah Antrian</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('antrian.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <input type="text" name="nama" class="form-control mb-2" placeholder="Nama" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold text-primary text-uppercase mb-1">
                                Nomor Antrian</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{($allDone) ? 'Semua Antrian Selesai' : $nomor_terkini->no_antrian}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col">

                    <form action="{{ route('antrian.previous') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $nomor_terkini->id ?? '' }}">
                        <button type="submit" class="btn btn-danger btn-block" @if (!$is_hv_process && !$allDone) disabled @endif>Previous</button>
                    </form>
                </div>
                <div class="col">
                    <form action="{{ route('antrian.next') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $nomor_terkini->id ?? '' }}">
                        <button type="submit" class="btn btn-primary btn-block" @if ($allDone) disabled @endif>Next</button>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card py-2">
            <div class="card-body">
                <table id="antrianTable" class="table table-bordered mt-4 text-dark">
                    <thead>
                        <tr>
                            <th class="d-none">ID</th>
                            <th>Nama</th>
                            <th>No Antrian</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($antrians as $antrian)
                        <tr>
                            <td class="d-none">{{ $antrian->id }}</td>
                            <td>{{ $antrian->nama }}</td>
                            <td>{{ $antrian->no_antrian }}</td>
                            <td><span class="badge {{ $antrian->status === 'proses' ? 'text-light bg-primary' : ($antrian->status === 'selesai' ? 'text-light bg-success' : 'bg-warning') }}">{{ $antrian->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#antrianTable').DataTable({
            "order": [
                [0, "desc"]
            ]
        });
    });
</script>
@endsection