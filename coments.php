<?php 
    require './functions/billet.php';
    $coment_par_page = 5;
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
        <?php

// Connexion à la base de données

        try {
            $bdd = new PDO('mysql:host=localhost;dbname=tp;charset=utf8', 'root', '');
        } catch (Exception $e) {
            die('Error: '.$e->getMessage());
        }
        
// Récupération et affichage du billet

        $response = $bdd->prepare('SELECT title, text, DATE_FORMAT(date, "%d/%m/%Y à %Hh%imin") AS datee, author FROM billets WHERE id = ?');
        $response->execute(array($_GET['billet']));
        $data = $response->fetch();

# ... Si l'id de l'url ne correspond à aucun billet, message d'erreur

        if(empty($data)) { 

            header('location: errors/pageError.php');

# ... Sinon, afficher le billet correspondant à l'id de l'url

        } else {

            AffichageBillet($data['title'], $data['author'], $data['datee'], $data['text'], 'javascript:history.back()', 'Retour');

            $response->closeCursor();

            echo '<p class="separator">Commentaires</p>';
            
// Récupération et affichage des commentaires

            if(empty($_GET['page'])) $_GET['page'] = 1;

            $response = $bdd->prepare('SELECT author, coment, DATE_FORMAT(date, "%d/%m/%Y à %Hh%imin") AS datee FROM coments WHERE id_billet = :id_billet ORDER BY datee LIMIT :min_coment, :max_coment');
            $response->bindValue(':min_coment', $_GET['page'] * $coment_par_page - $coment_par_page, PDO::PARAM_INT);
            $response->bindValue(':max_coment', $_GET['page'] * $coment_par_page, PDO::PARAM_INT);
            $response->bindValue(':id_billet', $_GET['billet']);
            $response->execute();

            $is_coment = false;

            while($data = $response->fetch()) {

                echo '<div class="box">
                        <div class="coment-header">
                            <p><span class="anotation">'.htmlspecialchars($data['datee']).' | </span><strong>'.htmlspecialchars($data['author']).'</strong></p>
                        </div>
                            <p>'.htmlspecialchars($data['coment']).'</p>
                    </div>';
                $GLOBALS['is_coment'] = true;
            }
            $response->closeCursor();

            if($is_coment == false) header('location: errors/pageError.php');

// Récupération du nombre de pages (de commentaires)

            $response = $bdd->prepare('SELECT COUNT(*) nb_coments FROM coments WHERE id_billet = ?');
            $response->execute(array($_GET['billet']));
            $data = $response->fetch();
            $response->closeCursor();

            $nb_coments = $data['nb_coments'];
            $nb_pages = ceil($nb_coments / $coment_par_page);

// Affichage des différents liens de pages avec for(){}

            for($i = 0; $i < $nb_pages; $i++) {
                
                echo '<a href="coments.php?billet='.$_GET['billet'].'&amp;page='.($i + 1).'">Page '.($i + 1).'</a><br>';
            }
        
// Formulaire pour l'envoi de commentaires avec POST sur addComent.php

            ?>     
            <div class="separator">
                <p>Ajouter un commentaire</p>
            </div>

            <div class="box" id="coment-container">
                <form method="post" action="./backend/addComent.php">
                    <label for="pseudo">Pseudo</label><br>
                    <input type="text" name="pseudo" id="pseudo" class="form-style">

                    <button type="submit">Envoyer</button><br>

                    <label for="coment">Commentaire</label><br>
                    <textarea name="coment" id="coment" cols="30" rows="10" class="form-style"></textarea>

                    <input type="hidden" name="billet" value="<?php echo $_GET['billet'] ?>">
                </form>
            </div>

        <?php 
        } 
        ?>

    </body>

</html>