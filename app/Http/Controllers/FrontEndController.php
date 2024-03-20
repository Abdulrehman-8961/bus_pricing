<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use DateTime;

class FrontEndController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bus_type = DB::table('bus_type')->where('is_deleted', '=', 0)->get();
        return view("frontend.busrechner", compact('bus_type'));
    }

        // Select Bus type
    function waehleBustyp($personenanzahl, $bustypen) {
        if ($personenanzahl <= 78) {
            foreach ($bustypen as $bustyp => $daten) {
                if ($personenanzahl <= $daten->capacity) {
                    return $daten->id;
                }
            }
        } else {
            // For groups larger than 78, combine bus types
            $totalCapacity = 0;
            $selectedBuses = [];
            foreach (array_reverse($bustypen, true) as $bustyp => $daten) { // start with largest bus
                while ($totalCapacity < $personenanzahl) {
                    $totalCapacity += $daten->capacity;
                    $selectedBuses[$bustyp] = ($selectedBuses[$bustyp] ?? 0) + 1;
                    if ($totalCapacity >= $personenanzahl) {
                        break;
                    }
                }
            }
            return $selectedBuses;
        }
        return null; // No suitable bus type found
    }


// Funktion zur Auswahl des Tarifs basierend auf der Entfernung, Function to select tariff based on distance
function waehleTarif($entfernung, $hinfahrtsdatum) {
    $dayOfWeek = strtolower($hinfahrtsdatum->format('l')); // Get the day of the week in lowercase
    if ($entfernung <= 20) {
        return 'one_way_transfer';
    } elseif ($entfernung <= 100) {
        return 'short_usage';
    } elseif ($entfernung <= 150) {
        return 'half_day_trip';
    } else {
        $tarif = 'full_day_trip';
        return $tarif;
    }
}


// Funktion zur Berechnung der Dauer in Dezimaltagen , Function to calculate duration in decimal days
public function berechneDauerInDezimaltagen($hinfahrtsdatum, $rueckfahrtdatum) {
    $dauerStunden = $rueckfahrtdatum->diff($hinfahrtsdatum)->days * 24 + $rueckfahrtdatum->diff($hinfahrtsdatum)->h + ($rueckfahrtdatum->diff($hinfahrtsdatum)->i / 60);
    $dauerTage = $dauerStunden / 24;
    return $dauerTage;
}



// Funktion zur Berechnung, ob der Bus vor Ort bleibt , Function for calculating whether the bus stays on site
function bleibtBusVorOrt($entfernung, $dauerInDezimaltagen) {
    $kmProTag = $entfernung / $dauerInDezimaltagen;
    return $kmProTag > 100;
}



// Funktion zur Berechnung des KM-Preises basierend auf der Entfernung, Function to calculate KM price based on distance
function berechneKmKosten($bustypen, $bustyp, $entfernung) {
    $max = DB::table('bus_type')->where('id',$bustyp)->first();
    // dd($max->max);
    $price_per_km = DB::table('price_per_km')->where('bus_type_id',$bustyp)->get();
    foreach ($price_per_km as $p) {
        $km_max = $p->kilometers;
        $preis = $p->price;
        if ($entfernung <= $km_max) {
            // dd($entfernung * $preis *2);
            return $entfernung * $preis *2;
        }
    }
    // return $entfernung * $bustypen[$bustyp]['km_preis']['max'];
    return $entfernung * $max->max;
}
function isHighSeason($start_date, $end_date) {
    // dd($start_date->format('Y-m-d'),$end_date->format('Y-m-d'));
    // $priceChange = DB::table('saison')->whereDate('start_zeitraum', '<=' ,$start_date->format('Y-m-d'))->whereDate('end_zeitraum', '>=' ,$start_date->format('Y-m-d'))->where('is_deleted',0)->get();
    $priceChange = DB::table('saison')
    ->where(function ($query) use ($start_date, $end_date) {
        $query->where(function ($query) use ($start_date, $end_date) {
            $query->where('start_zeitraum', '<=', $start_date->format('Y-m-d'))
                ->where('end_zeitraum', '>=', $start_date->format('Y-m-d'));
        })
        ->orWhere(function ($query) use ($start_date, $end_date) {
            $query->where('start_zeitraum', '<=', $end_date->format('Y-m-d'))
            ->where('end_zeitraum', '>=', $end_date->format('Y-m-d'));
        });
    })
    ->where('is_deleted',0)
    ->get();
    dd($priceChange);
    if (count($priceChange) > 0) {
        $totalPer = 0;
        foreach ($priceChange as $value) {
            $parts = explode('%',$value->presierhohung);
            $totalPer += $parts[0];
        }
        // dd($totalPer);
        return($totalPer);
    }
    return 0;
    // $month = $date->format('n');
    // $dayOfWeek = strtolower($date->format('l'));
    // return ($month == 6 || $month == 9) && in_array($dayOfWeek, ['friday', 'saturday', 'sunday', 'monday']);

}

