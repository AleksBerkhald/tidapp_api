<?php

declare (strict_types=1);
require_once __DIR__ . '/../src/tasks.php';

/**
 * Funktion för att testa alla aktiviteter
 * @return string html-sträng med resultatet av alla tester
 */
function allaTaskTester(): string {
// Kom ihåg att lägga till alla testfunktioner
    $retur = "<h1>Testar alla uppgiftsfunktioner</h1>";
    $retur .= test_HamtaEnUppgift();
    $retur .= test_HamtaUppgifterSida();
    $retur .= test_RaderaUppgift();
    $retur .= test_SparaUppgift();
    $retur .= test_UppdateraUppgifter();
    return $retur;
}

/**
 * Tester för funktionen hämta uppgifter för ett angivet sidnummer
 * @return string html-sträng med alla resultat för testerna 
 */
function test_HamtaUppgifterSida(): string {
    $retur = "<h2>test_HamtaUppgifterSida</h2>";
    
    try {
    //misslyckas med hämta sida -1
    $svar = hamtaSida("-1");
    if ($svar->getStatus() === 400) {
        $retur.= "<p class='ok'>Hämta uppgifter för sida -1 misslyckades som förväntat</p>";
    } else {
        $retur.= "<p class='error'>Misslyckat med att hämta sida -1<br>"
        . $svar->getStatus() . "Returnerades istället för förväntat 400</p>";
    }

    //misslyckas med hämta sida 0
    $svar = hamtaSida("0");
    if ($svar->getStatus() === 400) {
        $retur.= "<p class='ok'>Hämta uppgifter för sida 0 misslyckades som förväntat</p>";
    } else {
        $retur.= "<p class='error'>Misslyckat med att hämta sida 0<br>"
        . $svar->getStatus() . "Returnerades istället för förväntat 400</p>";
    }


    //misslyckas med hämta sida sju
    $svar = hamtaSida("sju");
    if ($svar->getStatus() === 400) {
        $retur.= "<p class='ok'>Hämta uppgifter för sida <i>sju</i> misslyckades som förväntat</p>";
    } else {
        $retur.= "<p class='error'>Misslyckat med att hämta sida -<i>sju</i><br>"
        . $svar->getStatus() . "Returnerades istället för förväntat 400</p>";
    }


    //lyckas med att hämta sida 1
    $svar = hamtaSida("1", 2);
    if ($svar->getStatus() === 200) {
        $retur.= "<p class='ok'>lyckades med att hämta sida</p>";
        $sista = $svar->getContent()->pages;
    } else {
        $retur.= "<p class='error'>misslyckat test att hämta sida 1<br>"
        . $svar->getStatus() . " returnerades istället för förväntat 200</p>";
    }

    //misslyckas med att hämta > antal sidor
    if(isset($sista)) {
        $sista++;
        $svar=hamtaSida("$sista", 2);
        if ($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades med att hämta sida > antal sidor, som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckat test att hämta sida > antal sidor<br>"
                . $svar->getStatus() . "returnerades istället för förväntat 400</p>";
        }
    }

    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Test för funktionen hämta uppgifter mellan angivna datum
 * @return string html-sträng med alla resultat för testerna
 */
function test_HamtaAllaUppgifterDatum(): string {
    $retur = "<h2>test_HamtaAllaUppgifterDatum</h2>";

    try {
        // misslyckas med från = igår till = 2024-01-01
        $svar = hamtaDatum('Igår', "2024-01-01");
        if($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades med att hämta poster mellan <i>igår</i> och 2024-01-01 som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckat test med att hämta poster mellan <i>igår</i> och 2024-01-01<br>"
            . $svar->getStatus() . "returnerades istället för förväntat 400</p>";
        }

        //misslyckaes med från 2024-01-01 till = imorgon
        $svar = hamtaDatum("2024-01-01", "Imorgon");
        if($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades med att hämta poster mellan 2024-01-01 och <i>imorgon</i> som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckat test med att hämta poster mellan 2024-01-01 och <i>imorgon</i><br>"
            . $svar->getStatus() . "returnerades istället för förväntat 400</p>";
        }

        //misslyckas med från=2024-12-07 till 2024-01-01
        $svar = hamtaDatum("2024-12-07", "2024-01-01");
        if($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades med att hämta poster mellan 2024-12-07 och 2024-01-01 som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckat test med att hämta poster mellan 2024-12-07 och 2024-01-01<br>"
            . $svar->getStatus() . "returnerades istället för förväntat 400</p>";
        }

        //misslyckas med från 2024-01-01 till 2024-01-37
        $svar = hamtaDatum("2024-01-01", "2024-01-37");
        if($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades med att hämta poster mellan 2024-01-01 och 2024-01-37 som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckat test med att hämta poster mellan 2024-01-01 och 2024-01-37<br>"
            . $svar->getStatus() . "returnerades istället för förväntat 400</p>";
        }

        //misslyckas med från2024-01-01 till 2023-01-01
        $svar = hamtaDatum("2024-01-01", "2023-01-01");
        if($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades med att hämta poster mellan 2024-01-01 och 2023-01-01 som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckat test med att hämta poster mellan 2024-01-01 och 2023-01-01<br>"
            . $svar->getStatus() . "returnerades istället för förväntat 400</p>";
        }

        //lyckas med korrekta datum
        //leta upp en månad mes poster
        $db = connectDb();
        $stmt = $db->query("SELECT YEAR(datum), MONTH(datum), COUNT(*) AS antal "
            . "FROM uppgifter "
            . "GROUP BY YEAR(datum), MONTH(datum) "
            . "ORDER BY antal DESC "
            . "LIMIT 0,1");
        $row=$stmt->fetch();
        $ar=$row[0];
        $manad=substr("0$row[1]",-2);
        $antal=$row[2];

        //hämta alla poster från den funna månaden
        $svar = hamtaDatum("$ar-$manad-01", date("Y-m-d", strtotime("Last day of $ar-$manad")));
        if($svar->getStatus() === 200 && count($svar->getContent()->tasks)===$antal) {
            $retur .="<p class='ok'>Lyckades hämta $antal poster för månad $ar-$manad som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckades med att hämta $antal poster för månad $ar-$manad<br>"
            . $svar->getStatus() . "returnerades istället för förväntat 200<br>"
                . print_r($svar->getContent(), true) . "</p>";
        }


    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Test av funktionen hämta enskild uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_HamtaEnUppgift(): string {
    $retur = "<h2>test_HamtaEnUppgift</h2>";

    try {
        // misslyckas med att h'mta id=0
        $svar = hamtaEnskildUppgift("0");
        if($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades hämta uppgift med id=0, som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckades med att hämta uppgift med id=0<br>"
                . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
                . print_r($svar->getContent(), true) . "</p>";
        }


        //misslyckas med att hämta id=sju
        $svar = hamtaEnskildUppgift("sju");
        if($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades hämta uppgift med id=sju, som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckades med att hämta uppgift med id=sju<br>"
                . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
                . print_r($svar->getContent(), true) . "</p>";
        }

        //misslyckas med att hämta id=3.14
        $svar = hamtaEnskildUppgift("3.14");
        if($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades hämta uppgift med id=3.14, som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckades med att hämta uppgift med id=3.14<br>"
                . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
                . print_r($svar->getContent(), true) . "</p>";
        }

        /*
        * lyckas hämta id som finns
        */
        //koppla databas - skapa transaktion
        $db = connectDb();
        $db->beginTransaction();

        //förbered data
    
        $content = hamtaAllaAktiviteter()->getContent();
        $aktiviteter = $content['activities'];
        $aktivitetId = $aktiviteter[0]->id;
        $postdata=["date"=> date('Y-m-d'),
            "time"=>"01:00",
            "description"=>"Testpost",
            "activityId"=> "$aktivitetId" ];

        //skapa post            
        $svar = sparaNyUppgift($postdata);
        $taskId=$svar->getContent()->id;
        // hämta nyss skapad post
        $svar = hamtaEnskildUppgift("$taskId");
        if($svar->getStatus() === 200) {
            $retur .="<p class='ok'>Lyckades hämta en uppgift</p>";
        } else {
            $retur .="<p class='error'>Misslyckades hämta nyskapa uppgift<br>"
                . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
                . print_r($svar->getContent(), true) . "</p>";
        }
        //gör rollback för att radera nyss skapad post

        //misslyckas med att hämta id som inte finns
        $taskId++;
        $svar= hamtaEnskildUppgift("$taskId");
        if($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades hämta en uppgift som inte finns</p>";
        } else {
            $retur .="<p class='error'>Misslyckades hämta en uppgift som inte finns<br>"
                . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
                . print_r($svar->getContent(), true) . "</p>";
        }       

    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    } finally {
        if($db) {
            $db->rollBack();
        }
    }

    return $retur;
}

/**
 * Test för funktionen spara uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_SparaUppgift(): string {
    $retur = "<h2>test_SparaUppgift</h2>";

    try {
        $db = connectDb();
        // skapa en transaktion så att vi slipper skräp i databasen
        $db->beginTransaction();
        //misslyckas med att spara på grund av saknad aktivitetId
        $postdata=['time' => '01:00',
            'date'=>'2023-12-31',
            'description' => 'Detta är en testpost'];

        $svar = sparaNyUppgift($postdata);
        if($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades med att spara uppgift utan aktivitetId, som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckat test med att spara uppgift utan aktivitetId<br>"
            . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }
        /*
        *lyckas med att spara post utan beskrivning
        */
        //förbered data
        $content = hamtaAllaAktiviteter()->getContent();
        $aktiviteter = $content['activities'];
        $aktivitetId = $aktiviteter[0]->id;
        $postdata=['time' => '01:00',
        'date'=>'2023-12-31',
        'activityId' => "$aktivitetId"];

        //testa
        $svar = sparaNyUppgift($postdata);
        if($svar->getStatus() === 200) {
            $retur .= "<p class='ok'>Lyckades spara uppgift utan beskrivning</p>";
        } else {
            $retur .="<p class='error'>Misslyckades med att spara uppgift utan beskrivning<br>"
            . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }
        /*
        *lyckas spara post med alla uppgifter
        */
        $postdata['description'] = 'Detta är en testpost';
        $svar = sparaNyUppgift($postdata);
        if($svar->getStatus() === 200) {
        $retur .= "<p class='ok'>Lyckades med att spara uppgift med alla uppgifter</p>";
    } else {
        $retur .="<p class='error'>Misslyckades med att spara uppgift med alla uppgifter<br>"
        . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
        . print_r($svar->getContent(), true) . "</p>";
    }

    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    } finally {
        if($db) {
            $db->rollBack();
        }
    }

    return $retur;
}

/**
 * Test för funktionen uppdatera befintlig uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_UppdateraUppgifter(): string {
    $retur = "<h2>test_UppdateraUppgifter</h2>";

    try {
        // Koppla databas + skapa transaktion
        $db=connectDb();
        $db->beginTransaction();
        
        // Hämta postdata
        $svar= hamtaSida("1");
        if($svar->getStatus()!==200){
            throw new Exception("Kunde inte hämta poster för test av Uppdatera uppgift");
        }
        $aktiviteter=$svar->getContent()->tasks;

        // Misslyckas med ogiltigt id=0
        $postdata=get_object_vars($aktiviteter[0]);
        $svar= uppdateraUppgift('0', $postdata);
        if($svar->getStatus()===400){
            $retur .="<p class='ok'>Misslyckades med att hämta post med id=0, som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckat test med att hämta post med id=0<br>"
            . $svar->getStatus(). "returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }
        // Misslyckas med ogiltigt id=sju
        $svar= uppdateraUppgift('sju', $postdata);
        if($svar->getStatus()===400){
            $retur .="<p class='ok'>Misslyckades med att hämta post med id=sju, som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckat test med att hämta post med id=sju<br>"
            . $svar->getStatus(). "returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }
        // Misslyckas med ogiltigt id=3.14
        $svar= uppdateraUppgift('3.14', $postdata);
        if($svar->getStatus()===400){
            $retur .="<p class='ok'>Misslyckades med att hämta post med id=3.14, som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckat test med att hämta post med id=3.14<br>"
            . $svar->getStatus(). "returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }
        // Lyckas med id som finns
        $id=$postdata['id'];
        $postdata['activityId']=(string) $postdata['activityId'];
        $postdata['description']= $postdata['description'] . "(Uppdaterad)";
        $svar= uppdateraUppgift("$id", $postdata);
        if($svar->getStatus()===200 && $svar->getContent()->result===true){
            $retur .="<p class='ok'>Uppdatera uppgift lyckades, som förväntat</p>";
        }else{
            $retur .="<p class='error'>Misslyckat test med att uppdatera uppgift<br>"
            . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }
    
        // Misslyckas med samma data
        $svar= uppdateraUppgift("$id", $postdata);
        if($svar->getStatus()===200 && $svar->getContent()->result===false){
            $retur .="<p class='ok'>Uppdatera uppgift misslyckades, som förväntat</p>";
        }else{
            $retur .="<p class='error'>Uppdatera uppgift misslyckades<br>"
            . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }

        // Misslyckas med felaktig indata
        $postdata['time'] = '09:70';
        $svar = uppdateraUppgift("$id", $postdata);
        if($svar->getStatus()===400){
            $retur .="<p class='ok'>Misslyckades med att uppdatera post med felaktig indata, som förväntat</p>";
        } else {
            $retur .="<p class='error'>Uppdatera uppgift med felaktig indata misslyckades<br>"
            . $svar->getStatus(). "returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }
        // Lyckas med saknad beskrivning
        $postdata['time']='01:30';
        unset($postdata['description']);
        $svar = uppdateraUppgift("$id", $postdata);
        if ($svar->getStatus()===200) {
            $retur .="<p class='ok'>Uppdatera uppgift med saknad beskrivning lyckades, som förväntat</p>";
        } else {
            $retur .="<p class='error'>Uppdatera uppgift med saknad beskrivning misslyckades<br>"
            . $svar->getStatus(). "returnerades istället för förväntat 200<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }
        // Lyckas med beskrivning
    
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    } finally{
        if($db){
            $db->rollBack();
        }
    }

    return $retur;
}

function test_KontrolleraIndata(): string {
    $retur = "<h2>test_KontrolleraIndata</h2>";

    try {
        //testa alla saknas
        $postdata=[];
        $svar= kontrolleraIndata($postdata);
        if(count($svar)===3) {
            $retur .="<p class='ok'>Test alla element saknas lyckades</p>";
        } else {
            $retur .="<p class='error'>Test alla element saknas misslyckades<br>"
            . count($svar)." felmeddelanden rapporterades istället för förväntat 3<br>"
                    . print_r($svar, true) . "</p>";
        }
        //testa att datum finns
        $postdata["date"]=date('Y-m-d');
        $svar= kontrolleraIndata($postdata);
        if(count($svar)===2) {
            $retur .="<p class='ok'>Test alla element saknas utom datum lyckades</p>";
        } else {
            $retur .="<p class='error'>Test alla element utom datum saknas misslyckades<br>"
            . count($svar)." felmeddelanden rapporterades istället för förväntat 2<br>"
                    . print_r($svar, true) . "</p>";
        }
        //testa att tid finns
        $postdata["time"]="01:00";
        $svar= kontrolleraIndata($postdata);
        if(count($svar)===1) {
            $retur .="<p class='ok'>Test alla element saknas utom datum och tid lyckades</p>";
        } else {
            $retur .="<p class='error'>Test alla element utom datum och tid saknas misslyckades<br>"
            . count($svar)." felmeddelanden rapporterades istället för förväntat 1<br>"
                . print_r($svar, true) . "</p>";
        }
        //testa att aktivitetid finns
        $content= hamtaAllaAktiviteter()->getContent();
        $aktiviteter=$content['activities'];
        $aktivitetId=$aktiviteter[0]->id;
        $postdata["activityId"]="$aktivitetId";
        $svar= kontrolleraIndata($postdata);
        if(count($svar)===0) {
            $retur .="<p class='ok'>Test description saknas lyckades</p>";
        } else {
            $retur .="<p class='error'>Test description saknas misslyckades<br>"
            . count($svar)." felmeddelanden rapporterades istället för förväntat 0<br>"
                    . print_r($svar, true) . "</p>";
        }
        //testa att alla element finns
        $postdata["description"]="Beskrivning finns också";
        $svar= kontrolleraIndata($postdata);
        if(count($svar)===0) {
            $retur .="<p class='ok'>Test alla element finns lyckades</p>";
        } else {
            $retur .="<p class='error'>Test alla element finns <br>"
            . count($svar)." felmeddelanden rapporterades istället för förväntat 0<br>"
                    . print_r($svar, true) . "</p>";
        }                    
        //testa felaktigt datum 2023-05-40
        $postdata["date"]="2023-05-40";
        $svar= kontrolleraIndata($postdata);
        if(count($svar)===1) {
            $retur .="<p class='ok'>Test felaktigt datum {$postdata["date"]} lyckades</p>";
        } else {
            $retur .="<p class='error'>Test felaktigt datum {$postdata["date"]} misslyckades<br>"
            . count($svar) . " felmeddelanden rapporterades istället för förväntat 1<br>"
                    . print_r($svar, true) . "</p>";
        }       
        //test felformatterad tid 1:00
        $postdata["date"]=date('Y-m-d');
        $postdata["time"]="1:00";
        $svar= kontrolleraIndata($postdata);
        if(count($svar)===1) {
            $retur .="<p class='ok'>Test felaktigt formaterad tid {$postdata["time"]} lyckades</p>";
        } else {
            $retur .="<p class='error'>Test felaktigt formaterad tid {$postdata["time"]} misslyckades<br>"
                . count($svar)." felmeddelanden rapporterades istället för förväntat 1<br>"
                . print_r($svar, true) . "</p>";
        }        
        //test felaktig tid 01:99
        $postdata["time"]="01:99";
        $svar= kontrolleraIndata($postdata);
        if(count($svar)===1) {
            $retur .="<p class='ok'>Test felaktig tid {$postdata["time"]} lyckades</p>";
        } else {
            $retur .="<p class='error'>Test felaktigt tid {$postdata["time"]} misslyckades<br>"
                . count($svar)." felmeddelanden rapporterades istället för förväntat 1<br>"
                    . print_r($svar, true) . "</p>";
        }       
        //test för lång tid 20:00
        $postdata["time"]="20:00";
        $svar= kontrolleraIndata($postdata);
        if(count($svar)===1) {
            $retur .="<p class='ok'>Test för lång tid {$postdata["time"]} lyckades</p>";
        } else {
            $retur .="<p class='error'>Test för lång tid {$postdata["time"]} misslyckades<br>"
            . count($svar)." felmeddelanden rapporterades istället för förväntat 1<br>"
                . print_r($svar, true) . "</p>";
        } 
        //test aktivitetid 0
        $postdata["time"]="03:30";
        $postdata["activityId"]="0";
        $svar= kontrolleraIndata($postdata);
        if(count($svar)===1) {
            $retur .="<p class='ok'>Test angivet aktivitetId saknas {$postdata["activityId"]} lyckades</p>";
        } else {
            $retur .="<p class='error'>Test angivet aktivitetId {$postdata["activityId"]} misslyckades<br>"
            . count($svar)." felmeddelanden rapporterades istället för förväntat 1<br>"
                . print_r($svar, true) . "</p>";
        }
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}


/**
 * Test för funktionen radera uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_RaderaUppgift(): string {
    $retur = "<h2>test_RaderaUppgift</h2>";

    try {

    //skapa transaktion
    $db=connectDb();
    $db->beginTransaction();
    //misslyckas med att radera post med id=sju
    $svar = raderaUppgift('sju');
    if($svar->getStatus()===400){
        $retur .="<p class='ok'>Misslyckades med att radera post med id=sju, som förväntat</p>";
    } else {
        $retur .="<p class='error'>Misslyckat test med att radera post med id=sju<br>"
        . $svar->getStatus(). "returnerades istället för förväntat 400<br>"
        . print_r($svar->getContent(), true) . "</p>";
    }
    //misslyckas med att radera post med id=0.1
    $svar = raderaUppgift('0.1');
    if($svar->getStatus()===400){
        $retur .="<p class='ok'>Misslyckades med att radera post med id=0.1, som förväntat</p>";
    } else {
        $retur .="<p class='error'>Misslyckat test med att radera post med id=0.1<br>"
        . $svar->getStatus(). "returnerades istället för förväntat 400<br>"
        . print_r($svar->getContent(), true) . "</p>";
    }
    //misslyckas med att radera post med id=0
    $svar = raderaUppgift('0');
    if($svar->getStatus()===400){
        $retur .="<p class='ok'>Misslyckades med att radera post med id=0, som förväntat</p>";
    } else {
        $retur .="<p class='error'>Misslyckat test med att radera post med id=0<br>"
        . $svar->getStatus(). "returnerades istället för förväntat 400<br>"
        . print_r($svar->getContent(), true) . "</p>";
    }
    /*
    * Lyckas med att radera post som finns
    */

    // Hämta poster
    $poster = hamtaSida("1");
    if($poster->getStatus()!==200){
        throw new Exception("Misslyckades med att hämta poster");
    }
    $uppgifter = $poster->getContent()->tasks;

    //ta fram id för första posten
    $testId = $uppgifter[0]->id;
    //lyckas radera id för första posten

    $svar = raderaUppgift("$testId");
    if($svar->getStatus()===200 && $svar->getContent()->result===true) {
        $retur .="<p class='ok'>Radera uppgift lyckades, som förväntat</p>";
    } else {
        $retur .="<p class='error'>Misslyckat test med att radera uppgift<br>"
        . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
        . print_r($svar->getContent(), true) . "</p>";
    }
    //misslyckas med att radera samma id som tidigare
    $svar = raderaUppgift("$testId");
    if($svar->getStatus()===200 && $svar->getContent()->result===false) {
        $retur .="<p class='ok'>misslyckades radera post som inte finns, som förväntat</p>";
    } else {
        $retur .="<p class='error'>misslyckat test att radera post som inte finns<br>"
        . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
        . print_r($svar->getContent(), true) . "</p>";
    }

    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    } finally {
        //ansluta transaktion
        if($db){
            $db->rollBack();
        }

    }

    return $retur;
}
