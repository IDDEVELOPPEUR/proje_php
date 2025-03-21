
<?php  
session_start();
include 'connexion.php'; 

    //verifier si la session de l'utilisateur exit toujours 
    if(!$_SESSION['idPersonne']){
      header("location:authentification.php?messagER=Veillez vous connecter !");
    }
//  la requête pour récupérer les matériels dont la quantité est supérieure à 0  
$stmt = $con->prepare("SELECT * FROM materiel WHERE quantite > 0");  
$stmt->execute();  
$materiels = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Réservation de Matériel</title>    
</head>  
<body>
<br>  
    <!-- lien vers la page enseignant -->  
    <a style="margin-left:20px;" href="enseignant.php" class="btn btn-dark" role="button">Retour</a>   
    <a style="margin-right:20px ;float:right" href="materiel.php" class="btn  btn-outline-warning" role="button">Actualiser</a>  <br><br><br> 
    <h1 class="text-center">Réserver un Matériel</h1>
    <div class="lecontener container-fluid">  
    <form class="container" method="POST" action="">  
        <div class="form-group">  
            <label for="materiel">Sélectionnez le matériel :</label>  
            <select name="FK_materiel" id="materiel" class="form-control" required>  
                <option value="" disabled>Choisissez un matériel</option>  
                <?php foreach ($materiels as $materiel): ?>  
                    <option value="<?php echo $materiel['idMateriel']; ?>">  
                        <?php echo $materiel['nomMateriel'] ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"> <?php echo $materiel['quantite']; ?></span>  
                    </option>  
                <?php endforeach; ?>  
            </select>  
        </div>  

        <div class="form-group">  
            <label for="dateRS">Date de réservation :</label>  
            <input type="date" name="dateRS" id="dateRS" class="form-control" required>  
        </div>  

        <div class="form-group">  
            <label for="heureRS">Heure de réservation :</label>  
            <input type="time" name="heureRS" id="heureRS" class="form-control" required>  
        </div>

        <div class="form-group">  
            <label for="dateFin">Date fin de réservation :</label>  
            <input type="date" name="dateFin" id="dateFin" class="form-control" required>  
        </div>

        <input type="submit" name="valider" value="Réserver" class="btn btn-block btn-success">  
    </form>  
    </div>
    <?php if (isset($_GET['rep'])) { ?>
            <div class="text-center alert alert-danger" role="alert"><?php echo $_GET['rep']; ?></div>
    <?php } ?>
</body>


                                <!--PHP  -->

    <?php  
    // Traitement du formulaire  
    if (isset($_POST["valider"])) { 
        extract($_POST); 
       // session_start(); 
        
        include 'connexion.php'; 
        $FK_personne = $_SESSION['idPersonne'];  
        // Vérifier la quantité du matériel choisi  
        $stmt = $con->prepare("SELECT quantite FROM materiel WHERE idMateriel = :FK_materiel");  
        $stmt->bindParam(':FK_materiel', $FK_materiel);  
        $stmt->execute();  
        $matSelec = $stmt->fetch(PDO::FETCH_ASSOC);  

        //variable qui reçoit la date d'aujourd'hui
        $dateAUJOURDHUI = date("Y-m-d"); 


        //vérification et remise de matériels dont la date fin est passé
        //ici j'ajoute + 1 à la quantite  du materiel dont sa reservation au niveau de la table reserverMateriel est passée
        

        //vérification de la disponibilité du matériel 
        if ($matSelec && $matSelec['quantite'] > 0) 
        {  

        
            //on verifie si la date debut  est inferieur a aujourd'hui et si la date de fin est inferieure à la date debut
            if($dateFin<$dateRS || $dateRS<$dateAUJOURDHUI){
                echo "<div class='alert alert-danger text-center' role='alert'>materiel.php?rep=La date début doit être supérieure (ou égale) à aujourd'hui ou la date fin doit être supérieure à date début !</div>";  
               
                
            }

            else{

       

                    // Insertion de la réservation dans la base de données  
                    $stmt = $con->prepare("INSERT INTO reserverMateriel (dateRS, heureRS,dateFin, FK_materiel, FK_personne) VALUES (:dateRS, :heureRS,:dateF, :FK_materiel, :FK_personne)");  
                    $stmt->bindParam(':dateRS', $dateRS);
                    $stmt->bindParam(':dateF', $dateFin);
                    $stmt->bindParam(':heureRS', $heureRS);
                    $stmt->bindParam(':FK_materiel', $FK_materiel);  
                    $stmt->bindParam(':FK_personne', $FK_personne);  
                

                    // ici on verifie si la requete a ete execute avec succes
                    if ($stmt->execute()) {  
                        //ici on diminue la quantité du matériel de 1 element  
                        $stmt = $con->prepare("UPDATE materiel SET quantite = quantite - 1 WHERE idMateriel = :FK_materiel");  
                        $stmt->bindParam(':FK_materiel', $FK_materiel);  
                        $stmt->execute();  

    

                        echo "<div class='alert alert-success text-center' role='alert'>Réservation effectuée avec succès !</div>";  
                
                    } 
                    else 
                    {  
                        echo "<div class='alert alert-danger' role='alert'>Erreur lors de la réservation.</div>";  
                    }  
                }        
        }
        else {  
            echo "<div class='alert alert-warning' role='alert'>Le matériel sélectionné n'est plus disponible.</div>";  
        }  
    }  
    ?>  

  
</body>  
</html>  