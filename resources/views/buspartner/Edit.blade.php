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
                            <div class="mb-4">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" name="role" id="role">
                                    <option value="">Select Value</option>
                                    <option value="Admin" {{ $data->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Dispatcher" {{ $data->role == 'Dispatcher' ? 'selected' : '' }}>
                                        Dispatcher</option>
                                    <option value="Employee" {{ $data->role == 'Employee' ? 'selected' : '' }}>Employee
                                    </option>
                                </select>
                                @error('phone_number')
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
        <div class="card w-100 position-relative overflow-hidden">
            {{-- <div class="px-4 py-3 border-bottom d-flex justify-content-between">
                <h5 class="card-title fw-semibold mb-0 lh-sm">Edit Password</h5>

            </div> --}}
            <div class="card-body p-4">
                <form method="POST" class="container-fluid" action="{{ url('/Employee/update-password/' . $data->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 col-lg-3 col-12 ">
                            <div class="mb-4">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" id="exampleInputPassword1">
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 col-12 ">
                            <div class="mb-4">
                                <label for="exampleInputPassword2" class="form-label">Password Confirmation</label>
                                <input type="password" class="form-control @error('confirm_password') is-invalid @enderror"
                                    name="confirm_password" id="exampleInputPassword2">
                                @error('confirm_password')
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
