@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid mw-100">
        <div class="card w-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <h4 class="mb-4"><span class="fw-bolder">VNR</span>: {{ $leads->vnr }}</h4>
                <div class="row">
                    <div class="col-md-2">
                        <h5 class="mb-3">DETAILS</h5>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <i class="ti ti-tag me-3"></i>
                                <p class="bg-dark text-white">FIRMENKUNDE</p>
                            </div>
                            <i class="ti ti-pencil"></i>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <i class="ti ti-user-circle me-3"></i>
                                <p>Volkan</p>
                            </div>
                            <i class="ti ti-pencil"></i>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <i class="ti ti-user-circle me-3"></i>
                                <p>Manuell erstellt</p>
                            </div>
                            <i class="ti ti-pencil"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <h5 class="mb-3">KONTACT</h5>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <i class="ti ti-user-circle me-3"></i>
                                <p class="fw-bolder" style="cursor: pointer;"
                                    onclick="edit('{{ $leads->firstname }} {{ $leads->lastname }}','name')">
                                    {{ $leads->firstname }} {{ $leads->lastname }}</p>
                            </div>
                            <i class="ti ti-pencil"></i>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <i class="ti ti-mail me-3"></i>
                                <p style="cursor: pointer;" onclick="edit('{{ $leads->email }}','email')">
                                    {{ $leads->email }}</p>
                            </div>
                            <i class="ti ti-pencil"></i>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <i class="ti ti-phone me-3"></i>
                                <p style="cursor: pointer;" onclick="edit('{{ $leads->phone }}','phone')">
                                    {{ $leads->phone }}</p>
                            </div>
                            <i class="ti ti-pencil"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <h5 class="mb-3">UNTERNEHMEN</h5>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <i class="ti ti-building me-3"></i>
                                <p class=""> </p>
                            </div>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <p>Address:</p>
                                <p>{{ $leads->start }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h5 class="mb-5">FAHRTDETAILS <span><i class="ti ti-pencil ms-2"></i></span></h5>
                            <p class="mb-3"><u>Hin und Rückfahrt</u><span><i class="ti ti-pencil ms-2"></i></span></p>
                            <p class="mb-0">Hinfahrt <span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->hinfahrt }}','hinfahrt')">{{ $leads->hinfahrt }}<i
                                        class="ti ti-pencil ms-2 me-2"></i></span><span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->hinfahrt_other_stops }}','hinfahrt_other_stops')">{{ $leads->hinfahrt_other_stops }}
                                    <i class="ti ti-pencil ms-2"></i></span>
                            </p>
                            <p class="mb-0">Rückfahrt
                                <span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->rueckfahrtt }}','rueckfahrtt')">{{ $leads->rueckfahrtt }}<i
                                        class="ti ti-pencil ms-2"></i></span><span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->rueckfahrtt_other_stops }}','rueckfahrtt_other_stops')">
                                    {{ $leads->rueckfahrtt_other_stops }}
                                    <i class="ti ti-pencil ms-2"></i></span>
                            </p>
                            <p class="mb-0" style="cursor: pointer;" onclick="edit('{{ $leads->pax }}','pax')">PAX.
                                {{ $leads->pax }}<span><i class="ti ti-pencil ms-2 me-2"></i></span></p>
                        </div>
                        <div class="mb-3">
                            <p class="mb-3"><u>Nachricht vom Kunden</u></p>
                            <p class="mb-0">{{ $leads->bemerkung }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="mb-3"><u>Notizer</u><span><i class="ti ti-pencil ms-2"></i></span></p>
                            <p class="mb-0">{{ $leads->bemerkung }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ url('Leads/update') }}/{{ $leads->id }}" method="post">
                    @csrf
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myModalLabel">
                            Kontact
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="editField" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">
                            Abbrechen
                        </button>
                        <button type="submit" class="btn btn-success">
                            Speichern
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('javascript')
    <script>
        function edit(value, fieldName) {
            $('#editField').val(value);
            $('#editField').attr("name", fieldName);
            $('#editmodal').modal('show');
        }
    </script>
@endsection
