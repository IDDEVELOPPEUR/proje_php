<?php  
// Traitement des données   
if (isset($_POST["inscription"])) {  
    extract($_POST); // Récupération des variables du formulaire  

    // Vérification de l'exactitude des deux mots de passe  
    if ($motPasse !== $confPasse) {
        header("location:inscription.php?msg=Les deux mots de passe ne sont pas identiques !");  
        exit();  
    } else {   
        try {  
            // On inclut la page de Connexion à la base de données  
            include("connexion.php");  

            // Variable qui récupère la date courante  
            $dateCourante = date("Y-m-d");   

            // @ INSERTION DANS LA TABLE PERSONNE  
            // Insertion avec la requête préparée dans la table personne  
            $req = $con->prepare("INSERT INTO personne (prenom, nom, telephone, email, adresse, profil) VALUES (:prenom, :nom, :telephone, :email, :adresse, :profil)");  
          
            // bindParam permet de lier les variables(valeurs) avec les paramètres de la requête  
            $req->bindParam(':prenom', $prenom);  
            $req->bindParam(':nom', $nom);  
            $req->bindParam(':telephone', $telephone);  
            $req->bindParam(':email', $email);  
            $req->bindParam(':adresse', $adresse);  
            $req->bindParam(':profil', $profil);

            // Ici  on exécute la requête $req  
            $req->execute();

            // Récupération de l'id de la dernière personne insérée  
            $lastId = $con->lastInsertId();  

            // @ INSERTION AU NIVEAU DE TABLE  COMPTE  
            // On vérifie si le login n'existe pas déjà dans compte avant d'insérer  
            $req1 = $con->prepare("SELECT login FROM compte WHERE login = :login");  
            $req1->bindParam(':login', $login);  
            $req1->execute();  

            $resultat = $req1->fetchAll();  

            // Si le login existe déjà  
            if ($resultat) {  
                header("location:inscription.php?MCE=Ce login existe déjà");  
                exit();  
            } else {  
                // On crypte le mot de passe   
                $cript = password_hash($motPasse, PASSWORD_DEFAULT);  
                // Insertion dans la table compte  
                $req2 = $con->prepare("INSERT INTO compte (FK_personne, statutCompte, login, motPasse, date_creation) VALUES (:lastId, :statut, :login, :motPasse, :dateCreation)");  
                $req2->bindParam(':lastId', $lastId);  
                $req2->bindParam(':statut', $profil);  
                $req2->bindParam(':login', $login);  
                $req2->bindParam(':motPasse', $cript);  
                $req2->bindParam(':dateCreation', $dateCourante);  
                $req2->execute();  
                
                // Maintenant on redirige l'utilisateur vers la page d'authentification  
                header("location:authentification.php?message=Votre Compte a été bien créé !");  
                exit();
            }  
        } catch (PDOException $e) {  
            die("Erreur : " . $e->getMessage());   
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
    <title>Inscription</title>  
</head>  
<header>
    

</header>
<body>  
<br>   
    <!-- lien vers la page accueil -->  
    <a style="margin-left:20px;" href="index.php" class="btn btn-dark" role="button">Vers Accueil</a>  
    
    <!-- titre de la page -->    
    <br><br><br><br>   

    <h1 class="text-center">Inscription</h1>  
    <div class="container container-fluid  leconteneurIns">        
        <!-- Si les deux mots de passe ne correspondent pas -->  
        <!-- On récupère le message msg -->  
        <?php if (isset($_GET['msg'])) { ?>  
            <div class="text-center alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['msg']); ?></div>  
        <?php } ?>  
        <?php if (isset($_GET['MCE'])) { ?>  
            <div class="text-center alert alert-warning" role="alert"><?php echo htmlspecialchars($_GET['MCE']); ?></div>  
        <?php } ?>  

        <form class="container container-fluid " method="POST" action="">  
            <div class="form-group">  
                <label for="prenom">Prénom</label>  
                <input class="form-control" required minlength="2" maxlength="80" type="text" name="prenom"  
                    placeholder="Votre prénom ici !" />  
            </div>  

            <div class="form-group">  
                <label for="nom">Nom</label>  
                <input class="form-control" required minlength="2" maxlength="60" type="text" name="nom"  
                    placeholder="Votre nom ici !" />  
            </div>  

            <div class="form-group">  
                <label for="adresse">Adresse</label>  
                <input class="form-control" required minlength="2" maxlength="100" type="text" name="adresse"  
                    placeholder="Votre adresse ici !" />  
            </div>  

            <div class="form-group">  
                <label for="telephone">Téléphone</label>  
                <input class="form-control" required minlength="9" type="tel" name="telephone"  
                    placeholder="Votre numéro téléphone ici !" maxlength="9" />  
            </div>  

            <div class="form-group">  
                <label for="email">Email</label>  
                <input class="form-control" required type="email" name="email" placeholder="VotreEmail@mail.mail"  
                    maxlength="100" />  
            </div>  

            <div class="form-group">  
                <label for="login">Login</label>  
                <input class="form-control" required minlength="3" maxlength="50" type="text" name="login"  
                    placeholder="Votre login" />  
            </div>  

            <div class="form-group">  
                <label for="motPasse">Mot de passe</label>  
                <input class="form-control" required minlength="4" maxlength="30" type="password" name="motPasse"  
                    placeholder="Votre mot de passe ici !" />  
            </div>  

            <div class="form-group">  
                <label for="confPasse">Confirmation du mot de passe</label>  
                <input class="form-control" required minlength="4" maxlength="30" type="password" name="confPasse"  
                    placeholder="Confirmer votre mot de passe" />  
            </div>  

            <div class="form-group">  
                <label for="profil">Profil</label>
                <select name="profil" class="form-control">  
                    <option selected value="Enseignant">Enseignant</option>  
                </select>  
            </div>  

            <input class="btn btn-primary btn-block" type="submit" name="inscription" value="Inscription" />  
        </form>  
        <hr>  
        <!-- Lien authentification -->  
        <div class="text-right">  
            <a class="btn btn-outline-success lien" href="authentification.php">J'ai déjà un compte</a>  
        </div><br>  
    </div>  

</body>  
</html>  