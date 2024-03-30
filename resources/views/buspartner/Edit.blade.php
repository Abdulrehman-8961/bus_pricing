@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid mw-100">





        <div class="card w-100 position-relative overflow-hidden">
            {{-- <div class="px-4 py-3 border-bottom d-flex justify-content-between">
                <h5 class="card-title fw-semibold mb-0 lh-sm">Edit Employee</h5>

            </div> --}}
            <div class="card-body p-4">
                <form method="POST" class="container-fluid" action="{{ url('/Bus-Partner/update/' . $data->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="lieferanten" class="form-label">Lieferanten-Nr.</label>
                                <input type="text" class="form-control @error('lieferanten') is-invalid @enderror"
                                    value="{{ $data->lieferanten }}" name="lieferanten" id="lieferanten" readonly>
                                @error('lieferanten')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="firmnname" class="form-label">Firmnname</label>
                                <input type="text" class="form-control @error('firmnname') is-invalid @enderror"
                                    value="{{ $data->firmnname }}" name="firmnname" id="firmnname"
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
                                value="{{ $data->adresse }}" name="adresse" id="adresse"
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
                                    value="{{ $data->stadt }}" name="stadt" id="stadt"
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
                                    <option value="{{ $row->id }}" {{ $data->bundesland == $row->id ? 'selected' : '' }}>{{ $row->bundsland }}</option>
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
                                    name="plz" id="plz" value="{{ $data->plz }}">
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
                                    <option value="{{ $row->id }}" {{ $data->bustype == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>
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
