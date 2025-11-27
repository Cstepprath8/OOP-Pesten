<?php
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

    public function ShowHand($huidigeSpeler = null)
    {
        echo "<hand class='p" . $this->spelerNr . "'>";
        foreach ($this->kaarten as $key => $kaart) {
            echo '<kaart onclick="window.location.href=\'index.php?Kaart=' . $key . '\'">';
            // Alleen voor de huidige speler zichtbaar
            if ($this->spelerNr == $huidigeSpeler) {
                $kaart->ShowKaart(true);
            } else {
                $kaart->ShowKaart(false); // achterkant tonen
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