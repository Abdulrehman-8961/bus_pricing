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

        <div class="card w-100 position-relative overflow-hidden add-bus-card {{ $errors->any() ? '' : 'd-none' }}">
            <div class="px-4 py-3 border-bottom d-flex justify-content-between">
                <h5 class="card-title fw-semibold mb-0 lh-sm">Add Bus Type</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" class="container-fluid" action="{{ url('/Bus-Type/Save') }}">
                    @csrf
                    <div class="row">
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
                                <label for="capacity" class="form-label">Capacity</label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                    value="{{ old('capacity') }}" name="capacity" id="capacity">
                                @error('capacity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12 ">
                            <div class="mb-4">
                                <label for="city_transfer" class="form-label">City Transfer</label>
                                <input type="number" class="form-control @error('city_transfer') is-invalid @enderror"
                                    name="city_transfer" value="{{ old('city_transfer') }}" id="city_transfer">
                                @error('city_transfer')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12 ">
                            <div class="mb-4">
                                <label for="one_way_transfer" class="form-label">One Way Transfer</label>
                                <input type="number" class="form-control @error('one_way_transfer') is-invalid @enderror"
                                    name="one_way_transfer" value="{{ old('one_way_transfer') }}" id="one_way_transfer">
                                @error('one_way_transfer')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-4">
                                <label for="short_usage" class="form-label">Short Usage</label>
                                <input type="number" class="form-control @error('short_usage') is-invalid @enderror"
                                    value="{{ old('short_usage') }}" name="short_usage" id="short_usage">
                                @error('short_usage')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-4">
                                <label for="half_day_trip" class="form-label">Half Day Trip</label>
                                <input type="number" class="form-control @error('half_day_trip') is-invalid @enderror"
                                    value="{{ old('half_day_trip') }}" name="half_day_trip" id="half_day_trip">
                                @error('half_day_trip')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-4">
                                <label for="full_day_trip" class="form-label">Full Day Trip</label>
                                <input type="number" class="form-control @error('full_day_trip') is-invalid @enderror"
                                    value="{{ old('full_day_trip') }}" name="full_day_trip" id="full_day_trip">
                                @error('full_day_trip')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="mb-4">
                                <label for="max" class="form-label">Max</label>
                                <input type="number" class="form-control @error('max') is-invalid @enderror"
                                    value="{{ old('max') }}" name="max" id="max" step="0.01">
                                @error('max')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <h4 class="mb-4">Price Per Kilometer</h4>
                        <div class="row">
                            <div class="col-lg-3 col-12">
                                <div class="mb-4">
                                    <label for="kilometers" class="form-label">Kilometers</label>
                                    <input type="number" class="form-control @error('kilometers') is-invalid @enderror"
                                        value="{{ old('kilometers') }}" name="kilometers[]" id="kilometers">
                                    @error('kilometers')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-12">
                                <div class="mb-4">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price') }}" name="price[]" id="price" step="0.01">
                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-1 col-12 d-flex align-items-center">
                                <a href="javascript:;" class="btn-remove-row" class="text-danger"><i
                                        class="fa fa-times color-primary"></i></a>
                            </div>
                        </div>
                        <div class="more-fields"></div>
                        <div class="row">
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-submit mt-3 add-more px-2 mb-5"><i
                                        class="fa fa-plus"></i>
                                    Mehr hinzufügen</button>

                            </div>
                            <div class="col-md-12 mb-4">
                                <button type="submit" class="btn btn-success me-2">Speichern</button>
                                <button type="button" onclick="window.location.reload();"
                                    class="btn btn-submit">Löschen</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <form class="container-fluid" method="GET" action="{{ url()->current() }}">
        <div class="row">
            <div class="d-flex col-lg-4 col-md-6 col-12 mb-3 py-3 px-2" style="background-color: #fff;">
                <input type="text" class="form-control me-1" name="search" value="{{ @$_GET['search'] }}">
                <button type="submit" class="btn btn-submit">Search</button>
            </div>
        </div>
    </form>

    <div class="card w-100 position-relative overflow-hidden">
        {{-- <div class="px-4 py-3 border-bottom d-flex justify-content-between">
                <h5 class="card-title fw-semibold mb-0 lh-sm">Bus Types</h5>
                <a href="{{ url('/Bus-Type/Add') }}"
                    class="justify-content-center btn btn-sm btn-light-primary text-primary font-medium d-flex align-items-center">
                    <i class="ti ti-plus fs-4 me-2"></i>
                    Add Bus
                </a>
            </div> --}}
        <div class="card-body p-4">

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
                    <thead class="text-dark fs-4">
                        <tr>
                            <th>
                                <h6 class="fs-4 fw-semibold mb-0">Sr#</h6>
                            </th>
                            <th>
                                <h6 class="fs-4 fw-semibold mb-0">Name</h6>
                            </th>
                            <th>
                                <h6 class="fs-4 fw-semibold mb-0">Capacity</h6>
                            </th>
                            <th>
                                <h6 class="fs-4 fw-semibold mb-0">Date</h6>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bus_type as $row)
                            <tr>
                                <td>
                                    <p class="mb-0 fw-normal">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <h6 class="fs-4 fw-semibold mb-0">
                                                {{ $row->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 fw-normal">{{ $row->capacity }}</p>
                                </td>
                                <td>
                                    <p class="mb-0 fw-normal">{{ date('Y-m-d', strtotime($row->created_at)) }}</p>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="#" class="text-muted" id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical fs-6"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center bus_details gap-3"
                                                    data-id="{{ $row->id }}" href="javascript:void()"><i
                                                        class="fs-4 ti ti-info-circle"></i>Details</a>
                                            </li>
                                            @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Dispatcher')
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-3"
                                                        href="{{ url('/Bus-Type/Edit/' . $row->id) }}"><i
                                                            class="fs-4 ti ti-edit"></i>Edit</a>
                                                </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3 delete"
                                                    href="{{ url('/Bus-Type/delete/' . $row->id) }}"><i
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
                            <td colspan="5">{{ $bus_type->links('pagination::bootstrap-5') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    </div>
    <!-- Modal -->

    <div class="modal fade" id="busDetails" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Bus Detail
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="details">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@section('javascript')
    <script>
        $(document).on('click', '#btn-add', function() {
            if ($('.add-bus-card').hasClass('d-none')) {
                $('.add-bus-card').removeClass('d-none');
            } else {
                $('.add-bus-card').addClass('d-none');
            }
        })
        $(document).on('click', '.bus_details', function() {
            var id = $(this).data('id');
            var url = '{{ asset('') }}bus_details/'
            $.ajax({
                url: url + id,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#busDetails').modal('show');
                    $('#details').html(data.html);
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
        $(document).on('click', '.add-more', function() {
            var html = `<div class="row">
                            <div class="col-lg-3 col-12">
                                <div class="mb-4">
                                    <label for="kilometers" class="form-label">Kilometers</label>
                                    <input type="number" class="form-control @error('kilometers') is-invalid @enderror"
                                        value="{{ old('kilometers') }}" name="kilometers[]" id="kilometers">
                                    @error('kilometers')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-12">
                                <div class="mb-4">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price') }}" name="price[]" id="price" step="0.01">
                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-1 col-12 d-flex align-items-center">
                                <a href="javascript:;" class="btn-remove-row" class="text-danger"><i
                                        class="fa fa-times color-primary"></i></a>
                            </div>
                        </div>`;
            $(".more-fields").append(html);
        })
        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('.row').remove();
        });
        $('tbody').on('click', '.truck-detail', function() {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'ajax',
                method: 'POST',
                data: {
                    'id': id
                },
                url: "{{ url('getTruckDetails') }}",
                success: function(res) {
                    if (res) {
                        function formatDate(inputDate) {
                            var parsedDate = new Date(inputDate);
                            var formattedDate = (parsedDate.getMonth() + 1).toString().padStart(2,
                                    '0') + '-' +
                                parsedDate.getDate().toString().padStart(2, '0') + '-' +
                                parsedDate.getFullYear();
                            return formattedDate;
                        }
                        $('#bus_details').modal('toggle');
                        $('#details').html(
                            `<div class="table-reponsive mb-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name </th>
                                        <th>Capacity</th>
                                        <th>Year </th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Date Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>` + res.t_num + `</td>
                                        <td>` + res.vin + `</td>
                                        <td>` + res.year + `</td>
                                        <td>` + res.make + `</td>
                                        <td>` + res.model + `</td>
                                        <td>` + res.created_on + `</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><h5 class="mb-1">Owner Name</h5><p>` + res.owner_name + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">Driver Name</h5><p>` + driver_name + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">Plate Number</h5><p>` + res.plate_number + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">2290 Renewal Date</h5><p>` + formatDate(res
                                .renewal_date_2290) + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">Truck Address</h5><p>` + res.truck_address + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">Trailer</h5><p>` + res.trailer + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">Card Renewal Date</h5><p>` + formatDate(res
                                .card_renew_date) + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">Sticker Renewal Date</h5><p>` + formatDate(res
                                .sticker_renew_date) + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">Damage Insurance Name</h5><p>` + res
                            .damage_insurance_name + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">Insurance Policy Number</h5><p>` + res
                            .insurance_policy_number + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">Damage Effective Date</h5><p>` + formatDate(res
                                .damage_effective_date) + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">Damage Expiry Date</h5><p>` + formatDate(res
                                .damage_expiry_date) + `</p></div>
                            <div class="col-md-6"><h5 class="mb-1">Trailer Registration Renewal Date</h5><p>` +
                            formatDate(res.trailer_reg_renew_date) + `</p></div>

                            <div class="col-md-12"><h5 class="mb-1">2290:</h5>` + report_2290 + `</div>
                            <div class="col-md-12"><h5 class="mb-1">Inspection:</h5>` + inspection + `</div>
                            <div class="col-md-12"><h5 class="mb-1">Cab Card:</h5>` + car_cab + `</div>
                            <div class="col-md-12"><h5 class="mb-1">Truck Lease:</h5>` + truck_lease + `</div>
                            <div class="col-md-12"><h5 class="mb-1">Physical Damage:</h5>` + physical_damage + `</div>
                            <div class="col-md-12"><h5 class="mb-1">Physical Notice:</h5>` + physical_notice + `</div>
                            <div class="col-md-12"><h5 class="mb-1">Trailer Registration:</h5>` + trailer_reg + `</div>
                            <div class="col-md-12"><h5 class="mb-1">W9:</h5>` + w9 + `</div>
                            <div class="col-md-12"><h5 class="mb-1">Saftey Report:</h5>` + saftey_report + `</div>
                        </div>
                        <div class="row"><div class="col-md-12"><h5 class="mb-1">Four Truck Pics:</h5></div>` + imgs +
                            `</div>`


                        );
                    }
                }
            });
        });
    </script>
@endsection
