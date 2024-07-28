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
                <div class="d-flex col-lg-8 col-md-8 col-12 mb-3 py-3 px-2">
                    <button id="btn-add" type="button" class="btn btn-success me-3" style="white-space: nowrap;"><i
                            class="fa fa-plus"></i> lead</button>
                    <div class="d-flex align-items-center bg-white px-3 me-3">
                        <i class="ti ti-search fs-8 me-3"></i>
                        <input type="text" class="form-control me-1" style="border: none;" id="search-input"
                            placeholder="Suchen" name="search" value="{{ @$_GET['search'] }}">
                    </div>
                    <input type="checkbox" class="btn-check btn-archive" id="btn-check-2-outlined" autocomplete="off">
                    <label class="btn" for="btn-check-2-outlined" style="background-color: #0B996D; color: #fff;">Archive
                        anzeigen</label><br>
                </div>
                <div class="d-flex col-lg-4 col-md-4 col-12">
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
                                    @foreach ($employee as $e)
                                        <option value="{{ $e->id }}">
                                            {{ $e->name }} {{ $e->last_name }}
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
                                <input size="40" class="form-control calculate_km" aria-required="true"
                                    aria-invalid="false" placeholder="e.g. Mohrenstraße 17, 10117 Berlin" value=""
                                    type="text" name="departure_point" id="departure_point">
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
                                <input size="40" class="form-control calculate_km" aria-required="true"
                                    aria-invalid="false" placeholder="e.g. Am Wall 135, 28195 Bremen" value=""
                                    type="text" name="arrival_point" id="arrival_point">
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
                                    @foreach ($countryCodes as $key => $cc)
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
        {{-- {{ dd($row) }} --}}

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
    <form action="{{ url('update-Employee_') }}" id="update_employee" method="POST">
        @csrf
        <input type="hidden" value="" name="updateId" id="updateId">
        <input type="hidden" value="" name="employee_id" id="employee_id">
    </form>
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            $(document).on('change', '.employee', function() {
                var employee_id = $(this).val();
                var updateId = $(this).data('id');
                $('#updateId').val(updateId);
                $('#employee_id').val(employee_id);
                $('#update_employee').submit();
            })
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
            $('.btn-archive').on('click', function() {
                if ($('.btn-archive').prop('checked')) {
                    var archive = 1;
                } else {
                    var archive = 0;
                }
                var search = $('#search-input').val();
                leadsView(1, search, archive);
            });
            $('#search-input').on('keyup', function() {
                var search = $(this).val();
                if ($('.btn-archive').prop('checked')) {
                    var archive = 1;
                } else {
                    var archive = 0;
                }
                leadsView(1, search, archive);
            });
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                if ($('.btn-archive').prop('checked')) {
                    var archive = 1;
                } else {
                    var archive = 0;
                }
                var page = $(this).attr('href').split('page=')[1]; // Get the page number
                var search = $('#search-input').val();
                leadsView(page, search, archive); // Load data for the clicked page
            });

        });
        leadsView(1, '', 0);

        function leadsView(page, search, archive) {
            $.ajax({
                url: "{{ url('getLeads') }}",
                method: "GET",
                data: {
                    page: page,
                    search: search,
                    archive: archive,
                },
                success: function(data) {
                    $('#leads-table').html(data);
                }
            });
        }

        // $(document).on('change', '.calculate_km', async function() {
        //     var departure_point = $('#departure_point').val();
        //     var arrival_point = $('#arrival_point').val();

        //     if (departure_point && arrival_point) {
        //         try {
        //             // Fetch both locations concurrently
        //             const [departureLocation, arrivalLocation] = await Promise.all([
        //                 getLocation(departure_point),
        //                 getLocation(arrival_point)
        //             ]);

        //             if (departureLocation && arrivalLocation) {
        //                 // Destructure latitude and longitude for both locations
        //                 const {
        //                     latitude: departureLat,
        //                     longitude: departureLon
        //                 } = departureLocation;
        //                 const {
        //                     latitude: arrivalLat,
        //                     longitude: arrivalLon
        //                 } = arrivalLocation;

        //                 // Calculate the distance using the haversine formula
        //                 var km = haversine(departureLat, departureLon, arrivalLat, arrivalLon);
        //                 console.log(`Distance: ${km.toFixed(2)} km`); // Log distance with two decimal points
        //                 $('#entfernung').val(km.toFixed(2));
        //             } else {
        //                 console.log("One or both locations not found.");
        //             }
        //         } catch (error) {
        //             console.error("Error getting location:", error);
        //         }
        //     }
        // });

        // async function getLocation(areaName) {
        //     try {
        //         let response = await fetch(
        //             `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(areaName)}`
        //         );

        //         if (!response.ok) {
        //             throw new Error("Network response was not ok");
        //         }

        //         let data = await response.json();
        //         if (data.length > 0) {
        //             const latitude = parseFloat(data[0].lat);
        //             const longitude = parseFloat(data[0].lon);
        //             return {
        //                 latitude,
        //                 longitude
        //             };
        //         } else {
        //             throw new Error("Location not found.");
        //         }
        //     } catch (error) {
        //         console.error("Error fetching location data:", error);
        //         throw error;
        //     }
        // }

        // function haversine(lat1, lon1, lat2, lon2) {
        //     const R = 6371; // Radius of the Earth in kilometers

        //     // Convert degrees to radians
        //     const toRadians = (degrees) => degrees * (Math.PI / 180);

        //     const lat1_rad = toRadians(lat1);
        //     const lon1_rad = toRadians(lon1);
        //     const lat2_rad = toRadians(lat2);
        //     const lon2_rad = toRadians(lon2);

        //     // Differences in coordinates
        //     const d_lat = lat2_rad - lat1_rad;
        //     const d_lon = lon2_rad - lon1_rad;

        //     // Haversine formula
        //     const a = Math.sin(d_lat / 2) ** 2 +
        //         Math.cos(lat1_rad) * Math.cos(lat2_rad) * Math.sin(d_lon / 2) ** 2;
        //     const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        //     // Distance in kilometers
        //     const distance = R * c;

        //     return distance;
        // }


        $(document).on('click', '.delete', function() {
            var deleteId = $(this).attr('data-id');
            if (deleteId) {
                Swal.fire({
                    title: 'Bist du sicher?',
                    text: 'Möchtest du diesen Lead wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ja, löschen!',
                    cancelButtonText: 'Nein, behalten',
                    customClass: {
                        confirmButton: 'btn btn-danger text-light me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = "{{ url('/Leads/delete') }}" + '/' + deleteId;
                    }
                });
            }
        })
    </script>
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBei1BFgaTBUtn3uaxlKUd7GPp1Jd2JHn0&callback=initAutocomplete&libraries=places&v=weekly" defer></script> --}}
        {{-- get distance via api --}}
    <script>
        $(document).on('change', '.calculate_km', function() {
            var departure_point = $('#departure_point').val();
            var arrival_point = $('#arrival_point').val();

            if (departure_point && arrival_point) {
                calculateDrivingDistance(departure_point, arrival_point);
            }
        });
        function calculateDrivingDistance(origin, destination) {


            const service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix({
                origins: [origin],
                destinations: [destination],
                travelMode: 'DRIVING',
                unitSystem: google.maps.UnitSystem.METRIC, // or google.maps.UnitSystem.IMPERIAL
            }, callback);
        }

        function callback(response, status) {
            if (status === 'OK') {
                const origin = response.originAddresses[0];
                const destination = response.destinationAddresses[0];
                const distanceElement = response.rows[0].elements[0];

                if (distanceElement.status === 'OK') {
                    const distanceInMeters = distanceElement.distance.value; // Distance in meters
            const distanceInKilometers = distanceInMeters / 1000;
// console.log(`Distance from ${origin} to ${destination} is ${distance} and it takes about ${duration}.`);
console.log(distanceInKilometers);
$('#entfernung').val(Math.round(distanceInKilometers));
                } else {
                    console.error('Error calculating distance:', distanceElement.status);
                }
            } else {
                console.error('Error with the request:', status);
            }
        }
    </script>
@endsection
