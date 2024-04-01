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

        <div class="card w-100 position-relative overflow-hidden add-user-card {{ $errors->any() ? '' : 'd-none' }}">
            <div class="card-body p-4">
                <form method="POST" class="container-fluid" action="{{ url('/Leads/save') }}">@csrf
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
                            <div class="mb-4">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" name="role" id="role">
                                    <option value="">Select Value</option>
                                    <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Dispatcher" {{ old('role') == 'Dispatcher' ? 'selected' : '' }}>
                                        Dispatcher</option>
                                    <option value="Employee" {{ old('role') == 'Employee' ? 'selected' : '' }}>Employee
                                    </option>
                                </select>
                                @error('phone_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 ">
                            <div class="mb-4">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" id="exampleInputPassword1">
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 ">
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


        <form class="container-fluid" method="GET" action="{{ url()->current() }}">
            <div class="row">
                <div class="d-flex col-lg-8 col-md-8 col-12 mb-3 py-3 px-2">
                    <button type="button" class="btn btn-success me-3" style="white-space: nowrap;"><i class="fa fa-plus"></i> Neukunde</button>
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
                                    <h6 class="fs-4 fw-semibold mb-0">Kunden-Nr.</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Firmenname</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Kundenname</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Label</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">E-mail</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Angelegt am</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Aktionsmenü</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leads as $row)
                                <tr>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->customer_number }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <h6 class="fs-4 fw-normal mb-0">{{ $row->firmaoptional }}
                                                    </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <h6 class="fs-4 fw-normal mb-0">{{ $row->firstname }} {{ $row->lastname }}
                                                    </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">@if ($row->grund == 'Privat')
                                            Privat
                                        @elseif($row->grund == 'Verein')
                                            Verein
                                        @else
                                            Schule
                                        @endif</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->email }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ date('d.m.Y',strtotime($row->created_at)) }}</p>
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
                                                        href="{{ url('/Leads/delete') }}/{{ $row->id }}"></i>Löschen</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">{{ $leads->links('pagination::bootstrap-5') }}</td>
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
    </script>
@endsection
