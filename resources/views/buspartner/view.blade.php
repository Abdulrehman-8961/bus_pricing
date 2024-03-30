@extends('layouts.dashboard')

@section('content')
@php
    $l_no = DB::table('bus_partner')
    ->latest('created_at')
    ->first();
    if (isset($l_no)) {
        $last_no = $l_no->lieferanten;
        $new_no = $last_no + 1;
    } else {
        $new_no = '1400';
    }
@endphp
    <div class="container-fluid mw-100">
        <form class="container-fluid" method="GET" action="{{ url()->current() }}">
            <div class="row">
                <div class="d-flex col-lg-8 col-md-8 col-12 mb-3 py-3 px-2">
                    <button type="button" class="btn btn-success me-3" id="btn-add" style="white-space: nowrap;"><i class="fa fa-plus"></i> Neuer Partner</button>
                    <div class="d-flex align-items-center bg-white px-3 me-3">
                        <i class="ti ti-chevron-right fs-6 me-3"></i>
                        <select name="" id="" style="border: none;" class="form-control">
                            <option value="">Kategorie ausählen</option>
                        </select>
                    </div>
                    <div class="d-flex align-items-center bg-white px-3">
                        <i class="ti ti-search fs-8 me-3"></i>
                        <input type="text" class="form-control me-1" style="border: none;" placeholder="Suchen" name="search" value="{{ @$_GET['search'] }}">
                    </div>
                    {{-- <button type="submit" class="btn btn-submit">Search</button> --}}
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

                {{-- <form class="container-fluid" method="GET" action="{{ url()->current() }}">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                            <input type="text" class="form-control" name="search" value="{{ @$_GET['search'] }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <button type="submit" class="btn btn-primary rounded-2">Search</button>
                            <button type="button" onclick="window.location.href='{{ url()->current() }}'"
                                class="btn btn-warning  rounded-2 ms-2">Clear</button>
                        </div>
                    </div>
                </form> --}}


                <div class="table-responsive rounded-2 mb-4" style="min-height: 200px;">
                    <table class="table text-nowrap customize-table mb-0 align-middle">
                        <thead class="fs-4">
                            <tr>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Leiferanten-Nr.</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Firmenname</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Stadt</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Bundesland</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">PLZ</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Aktionsmenü</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                                <tr>
                                    <td>
                                        <h6 class="mb-0 fw-bolder">{{ $row->lieferanten }}</h6>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->firmnname }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->stadt }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->bundsland_name }}</p>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 fw-bolder">{{ $row->plz }}</h6>
                                    </td>
                                    <td>
                                        <div class="dropdown dropstart">
                                            <a href="#" class="text-muted" id="dropdownMenuButton"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @if (Auth::user()->role == 'Admin')
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                                            href="{{ url('Bus-Partner/edit') }}/{{ $row->id }}"><i
                                                                class="fs-4 ti ti-edit"></i>Edit</a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-3 delete"
                                                        href="{{ url('Bus-Partner/delete') }}/{{ $row->id }}"><i
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
                                <td colspan="6">{{ $data->links('pagination::bootstrap-5') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="card w-100 position-relative overflow-hidden add-user-card {{ $errors->any() ? '' : 'd-none' }}">
            <div class="card-body p-4">
                <form method="POST" class="container-fluid" action="{{ url('/Bus-Partner/save') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="lieferanten" class="form-label">Lieferanten-Nr.</label>
                                <input type="text" class="form-control @error('lieferanten') is-invalid @enderror"
                                    value="{{ $new_no }}" name="lieferanten" id="lieferanten" readonly>
                                @error('lieferanten')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="firmnname" class="form-label">Firmnname</label>
                                <input type="text" class="form-control @error('firmnname') is-invalid @enderror"
                                    value="{{ old('firmnname') }}" name="firmnname" id="firmnname"
                                    aria-describedby="emailHelp">
                                @error('firmnname')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                                    value="{{ old('adresse') }}" name="adresse" id="adresse"
                                    aria-describedby="adresse">
                                @error('adresse')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="stadt" class="form-label">Stadt</label>
                                <input type="text" class="form-control @error('stadt') is-invalid @enderror"
                                    value="{{ old('stadt') }}" name="stadt" id="stadt"
                                    aria-describedby="stadt">
                                @error('stadt')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @php
                            $bundesland = DB::table('bundeslander')->where('is_deleted',0)->get();
                            $bustype = DB::table('bus_type')->where('is_deleted',0)->get();
                        @endphp
                        <div class="col-lg-3 col-12">
                            <div class="mb-4">
                                <label for="bundesland" class="form-label">Bundesland</label>
                                <select class="form-control" name="bundesland" id="bundesland">
                                    <option value="">Select Bundesland</option>
                                    @foreach($bundesland as $row)
                                    <option value="{{ $row->id }}" {{ old('bundesland') == $row->id ? 'selected' : '' }}>{{ $row->bundsland }}</option>
                                    @endforeach
                                </select>
                                @error('bundesland')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12 ">
                            <div class="mb-4">
                                <label for="plz" class="form-label">PLZ</label>
                                <input type="text" class="form-control @error('plz') is-invalid @enderror"
                                    name="plz" id="plz" value="{{ old('plz') }}">
                                @error('plz')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-4">
                                <label for="bustype" class="form-label">Bustypen</label>
                                <select class="form-control" name="bustype" id="bustype">
                                    <option value="">Select Bus</option>
                                    @foreach($bustype as $row)
                                    <option value="{{ $row->id }}" {{ old('bustype') == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                @error('bustype')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <button type="submit" class="btn btn-success me-2">speichern</button>
                            <button type="button" onclick="window.location.reload();"
                                class="btn btn-submit">Löschen</button>
                        </div>
                    </div>
                </form>
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
    </script>
@endsection
