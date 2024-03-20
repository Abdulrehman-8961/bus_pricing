@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid mw-100">

        <div class="card w-100 position-relative overflow-hidden add-user-card">
            <div class="card-body p-4">
                <form method="POST" class="container-fluid" action="{{ url('/Link/save') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8 col-12">
                            <div class="mb-3">
                                <label for="link" class="form-label">Support Link</label>
                                <input type="text"
                                    class="form-control @error('link') is-invalid @enderror"
                                    value="{{ $setting->link }}" name="link" id="link"
                                    aria-describedby="link">
                                @error('link')
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
    </div>
@endsection
