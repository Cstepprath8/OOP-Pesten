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
        // Check of het een joker is
        if ($this->waarde === 'X' && $this->teken === 'X') {
            $filename = "Foto/XX.svg";
        } else {
            $filename = "Foto/" . $this->teken . $this->waarde . ".svg";
        }
    } else {
        $filename = "Foto/" . $spelerKleur . ".svg";
    }
    echo '<img src="' . $filename . '" width="120">';
}

    
}








