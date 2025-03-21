<?php
    session_start();
    include 'connexion.php';
    //si la variable de session idPersonne n"est pas présent , on le redirige vers autentification
    if(!$_SESSION['idPersonne']){
      header("location:authentification.php?messagER=Veillez vous connecter !");
    }
    // @ AJOUT SUPPLEMENTAIRE

    //ici on verifie tous les réservations dont dateFin est supérieure à aujourd'hui et on met la disponiblité à 1
    $d2 = $con->prepare("UPDATE reserverSalle join salle SET disponibilite = 1 WHERE dateFin < CURDATE()");
    $d2->execute();

    // Préparer la requête pour récupérer les salles disponibles  
    $stmt = $con->prepare("SELECT idSalle, nomSalle FROM salle WHERE disponibilite = 1");
    $stmt->execute();
    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // on vérifie si les champs sont remplis  
    if (isset($_POST["valider"])) {
        extract($_POST);
        // on récupère les données du formulaire  

        // Récupérer l'ID de l'enseignant de la session 
        //session_start(); 
        $FK_personne = $_SESSION['idPersonne'];

        // Récupération des données au niveau du formulaire   
        $dateRS = $_POST['dateRS'];
        $dateFin = $_POST['dateFin'];
        $heureRS = $_POST['heureRS'];
        $FK_Salle = $_POST['nomSalle'];

        //variable qui reçoit la date d'aujourd'hui
        $dateAUJOURDHUI = date("Y-m-d"); 


        //on verifie si la date debut  est inferieur a aujourd'hui et si la date de fin est inferieure à la date debut
        if($dateFin<$dateRS || $dateRS<$dateAUJOURDHUI){
            header("location:salle.php? rep=La date début doit être supérieure (ou égale) à aujourd'hui ou la date fin doit être supérieure à date début !");
            exit();
        }
        else{

            // Préparer la requête d'insertion  
            $req = $con->prepare("INSERT INTO reserverSalle (dateRS, heureRS,dateFin, FK_personne, FK_Salle) VALUES (:date, :heure,:dateF, :id, :idSalle)");
            
            // Lier les paramètres  
            $req->bindParam(":date", $dateRS);
            $req->bindParam(":heure", $heureRS);
            $req->bindParam(":dateF", $dateFin);
            $req->bindParam(":id", $FK_personne);
            $req->bindParam(":idSalle", $FK_Salle);

        
            
            // Exécution de la requête  ET mise a jour de la disponibilite
            if ($req->execute()) {
                // Mettre à jour la disponibilité de la salle à 0
                $stmt = $con->prepare("UPDATE salle SET disponibilite = 0 WHERE idSalle = :IDSalle");
                $stmt->bindParam(':IDSalle', $FK_Salle);
                $stmt->execute();
                header("location:voirReserves.php?SRV=Réservation effectuée avec succès !");
                exit();
            } 
            else 
            {
                header("location:salle.php?MRE=Erreur lors de la réservation de la salle !");
                exit();
            }
        }

}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/bootstrap.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver Salle</title>
</head>




<body><br>
    <!-- lien vers la page enseignant -->
    <a style="margin-left:20px;" href="enseignant.php" class="btn btn-dark" role="button">Retour</a>

    <br><br><br><br>
    <!-- Formulaire pour les salles -->
    <!-- titre de la page -->
    <h1 class="text-center">Réservation Salle</h1>
    <div class="container lecontener container-fluid">
        <!-- Si la reservation a échoué -->
        <!-- On récupère le message MRE (message de reservation erreur)-->
        <?php if (isset($_GET['MRE'])) { ?>
            <div class="text-center alert alert-danger" role="alert"><?php echo $_GET['MRE']; ?></div>
        <?php } ?>

        <!-- On récupère le message d'erreur pour les dates -->
        <?php if (isset($_GET['rep'])) { ?>
            <div class="text-center alert alert-danger" role="alert"><?php echo $_GET['rep']; ?></div>
        <?php } ?>
        <!-- message qui montre que la reservation est valide -->
        <?php if (isset($_GET['MRV'])) { ?>
            <div class="text-center alert alert-success" role="alert"><?php echo $_GET['MRV']; ?></div>
        <?php } ?>

        <form class="container" method="POST" action="">


            <div class="form-group">

                <label for="nomSalle">Salles disponibles:</label>
                <select class="form-control" name="nomSalle" id="nomSalle" required>
                    <!-- Option par défaut -->
                    <option disabled>Sélectionnez une salle</option>
                    <!-- ce sont maintenant les options avec les données de la base de données -->
                    <?php foreach ($salles as $salle): ?>
                        <option value="<?php echo $salle['idSalle']; ?>"><?php echo $salle['nomSalle']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="dateRS">Date début:</label>
                <input class="form-control" required type="date" id="dateRS" name="dateRS" />
            </div>

            <div class="form-group">
                <label for="heureRS">Heure début:</label>
                <input class="form-control" required type="time" name="heureRS" id="heureRS" />
            </div>

            <div class="form-group">
                <label for="dateFin">Date fin:</label>
                <input class="form-control" required type="date" id="dateFin" name="dateFin" />
            </div>

            <input class="btn btn-success btn-block" type="submit" name="valider" value="Valider" />
        </form>
        <hr>
        <br>
    </div>
</body>

</html>