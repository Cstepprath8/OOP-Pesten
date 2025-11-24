<?php
//Colin Stepprath
include 'Kaart.php';

$Deck = new Deck();
$speler1 = new Hand(0);
$speler2 = new Hand(1);
$aantalKaarten = 7;

    for($I = 0; $I < $aantalKaarten; $I++){
        $kaart = $Deck->Rapen();
        $speler1->ToevoegenAanHand($kaart);
        $kaart = $Deck-> Rapen();
        $speler2->ToevoegenAanHand($kaart);
    }

    $Game = new Gameleader(2); 
    $D = count($Game->Deck->kaarten);if($D>21){$D=21;} 
    $A = count($Game->Aflegstapel->kaarten);if($A>21){$A=21;} 
    // $PK1 = count($Game->Spelers[0]->kaarten); 
    // $PK2 = count($Game->Spelers[1]->kaarten);

    if(isset($_GET['reset'])){session_destroy();header("location:Index.php");}


    //Session Start Begin
    session_start(); 
    $aantalSpelers = 4; 
    if(isset($_SESSION['Game'])){$Game = $_SESSION['Game'];}else{
    $Game = new Gameleader($aantalSpelers); if(isset($_GET['Kaart'])){$Game->Klik($_GET['Kaart']);}

    }
    $_SESSION['Game'] = $Game;
?>
<html>

<head>
    <title> Kaartenspel </title>
    <style type="text/css"> 
    kaart img{height:154px;}hand{width:300px;height:200px;display:block;position:absolute;} hand kaart{display:block;position:inherit;bottom:0px;}hand kaart:hover{top:0px;} deck,aflegstapel{width:125px;left:250px;height:175px;float:left;display:block;} deck kaart{left:<?php echo $D;?>px;bottom:<?php echo $D;?>px;position:absolute;} tafel{width: 250px;left:250px;top: 250px;position: absolute;} <?php for($d=$D;$d>0;$d--){?>deck kaart:nth-child(<?php echo $d;?>) {left:<?php echo $d;?>px;bottom:<?php echo $d;?>px;}<?php }?> .P0{left:50px;bottom:20px;} .P1{transform:rotate(180deg);left:450px;top:20px;} <?php for($p1=0;$p1<$PK1;$p1++){?> .P0 kaart:nth-child(<?php echo $p1;?>) {left:calc(<?php echo $p1;?> * 100px);}<?php }?> <?php for($p2=0;$p2<$PK2;$p2++){?> .P1 kaart:nth-child(<?php echo $p2;?>) {left:calc(<?php echo $p2;?> * 100px);}<?php }?> 
    <?php for($a=$A;$a>0;$a--){?>aflegstapel kaart:nth-child(<?php echo $a;?>) {left:<?php echo $a+125;?>px;bottom:<?php echo $a;?>px;}<?php }?> aflegstapel kaart{left:<?php echo $A;?>px;bottom:<?php echo $A;?>px;position:absolute;}
   </style>
    </head>

<body>
    <h1>Jouw deck:</h1>
    <?php 
    $speler1->ShowHand();
    echo "<tafel>";
    $Deck->ShowDeck();
    echo "</tafel>";
    $speler2->ShowHand();
    ?>

    <a href="index.php?reset=">Reset Game</a>
</body>

</html>