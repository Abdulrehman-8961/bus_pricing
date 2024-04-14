@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid mw-100">
        {{-- <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Employees</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted " href="{{ url('/home') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Employees</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="{{ asset('public') }}/dist/images/breadcrumb/ChatBc.png" alt=""
                                class="img-fluid mb-n4">
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- @if (Auth::user()->role == 'Admin')
            <div class="row mb-5">
                <div class="text-end">
                    <input type="button" id="btn-add" class="btn btn-submit" value="NEU HINZUFÜGEN">
                </div>
            </div>
        @endif --}}




        <form class="container-fluid" method="GET" action="{{ url()->current() }}">
            <div class="row">
                <div class="d-flex col-lg-6 col-md-6 col-12 mb-3 py-3 px-2">
                    <button id="btn-add" type="button" class="btn btn-success me-3" style="white-space: nowrap;"><i
                            class="fa fa-plus"></i> lead</button>
                    <div class="d-flex align-items-center bg-white px-3">
                        <i class="ti ti-search fs-8 me-3"></i>
                        <input type="text" class="form-control me-1" style="border: none;" placeholder="Suchen"
                            name="search" value="{{ @$_GET['search'] }}">
                    </div>
                    {{-- <button type="submit" class="btn btn-submit">Search</button> --}}
                </div>
                <div class="d-flex col-lg-4 col-md-4 col-12">
                </div>
                <div class="col-lg-2 col-md-2 col-12 text-end">
                    {{-- <button type="button" class="btn btn bg-white mt-3" style="white-space: nowrap;"><i
                            class="fa fa-plus me-2"></i> label</button> --}}
                </div>
            </div>
        </form>

        <div class="card w-100 position-relative overflow-hidden add-user-card {{ $errors->any() ? '' : 'd-none' }}">
            <div class="card-body p-4">
                <form method="POST" class="container-fluid" action="{{ url('/Lead/save') }}">@csrf
                    <div class="row">
                        <div class="col-lg-4 col-12">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">First Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" name="name" id="exampleInputEmail1"
                                    aria-describedby="emailHelp">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name') }}" name="last_name" id="last_name"
                                    aria-describedby="emailHelp">
                                @error('last_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" name="email" id="exampleInputEmail1"
                                    aria-describedby="emailHelp">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Phone</label>
                                <input type="number" class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}" name="phone" id="exampleInputphone1"
                                    aria-describedby="phoneHelp">
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="mb-4">
                                <label for="label" class="form-label">Labels</label>
                                <select class="form-control" name="label" id="label_field">
                                    <option value="">Select Value</option>
                                    <option value="Schule" {{ old('label') == 'Schule' ? 'selected' : '' }}>Schule</option>
                                    <option value="Firma" {{ old('label') == 'Firma' ? 'selected' : '' }}>Firma</option>
                                    <option value="Verein" {{ old('label') == 'Verein' ? 'selected' : '' }}>
                                        Verein</option>
                                    <option value="Privat" {{ old('label') == 'Privat' ? 'selected' : '' }}>Privat
                                    </option>
                                </select>
                                @error('phone_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 firma d-none">
                            <div class="mb-3">
                                <label for="firma_name" class="form-label">Firma Name</label>
                                <input type="text" class="form-control @error('firma_name') is-invalid @enderror"
                                    value="{{ old('firma_name') }}" name="firma_name" id="firma_name"
                                    aria-describedby="firma_name">
                                @error('firma_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="mb-4">
                                <label for="quelle" class="form-label">Quelle</label>
                                <select class="form-control" name="quelle" id="quelle">
                                    <option value="">Select Value</option>
                                    <option value="E-Mail" {{ old('quelle') == 'E-Mail' ? 'selected' : '' }}>E-Mail
                                    </option>
                                    <option value="Online" {{ old('quelle') == 'Online' ? 'selected' : '' }}>
                                        Online</option>
                                    <option value="Anruf" {{ old('quelle') == 'Anruf' ? 'selected' : '' }}>Anruf
                                    </option>
                                </select>
                                @error('phone_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @php
                            $employee = DB::table('users')->where('role', 'Employee')->get();
                        @endphp
                        <div class="col-lg-4 col-12">
                            <div class="mb-4">
                                <label for="" class="form-label">Kundenbetreuer</label>
                                <select class="form-control" name="kundenbetreuer" id="">
                                    <option value="">Select Value</option>
                                    @foreach ($employee as $row)
                                        <option value="{{ $row->id }}">
                                            {{ $row->name }} {{ $row->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('phone_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Hinfahrt</label>
                                <input type="date" class="form-control @error('hinfahrt') is-invalid @enderror"
                                    name="hinfahrt" id="hinfahrt">
                                @error('hinfahrt')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Rückfahrt</label>
                                <input type="date" class="form-control @error('rückfahrt') is-invalid @enderror"
                                    name="rückfahrt" id="rückfahrt">
                                @error('rückfahrt')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Pax</label>
                                <input type="number" class="form-control @error('pax') is-invalid @enderror"
                                    name="pax" id="pax">
                                @error('pax')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Entfernung</label>
                                <input type="number" class="form-control @error('entfernung') is-invalid @enderror"
                                    name="entfernung" id="entfernung">
                                @error('entfernung')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Notizen</label>
                                <input type="text" class="form-control @error('notizen') is-invalid @enderror"
                                    name="notizen" id="notizen">
                                @error('notizen')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <h4 class="mb-3">Address</h4>
                            <div class="col-md-3">
                                <label for="" class="form-label">Supplement</label>
                                <input type="text" name="supplement" class="form-control" value="{{ old('supplement') }}" placeholder="Additional address information">
                            </div>
                            <div class="col-md-3">
                                <label for="" class="form-label">Street</label>
                                <input type="text" name="street" class="form-control" value="{{ old('street') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="" class="form-label">Zip Code</label>
                                <input type="number" name="zip_code" class="form-control" value="{{ old('zip_code') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="" class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ old('country') }}">
                            </div>
                            <div class="col-md-1">
                                <label for="" class="form-label">Country Code</label>
                                <input type="text" name="country_code" class="form-control" value="{{ old('country_code') }}">
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
                                    <h6 class="fs-4 fw-semibold mb-0">VNR</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Titel</h6>
                                </th>
                                {{-- <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Nächste Aktivität</h6>
                                </th> --}}
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Labels</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Quelle</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Lead erstellt</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Kundenbetreuer</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Kunden-Nr</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Aktionsmenü</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leads as $row)
                                @php
                                    $user = DB::table('users')
                                        ->where('id', $row->kundenbetreuer)
                                        ->first();
                                @endphp
                                <tr>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->vnr }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <h6 class="fs-4 fw-normal mb-0">{{ $row->firstname }}
                                                    {{ $row->lastname }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <h6 class="fs-4 fw-normal mb-0">
                                                </h6>
                                            </div>
                                        </div>
                                    </td> --}}
                                    <td>
                                        @if ($row->grund == 'Privat')
                                            <span class="badge fw-semibold py-1 w-85 bg-primary text-white">Privat</span>
                                        @elseif($row->grund == 'Verein')
                                            <span class="badge fw-semibold py-1 w-85 bg-warning">Verein</span>
                                        @elseif($row->grund == 'Firma')
                                            <span class="badge fw-semibold py-1 w-85 bg-danger">Firma</span>
                                        @else
                                            <span class="badge fw-semibold py-1 w-85 bg-info">Firma</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->quelle }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ date('d. M. Y, H:i', strtotime($row->created_at)) }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ @$user->name }} {{ @$user->last_name }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->customer_number }}</p>
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
                                                            href="{{ url('/Leads/edit') }}/{{ $row->id }}">Bearbeiten</a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-3 delete"
                                                        href="{{ url('/Leads/delete') }}/{{ $row->id }}">Löschen</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-3 delete"
                                                        href="{{ url('/Transfer-To-Deal') }}/{{ $row->id }}">In Deal
                                                        umwandeln</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="9">{{ $leads->links('pagination::bootstrap-5') }}</td>
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
        $(document).on('change','#label_field', function(){
            var value = $('#label_field option:selected').val();
            console.log(value);
            if(value == "Firma"){
                $('.firma').removeClass('d-none');
            } else {
                $('.firma').addClass('d-none');
            }
        })
    </script>
@endsection