// Kalkulationsfunktion, Calculation function
function berechneKostenFuerEinenBus($bustypen, $gewaehlter_bustyp, $entfernung, $hinfahrtsdatum, $rueckfahrtdatum, $bundesland) {
    // dd($bundesland);
    // Wähle den Tarif basierend auf der Entfernung und Hochsaison, Choose the tariff based on distance and peak season
    $service_typ = $this->waehleTarif($entfernung, $hinfahrtsdatum);

    // Berechne die Dauer der Fahrt, Calculate the duration of the journey
    $dauer = $rueckfahrtdatum->diff($hinfahrtsdatum)->h + ($rueckfahrtdatum->diff($hinfahrtsdatum)->i / 60);

    // Überprüfe, ob der Hochsaison-Tarif ausgewählt ist Check that the high season rate is selected
    $isHighSeason = strpos($service_typ, 'high_season') !== false;

    // Holen Sie sich den korrekten Tarifpreis, Get the correct tariff price
    // dd($bustypen);
    $bus_type = DB::table('bus_type')->where('id',$gewaehlter_bustyp)->where('is_deleted',0)->first();
    $fedralPer = 0;
    $fedral = DB::table('bundeslander')->where('id',$bundesland)->first();
    // dd($fedral);
    if($fedral){
        $parts = explode('%',$fedral->presierhohung);
        // dd($parts);
        $fedralPer = $parts[0];
    }
    $totalPer = $this->isHighSeason($hinfahrtsdatum,$rueckfahrtdatum);
    if($totalPer > 0){
        $totalPer = $totalPer + $fedralPer;
        // dd($bus_type->$service_typ);
        $tageskosten = $bus_type->$service_typ*(1 + ($totalPer / 100));
    } else {
        $tageskosten = $bus_type->$service_typ*(1 + ($fedralPer / 100));
    }

    // if ($this->isHighSeason($hinfahrtsdatum)) {
    //     // Hochsaison-Zuschlag
    //     // $tageskosten = $bustypen[$gewaehlter_bustyp][$service_typ]*1.15;
    //     dd($totalPer);
    //     $tageskosten = $bus_type->$service_typ*1.15;
    // } else {
    //     $tageskosten = $bus_type->$service_typ;
    // }

    // Berechne den KM-Preis
    $km_kosten = $this->berechneKmKosten($bustypen, $gewaehlter_bustyp, $entfernung);

    // dd($km_kosten);

    // Entscheide, welcher Preis genommen wird
    $dauerInDezimaltagen = $this->berechneDauerInDezimaltagen($hinfahrtsdatum, $rueckfahrtdatum);

	// Prüfen, ob die Zahl kleiner als 1 ist
		if ($dauerInDezimaltagen < 1) {
		    $ergebnis = 1;
		} else {
		    $ergebnis = number_format($dauerInDezimaltagen, 2, '.', '');
		}

	     $fukmkalkulation =  $entfernung / $ergebnis;
	     $ohnefukmkalkulation =  $entfernung ;
	     $busBleibtVorOrt = $this->bleibtBusVorOrt($entfernung, $dauerInDezimaltagen);
    if ($busBleibtVorOrt && $fukmkalkulation >= 200 && $ergebnis !== 1) {
        // Wenn KM-Kosten höher sind, nimm die goldene Mitte
      return $km_kosten ;
      }
       else if (!$busBleibtVorOrt && $ohnefukmkalkulation >= 199) {
        // Sonst nimm den Tagessatz
       return $km_kosten;
    	}
     else {
        // Sonst nimm den Tagessatz
        return $tageskosten;

    }
}



