<?php
//Colin Stepprath

//Class kaart
class Kaart {
    private $waarde;
    private $teken;
    function __construct($waarde, $teken){
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

    public function ShowKaart(){
        echo '<img src="Foto_Kaartenspel.png" />';
    }
}
//Class Deck
class Deck{
    public $kaarten;
    private $waardes; //VOORBEELD: (‘2’ t/m ‘10’ , ‘ J’ , ‘Q’ , ‘K’ , ‘A’)
    private $tekens; // VOORBEELD: (‘H’ , ‘S’ , ‘R’ , ‘K’)
    function __construct()
    {
        $this->kaarten = [];

        $this->tekens = array('H' , 'S' , 'R' , 'K');

        $this->waardes = array('2' , '3' , '4' , '5' , '6' , '7' , '8' , '9' , '10' , 'J' , 'Q' , 'K' , 'A');

        $this->MaakNieuwDeck();
    }

    
//Makes a new deck
    private function MaakNieuwDeck(){
        $nr=0;
        for($T=0; $T < count($this->tekens); $T++){
            for($W=0; $W < count($this->waardes); $W++){
                $this->kaarten[$nr] = new Kaart($this->waardes[$W], $this->tekens[$T]);
                $nr++;
            }
        }

    }
//shows the deck
    public function ShowDeck(){
        echo "<deck>";
        foreach($this->kaarten as $kaart){
            echo "<kaart>";
            $kaart->ShowKaart();
            echo "</kaart>";
        }
        echo "</deck>";
    }

    public function Rapen() {
    $bovensteKaart = array_shift($this->kaarten); 
    return $bovensteKaart;
}

    public function Schudden(){
        shuffle($this->kaarten);
        shuffle($this->kaarten);
    }

}

//Class Hand
class hand {
    public $kaarten;
    private $spelerNr;
    function __construct($spelerNr){
        $this->kaarten = [];
        $this->spelerNr = $spelerNr;
    }
    
    public function ToevoegenAanHand($kaart){
        array_push($this->kaarten,$kaart);
    }

    public function ShowHand(){
        echo "<hand class='p" .$this->spelerNr . "'>";
        foreach($this->kaarten as $kaart){
            echo "<kaart>";
            $kaart->ShowKaart();
            echo "</kaart>";
        }
        echo "</hand>";
    }    
}
