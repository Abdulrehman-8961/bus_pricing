@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid mw-100">

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
                        <select name="category" id="category" style="border: none;" class="form-control">
                            <option value="">Kategorie ausählen</option>
                            <option value="kunden_nr" {{ @$_GET['category'] == 'kunden_nr' ? 'selected' : '' }}>Kunden-Nr</option>
                            <option value="firmenname" {{ @$_GET['category'] == 'firmenname' ? 'selected' : '' }}>Firmenname</option>
                            <option value="kundenname" {{ @$_GET['category'] == 'kundenname' ? 'selected' : '' }}>Kundenname</option>
                            <option value="label" {{ @$_GET['category'] == 'label' ? 'selected' : '' }}>Label</option>
                            <option value="email" {{ @$_GET['category'] == 'email' ? 'selected' : '' }}>E-mail</option>
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
            <div class="card-body">


                <div class="table-responsive rounded-2 mb-4" style="min-height: 200px;">
                    <table class="table text-nowrap customize-table mb-0 align-middle">
                        <thead class="fs-4">
                            <tr>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Bearbeiter</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Kunden-Nr.</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Angebots-Nr.</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">R-Nr.</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">VNR</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Firmename</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Notizen</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Bezahlt</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Buspartner</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Fahrtdatum</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Aktionsmenu</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leads as $row)
                                <tr>
                                    <td>
                                        <p class="mb-0 fw-normal"></p>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <h6 class="fs-4 fw-normal mb-0">{{ $row->customer_number }}
                                                    </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <h6 class="fs-4 fw-normal mb-0">
                                                    </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal"></p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->vnr }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->firmaoptional }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal"></p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal"></p>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-normal">{{ $row->partner_firmnname }}</p>
                                    </td>

                                    <td>
                                        <p class="mb-0 fw-normal"></p>
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
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="11">{{ $leads->links('pagination::bootstrap-5') }}</td>
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
