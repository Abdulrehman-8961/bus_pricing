@extends('layouts.dashboard')

@section('content')
    <style>
        .btn-submit {
            margin: 2px;
            background-image: linear-gradient(161deg, #990B0C 82%, #990B0C 100%);
            color: #fff;
            padding-right: 20px;
            padding-left: 20px;
        }

        .btn-submit:hover {
            background-image: linear-gradient(180deg, #2B2B2B 0%, #2B2B2B 100%);
            color: #fff;
        }

        .btn:focus {
            box-shadow: none !important;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .form-label {
            margin-bottom: 1rem;
            /* Add margin between label and input field */
        }

        .form-control {
            border-radius: 12px !important;
        }


        .form-control:focus {
            border-color: #CED4DA !important;
            box-shadow: none !important;
            outline: none;
        }

        .card {
            background-color: rgba(255, 255, 255);
            border-top: 6px solid black;
            border-radius: 0rem !important;
        }

        .custom-background {
            background-color: white;
            padding: 25px;
            /* Abstand von oben */
            margin-top: 25px;
        }
    </style>
    @php
        $items = DB::table('bundeslander')->where('is_deleted', 0)->get();
    @endphp
    <div class="container-fluid mw-100">
        <div class="row mb-2" style="margin-top: 40px">
            <h1 class="text-dark fw-bolder">Get Your Price</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ url('Estimated-Price') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label for="personenanzahl" class="fw-normal form-label">Personenanzahl:</label>
                            <input type="number" class="form-control fo " id="personenanzahl" name="personenanzahl"
                                placeholder="Anzahl der Passagiere" required>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label for="entfernung" class="fw-normal form-label">Entfernung (in km):</label>
                            <input type="number" class="form-control form-contr m" id="entfernung" name="entfernung"
                                placeholder="Entfernung in Kilometern" required>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label for="entfernung" class="fw-normal form-label">Bundesland:</label>
                            <select class="form-control custom-select" name="bundesland">
                                <option value=""></option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" @if (session('selected_id') == $item->id) selected @endif
                                        style="color:black;">{{ $item->bundsland }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label for="hinfahrtsdatum" class="fw-normal form-label">Hinfahrtsdatum und
                                -uhrzeit:</label>
                            <input type="datetime-local" class="form-control  " id="hinfahrtsdatum" name="hinfahrtsdatum"
                                required>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label for="rueckfahrtdatum" class="fw-normal form-label">Rückfahrtdatum und
                                -uhrzeit:</label>
                            <input type="datetime-local" class="form-control  " id="rueckfahrtdatum" name="rueckfahrtdatum"
                                required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-auto mb 1 mt-2">
                            <input type="submit" class="btn btn-submit" value="PREIS BERECHNEN">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="custom-background">
            <!-- Ungeordnete Liste (ul) für die Busdaten -->
            <h3>Bustypen</h3>
            <ul>
                @foreach ($bus_type as $bus)
                    <li>{{ $bus->name }} = {{ $bus->capacity }}</li>
                @endforeach
                {{-- <li>MiniVan = 8</li>
                    <li>Kleinbus = 18</li>
                    <li>Midibus = 28</li>
                    <li>Reisebus = 50</li>
                    <li>ReisebusXL = 57</li>
                    <li>Doppeldecker = 78</li> --}}
            </ul>
        </div>

        <div class="custom-background" style="margin-bottom: 50px;">


            <! Marge- und Rabattberechnung>
                <html>

                <head>
                    <style>
                        #ergebnis,
                        #hinweis {
                            margin-top: 20px;
                        }

                        .hinweis {
                            color: red;
                        }
                    </style>
                    <script>
                        function berechneVerkaufspreise() {
                            var ekNetto = parseFloat(document.getElementById('ek').value);
                            var rabatt = parseFloat(document.getElementById('rabatt').value) || 0;
                            var mwstSatz = 19; // Mehrwertsteuersatz von 19%  VAT rate of 19%
                            var margen = [20, 25, 30];
                            var ergebnisText = "";
                            var mindestmargeAngepasst = false;

                            document.getElementById('hinweis').innerHTML = ""; // Hinweis zurücksetzen  Reset notice

                            // Mindestmarge Anpassung ohne Rabatt für Beträge unter 1300€, Minimum margin adjustment without discount for amounts under €1300
                            if (ekNetto < 1300) {
                                var vkNetto = ekNetto + 200; // Mindestmarge hinzufügen, Add minimum margin
                                var vkBrutto = vkNetto * (1 + mwstSatz /
                                    100); // Brutto berechnen ohne Rabatt, Calculate gross without discount
                                ergebnisText = "<p>Mindestmarge: Netto: " + vkNetto.toFixed(2).replace('.', ',') + "€, Brutto: " + vkBrutto
                                    .toFixed(2).replace('.', ',') +
                                    "€</br><b>Hinweis:</b> Auf die Mindestmarge kann kein Rabatt gewährt werden.</p>";
                                mindestmargeAngepasst = true;
                            }

                            if (!mindestmargeAngepasst) {
                                for (var i = 0; i < margen.length; i++) {
                                    var angepassterRabatt = rabatt;
                                    // Rabattanpassung nur für 20% Marge
                                    if (margen[i] === 20 && rabatt > 4) {
                                        angepassterRabatt = 4; // Rabatt auf 4% beschränken
                                        document.getElementById('hinweis').innerHTML =
                                            "<p class='hinweis'>Bei 20% Marge kann maximal 4% Rabatt gewährt werden.</p>";
                                    }

                                    var vkNettoOhneRabatt = ekNetto + (ekNetto * margen[i] / 100);
                                    var vkNettoMitRabatt = vkNettoOhneRabatt - (vkNettoOhneRabatt * angepassterRabatt /
                                        100); // Rabatt vom VK Netto abziehen
                                    var vkBruttoMitRabatt = vkNettoMitRabatt * (1 + mwstSatz / 100); // Brutto berechnen
                                    var tatsaechlicheMarge = ((vkNettoMitRabatt - ekNetto) / ekNetto) *
                                        100; // Tatsächliche Marge nach Rabatt

                                    ergebnisText += "<p>" + margen[i] + "% Marge: Netto: " + vkNettoMitRabatt.toFixed(2).replace('.', ',') +
                                        "€, Brutto: " + vkBruttoMitRabatt.toFixed(2).replace('.', ',') + "€ (" + tatsaechlicheMarge.toFixed(
                                            2).replace('.', ',') + "% Marge nach Rabatt)" + ((angepassterRabatt > 0) ? " inkl. " +
                                            angepassterRabatt.toFixed(2).replace('.', ',') + "% Rabatt" : "") + "</p>";
                                }
                            }

                            document.getElementById('ergebnis').innerHTML = ergebnisText;
                            return false; // verhindert das Neuladen der Seite
                        }
                    </script>
                </head>

                <body>
                    <h3>Marge- und Rabattberechnung</h3>
                    <form onsubmit="return berechneVerkaufspreise();">
                        <label for="ek">Einkaufspreis (Netto):</label>
                        <input type="number" id="ek" name="ek" step="0.01" required><br><br>

                        <label for="rabatt">Rabatt in % (max. 5%):</label>
                        <input type="number" id="rabatt" name="rabatt" step="0.01" min="0" max="5"
                            onchange="berechneVerkaufspreise();"><br><br>

                        <input type="submit" value="Verkaufspreise berechnen">
                    </form>

                    <div id="hinweis"></div>
                    <div id="ergebnis"></div>
                </body>

                </html>
                <! ENDE Marge- und Rabattberechnung>





        </div>
    </div>
@endsection
@section('javascript')
<script>
    document.getElementById('hinfahrtsdatum').addEventListener('change', function() {
        var hinfahrtsdatum = this.value;
        var rueckfahrtdatumElement = document.getElementById('rueckfahrtdatum');

        if (rueckfahrtdatumElement.value === '' || new Date(rueckfahrtdatumElement.value) < new Date(hinfahrtsdatum)) {
            rueckfahrtdatumElement.value = hinfahrtsdatum;
        }
    });
</script>
@endsection
