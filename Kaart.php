<?php
//Colin Stepprath

//Class kaart
class Kaart
{
    private $waarde;
    private $teken;
    function __construct($waarde, $teken)
    {
        $this->waarde = $waarde;
        $this->teken = $teken;
    }

    public function GetWaarde()
    {
        return $this->waarde;
    }

    public function GetTeken()
    {
        return $this->teken;
    }

    public function ShowKaart($Zichtbaar = false, $spelerKleur = 'blauw')
    {
        if ($Zichtbaar) {
            $filename = "Foto/" . $this->teken . $this->waarde . ".svg";
        } else {
            $filename = "Foto/" . $spelerKleur . ".svg";
        }
        echo '<img src="' . $filename . '" width="120">';
    }
}

//Class Deck
class Deck
{
    public $kaarten;
    private $waardes; //EXAMPLE: (‘2’ t/m ‘10’ , ‘ J’ , ‘Q’ , ‘K’ , ‘A’)
    private $tekens; // EXAMPLE: (‘H’ , ‘S’ , ‘R’ , ‘K’)
    function __construct()
    {
        $this->kaarten = [];

        $this->tekens = array('H', 'S', 'R', 'K');

        $this->waardes = array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A');

        $this->MaakNieuwDeck();
    }


    //Makes a new deck
    private function MaakNieuwDeck()
    {
        $nr = 0;
        for ($T = 0; $T < count($this->tekens); $T++) {
            for ($W = 0; $W < count($this->waardes); $W++) {
                $this->kaarten[$nr] = new Kaart($this->waardes[$W], $this->tekens[$T]);
                $nr++;
            }
        }
    }
    //shows the deck
    public function ShowDeck()
    {
        echo "<deck onclick='window.location.href=`index.php?Kaart=pakken`;'>";
        foreach ($this->kaarten as $kaart) {
            echo "<kaart>";
            $kaart->ShowKaart();
            echo "</kaart>";
        }
        echo "</deck>";
    }

    public function Rapen()
    {
        $bovensteKaart = array_shift($this->kaarten);
        return $bovensteKaart;
    }

    public function Schudden()
    {
        shuffle($this->kaarten);
        shuffle($this->kaarten);
    }
}

//Class Hand
class Hand
{
    public $kaarten;
    private $spelerNr;
    function __construct($spelerNr)
    {
        $this->kaarten = [];
        $this->spelerNr = $spelerNr;
    }

    public function ToevoegenAanHand($kaart)
    {
        array_push($this->kaarten, $kaart);
    }

    public function ShowHand($id = null)
    {
        echo "<hand class='p" . $this->spelerNr . "'>";
        foreach ($this->kaarten as $key => $kaart) {
            echo "<kaart onclick='window.location.href=`index.php?Kaart=" . $key . "`;'>";
            if ($this->spelerNr == $id) {
                $kaart->ShowKaart(true);
            } else {
                $kaart->ShowKaart();
            }
            echo "</kaart>";
        }
        echo "</hand>";
    }
    public function VerwijderVanHand($id)
    {
        // select kaart via $id 
        $kaart = $this->kaarten[$id];
        unset($this->kaarten[$id]);
        $this->HerschikHand();
        return $kaart;
    }
    private function HerschikHand()
    {
        $nr = 0;
        $tijdelijkDeck = $this->kaarten;
        // Maak de variable Kaarten leeg
        $this->kaarten = [];
        foreach ($tijdelijkDeck as $kaart) {
            //Vul de array Kaarten weer per kaart
            $this->kaarten[$nr] = $kaart;
            $nr++;
        }
    }
}

//Class Gameleader
class Gameleader
{
    public $Deck;
    public $Aflegstapel;
    public $Spelers;
    private $beurt;
    private $LR;
    private $huidigTeken = null;

