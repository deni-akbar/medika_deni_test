@extends('layouts.user-layout')

@section('title', 'Dashboard')

@section('content')
<h1 class="h3 mb-4">Antrian User</h1>
<div class="row">
    <div class="col-lg-12">
        <div class="col-xl-3 col-md-6 mb-4 mx-auto" id="headerAntrian">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold text-primary text-uppercase mb-1">
                                Nomor Antrian</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="noAntrianRealtime">{{$nomor_terkini->no_antrian}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card py-2">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="antrianTable" class="table table-bordered mt-4 text-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>No Antrian</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($antrians as $antrian)
                            <tr>
                                <td>{{ $antrian->id }}</td>
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
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var antrianTable = $('#antrianTable').DataTable({
            "order": [
                [0, "desc"]
            ]
        });

        function fetchTableRealtime() {
            $.get("{{ route('antrian.tableJson') }}", function(data) {
                antrianTable.clear();
                data.forEach(function(antrian) {
                    antrianTable.row.add([
                        antrian.id,
                        antrian.nama,
                        antrian.no_antrian,
                        '<span class="badge ' +
                        (antrian.status === 'proses' ? 'text-light bg-primary' :
                            (antrian.status === 'selesai' ? 'text-light bg-success' : 'bg-warning')) +
                        '">' + antrian.status + '</span>'
                    ]);
                });
                antrianTable.draw(false);
            });
        }
        setInterval(fetchTableRealtime, 2000);
    });

    function fetchAntrianRealtime() {
        $.get("{{ route('antrian.json') }}", function(data) {
            $('#noAntrianRealtime').text(data.no_antrian);
        });
    }
    setInterval(fetchAntrianRealtime, 2000); // update setiap 2 detik
</script>
@endsection