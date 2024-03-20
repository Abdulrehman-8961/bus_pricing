@extends('layouts.dashboard')

@section('content')
    <style>

        .card {
            background-color: rgba(255, 255, 255);
            border-top: 6px solid black;
            border-radius: 0rem !important;
            /* Set the background color of the card with some opacity */
        }

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

        .form-label {
            margin-bottom: 1rem;
            /* Add margin between label and input field */
        }

        .form-control {
            border-radius: 0.50rem !important;
        }


        .form-control:focus {
            border-color: #CED4DA !important;
            box-shadow: none !important;
            outline: none;
        }

        .custom-background {
            background-color: white;
            padding: 25px;
            /* Abstand von oben */
            margin-top: 25px;
        }
    </style>
    <div class="container-fluid mw-100">
        <!-- <div class="row mb-3 mt-2">
            <p class="text-white fs-2">Deutschland und Europaweit</p>
        </div>
        <div class="row mb-2">
            <h1 class="text-white fw-bolder">Eigenen Bus mit Fahrer mieten</h1>
        </div> -->
        <div class="card  mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <p>Die Gesamtdauer beträgt <span class="fw-bold"><?php echo number_format($dauerInDezimaltagen, 2); ?></span> Tage in
                            Dezimalzahlen und <?php echo number_format($dauerStunden, 2); ?> Stunden</p>
                    </div>
                </div>
                <?php if($kostenvoranschlag){ ?>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <p>Der Kostenvoranschlag beträgt: <span class="fw-bold">€<?php echo number_format(ceil($kostenvoranschlag), 2, ',', '.'); ?></span>.</p>
                        <p> Nettopreis: <span class="fw-bold">€<?php echo number_format(ceil($nettopreis), 2, ',', '.'); ?></span>.</p>
                        <p> Geschätzter EK: <span class="fw-bold">€<?php echo number_format(ceil($gek), 2, ',', '.'); ?> inkl.</span></p>

                        <?php if($note != ""){ ?>
                        <p class="fw-bold"><?php echo $zusaetzlicheInfo; ?>
                        </p>
                        <?php } ?>
                    </div>
                </div>
                <?php } else { ?>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <p class="fw-bold">Für die angegebene Personenanzahl ist kein passender Bustyp verfügbar.</p>
                    </div>
                </div>
                <?php }  ?>
                <div class="row">
                    <div class="col-auto mb-3">
                        <a href="{{ url('/Buspreiskalkulation') }}" class="btn btn-submit">ZURÜCK</a>
                    </div>


                </div>
            </div>
        </div>



        <div class="custom-background">
            <!-- Ungeordnete Liste (ul) für die Busdaten -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h3>Bundeslandzuschläge</h3>
                    <ul>
                        <li>Schleswig-Holstein + 15%</li>
                        <li>Mecklenburg-Vorpommern + 20%</li>
                        <li>Bayern + 20%</li>
                        <li>Baden-Württemberg + 20%</li>
                    </ul>
                </div>

                <div class="col-md-4 mb-3">
                    <h3>Saisonzuschläge</h3>
                    <ul>
                        <li>Juni & September immer von Freitags bis Montags + 20%</li>
                        <li style="margin-top:15px;  background: #e3e3e3; padding: 25px;"><b>Hinweis:</b> Bitte an
                            Wochenenden im Juni und September aufgrund von Kapazitätsengpässen stets mit der Dispo
                            Rücksprache halten.</li>
                    </ul>
                </div>

                <div class="col-md-4 mb-3">
                    <h3>EM 2024</h3>
                    <ul>
                        <li>Juni & Juli vorläufig immer +5% <br>(außer Fr-Mo im Juni +20%)</li>

                    </ul>
                </div>

            </div>
        </div>


        <!-- ENDE -->
    </div>

@endsection
