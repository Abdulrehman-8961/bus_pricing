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
                        <input type="text" class="form-control me-1" style="border: none;" id="search-input"
                            placeholder="Suchen" name="search" value="{{ @$_GET['search'] }}">
                    </div>
                </div>
                <div class="d-flex col-lg-4 col-md-4 col-12">
                </div>
                <div class="col-lg-2 col-md-2 col-12 text-end">
                </div>
            </div>
        </form>

        <div class="card w-100 position-relative overflow-hidden add-user-card {{ $errors->any() ? '' : 'd-none' }}">
            <div class="card-body p-4">
                <form method="POST" class="container-fluid" action="{{ url('/Lead/save') }}">@csrf
                    <div class="row">
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
                        <div class="col-lg-4 col-12">
                            <div class="mb-4">
                                <label for="label" class="form-label">Labels</label>
                                <select class="form-control" name="label" id="label_field">
                                    <option value="">bitte auswählen</option>
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
                        <div class="col-lg-6 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Abfahrtsort</label>
                                <input size="40" class="form-control" aria-required="true" aria-invalid="false"
                                    placeholder="e.g. Mohrenstraße 17, 10117 Berlin" value="" type="text"
                                    name="departure_point">
                                @error('departure_point')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Abfahrtsdatum und Uhrzeit</label>
                                <input type="datetime-local" class="form-control @error('hinfahrt') is-invalid @enderror"
                                    name="hinfahrt" id="hinfahrt">
                                @error('hinfahrt')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Ankunftsort</label>
                                <input size="40" class="form-control" aria-required="true" aria-invalid="false"
                                    placeholder="e.g. Am Wall 135, 28195 Bremen" value="" type="text"
                                    name="arrival_point">
                                @error('arrival_point')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Ankunftsdatum und Uhrzeit</label>
                                <input type="datetime-local" class="form-control @error('rückfahrt') is-invalid @enderror"
                                    name="rückfahrt" id="rückfahrt">
                                @error('rückfahrt')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Pax</label>
                                <input type="number" class="form-control @error('pax') is-invalid @enderror"
                                    name="pax" id="pax">
                                @error('pax')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Entfernung in KM</label>
                                <input type="number" class="form-control @error('entfernung') is-invalid @enderror"
                                    name="entfernung" id="entfernung">
                                @error('entfernung')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Vorname</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" name="name" id="exampleInputEmail1"
                                    aria-describedby="emailHelp">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Nachname</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name') }}" name="last_name" id="last_name"
                                    aria-describedby="emailHelp">
                                @error('last_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 firma d-none">
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
                        <div class="col-lg-6 col-12 firma d-none">

                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">E-Mail</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" name="email" id="exampleInputEmail1"
                                    aria-describedby="emailHelp">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Telefonnummer</label>
                                <input type="number" class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}" name="phone" id="exampleInputphone1"
                                    aria-describedby="phoneHelp">
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>






                        {{-- <div class="col-lg-4 col-12 ">
                            <div class="mb-4">
                                <label for="" class="form-label">Notizen</label>
                                <input type="text" class="form-control @error('notizen') is-invalid @enderror"
                                    name="notizen" id="notizen">
                                @error('notizen')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}


                        <div class="row mb-4">
                            <h4 class="mb-3">Address</h4>
                            <div class="col-md-3">
                                <label for="" class="form-label">Straße </label>
                                <input type="text" name="street" class="form-control" value="{{ old('street') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="" class="form-label">PLZ</label>
                                <input type="number" name="zip_code" class="form-control"
                                    value="{{ old('zip_code') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="" class="form-label">Land</label>
                                <input type="text" name="country" class="form-control" value="{{ old('country') }}">
                            </div>
                            @php
                                $countryCodes = [
                                    'DE' => 'Germany',
                                    'US' => 'United States',
                                    'FR' => 'France',
                                ];
                            @endphp
                            <div class="col-md-1">
                                <label for="" class="form-label">Ländercode</label>
                                <select class="form-control" name="country_code" id="country_code">
                                    @foreach ($countryCodes as $key => $row)
                                        <option value="{{ $key }}" {{ $key == 'de' ? 'selected' : '' }}>
                                            {{ $key }}</option>
                                    @endforeach
                                </select>

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


                <div id="leads-table" class="table-responsive rounded-2 mb-4" style="min-height: 200px;">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            $(document).on('click', '#btn-add', function() {
                if ($('.add-user-card').hasClass('d-none')) {
                    $('.add-user-card').removeClass('d-none');
                } else {
                    $('.add-user-card').addClass('d-none');
                }
            })
            $(document).on('change', '#label_field', function() {
                var value = $('#label_field option:selected').val();
                // console.log(value);
                if (value == "Firma" || value == "Schule" || value == "Verein") {
                    $('.firma').removeClass('d-none');
                } else {
                    $('.firma').addClass('d-none');
                }
            })
            $('#search-input').on('keyup', function() {
                var search = $(this).val();
                leadsView(1,search);
            });
            $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1]; // Get the page number
            var search = $('#search-input').val();
            leadsView(page, search); // Load data for the clicked page
        });

        });
        leadsView(1, '');

        function leadsView(page,search) {
            $.ajax({
                url: "{{ url('getLeads') }}",
                method: "GET",
                data: {
                    page: page,
                    search: search
                },
                success: function(data) {
                    $('#leads-table').html(data);
                }
            });
        }
    </script>
@endsection
