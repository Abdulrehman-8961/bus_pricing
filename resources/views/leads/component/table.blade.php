<table class="table text-nowrap customize-table mb-0 align-middle">
    <thead class="fs-4">
        <tr>
            <th>
                <h6 class="fs-4 fw-semibold mb-0">VNR</h6>
            </th>
            <th>
                <h6 class="fs-4 fw-semibold mb-0">Titel</h6>
            </th>
            {{-- <th>
                <h6 class="fs-4 fw-semibold mb-0">Nächste Aktivität</h6>
            </th> --}}
            <th>
                <h6 class="fs-4 fw-semibold mb-0">Labels</h6>
            </th>
            <th>
                <h6 class="fs-4 fw-semibold mb-0">Quelle</h6>
            </th>
            <th>
                <h6 class="fs-4 fw-semibold mb-0">Lead erstellt</h6>
            </th>
            <th>
                <h6 class="fs-4 fw-semibold mb-0">Kundenbetreuer</h6>
            </th>
            <th>
                <h6 class="fs-4 fw-semibold mb-0">Kunden-Nr</h6>
            </th>
            <th>
                <h6 class="fs-4 fw-semibold mb-0">Aktionsmenü</h6>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leads as $row)
            @php
                $user = DB::table('users')
                    ->where('id', $row->kundenbetreuer)
                    ->first();
            @endphp
            <tr>
                <td>
                    <p class="mb-0 fw-normal">{{ $row->vnr }}</p>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="">
                            <h6 class="fs-4 fw-normal mb-0">{{ $row->firstname }}
                                {{ $row->lastname }}
                            </h6>
                        </div>
                    </div>
                </td>
                {{-- <td>
                    <div class="d-flex align-items-center">
                        <div class="">
                            <h6 class="fs-4 fw-normal mb-0">
                            </h6>
                        </div>
                    </div>
                </td> --}}
                <td>
                    @if ($row->grund == 'Privat')
                        <span class="badge fw-semibold py-1 w-85 bg-primary text-white">Privat</span>
                    @elseif($row->grund == 'Verein')
                        <span class="badge fw-semibold py-1 w-85 bg-warning">Verein</span>
                    @elseif($row->grund == 'Firma')
                        <span class="badge fw-semibold py-1 w-85 bg-danger">Firma</span>
                    @else
                        <span class="badge fw-semibold py-1 w-85 bg-info">Schule</span>
                    @endif
                </td>
                <td>
                    <p class="mb-0 fw-normal">{{ $row->quelle }}</p>
                </td>
                <td>
                    <p class="mb-0 fw-normal">{{ date('d. M. Y, H:i', strtotime($row->created_at)) }}
                    </p>
                </td>
                <td>
                    <p class="mb-0 fw-normal">{{ @$user->name }} {{ @$user->last_name }}</p>
                </td>
                <td>
                    <p class="mb-0 fw-normal">{{ $row->customer_number }}</p>
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
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-3 delete"
                                    href="{{ url('/Transfer-To-Deal') }}/{{ $row->id }}">In Deal
                                    umwandeln</a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9">{{ $leads->appends(['search' => $search])->links('pagination::bootstrap-5') }}</td>
        </tr>
    </tfoot>
</table>
