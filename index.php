<?php
// Colin Stepprath
include 'Kaart.php';
include 'Deck.php';
include 'Hand.php';
include 'Aflegstapel.php';
include 'Gameleader.php';

session_start();

// Reset game 
if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// $tegenComputer = false;
// if (isset($_GET['playvscomputer'])) {
//     $tegenComputer = true;
//     $_SESSION['tegenComputer'] = true;
// } elseif (isset($_SESSION['tegenComputer'])) {
//     $tegenComputer = $_SESSION['tegenComputer'];
// }


$aantalSpelers = 4;

// Laad of start nieuw spel
if (isset($_SESSION['Game'])) {
    $Game = $_SESSION['Game'];
} else {
    $Game = new Gameleader($aantalSpelers);
}

// Kaart klikken
if (isset($_GET['Kaart'])) {
    $Game->Klik($_GET['Kaart']);

    // Laat computer spelen als tegenComputer aan staat
    // if ($tegenComputer) {
    //     $Game->computerZet();
    // }
}

$_SESSION['Game'] = $Game;

// Aantal kaarten
$D = count($Game->Deck->kaarten);
$A = count($Game->Aflegstapel->kaarten);

// Huidige speler
$beurt = $Game->GetBeurt();
?>
<html>

<head>
    <title>Kaartenspel</title>
    <style type="text/css">
        body {
            /* background-color: green;  */
            background-image: url('Foto/Blackjack tafel OOP_Pesten.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

        }

        /* Basis styling */
        kaart img {
            height: 154px;
        }

        hand {
            width: 300px;
            height: 200px;
            display: block;
            position: absolute;
        }

        hand kaart {
            position: absolute;
            bottom: 0px;
            transition: bottom 0.2s ease;
        }

        hand kaart:hover {
            bottom: 20px;
        }


        /* Container voor deck en aflegstapel */
        .stapels {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            gap: 50px;
        }

        /* Deck en Aflegstapel basis */
        deck,
        aflegstapel {
            width: 125px;
            height: 175px;
            position: relative;
        }

        /* Button Reset */
        .btn-clean {
            padding: 8px 18px;
            background: #1a1a1a;
            color: #f0f0f0;
            border: 1px solid #444;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.15s ease-in-out;
            font-family: Arial, sans-serif;
            text-decoration: none;
            display: inline-block;
        }

        /* Button Reset Hover */
        .btn-clean:hover {
            background: #2c2c2c;
            border-color: #c3b8b8ff;
        }

        .btn-clean:active {
            background: #000;
            border-color: #888;
            transform: scale(0.97);
        }


        /* Deck kaarten */
        <?php
        $maxOffset = 20;
        for ($d = 0; $d < $D; $d++) {
            $offset = min($d * 2, $maxOffset);
            echo "deck kaart:nth-child(" . ($d + 1) . ") { left: {$offset}px; bottom: {$offset}px; position: absolute; }\n";
        }
        for ($a = 0; $a < $A; $a++) {
            $offset = min($a * 2, $maxOffset);
            echo "aflegstapel kaart:nth-child(" . ($a + 1) . ") { left: {$offset}px; bottom: {$offset}px; position: absolute; }\n";
        }
        ?>hand {
            display: block;
            position: absolute;
            width: auto;
            height: auto;
        }

        /* Spelersposities */
        hand.p0 {
            left: 42%;
            bottom: 10px;
            transform: translateX(-50%) rotate(0deg);
        }

        hand.p1 {
            left: 150px;
            top: 250px;
            transform: rotate(90deg);
        }

        hand.p2 {
            left: 58%;
            top: 20px;
            transform: rotate(180deg);
        }

        hand.p3 {
            right: 150px;
            top: 550px;
            transform: rotate(-90deg);
        }

        /* Rotatie per speler */
        <?php
        for ($s = 0; $s < $aantalSpelers; $s++) {
            $kaarten = count($Game->Spelers[$s]->kaarten);
            if ($kaarten <= 0) continue;
            $graden = ($kaarten > 15) ? 150 : 80;
            for ($k = 1; $k <= $kaarten; $k++) {
                $rot = (($graden / $kaarten) * $k) - ($graden / 2);
                $left = ($k - 1) * 25;
                echo ".p{$s} kaart:nth-child({$k}) { transform: rotate({$rot}deg); left: {$left}px; }\n";
            }
        }
        ?>
    </style>
</head>

<body>


    <?php if ($Game->winner !== null): ?>
        <h1 style="color:white;">Speler <?php echo $Game->winner; ?> heeft gewonnen!</h1>
        <a href="index.php?reset=1" class="btn-clean">Nieuwe ronde starten</a>
    <?php else: ?>
        <div class="stapels">
            <?php
            $Game->Deck->ShowDeck();
            $Game->Aflegstapel->ShowAflegstapel();
            ?>
        </div>

        <?php
        for ($i = 0; $i < $aantalSpelers; $i++) {
            $Game->Spelers[$i]->ShowHand($beurt);
        }
        ?>
    <?php endif; ?>



    <a href="index.php?reset=1" class="btn-clean">Reset Game</a>
    <!-- <a href="index.php?playvscomputer=1" class="btn-clean">Speel tegen de Computer</a> -->


</body>

</html>