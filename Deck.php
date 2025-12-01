<?php
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
        $this->Schudden();
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

        $this->kaarten[] = new Kaart('X', 'X');
        $this->kaarten[] = new Kaart('X', 'X');

    }
    //shows the deck
    public function ShowDeck()
    {
        echo '<deck onclick="window.location.href=\'index.php?Kaart=pakken\'">';
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
