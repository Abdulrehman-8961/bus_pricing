@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid mw-100">
        <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Add Bus Type</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted " href="{{ url('/Bus-Type') }}">Bus Types</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Add Bus Type</li>
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
        </div>
        <div class="card w-100 position-relative overflow-hidden">
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
                                    name="city_transfer" id="city_transfer">
                                @error('city_transfer')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-12 ">
                            <div class="mb-4">
                                <label for="one_way_transfer" class="form-label">One Way Transfer</label>
                                <input type="number" class="form-control @error('one_way_transfer') is-invalid @enderror"
                                    name="one_way_transfer" id="one_way_transfer">
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
                                        class="fa fa-times"></i></a>
                            </div>
                        </div>
                        <div class="more-fields"></div>
                        <div class="row">
                            <div class="col-lg-1">
                                <button type="button" class="btn btn-primary mt-3 add-more px-2 mb-5"><i class="fa fa-plus"></i>
                                    Add More</button>

                        </div>
                        <div class="col-md-12 mb-4">
                            <button type="submit" class="btn btn-primary py-8  rounded-2">Save Changes</button>
                            <button type="button" onclick="window.location.reload();"
                                class="btn btn-warning py-8  rounded-2 ms-2">Discard</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
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
                                        class="fa fa-times"></i></a>
                            </div>
                        </div>`;
            $(".more-fields").append(html);
        })
        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('.row').remove();
        });
    </script>
@endsection
