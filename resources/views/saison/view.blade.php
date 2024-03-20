@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid mw-100">
        @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Dispatcher')
            <div class="row mb-5">
                <div class="text-end">
                    <input type="button" id="btn-add" class="btn btn-submit" value="NEU HINZUFÜGEN">
                </div>
            </div>
        @endif

        <div class="card w-100 position-relative overflow-hidden add-user-card {{ $errors->any() ? '' : 'd-none' }}">
            <div class="card-body p-4">
                <form method="POST" class="container-fluid" action="{{ url('/Saison/save') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="zeitraum" class="form-label">Zeitraum</label>
                                <input type="text"
                                    class="form-control shawCalRanges @error('zeitraum') is-invalid @enderror"
                                    value="{{ old('zeitraum') }}" name="zeitraum" id="zeitraum"
                                    aria-describedby="zeitraum">
                                @error('zeitraum')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" name="name" id="name">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="presierhohung" class="form-label">Preiserhöhung in %</label>
                                <input type="text"
                                    class="form-control customInput @error('presierhohung') is-invalid @enderror"
                                    value="{{ old('presierhohung') }}" name="presierhohung" id="presierhohung"
                                    aria-describedby="presierhohung">
                                @error('presierhohung')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="meldung" class="form-label">Meldung</label>
                                <input type="text" class="form-control @error('meldung') is-invalid @enderror"
                                    value="{{ old('meldung') }}" name="meldung" id="meldung" aria-describedby="meldung">
                                @error('meldung')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-4 mt-3">
                            <button type="submit" class="btn btn-success me-2">speichern</button>
                            <button type="button" onclick="window.location.reload();"
                                class="btn btn-submit">Löschen</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <form id="search-form" class="container-fluid" method="GET" action="{{ url()->current() }}">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12 mb-3 py-3 px-2 d-flex" style="background-color: #fff;">
                    <i class="ti ti-search fs-8 me-3"></i><input type="text" value="{{ date('m/d/Y',strtotime(@$startDate ?? now())) }} - {{ date('m/d/Y',strtotime(@$endDate ?? now())) }}" class="form-control shawCalRanges2" name="dateRange" id="dateRange">
                </div>
            </div>
        </form>


        <div class="card w-100 position-relative overflow-hidden">
            {{-- <div class="px-4 py-3 border-bottom d-flex justify-content-between">
                <h5 class="card-title fw-semibold mb-0 lh-sm">Employees</h5>
                <a href="{{ url('/Employee/add') }}"
                    class="justify-content-center btn btn-sm btn-light-primary text-primary font-medium d-flex align-items-center">
                    <i class="ti ti-plus fs-4 me-2"></i>
                    Add Employee
                </a>
            </div> --}}
            <div class="card-body">




                <div class="table-responsive rounded-2 mb-4" style="min-height: 200px;">
                    <table class="table text-nowrap customize-table mb-0 align-middle">
                        <thead class="fs-4">
                            <tr>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Zeitraum</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Name</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Preiserhöhung</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Meldung</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Aktionsmenü</h6>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($saison as $row)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <h6 class="fs-4 fw-normal mb-0">
                                                    {{ date('d.m.Y', strtotime($row->start_zeitraum)) }} -
                                                    {{ date('d.m.Y', strtotime($row->end_zeitraum)) }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <h6 class="fs-4 fw-normal mb-0">
                                                    {{ $row->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">+ {{ $row->presierhohung }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->meldung }}</p>
                                    </td>
                                    <td>
                                        <div class="dropdown dropstart">
                                            <a href="#" class="text-muted" id="dropdownMenuButton"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Dispatcher')
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                                            href="{{ url('/Saison/edit/' . $row->id) }}"><i
                                                                class="fs-4 ti ti-edit"></i>Edit</a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-3 delete"
                                                        href="{{ url('/Saison/delete/' . $row->id) }}"><i
                                                            class="fs-4 ti ti-trash"></i>Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">{{ $saison->links('pagination::bootstrap-5') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        $(document).on('click', '#btn-add', function() {
            if ($('.add-user-card').hasClass('d-none')) {
                $('.add-user-card').removeClass('d-none');
            } else {
                $('.add-user-card').addClass('d-none');
            }
        })
        $(".shawCalRanges").daterangepicker();
        $(".shawCalRanges2").daterangepicker();
        $(".shawCalRanges2").on('change',function(){
            $('#search-form').submit();
        });

        $('.customInput').on('input', function() {
            var value = $(this).val().replace(/[^\d.]/g,
                ''); // Remove non-numeric and non-decimal characters
            var parts = value.split('.');
            if (parts.length > 1) {
                // Limit to 2 digits before the decimal point and 3 digits after
                parts[0] = parts[0].slice(0, 2);
                parts[1] = parts[1].slice(0, 3);
                value = parts.join('.');
            } else {
                // Limit to 2 digits
                value = value.slice(0, 2);
            }
            $(this).val(value + '%');
        });
    </script>
@endsection
