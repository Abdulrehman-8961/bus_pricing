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
        // dd($data);
        $combinedAddress = '';
        if (isset($data['content']) && !empty($data['content'])) {
            $content_1 = $data['content'][0];
            $billingAddress = @$content_1['addresses']['billing'][0];
            $company_name = @$content_1['company']['name'];
            $emailAddress = @$content_1['emailAddresses']['private'][0];
            if(!isset($emailAddress)){
                $emailAddress = @$content_1['emailAddresses']['business'][0];
            }
            $lex_phone = @$content_1['phoneNumbers']['private'][0];
            if ($lex_phone == '') {
                # code...
                $lex_phone = @$content_1['phoneNumbers']['business'][0];
            }
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

        // get files

        $curl_file = curl_init();

        $contactId = @$data['content'][0]['id'];

        curl_setopt_array($curl_file, [
            CURLOPT_URL =>
                'https://api.lexoffice.io/v1/voucherlist?voucherType=any&voucherStatus=any&contactId=' .
                @$contactId,
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

        $response_file = curl_exec($curl_file);

        curl_close($curl_file);
        // echo $response;

        $data_file = json_decode($response_file, true);
        // dd($data_file);

        if (!empty($data_file['content'])) {
            $voucherID = $data_file['content'][0]['id'];
            $voucherType = $data_file['content'][0]['voucherType'];
            $voucherStatus = $data_file['content'][0]['voucherStatus'];
            $voucherNumber = $data_file['content'][0]['voucherNumber'];
            $totalAmount = $data_file['content'][0]['totalAmount'];
            $createdDate = $data_file['content'][0]['createdDate'];
        } else {
            $voucherID = 0;
            $voucherType = '';
            $voucherStatus = '';
            $voucherNumber = '';
            $totalAmount = '';
            $createdDate = '';
        }
    @endphp
    <div class="container-fluid mw-100">
        <div class="card w-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <h4 class="mb-4 me-3"><span class="fw-bolder">VNR</span>: {{ $leads->vnr }}</h4>
                        <h4 class="mb-4 me-3"><span class="fw-bolder">Kunden-Nr</span>: {{ $leads->customer_number }}</h4>
                        {{-- <h4 class="mb-4"><span class="fw-bolder">Company</span>: <span style="cursor: pointer;"
                            onclick="edit('{{ $company_name }}','company_name','Company Name')">{{ $company_name }}</span></h4> --}}
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
                                        <option value="Firma" {{ $leads->grund == 'Firma' ? 'selected' : '' }}>Firma
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
                            <div class="d-flex" style="cursor: pointer;"
                                onclick="edit('{{ @$firstName }} {{ @$lastName }}','name','Kontakt')">
                                <i class="ti ti-user-circle me-3"></i>
                                <p class="fw-bolder" style="cursor: pointer;"
                                    onclick="edit('{{ @$firstName }} {{ @$lastName }}','name','Kontakt')">
                                    {{ @$firstName }} {{ @$lastName }}</p>
                            </div>
                            <i class="ti ti-pencil" style="margin-bottom: 18px;"></i>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex" style="cursor: pointer;"
                                onclick="edit('{{ @$emailAddress }}','email','E-mail')">
                                <i class="ti ti-mail me-3"></i>
                                <p style="cursor: pointer;" onclick="edit('{{ @$emailAddress }}','email','E-mail')">
                                    {{ @$emailAddress }}</p>
                            </div>
                            <i class="ti ti-pencil" style="margin-bottom: 18px;"></i>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex" style="cursor: pointer;"
                                onclick="edit('{{ isset($lex_phone) ? $lex_phone : $leads->phone }}','phone','Phone')">
                                <i class="ti ti-phone me-3"></i>
                                <p style="cursor: pointer;"
                                    onclick="edit('{{ isset($lex_phone) ? $lex_phone : $leads->phone }}','phone','Phone')">
                                    {{ isset($lex_phone) ? $lex_phone : $leads->phone }}</p>
                            </div>
                            <i class="ti ti-pencil" style="margin-bottom: 18px;"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <h5 class="mb-3">UNTERNEHMEN</h5>
                        <div class="mb-3 d-flex justify-content-between align-items-center" style="margin-top: 30px;">
                            <div class="d-flex" style="cursor: pointer;"
                                onclick="edit('{{ @$company_name }}','company_name','Company Name')">
                                <i class="ti ti-building me-3"></i>
                                <p class="" style="cursor: pointer;"
                                    onclick="edit('{{ @$company_name }}','company_name','Company Name')">
                                    {{ @$company_name ? @$company_name : 'Firmenname nicht vorhanden' }} </p>
                            </div>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex"
                                @if (isset($billingAddress)) style="cursor: pointer;"
                            onclick="updateAddress('{{ @$billingAddress['street'] }}','{{ @$billingAddress['city'] }}','{{ @$billingAddress['zip'] }}','{{ @$billingAddress['countryCode'] }}')" @endif>
                                <p>Adresse: </p>
                                <p style="cursor: pointer;"
                                    onclick="updateAddress('{{ @$billingAddress['street'] }}','{{ @$billingAddress['city'] }}','{{ @$billingAddress['zip'] }}','{{ @$billingAddress['countryCode'] }}')">
                                    {{ isset($billingAddress) ? @$billingAddress['street'] . ' ' . @$billingAddress['city'] . ' ' . @$billingAddress['zip'] . ' ' . @$billingAddress['countryCode'] : 'Adresse nicht vorhanden' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h5 class="mb-5">FAHRTDETAILS</h5>
                            <p class="mb-3"><u>Hin und Rückfahrt</u></p>
                            <p class="mb-3">Hinfahrt <br>
                                <span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->menu_731 }}','menu_731','Time of departure')">{{ date('H:i', strtotime($leads->menu_731)) }}<i
                                        class="ti ti-pencil ms-2 me-2"></i></span><br>
                                <span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->hinfahrt }}','hinfahrt','Hinfahrt')">{{ date('d.m.Y', strtotime($leads->hinfahrt)) }}<i
                                        class="ti ti-pencil ms-2 me-2"></i></span><br><span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->start }}','start','Address')">{{ $leads->start }}
                                    <i class="ti ti-pencil ms-2"></i></span>
                            </p>
                            <p class="mb-3">Rückfahrt <br>
                                <span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->menu_732 }}','menu_732','Time of return trip')">{{ date('H:i', strtotime($leads->menu_732)) }}<i
                                        class="ti ti-pencil ms-2"></i></span> <br>
                                <span style="cursor: pointer;"
                                    onclick="edit('{{ $leads->rueckfahrtt }}','rueckfahrtt','Rückfahrt')">{{ date('d.m.Y', strtotime($leads->rueckfahrtt)) }}<i
                                        class="ti ti-pencil ms-2"></i></span><br><span style="cursor: pointer;"
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
                                onclick="edit('{{ $leads->notizer }}','notizer','Notizen')"><u>Notizen</u><span></span>
                            </p>
                            <p class="mb-0" style="cursor: pointer;"
                                onclick="edit('{{ $leads->notizer }}','notizer','Notizen')">{{ $leads->notizer }}</p>
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
                                                            <h6 class="fs-4">
                                                                @if ($fileExtension == 'png' || $fileExtension == 'jpeg' || $fileExtension == 'jpg')
                                                                    <i class="ti ti-photo me-2"></i>
                                                                @elseif($fileExtension == 'pdf')
                                                                    <i class="ti ti-file-text me-2"></i>
                                                                @elseif($fileExtension == 'xlsx' || $fileExtension == 'csv')
                                                                    <i class="ti ti-file-analytics me-2"></i>
                                                                @else
                                                                    <i class="ti ti-file me-2"></i>
                                                                @endif
                                                                <a style="text-decoration: none; color: black;"
                                                                    href="{{ asset('public') }}/uploads/{{ $row->description }}"
                                                                    download>{{ $row->description }}</a>
                                                            </h6>
                                                        @else
                                                            <h6 class="fs-4">{{ $row->description }}</h6>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                    @if (!empty($data_file['content']) && $data_file['content'] > 0)
                                        @foreach ($data_file['content'] as $da)
                                            <li
                                                class="timeline-item d-flex position-relative overflow-hidden history all">
                                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                                    <span
                                                        class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                                </div>
                                                <div class="card ms-3 w-100" style="cursor: pointer;"
                                                    onclick="downloadFile('{{ $da['id'] }}','{{ $da['voucherType'] }}')">
                                                    <div class="card-body p-3 px-3">
                                                        <p class="text-muted fs-2">
                                                            {{ date('d. M H:i', strtotime($da['createdDate'])) }} -
                                                            {{ $da['voucherType'] }} - {{ $da['voucherStatus'] }}</p>
                                                        <div class="d-flex justify-content-between">
                                                            <h6 class="fs-4">{{ $da['voucherNumber'] }}</h6>
                                                            <h6 class="fs-4">
                                                                {{ number_format((float) $da['totalAmount'], 2, ',', '') }}
                                                                €</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
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
                                    {{-- @foreach ($documente as $row)
                                        @php
                                            $user = DB::table('users')
                                                ->where('id', @$row->by_user_id)
                                                ->first();
                                        @endphp --}}
                                    @if (!empty($data_file['content']) && $data_file['content'] > 0)
                                        @foreach ($data_file['content'] as $da)
                                            <li
                                                class="timeline-item d-flex position-relative overflow-hidden d-none history dokumente">
                                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                                    <span
                                                        class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                                </div>
                                                <div class="card ms-3 w-100" style="cursor: pointer;"
                                                    onclick="downloadFile('{{ $da['id'] }}','{{ $da['voucherType'] }}')">
                                                    <div class="card-body p-3 px-3">
                                                        <p class="text-muted fs-2">
                                                            {{ date('d. M H:i', strtotime($da['createdDate'])) }} -
                                                            {{ $da['voucherType'] }} - {{ $da['voucherStatus'] }}</p>
                                                        <div class="d-flex justify-content-between">
                                                            <h6 class="fs-4">{{ $da['voucherNumber'] }}</h6>
                                                            <h6 class="fs-4">
                                                                {{ number_format((float) $da['totalAmount'], 2, ',', '') }}
                                                                €</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                    {{-- @endforeach --}}
                                    @php
                                        $all_leads = DB::table('leads')
                                            ->where('is_deleted', 0)
                                            ->where('id', $leads->id)
                                            ->get();
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
                                                    <h6 class="fs-4"><a
                                                            href="{{ url('/Leads/edit/') }}/{{ $row->id }}"
                                                            style="text-decoration: none; color: black;">{{ $row->vnr }}</a>
                                                    </h6>
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
    @php
        $countryCodes = [
            'DE' => 'Germany',
            'US' => 'United States',
            'FR' => 'France',
        ];
    @endphp
    <div class="modal fade" id="editAddress" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ url('Leads/address/update') }}/{{ $leads->id }}" method="post">
                    @csrf
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title">
                            Address
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-3">
                                <label for="">Straße </label>
                                <input type="text" id="street" name="street" class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="">PLZ</label>
                                <input type="text" id="zip_code" name="zip_code" class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="">Land</label>
                                <input type="text" id="country" name="country" class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="">Ländercode</label>
                                <select class="form-control" name="country_code" id="country_code">
                                    @foreach ($countryCodes as $key => $row)
                                        <option value="{{ $key }}">
                                            {{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
    <form id="formDownload" action="{{ url('downloadFile') }}" method="POST">
        <input type="hidden" name="v_id" id="v_id">
        <input type="hidden" name="v_type" id="v_type">
    </form>
@endsection

@section('javascript')
    <script>
        function edit(value, fieldName, title) {
            $('#editField').attr('type', 'text');
            $('#editField').val('');
            if ($('#editField').hasClass('datepicker-autoclose')) {
                $('#editField').datepicker('destroy');
            }
            $('#editField').removeClass("datepicker-autoclose");
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

        function updateAddress(street, city, zip, countryCode) {
            $('#street').val(street);
            $('#zip_code').val(zip);
            $('#country').val(city);
            $('#country_code').val(countryCode);
            $('#editAddress').modal('show');
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

        function downloadFile(v_id, v_type) {
            if(v_id && v_type){
                $('#v_id').val(v_id);
                $('#v_type').val(v_type);
                $('#formDownload').submit();
            }
            // $.ajax({
            //     url: "{{ url('downloadFile') }}",
            //     method: "GET",
            //     data: {
            //         v_id: v_id,
            //         v_type: v_type,
            //     },
            //     success: function(data) {}
            // });
        }
    </script>
@endsection
