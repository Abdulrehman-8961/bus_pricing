@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid mw-100">





        <div class="card w-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <form method="POST" class="container-fluid" action="{{ url('/Saison/update/' . $saison->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="zeitraum" class="form-label">Zeitraum</label>
                                <input type="text" class="form-control shawCalRanges @error('zeitraum') is-invalid @enderror"
                                    value="{{ date('m/d/Y',strtotime($saison->start_zeitraum)) }} - {{ date('m/d/Y',strtotime($saison->end_zeitraum)) }}" name="zeitraum" id="zeitraum"
                                    aria-describedby="emailHelp">
                                @error('zeitraum')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ $saison->name }}" name="name" id="name"
                                    aria-describedby="emailHelp">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label for="presierhohung" class="form-label">Preiserhöhung in %</label>
                                <input type="text" class="form-control customInput @error('presierhohung') is-invalid @enderror"
                                    value="{{ $saison->presierhohung }}" name="presierhohung" id="presierhohung"
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
                                    value="{{ $saison->meldung }}" name="meldung" id="meldung"
                                    aria-describedby="meldung">
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
    </div>
@endsection
@section('javascript')
<script>
    $(".shawCalRanges").daterangepicker();

    $('.customInput').on('input', function() {
            var value = $(this).val().replace(/[^\d.\-+]/g, ''); // Allow +, -, numeric, and decimal characters
            var prefix = ''; // Initialize prefix
            if (value.charAt(0) === '-') {
                prefix = '-';
                value = value.substring(1); // Remove the first character if it's -
            } else if (value.charAt(0) === '+') {
                prefix = '+';
                value = value.substring(1); // Remove the first character if it's +
            } else {
                prefix = '+';
            }
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
            $(this).val(prefix + value + '%');
        });
</script>
@endsection
