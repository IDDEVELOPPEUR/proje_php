<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">   
    <link rel="stylesheet" href="css/bootstrap.css">  
    <link rel="stylesheet" href="css/style.css">  
    <script src="js/bootstrap.js"></script>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Authentification</title>  
</head>  
<body>  
    <br>   
    <!-- lien vers la page accueil -->  
    <a style="margin-left:20px;" href="index.php" class="btn btn-dark btn-hover" role="button">Vers Accueil</a>  
   <br><br>
    <?php  
    // récupération du message m d'erreur depuis inscription.php
    if (isset($_GET['m'])) {  
        echo '<div class="alert alert-danger text-center" role="alert">' .$_GET['m']. '</div>';  
    }  
    
    // récupération du message que le compte est créé depuis depuis authentification.php  
    if (isset($_GET['message'])) {  
        echo '<div class="alert alert-success text-center" role="alert">' .$_GET['message']. '</div>';  
    }
    //recuperation du message qui dit demande à l'utilisateur de se connecter car elle voulez rentrer dans les autre page sans se connecter
    if (isset($_GET['messagER'])) {  
        echo '<div class="alert alert-info text-center" role="alert">' .$_GET['messagER']. '</div>';  
    }?>
        <!-- message de déconnection reussie -->
        <?php 
    if (isset($_GET['dec'])) {  
        echo '<div class="alert alert-info text-center" role="alert">' .$_GET['dec']. '</div>';  
    }?>
    <!-- titre de la page -->    
    <br>  
    <h1 class="text-center">Authentification</h1>  
    
    
    <div class="container lecontener container-fluid"> 
        <form action="#" method="POST">  
            <div class="form-group">  
                <label for="">Login</label>  
                <input required class="form-control" type="text" name="login" placeholder="Mettez le login">  
            </div>  
            <div class="form-group">  
                <label for="">Mot de passe</label>  
                <input required class="form-control" type="password" name="motPasse" placeholder="Mettez le mot de passe">  
            </div>  
            <input class="btn btn-primary btn-block mon-anime" name="connecter" type="submit" value="Se connecter">  
        </form>  
        <hr>  
        <p class="text-center">Vous n'avez pas de compte ? <a class="lien2" href="inscription.php">Inscrivez-vous ici</a></p>  
    </div>  <br><br>

    <?php  


    // on traite les données entrées  
    if (isset($_POST['connecter'])) {  
        extract($_POST);  
        // Inclusion de la connexion à la base de données  
        include 'connexion.php';  

        // on utilise une requête préparée pour récupérer et comparer les données  
        $req = $con->prepare("SELECT *  FROM personne JOIN compte ON idPersonne = FK_personne WHERE login = ?");  
        $req->execute([$login]);  

        // on vérifie si un utilisateur avec ce login existe  
        if ($donnee = $req->fetch(PDO::FETCH_ASSOC)) {  
            // on vérifie le mot de passe en utilisant password_verify  
            if (password_verify($motPasse, $donnee['motPasse'])) { 
                 
                // on commence toujours la session  
                session_start();  

                // on stock les données de l'utilisateur dans des variables de session  
                
                $_SESSION['prenom'] = $donnee["prenom"];  
                $_SESSION['nom'] = $donnee["nom"];  
                $_SESSION['login'] = $donnee["login"];  
                $_SESSION['idPersonne'] = $donnee["idPersonne"];  

                // ici on redirige l'utilisateur vers sa page selon son profil   
                // si il est enseignant  
                if ($donnee["profil"] == "Enseignant") {  
                    header("location:enseignant.php?ms=Bienvenue cher ". $_SESSION["prenom"]);  
                    exit();  
                }    
            } else {  
                header("location:authentification.php?m=Mot de passe incorrect !");  
                exit();  
            }  
        } else {  
            header("location:authentification.php?m=Login incorrect !");  
            exit();  
        }  
    }  
    ?>  
</body>  
</html>  