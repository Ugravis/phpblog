<?php

// Vérification des entrées

    $id_billet = $_POST['billet'];

    if(isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['coment']) && !empty($_POST['coment'])) {

        $pseudo = $_POST['pseudo'];
        $coment = $_POST['coment'];
        
    } else header ('location: coments.php?billet='.$id_billet);

// Connexion à la base de données

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=tp;charset=utf8', 'root', '');
    } catch (Exception $e) {
        die('Error : '.$e->getMessage());
    }

// Creation du nouveau commentaire et renvoie à coment.php?id=x

    $newData = $bdd->prepare('INSERT INTO coments (id_billet, coment, author, date) VALUES (?, ?, ?, NOW())');
    $newData->execute(array($id_billet, $coment, $pseudo));

    header ('location: coments.php?billet='.$id_billet);

?>