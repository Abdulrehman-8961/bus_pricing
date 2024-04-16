@extends('layouts.dashboard')

@section('content')
    @php
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.lexoffice.io/v1/contacts?number=' . @$leads->customer_number,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E',
                'Accept: application/json',
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;

        $data = json_decode($response, true);
        $combinedAddress = '';
        if (isset($data['content']) && !empty($data['content'])) {
            $content_1 = $data['content'][0];
            $billingAddress = @$content_1['addresses']['billing'][0];
            $company_name = @$content_1['company']['name'];
            if ($billingAddress) {
                $combinedAddress = implode(', ', array_filter($billingAddress));
            }
            $contents = $data['content'];
            $values = [];
            foreach ($contents as $content) {
                // dd($content);
                $resuorceid = $content['id'];
                $customer_number = @$content['roles']['customer']['number'];
                $firstName = @$content['person']['firstName'];
                $lastName = @$content['person']['lastName'];
                if ($firstName == '' && $lastName == '') {
                    $firstName = @$content['company']['contactPersons'][0]['firstName'];
                    $lastName = @$content['company']['contactPersons'][0]['lastName'];
                }
                $values[] = [
                    'name' => $firstName . ' ' . $lastName,
                    'customer_number' => $customer_number,
                ];
            }
        }
    @endphp
    <div class="container-fluid mw-100">
        <div class="card w-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <h4 class="mb-4 me-3"><span class="fw-bolder">VNR</span>: {{ $leads->vnr }}</h4>
                        <h4 class="mb-4 me-3"><span class="fw-bolder">Kunden-Nr</span>: {{ $leads->customer_number }}</h4>
                        <h4 class="mb-4"><span class="fw-bolder">Company</span>: <span style="cursor: pointer;"
                            onclick="edit('{{ $company_name }}','company_name','Company Name')">{{ $company_name }}</span></h4>
                    </div>
                    <a href="{{ url('/Transfer-To-Deal') }}/{{ $leads->id }}" class="btn-success mb-4">In Deal
                        umwandeln</a>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <h5 class="mb-3">DETAILS</h5>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-tag me-3"></i>Label:
                                {{-- <p class="bg-dark text-white">FIRMENKUNDE</p> --}}
                                <form id="label-form" action="{{ url('update-label') }}/{{ $leads->id }}" method="POST">
                                    @csrf
                                    <select name="label" id="label" class="form-control" style="border: none"
                                        onchange="document.getElementById('label-form').submit();">
                                        <option value=""></option>
                                        <option value="Privat" {{ $leads->grund == 'Privat' ? 'selected' : '' }}>Privat
                                        </option>
                                        <option value="Verein" {{ $leads->grund == 'Verein' ? 'selected' : '' }}>Verein
                                        </option>
                                        <option value="Schule&#047;Universität"
                                            {{ $leads->grund == 'Schule&#047;Universität' ? 'selected' : '' }}>Schule
                                        </option>
                                    </select>
                                </form>
                            </div>
                            <i class="ti ti-pencil"></i>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-user-circle me-3"></i>KB:
                                {{-- <p>Volkan</p> --}}
                                @php
                                    $employee = DB::table('users')->where('role', 'Employee')->get();
                                @endphp
                                <form id="employee-form" action="{{ url('update-Employee') }}/{{ $leads->id }}"
                                    method="POST">
                                    @csrf
                                    <select class="form-control" name="employee" id="employee" style="border: none;"
                                        onchange="document.getElementById('employee-form').submit();">
                                        <option value=""></option>
                                        @foreach ($employee as $row)
                                            <option value="{{ $row->id }}"
                                                {{ $leads->kundenbetreuer == $row->id ? 'selected' : '' }}>
                                                {{ $row->name }} {{ $row->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                            <i class="ti ti-pencil"></i>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-user-circle me-3"></i>Quelle:
                                {{-- <p>Manuell erstellt</p> --}}
                                <form id="quelle-form" action="{{ url('update-quelle') }}/{{ $leads->id }}"
                                    method="POST">
                                    @csrf
                                    <select class="form-control" name="quelle" id="quelle" style="border: none;"
                                        onchange="document.getElementById('quelle-form').submit();">
                                        <option value=""></option>
                                        <option value="Online" {{ $leads->quelle == 'Online' ? 'selected' : '' }}>Online
                                        </option>
                                        <option value="E-Mail" {{ $leads->quelle == 'E-Mail' ? 'selected' : '' }}>E-Mail
                                        </option>
                                        <option value="Anruf" {{ $leads->quelle == 'Anruf' ? 'selected' : '' }}>Anruf
                                        </option>
                                    </select>
                                </form>
                            </div>
                            <i class="ti ti-pencil"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <h5 class="mb-3">KONTAKT</h5>
                        <div class="mb-3 d-flex justify-content-between align-items-center" style="margin-top: 27px;">
                            <div class="d-flex">
                                <i class="ti ti-user-circle me-3"></i>
                                <p class="fw-bolder" style="cursor: pointer;"
                                    onclick="edit('{{ $leads->firstname }} {{ $leads->lastname }}','name','Kontakt')">
                                    {{ $leads->firstname }} {{ $leads->lastname }}</p>
                            </div>
                            <i class="ti ti-pencil" style="margin-bottom: 18px;"></i>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <i class="ti ti-mail me-3"></i>
                                <p style="cursor: pointer;" onclick="edit('{{ $leads->email }}','email','E-mail')">
                                    {{ $leads->email }}</p>
                            </div>
                            <i class="ti ti-pencil" style="margin-bottom: 18px;"></i>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <i class="ti ti-phone me-3"></i>
                                <p style="cursor: pointer;" onclick="edit('{{ $leads->phone }}','phone','Phone')">
                                    {{ $leads->phone }}</p>
                            </div>
                            <i class="ti ti-pencil" style="margin-bottom: 18px;"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <h5 class="mb-3">UNTERNEHMEN</h5>
                        <div class="mb-3 d-flex justify-content-between align-items-center" style="margin-top: 30px;">
                            <div class="d-flex">
                                <i class="ti ti-building me-3"></i>
                                <p class=""> </p>
                            </div>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <p>Address:</p>
                                <p>{{ $combinedAddress }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h5 class="mb-5">FAHRTDETAILS</h5>
                            <p class="mb-3"><u>Hin und Rückfahrt</u></p>
                            <p class="mb-0">Hinfahrt
                                <span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->menu_731 }}','menu_731','Time of departure')">{{ date('H:i', strtotime($leads->menu_731)) }}<i
                                        class="ti ti-pencil ms-2 me-2"></i></span>
                                <span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->hinfahrt }}','hinfahrt','Hinfahrt')">{{ date('d.m.Y', strtotime($leads->hinfahrt)) }}<i
                                        class="ti ti-pencil ms-2 me-2"></i></span><span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->start }}','start','Address')">{{ $leads->start }}
                                    <i class="ti ti-pencil ms-2"></i></span>
                            </p>
                            <p class="mb-0">Rückfahrt
                                <span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->menu_732 }}','menu_732','Time of return trip')">{{ date('H:i', strtotime($leads->menu_732)) }}<i
                                        class="ti ti-pencil ms-2"></i></span>
                                <span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->rueckfahrtt }}','rueckfahrtt','Rückfahrt')">{{ date('d.m.Y', strtotime($leads->rueckfahrtt)) }}<i
                                        class="ti ti-pencil ms-2"></i></span><span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->ziel }}','ziel','Address')">
                                    {{ $leads->ziel }}
                                    <i class="ti ti-pencil ms-2"></i></span>
                            </p>
                            <p class="mb-0" style="cursor: pointer;"
                                onclick="edit('{{ $leads->pax }}','pax','Pax')">PAX.
                                {{ $leads->pax }}<span><i class="ti ti-pencil ms-2 me-2"></i></span></p>
                            <p class="mb-0" style="cursor: pointer;"
                                onclick="edit('{{ $leads->entfernung }}','entfernung','Entfernung')">Entfernung.
                                {{ $leads->entfernung }} Km<span><i class="ti ti-pencil ms-2 me-2"></i></span></p>
                        </div>
                        <div class="mb-3">
                            <p class="mb-3"><u>Nachricht vom Kunden</u></p>
                            <p class="mb-0">{{ $leads->bemerkung }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="mb-3" style="cursor: pointer;"
                                onclick="edit('{{ $leads->notizer }}','notizer','Notizer')"><u>Notizen</u><span></span>
                            </p>
                            <p class="mb-0" style="cursor: pointer;"
                                onclick="edit('{{ $leads->notizer }}','notizer','Notizer')">{{ $leads->notizer }}</p>
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-top: -60px;">
                        <h4 class="card-title">VERLAUF</h4>
                        <div class="card">
                            <div class="card-body bg-light" style="max-height: 300px; overflow-y: auto;">
                                <ul class="timeline-widget mb-0 position-relative mb-n5">
                                    <div class="d-flex mb-4">
                                        <div class="me-1">
                                            <input type="radio" class="btn-check" name="options" id="option1"
                                                autocomplete="off" value="all" checked>
                                            <label class="btn px-1 font-small" for="option1">Alle</label>
                                        </div>
                                        <div class="me-1">
                                            <input type="radio" class="btn-check" name="options" id="option2"
                                                autocomplete="off" value="notizen">
                                            <label class="btn px-1 font-small" for="option2">Notizen</label>
                                        </div>
                                        {{-- <div class="me-1">
                                            <input type="radio" class="btn-check" name="options" id="option3"
                                                autocomplete="off" value="aktivitation">
                                            <label class="btn px-1 font-small" for="option3">Aktivitation</label>
                                        </div> --}}
                                        {{-- <div class="me-1">
                                            <input type="radio" class="btn-check" name="options" id="option4"
                                                autocomplete="off" value="email">
                                            <label class="btn px-1 font-small" for="option4"
                                                style="white-space: nowrap;">E-mail</label>
                                        </div> --}}
                                        <div class="me-1">
                                            <input type="radio" class="btn-check" name="options" id="option5"
                                                autocomplete="off" value="dateien">
                                            <label class="btn px-1 font-small" for="option5">Dateien</label>
                                        </div>
                                        <div class="me-1">
                                            <input type="radio" class="btn-check" name="options" id="option6"
                                                autocomplete="off" value="dokumente">
                                            <label class="btn px-1 font-small" for="option6">Dokumente</label>
                                        </div>
                                        <div class="me-1">
                                            <input type="radio" class="btn-check" name="options" id="option7"
                                                autocomplete="off" value="leads">
                                            <label class="btn px-1 font-small" for="option7">Leads</label>
                                        </div>
                                        <div class="me-1">
                                            <input type="radio" class="btn-check" name="options" id="option8"
                                                autocomplete="off" value="deals">
                                            <label class="btn px-1 font-small" for="option8">Deals</label>
                                        </div>
                                    </div>
                                    @php
                                        $history = DB::table('log_history')
                                            ->where('lead_id', $leads->id)
                                            ->orderBy('id', 'desc')
                                            ->get();
                                        $notizen = DB::table('log_history')
                                            ->where('lead_id', $leads->id)
                                            ->where('description', 'like', '%notiz%')
                                            ->orderBy('id', 'desc')
                                            ->get();
                                        $aktivitaten = DB::table('log_history')
                                            ->where('lead_id', $leads->id)
                                            ->where('description', 'like', '%aktivitaten%')
                                            ->orderBy('id', 'desc')
                                            ->get();
                                        $email = DB::table('log_history')
                                            ->where('lead_id', $leads->id)
                                            ->where('description', 'like', '%E-mail%')
                                            ->orderBy('id', 'desc')
                                            ->get();
                                        $dateien = DB::table('log_history')
                                            ->where('lead_id', $leads->id)
                                            ->where('description', 'like', '%dateien%')
                                            ->orderBy('id', 'desc')
                                            ->get();
                                        $documente = DB::table('log_history')
                                            ->where('lead_id', $leads->id)
                                            ->where('description', 'like', '%documente%')
                                            ->orderBy('id', 'desc')
                                            ->get();
                                        $leads_data = DB::table('log_history')
                                            ->where('lead_id', $leads->id)
                                            ->where(function ($query) {
                                                $query
                                                    ->where('description', 'like', '%E-mail%')
                                                    ->orWhere('description', 'like', '%notiz%');
                                            })
                                            ->orderBy('id', 'desc')
                                            ->get();
                                    @endphp
                                    @foreach ($history as $row)
                                        @php
                                            $user = DB::table('users')
                                                ->where('id', @$row->by_user_id)
                                                ->first();
                                        @endphp
                                        @if ($user)
                                            <li class="timeline-item d-flex position-relative overflow-hidden history all">
                                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                                    <span
                                                        class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                                </div>
                                                <div class="card ms-3">
                                                    <div class="card-body p-3 px-3">
                                                        <p class="text-muted fs-2">
                                                            {{ date('d. M H:i', strtotime($row->created_at)) }} -
                                                            {{ $user->name }} {{ $user->last_name }}</p>
                                                        @php
                                                            $fileExtension = pathinfo(
                                                                $row->description,
                                                                PATHINFO_EXTENSION,
                                                            );
                                                        @endphp
                                                        @if (!empty($fileExtension))
                                                            <h6 class="fs-4"><a
                                                                    style="text-decoration: none; color: black;"
                                                                    href="{{ asset('public') }}/uploads/{{ $row->description }}"
                                                                    download>{{ $row->description }}</a></h6>
                                                        @else
                                                            <h6 class="fs-4">{{ $row->description }}</h6>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                    @foreach ($notizen as $row)
                                        @php
                                            $user = DB::table('users')
                                                ->where('id', @$row->by_user_id)
                                                ->first();
                                        @endphp
                                        @if ($user)
                                            <li
                                                class="timeline-item d-flex position-relative overflow-hidden d-none history notizen">
                                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                                    <span
                                                        class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                                </div>
                                                <div class="card ms-3">
                                                    <div class="card-body p-3 px-3">
                                                        <p class="text-muted fs-2">
                                                            {{ date('d. M H:i', strtotime($row->created_at)) }} -
                                                            {{ $user->name }} {{ $user->last_name }}</p>
                                                        <h6 class="fs-4">{{ $row->description }}</h6>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                    {{-- @foreach ($aktivitaten as $row)
                                        @php
                                            $user = DB::table('users')
                                                ->where('id', @$row->by_user_id)
                                                ->first();
                                        @endphp
                                        @if ($user)
                                            <li
                                                class="timeline-item d-flex position-relative overflow-hidden d-none history aktivitation">
                                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                                    <span
                                                        class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                                </div>
                                                <div class="card ms-3">
                                                    <div class="card-body p-3 px-3">
                                                        <p class="text-muted fs-2">
                                                            {{ date('d. M H:i', strtotime($row->created_at)) }} -
                                                            {{ $user->name }} {{ $user->last_name }}</p>
                                                        <h6 class="fs-4">{{ $row->description }}</h6>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach --}}
                                    {{-- @foreach ($email as $row)
                                        @php
                                            $user = DB::table('users')
                                                ->where('id', @$row->by_user_id)
                                                ->first();
                                        @endphp
                                        @if ($user)
                                            <li
                                                class="timeline-item d-flex position-relative overflow-hidden d-none history email">
                                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                                    <span
                                                        class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                                </div>
                                                <div class="card ms-3">
                                                    <div class="card-body p-3 px-3">
                                                        <p class="text-muted fs-2">
                                                            {{ date('d. M H:i', strtotime($row->created_at)) }} -
                                                            {{ $user->name }} {{ $user->last_name }}</p>
                                                        <h6 class="fs-4">{{ $row->description }}</h6>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach --}}
                                    <li
                                        class="timeline-item d-flex position-relative overflow-hidden d-none history dateien">
                                        <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                            <span
                                                class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                            <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                        </div>
                                        <div class="card ms-3">
                                            <div class="card-body p-3 px-3">
                                                <form id="upload_image"
                                                    action="{{ url('upload_file') }}/{{ $leads->id }}" method="post"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="file" name="upload" class="form-control"
                                                        id="upload">
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                    @foreach ($documente as $row)
                                        @php
                                            $user = DB::table('users')
                                                ->where('id', @$row->by_user_id)
                                                ->first();
                                        @endphp
                                        @if ($user)
                                            <li
                                                class="timeline-item d-flex position-relative overflow-hidden d-none history dokumente">
                                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                                    <span
                                                        class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                                </div>
                                                <div class="card ms-3">
                                                    <div class="card-body p-3 px-3">
                                                        <p class="text-muted fs-2">
                                                            {{ date('d. M H:i', strtotime($row->created_at)) }} -
                                                            {{ $user->name }} {{ $user->last_name }}</p>
                                                        <h6 class="fs-4">{{ $row->description }}</h6>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                            @php
                                                $all_leads = DB::table('leads')->where('is_deleted',0)->orderBy('id','desc')->get();
                                            @endphp
                                    @foreach ($all_leads as $row)
                                        @php
                                            // dd($row);
                                        @endphp
                                        <li
                                            class="timeline-item d-flex position-relative overflow-hidden d-none history leads">
                                            <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                                <span
                                                    class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                                <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                            </div>
                                            <div class="card ms-3">
                                                <div class="card-body p-3 px-3">
                                                    <h6 class="fs-4"><a href="{{ url('/Leads/edit/') }}/{{ $row->id }}" style="text-decoration: none; color: black;">{{ $row->vnr }}</a></h6>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    @php
                                        $current_deals = DB::table('leads')
                                            ->where('vnr', $leads->vnr)
                                            ->where('in_deal', 1)
                                            ->get();
                                    @endphp
                                    @foreach ($current_deals as $row)
                                        @php

                                        @endphp
                                        <li
                                            class="timeline-item d-flex position-relative overflow-hidden d-none history deals">
                                            <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                                <span
                                                    class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                                <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                            </div>
                                            <div class="card ms-3">
                                                <div class="card-body p-3 px-3">
                                                    <h6 class="fs-4" style="cursor: pointer;"
                                                        onclick="window.location = '{{ url('/Deals') }}'">
                                                        {{ $row->firstname }} {{ $row->lastname }}</h6>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ url('Leads/update') }}/{{ $leads->id }}" method="post">
                    @csrf
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myModalLabel">

                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="editField" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">
                            Abbrechen
                        </button>
                        <button type="submit" class="btn btn-success">
                            Speichern
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('javascript')
    <script>
        function edit(value, fieldName, title) {
            $('.modal-title').html(title);
            $('#editField').attr("name", fieldName);
            if (fieldName == "hinfahrt" || fieldName == "rueckfahrtt") {
                $('#editField').addClass("datepicker-autoclose");
                jQuery(".datepicker-autoclose").datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    // startDate: new Date()
                })
            } else if (fieldName == "menu_732" || fieldName == "menu_731") {
                $('#editField').attr('type', 'time');
                $('#editField').val(value);
            } else {
                $('#editField').val(value);
            }
            $('#editmodal').modal('show');
        }
        // jQuery(".datepicker-autoclose").datepicker({
        //     autoclose: true,
        //     todayHighlight: true,
        //     startDate: new Date()
        // })
        $(document).ready(function() {
            $('input[type="radio"]').change(function() {
                var value = $(this).val();
                $('.history').addClass('d-none');
                $('.' + value).removeClass('d-none');
            });
            $('#upload').change(function() {
                $('#upload_image').submit();
            });
        });
    </script>
@endsection
