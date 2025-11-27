<?php //Class Aflegstapel
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
        $teller = count($this->kaarten);
        if ($teller > 0) {
            // Alle kaarten behalve de laatste als achterkant
            for ($i = 0; $i < $teller - 1; $i++) {
                echo "<kaart>";
                $this->kaarten[$i]->ShowKaart(false); // achterkant
                echo "</kaart>";
            }
            // Bovenste kaart zichtbaar
            echo "<kaart>";
            $this->kaarten[$teller - 1]->ShowKaart(true);
            echo "</kaart>";
        }
        echo "</aflegstapel>";
    }
}