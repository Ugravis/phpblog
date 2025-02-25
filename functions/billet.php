<?php

// Permet d'afficher un billet

    function AffichageBillet($a, $b, $c, $d, $link_value, $link_name) {

        echo '<div class="box">
                <div class="billet-header">
                    <h3>'.htmlspecialchars($a).'</h3>
                    <p class="anotation"> Par '.htmlspecialchars($b).' le '.htmlspecialchars($c).'</p>
                </div>
                    <p class="billet-contenu">'.htmlspecialchars($d).'</p>
                    <a href="'.$link_value.'">'.$link_name.'</a>
            </div>';
    }

?>