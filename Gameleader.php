<?php
//Class Gameleader
class Gameleader
{
    public $Deck;
    public $Aflegstapel;
    public $Spelers;
    private $beurt;
    private $LR;
    private $huidigTeken = null;
    public $winner = null;

    function __construct($SpelersAantal)
    {
        //initialiseren van de fields 
        $this->Deck = new Deck();
        $this->Aflegstapel = new Aflegstapel();
        $this->Spelers = [];
        $this->beurt = null;
        $this->LR = true;
        // aanmaken van de spelers 
        for ($i = 0; $i < $SpelersAantal; $i++) {
            $this->Spelers[] = new Hand($i);
        }
        // kaarten verdelen 
        $AantalKaartenPerSpeler = 7;
        for ($j = 0; $j < $AantalKaartenPerSpeler; $j++) {
            foreach ($this->Spelers as $speler) {
                $speler->ToevoegenAanHand($this->Deck->Rapen());
            }
        }
        // Kaart van het deck rapen en op de aflegstapel plaatsen 
        $eerste = $this->Deck->Rapen();

        while ($eerste->GetWaarde() === 'X') {
            // Joker terug in het deck stoppen en opnieuw schudden
            array_push($this->Deck->kaarten, $eerste);
            $this->Deck->Schudden();
            $eerste = $this->Deck->Rapen();
        }

        $this->Aflegstapel->PlaatKaart($eerste);

        // Random beginnende speler kiezen   
        $this->beurt = rand(0, $SpelersAantal - 1);
    }
    private function VolgendeSpeler()
    {
        if ($this->LR) {
            $this->beurt++;
        } else {
            $this->beurt--;
        }
        if ($this->beurt == count($this->Spelers)) {
            $this->beurt = 0;
        }
        if ($this->beurt == -1) {
            $this->beurt = count($this->Spelers) - 1;
        }
    }
    function Show()
    {
        for ($i = 0; $i < count($this->Spelers); $i++) {
            $this->Spelers[$i]->ShowHand($this->beurt);
        }
        echo "<tafel>";
        $this->Deck->ShowDeck();
        $this->Aflegstapel->ShowAflegstapel();
        echo "</tafel>";
    }
    private function speelKaart($kaartid)
    {



        if (
            empty($this->Aflegstapel->kaarten) || $this->Spelers[$this->beurt]
                ->kaarten[$kaartid]->GetWaarde() == $this->Aflegstapel->kaarten[count($this->Aflegstapel->kaarten) - 1]
            ->GetWaarde() || $this->Spelers[$this->beurt]->kaarten[$kaartid]->GetTeken() == $this->Aflegstapel->kaarten[count($this->Aflegstapel->kaarten) - 1]
            ->GetTeken() || ($this->Spelers[$this->beurt]->kaarten[$kaartid]->GetWaarde() == 'J') || ($this->Spelers[$this->beurt]->kaarten[$kaartid]->GetWaarde() == 'X')
            || ($this->Aflegstapel->kaarten[count($this->Aflegstapel->kaarten) - 1]->GetWaarde() == 'X')


        ) {
            $kaart = $this->Spelers[$this->beurt]->VerwijderVanHand($kaartid);
            $this->Aflegstapel->PlaatKaart($kaart);
            if ($this->checkWin($this->beurt)) return;

            switch ($kaart->GetWaarde()) {
                //Volgende speler raapt 2 kaarten
                case '2':
                    $this->VolgendeSpeler();
                    $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
                    $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
                    break;
                //8 = Wacht beurt overslaan
                case '8':
                    $this->VolgendeSpeler();
                    $this->VolgendeSpeler();
                    break;
                //Iedereen geeft willekeurig 1 kaart af
                case '10':
                    $AantalSpelers = count($this->Spelers);
                    $doorgegevenKaarten = [];

                    for ($i = 0; $i < $AantalSpelers; $i++) {
                        if (count($this->Spelers[$i]->kaarten) > 0) {
                            $rand = array_rand($this->Spelers[$i]->kaarten);
                            $doorgegevenKaarten[$i] = $this->Spelers[$i]->VerwijderVanHand($rand);
                        }
                    }
                    for ($i = 0; $i < $AantalSpelers; $i++) {
                        $volgende = ($i + 1) % $AantalSpelers;
                        if (isset($doorgegevenKaarten[$i])) {
                            $this->Spelers[$volgende]->ToevoegenAanHand($doorgegevenKaarten[$i]);
                        }
                    }

                    $this->VolgendeSpeler();
                    break;
                //kan altijd worden opgegooid en word vervolgens mee verder gespeelt
                case 'J':
                    $this->huidigTeken = $kaart->GetTeken();
                    $this->VolgendeSpeler();
                    break;

                //Joker volgende persoon raapt 5 kaarten maar mag wel keuze wat voor kaar thet word zoals: 'H', 'S', 'R', 'K'
                case 'X':
                    // Bepaal volgende speler die kaarten moet pakken
                    $volgende = ($this->beurt + 1) % count($this->Spelers);

                    // Volgende speler raapt 5 kaarten
                    for ($i = 0; $i < 5; $i++) {
                        $this->Spelers[$volgende]->ToevoegenAanHand($this->Deck->Rapen());
                    }

                    // Beurt voorbij: huidige speler is gedaan
                    $this->VolgendeSpeler();
                    break;





                    break;
                //je mag nog een kaart opgooien 
                case 'K':
                    $speler = $this->Spelers[$this->beurt];

                    // Zoek een kaart die mag worden gespeeld (zelfde waarde of teken)
                    $nextCardIndex = null;
                    foreach ($speler->kaarten as $key => $k) {
                        if ($k->GetWaarde() == $kaart->GetWaarde() || $k->GetTeken() == $kaart->GetTeken() || in_array($k->GetWaarde(), ['J', 'X'])) {
                            $nextCardIndex = $key;
                            break;
                        }
                    }

                    if ($nextCardIndex !== null) {
                        // Speel de volgende kaart direct, beurt blijft bij dezelfde speler
                        $this->speelKaart($nextCardIndex);
                    } else {
                        // Geen speelbare kaart → pak er één en ga naar de volgende speler
                        $speler->ToevoegenAanHand($this->Deck->Rapen());
                        $this->VolgendeSpeler();
                    }
                    break;

                case 'A':
                    $this->LR = !$this->LR;
                    $this->VolgendeSpeler();
                    break;

                default:
                    $this->VolgendeSpeler();
                    break;
            }
            $this->Aflegstapel->PlaatKaart($kaart);
        }
    }
    private function checkWin($spelerIndex)
    {
        $speler = $this->Spelers[$spelerIndex];

        if (count($speler->kaarten) == 0) {
            $this->winner = $spelerIndex;
            return true;
        }

        // Laatste kaart speciale logica
        if (count($speler->kaarten) == 1) {
            $laatste = $speler->kaarten[0];
            if (in_array($laatste->GetWaarde(), ['X'])) {
                $speler->ToevoegenAanHand($this->Deck->Rapen());
                $speler->ToevoegenAanHand($this->Deck->Rapen());
            }
        }


        return false;
    }


