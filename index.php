<?php 
    require './functions/billet.php';
    $billets_par_page = 5;
?>

<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <title>Ublog</title>
        <link rel="stylesheet" href="./css/style.css">
    </head>

    <body>
        <h1>Ublog</h1>
        <p class="separator">Derniers articles...</p>
        <?php

// Connexion à la base de donnée

            try {
                $bdd = new PDO('mysql:host=localhost;dbname=tp;charset=utf8', 'root', '');
            } catch (Exception $e) {
                die('Error: '.$e->getMessage());
            }

// Récupération et affichage des billets

            if(empty($_GET['page'])) $_GET['page'] = 1;

            $response = $bdd->prepare('SELECT id, title, text, DATE_FORMAT(date, "%d/%m/%Y à %Hh%imin") AS datee, author FROM billets ORDER BY date DESC LIMIT :min_billet, :max_billet');
            $response->bindValue(':min_billet', $_GET['page'] * $billets_par_page - $billets_par_page, PDO::PARAM_INT);
            $response->bindValue(':max_billet', $_GET['page'] * $billets_par_page, PDO::PARAM_INT);
            $response->execute();

            $is_billets = false;

            while($data = $response->fetch()) {

                AffichageBillet($data['title'], $data['author'], $data['datee'], $data['text'], './coments.php?billet='.$data['id'], 'Commentaires');
                $GLOBALS['is_billets'] = true;
            } 
            $response->closeCursor();

            if($is_billets == false) header('location: errors/pageError.php');

// Récupération du nombre de pages

            $response = $bdd->query('SELECT COUNT(*) AS nb_billets FROM billets');
            $data = $response->fetch();
            $response->closeCursor();

            $nb_billets = $data['nb_billets'];
            $nb_pages = ceil($nb_billets / $billets_par_page);

// Affichage des différents liens de pages avec for(){}

            for($i = 0; $i < $nb_pages; $i++) {

                echo '<a href="index.php?page='.($i + 1).'">Page'.($i + 1).'</a><br>';
            }

        ?>
    </body>

</html>