    function __construct($SpelersAantal)
    {
        //initialiseren van de fields 
        $this->Deck = new Deck();
        $this->Aflegstapel = new Aflegstapel();
        $this->Spelers = [];
        $this->beurt = null;
        // aanmaken van de spelers 
        for ($i = 0; $i < $SpelersAantal; $i++) {
            $this->Spelers[] = new Hand($i + 1);
        }
        // kaarten verdelen 
        $AantalKaartenPerSpeler = 7;
        for ($j = 0; $j < $AantalKaartenPerSpeler; $j++) {
            foreach ($this->Spelers as $speler) {
                $speler->ToevoegenAanHand($this->Deck->Rapen());
            }
        }
        // Kaart van het deck rapen en op de aflegstapel plaatsen 
        $this->Aflegstapel->PlaatKaart($this->Deck->Rapen());

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
        $this->winnen();


        if (
            empty($this->Aflegstapel->kaarten) || $this->Spelers[$this->beurt]
                ->kaarten[$kaartid]->GetWaarde() == $this->Aflegstapel->kaarten[count($this->Aflegstapel->kaarten) - 1]
            ->GetWaarde() || $this->Spelers[$this->beurt]->kaarten[$kaartid]->GetTeken() == $this->Aflegstapel->kaarten[count($this->Aflegstapel->kaarten) - 1]
            ->GetTeken() || ($this->Spelers[$this->beurt]->kaarten[$kaartid]->GetWaarde() == 'J')
        ) {
            $kaart = $this->Spelers[$this->beurt]
                ->VerwijderenVanHand($kaartid);

            switch ($kaart->GetWaarde()) {
                case '2';
                    $this->VolgendeSpeler();
                    $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
                    $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
                    break;
                
                case '8';
                    $this->VolgendeSpeler();
                    $this->VolgendeSpeler();
                    break;

                case '10';
                    $AantalSpelers = count($this->Spelers);
                    $doorgegevenKaarten = [];

                    for ($i = 0; $i < $AantalSpelers; $i++){
                        $volgende = ($i + 1) % $AantalSpelers;
                        if(isset($doorgegevenKaarten[$i])) {
                            $this->Spelers[$volgende]->ToevoegenAanHand($doorgegevenKaarten[$i]);
                        }
                    }
                    $this->VolgendeSpeler();
                    break;
                
                case 'J';
                $this->huidigTeken = $kaart->GetTeken();
                    $this->VolgendeSpeler();
                    break;

                case 'K';
                   $speler = $this->Spelers[$this->beurt];
                   $kanSpelen = false;

                   foreach ($speler->kaarten as $key => $Kaarthand) {
                    if ($Kaarthand->GetWaarde() == $kaart->GetWaarde() || $Kaarthand->GetTeken() == $kaart->GetTeken()){
                        $kanSpelen = true;
                        $this->speelKaart($key);
                    }
                   }
                    break;

                    if(!$kanSpelen){
                        $speler->ToevoegenAanHand($this->Deck->Rapen());
                        $this->VolgendeSpeler();
                    }

                case 'A';
                   $this->LR = !$this->LR;
                   $this->VolgendeSpeler();
                    break;

                case 'X';
                   $this->VolgendeSpeler();
                   for ($i = 0; $i < 5;$i++){
                    $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
                   }
                   $this->VolgendeSpeler();
                    break;


                default;
                    $this->VolgendeSpeler();
                    break;
            }
            $this->Aflegstapel->PlaatKaart($kaart);
        }

        
    }

    private function winnen()
    {
        $speler = $this->Spelers[$this->beurt];
        if (count($speler->kaarten) == 1) {
            $laatsteKaart = $speler->kaarten[0];
            $waarde = $laatsteKaart->GetWaarde();
            if (in_array($waarde, ['2', '8', '10', 'X'])) {
                $speler->ToevoegenAanHand($this->Deck->Rapen());
                $speler->ToevoegenAanHand($this->Deck->Rapen());
            }
        }
    }

    public function Klik($waarde)
    {
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
}


//Class Aflegstapel
class Aflegstapel
{
    public $kaarten;
    function __construct()
    {
        $this->kaarten = [];
    }
    function PlaatKaart($kaart)
    {
        array_push($this->kaarten, $kaart);
    }
    function GeefAlleKaarten()
    {
        $AlleKaarten = [];

        $teller = count($this->kaarten) - 1;
        for ($i = 0; $i < $teller; $i++) {
            $AlleKaarten[] = $this->kaarten[$i];
            unset($this->kaarten[$i]);
        }
        $this->kaarten = array_values($this->kaarten);

        return $AlleKaarten;
    }
    function ShowAflegstapel()
    {
        echo "<aflegstapel>";
        foreach ($this->kaarten as $kaart) {
            echo "<kaart>";
            $kaart->ShowKaart();
            echo "/kaart";
        }
        echo "</aflegstapel>";
    }
}