    public function Klik($waarde)
    {
        if ($this->winner !== null) {
            return;
        }

        if ($waarde == "pakken") {
            $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
            if (count($this->Deck->kaarten) < 3) {
                $kaarten = $this->Aflegstapel->GeefAlleKaarten();
                foreach ($kaarten as $kaart) {
                    array_push($this->Deck->kaarten, $kaart);
                }
                $this->Deck->Schudden();
            }
            $this->VolgendeSpeler();
        } else {
            $this->speelKaart($waarde);
        }
    }

    private function KaartPakken()
    {
        $kaart = $this->Deck->Rapen();
        $this->Spelers[$this->beurt]->ToevoegenAanHand($kaart);
    }

    public function GetBeurt()
    {
        return $this->beurt;
    }

    private function kanSpelen($kaart)
    {
        $bovenste = end($this->Aflegstapel->kaarten);
        return (
            $kaart->GetWaarde() == $bovenste->GetWaarde() ||
            $kaart->GetTeken() == $bovenste->GetTeken() ||
            ($kaart->GetWaarde() == 'J') || ($kaart->GetWaarde() == 'X'));
    }

    //Computer code:
    // public function computerZet() {
    //     if ($this->winner !== null) return;

    //     // Computers: p1, p2, p3
    //     while ($this->beurt != 0) { 
    //         $hand = $this->Spelers[$this->beurt]->kaarten;

    //         $bovenste = end($this->Aflegstapel->kaarten);
    //         $gespeeld = false;

    //         // Kijk of er een speelbare kaart is
    //         foreach ($hand as $key => $kaart) {
    //             if ($kaart->GetWaarde() == $bovenste->GetWaarde() || $kaart->GetTeken() == $bovenste->GetTeken() || $kaart->GetWaarde() == 'J') {
    //                 $this->speelKaart($key);
    //                 $gespeeld = true;
    //                 break;
    //             }
    //         }

    //         // Als geen kaart speelbaar, pakken
    //         if (!$gespeeld) {
    //             $this->Klik("pakken");
    //         }
    //     }
    // }

}
