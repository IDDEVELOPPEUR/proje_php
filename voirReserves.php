<?php
//demarage de la session
session_start();
//inclusion de la page de connection
include "connexion.php";

//      Partie salle
//on selectionne toutes les sallles dont que l'utilisateur a reservé
$req = $con->prepare("select * from personne  inner join reserverSalle on idPersonne=FK_personne inner join salle  on idSalle=FK_Salle where FK_personne= :session");
$req->bindParam(":session", $_SESSION['idPersonne']);
$req->execute();

//on recupere  toutes les lignes de donnnée et on les stocke dans la variable donnees
$donnees = $req->fetchAll();


//Partie materiel
//on selectionne tous les materiels que l'utilisateur a réservé
$r = $con->prepare("select * from personne  inner join reserverMateriel on idPersonne=FK_personne inner join materiel on idMateriel=FK_materiel  where FK_personne= :session " );
$r->bindParam(":session", $_SESSION['idPersonne']);
$r->execute();

//on recupere  toutes les lignes de donnnée et on les stocke dans la variable contenus
$contenus = $r->fetchAll();
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/bootstrap.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir salle</title>
</head>

<body> <!-- lien vers la page enseignant --> <br>
    <a style="margin-left:20px;" href="enseignant.php" class="btn btn-dark" role="button">Retour</a>
    <br><br>
    <!-- recuperation des messages de succès -->
    <?php
    if (isset($_GET['SRV'])) {
        echo '<div class="alert alert-success text-center" role="alert">' . $_GET['SRV'] . '</div>';
    }
    ?>
    <?php
    if (isset($_GET['MV'])) {
        echo '<div class="alert alert-success text-center" role="alert">' . $_GET['MV'] . '</div>';
    }
    ?>
    <br>
    <h1 class="text-center ">Salle réservées </h1><br>
    <div class="container text-center ">
        <table class="table table-hover table-bordered ">
            <thead>
                <tr>
                    <th class="text-bg-success">Id</th>
                    <th class="text-bg-success">Réservation</th>
                    <th class="text-bg-success">Date début</th>
                    <th class="text-bg-success">Heure</th>
                    <th class="text-bg-success">Date fin</th>
                    <th class="text-bg-success">Nom salle</th>
                    <th class="text-bg-success">Réservée par:</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donnees as $donnee) { ?>
                    <tr>
                        <!-- affichage des données au niveau de voir mes reservations -->
                        <td class="table-success"><?php echo $_SESSION['idPersonne']; ?></td>
                        <td><?php echo $donnee['numReservation']; ?></td>
                        <td><?php echo $donnee['dateRS']; ?></td>
                        <td><?php echo $donnee['heureRS']; ?></td>
                        <td><?php echo $donnee['dateFin']; ?></td>
                        <td><?php echo $donnee['nomSalle']; ?></td>
                        <td><?php echo $donnee['prenom'] . " " . $donnee['nom']; ?></td>


                    </tr>
                <?php } ?>

        </table>
    </div>
    <br><br>



    <!--tableau de materiels reserves -->
    <h1 class="text-center">Matériels réservés</h1><br>

    <div class="container text-center ">
        <table class="table table-hover table-bordered ">
            <thead>
                <tr class="table-success ">
                    <th class="text-bg-success">Id</th>
                    <th class="text-bg-success">Réservation</th>
                    <th class="text-bg-success">Date début</th>
                    <th class="text-bg-success">Heure</th>
                    <th class="text-bg-success">Date fin</th>
                    <th class="text-bg-success">Nom materiel</th>
                    <th class="text-bg-success">Modèle</th>
                    <th class="text-bg-success">Marque</th>
                    <th class="text-bg-success">Réservée par:</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contenus as $contenu) { ?>
                    <tr>
                        <!-- affichage des données au niveau de voir mes reservations -->
                        <td class="table-success"><?php echo $_SESSION['idPersonne']; ?></td>
                        <td><?php echo $contenu['numReservation']; ?></td>
                        <td><?php echo $contenu['dateRS']; ?></td>
                        <td><?php echo $contenu['heureRS']; ?></td>
                        <td><?php echo $contenu['dateFin']; ?></td>
                        <td><?php echo $contenu['nomMateriel']; ?></td>
                        <td><?php echo $contenu['modele']; ?></td>
                        <td><?php echo $contenu['marque']; ?></td>
                        <td><?php echo $contenu['prenom'] . " " . $donnee['nom']; ?></td>


                    </tr>
                <?php } ?>

        </table>
    </div><br><br>
</body>

</html>