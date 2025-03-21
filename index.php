<?php 
    session_start();
    include "connexion.php";
    //pour les salles reservées
    $req = $con->prepare("select * from personne p join reserversalle rs on p.`idPersonne` = rs.`FK_personne` join salle s on s.`idSalle` = rs.`FK_Salle`");  
    $req->execute();
    $reservations = $req->fetchAll(PDO::FETCH_ASSOC);
    //pour les materiels reservés
    $req2 = $con->prepare("select * from personne p join reservermateriel rm on p.`idPersonne` = rm.`FK_personne` join materiel m on m.`idMateriel` = rm.`FK_materiel`");  
    $req2->execute();
    $reservationsMateriel = $req2->fetchAll(PDO::FETCH_ASSOC);
    

    //ici on supprime toutes les reservations dont la date fin est passee
    //date courante
    $AUJOURDHUI = date("Y-m-d"); 
    // POUR LES RESERVATIONS DE MATERIELS
    $sup=$con->prepare("delete from reserverMateriel where dateFin < :auj");
    $sup->bindParam(":auj", $AUJOURDHUI);
    $sup->execute();
    // POUR LES RESERVATIONS DE SALLES
    $sup1=$con->prepare("delete from reserverSalle where dateFin < :auj");
    $sup1->bindParam(":auj", $AUJOURDHUI);
    $sup1->execute();


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap.css" rel="stylesheet"integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="p-3 m-0 border-0">
        <!-- Navbar principale -->
        <nav class="navbar navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <h1 class="navbar-brand text-center">GESTION DE LA RESERVATION DES SALLES ET MATERIELS</h1>
                <!-- Bouton pour afficher le menu offcanvas avec l'icon à trois barrettes -->
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#listeLiens"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Menu offcanvas -->
                <div class="offcanvas offcanvas-end text-bg-dark" id="listeLiens">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title">Aller vers:</h5>
                        <button type="button" class="btn-close btn-close-white " data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="btn btn-outline-primary" href="inscription.php">Inscription</a>
                            </li><br>
                            <li class="nav-item">
                                <a class="btn btn-outline-success" href="authentification.php">Connexion</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <hr>
    </header>

    <main><br>
        <h1 class="text-center">Planning des salles de cours</h1><hr>
        <div class="container text-center">
            <div>
                <table class="table table-hover text-center table-bordered ">
                    <thead >
                        <tr>
                            <th class="text-bg-success">Numéro réservation</th>
                            <th class="text-bg-success">Date début</th>
                            <th class="text-bg-success">Heure début</th>
                            <th class="text-bg-success">Date fin</th>
                            <th class="text-bg-success">Nom salle</th>
                            <th class="text-bg-success">Enseignant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation) {?>
                        <tr>
                            <td><?php echo $reservation['numReservation'];?></td>
                            <td><?php echo $reservation['dateRS'];?></td>
                            <td><?php echo $reservation['heureRS'];?></td>
                            <td><?php echo $reservation['dateFin'];?></td>
                            <td><?php echo $reservation['nomSalle'];?></td>
                            <td><?php echo $reservation['prenom']." ".$reservation['nom'];?></td>
                        </tr>
                        <?php }?>
        
   
                    </tbody>
                </table>

                <br> <hr> <br>
                <h1 class="text-center">Planning des materiels</h1><hr>

                <table class="table table-hover text-center table-bordered ">
                    <thead >
                        <tr>
                            <th class="text-bg-success">Numéro réservation</th>
                            <th class="text-bg-success">Date début</th>
                            <th class="text-bg-success">Heure début</th>
                            <th class="text-bg-success">Date fin</th>
                            <th class="text-bg-success">Nom matériel</th>
                            <th class="text-bg-success">Modèle</th>
                            <th class="text-bg-success">Marque</th>
                            <th class="text-bg-success">Enseignant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservationsMateriel as $re) {?>
                        <tr>
                            <td><?php echo $re['numReservation'];?></td>
                            <td><?php echo $re['dateRS'];?></td>
                            <td><?php echo $re['heureRS'];?></td>
                            <td><?php echo $re['dateFin'];?></td>
                            <td><?php echo $re['nomMateriel'];?></td>
                            <td><?php echo $re['modele'];?></td>
                            <td><?php echo $re['marque'];?></td>
                            <td><?php echo $re['prenom']." ".$re['nom'];?></td>
                        </tr>
                        <?php }?>
        
   
                    </tbody>
                </table>

            </div>
        </div>

    </main><hr> <br>


    <footer>

    </footer>

</body>

</html>