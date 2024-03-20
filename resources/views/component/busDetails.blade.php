
    <div class="row">
        <div class="col-lg-3 col-12">
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p style="font-weight: bold; white-space: nowrap;">Name:</p>
            </div>
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p style="font-weight: bold; white-space: nowrap;">Capacity:</p>
            </div>
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p style="font-weight: bold; white-space: nowrap;">City Transfer:</p>
            </div>
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p style="font-weight: bold; white-space: nowrap;">One Way Transfer:</p>
            </div>
        </div>
        <div class="col-lg-3 col-12">
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p>{{ $item->name }}</p>
            </div>
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p>{{ $item->capacity }}</p>
            </div>
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p>{{ $item->city_transfer }}</p>
            </div>
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p>{{ $item->one_way_transfer }}</p>
            </div>
        </div>
        <div class="col-lg-3 col-12">
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p style="font-weight: bold; white-space: nowrap;">Short Usage:</p>
            </div>
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p style="font-weight: bold; white-space: nowrap;">Half Day Trip:</p>
            </div>
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p style="font-weight: bold; white-space: nowrap;">Full Day Trip:</p>
            </div>
        </div>
        <div class="col-lg-3 col-12">
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p>{{ $item->short_usage }}</p>
            </div>
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p>{{ $item->half_day_trip }}</p>
            </div>
            <div class="col-12 d-flex align-items-center px-5 mb-3">
                <p>{{ $item->full_day_trip }}</p>
            </div>
        </div>
    </div>
<h4 class="mt-4">Price Per Kilometer</h4>
<div class="table-reponsive mb-3">

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Sr# </th>
                <th>Kilometers </th>
                <th>Price </th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = count($price);
            @endphp
            @foreach ($price as $value)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $value->kilometers }}</td>
                    <td>{{ $value->price }}</td>
                </tr>
            @endforeach
            <tr>
                <td>{{ $count + 1 }}</td>
                <td>Max</td>
                <td>{{ $item->max }}</td>
            </tr>
        </tbody>
    </table>
</div>
