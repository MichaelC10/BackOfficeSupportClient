<?php
function PrettyDate($DateSql, $heure=true, $jourSemaine=true, $jourNumeric=true)
{
    $FR_D= array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
    $FR_M= array("janvier", "février", "mars", "avril", "mai", "juin","juillet", "août", "septembre", "octobre", "novembre", "décembre");

    $splitDateHeure=explode(' ', $DateSql);

    if($heure){
        $splitHeure=explode(':', $splitDateHeure[1]);
    }

    $splitDate=explode('-', $splitDateHeure[0]);

    $anne=$splitDate[0];
    $mois=$splitDate[1];
    $jour=$splitDate[2];

    $heures=($heure) ? " - ". $splitHeure[0].":".$splitHeure[1] : "";

    if($jourSemaine)
    {
        $dateObj = new DateTime($DateSql);
        $NbJour=$dateObj->format("w");
        return $FR_D[$NbJour-1]." ".$jour." ".$FR_M[$mois-1]." ".$anne .$heures;
    }else{
        $jour=$jourNumeric ? $jour : "";
        return $jour." ".ucfirst($FR_M[$mois-1])." ".$anne .$heures;
    }
}

?>