function berechneKosten($bustypen, $gewaehlter_bustyp, $entfernung, $hinfahrtsdatum, $rueckfahrtdatum, $bundesland) {
    $gesamtkosten = 0;
    // dd($gewaehlter_bustyp);
    if (is_array($gewaehlter_bustyp)) {
        // Handle multiple bus types
        foreach ($gewaehlter_bustyp as $bustyp => $anzahl) {
            for ($i = 0; $i < $anzahl; $i++) {
                $gesamtkosten += $this->berechneKostenFuerEinenBus($bustypen, $bustyp, $entfernung, $hinfahrtsdatum, $rueckfahrtdatum, $bundesland);
            }
        }
    } else {
        // Handle a single bus type
        $gesamtkosten = $this->berechneKostenFuerEinenBus($bustypen, $gewaehlter_bustyp, $entfernung, $hinfahrtsdatum, $rueckfahrtdatum, $bundesland);
    }
// dd($gesamtkosten);
    return $gesamtkosten;
}

    public function calculation(Request $request){
        $personenanzahl = isset($request->personenanzahl) ? intval($request->personenanzahl) : 0;
        $entfernung = isset($request->entfernung) ? intval($request->entfernung) : 0;
        $bundesland = isset($request->bundesland) ? intval($request->bundesland) : 0;
        $hinfahrtsdatum = isset($request->hinfahrtsdatum) ? new DateTime($request->hinfahrtsdatum) : new DateTime();
        $rueckfahrtdatum = isset($request->rueckfahrtdatum) ? new DateTime($request->rueckfahrtdatum) : new DateTime();

        $bustypen = DB::table('bus_type')
        ->where('is_deleted',0)
        ->get();

        // Berechne die Dauer in Dezimaltagen, Calculate the duration in decimal days
        $dauerInDezimaltagen = $this->berechneDauerInDezimaltagen($hinfahrtsdatum, $rueckfahrtdatum);

        // Ausgabe der Dauer in Dezimaltagen, Output of the duration in decimal days
        // echo "Die Gesamtdauer beträgt " . number_format($dauerInDezimaltagen, 2) . " Tage in Dezimalzahlen.\n";
        $dauerInDezimaltagen = max($dauerInDezimaltagen, 1);

        // Auswahl des Bustyps basierend auf der Personenanzahl
        $gewaehlter_bustyp = $this->waehleBustyp($personenanzahl, $bustypen);
        // dd($gewaehlter_bustyp);
        // Führe die Kalkulation durch, wenn ein passender Bustyp gefunden wurde, Carry out the calculation when a suitable bus type has been found
        if ($gewaehlter_bustyp) {
            // Führe die Kalkulation mit dem gewählten Bustyp durch
            $kostenvoranschlag = $this->berechneKosten($bustypen, $gewaehlter_bustyp, $entfernung, $hinfahrtsdatum, $rueckfahrtdatum, $bundesland);

            // Berechne, ob der Bus vor Ort bleibt
            $busBleibtVorOrt = $this->bleibtBusVorOrt($entfernung, $dauerInDezimaltagen);

            // Variable für zusätzliche Informationen
            $zusaetzlicheInfo = "";
            $note = "";


            $kmProTagtagesfahrt =  $entfernung / $dauerInDezimaltagen;
            $kmProohnefu =  $entfernung;
            // Anpassung der Kostenvoranschlag-Berechnung
            if (!$busBleibtVorOrt) {
                // Wenn der Bus nicht vor Ort bleibt, werden die Kosten als einfache Hin- und Rückfahrt berechnet
                if ($kmProohnefu > 199) {
                    $kostenvoranschlag *= 2;

                } else {

                    if ($dauerInDezimaltagen == 1) {$kostenvoranschlag *= 1;}
                    else {

                        $kostenvoranschlag *= 2;

                    }
                }

                $zusaetzlicheInfo = "ohne FU";
            } else {
                // Ansonsten werden die Kosten basierend auf der Dauer in Tagen berechnet


                if ($kmProTagtagesfahrt > 199) {

                    if ($dauerInDezimaltagen == 1) {$kostenvoranschlag *= 1;}
                        else {

                        $kostenvoranschlag *= 2;

                    }

                }
                else {
                // Prüfen, ob die Zahl kleiner als 1 ist
                if ($dauerInDezimaltagen < 1) {
                    $ergebnis = 1;
                } else {
                    if ($dauerInDezimaltagen >= 1.2 && $dauerInDezimaltagen <= 1.99) {
                        // Aufrunden auf 2
                        $dauerInDezimaltagen = 2;
                    }
                    $ergebnis = number_format($dauerInDezimaltagen, 2, '.', '');
                }

                $kostenvoranschlag *= $ergebnis;
                }
                $zusaetzlicheInfo = "+ Fahrerunterkunft (Hinweis: gilt nicht für Fahrten am selben Tag)";
                $note = "(Hinweis: gilt nicht für Fahrten am selben Tag)";
            }

            // echo " Der Kostenvoranschlag beträgt: €" . number_format(ceil($kostenvoranschlag), 2, ',', '.') . "\n";
            // echo $zusaetzlicheInfo . "\n";

        } else {
            // echo "Für die angegebene Personenanzahl ist kein passender Bustyp verfügbar.\n";
        }

        // Berechnung von 5% des Kostenvoranschlags, Calculation of 5% of the estimate
        $abzug = $kostenvoranschlag * 0.07;

        // Abziehen des berechneten Betrags vom ursprünglichen Kostenvoranschlag, Deducting the calculated amount from the original estimate
        $kostenvoranschlag -= $abzug;

        // Angenommen, $kostenvoranschlag ist der Bruttopreis, Assume $estimate is the gross price
        $bruttopreis = ceil($kostenvoranschlag);

        // Berechnung des Nettopreises, Calculation of the net price
        // Die Formel ist: Nettopreis = Bruttopreis / (1 + Mehrwertsteuersatz)
        $mwstSatz = 0.19; // 19% Mehrwertsteuer
        $nettopreis = $bruttopreis / (1 + $mwstSatz);


        $ek = 0.3; // 30% Einkauf
        $gek = $bruttopreis / (1 + $ek);

        // Anzeigen des Nettopreises, formatiert auf zwei Dezimalstellen, Show net price formatted to two decimal places
        //echo number_format($nettopreis, 2, ',', '.');
        $dauerStunden = $rueckfahrtdatum->diff($hinfahrtsdatum)->days * 24 + $rueckfahrtdatum->diff($hinfahrtsdatum)->h + ($rueckfahrtdatum->diff($hinfahrtsdatum)->i / 60);


        return view('frontend.statistik3', compact("dauerInDezimaltagen","dauerStunden","kostenvoranschlag","nettopreis","gek","note","zusaetzlicheInfo"));

    }


}
