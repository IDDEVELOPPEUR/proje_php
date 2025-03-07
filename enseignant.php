<?php
session_start();
    include 'connexion.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap.css" rel="stylesheet"integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Page Enseignant</title>
</head>
<body>

<header class="p-3 m-0 border-0">
                                         <!-- Navbar principale -->

        <nav class="navbar navbar-dark bg-success fixed-top">
            <div class="container-fluid">
                <h1 class="navbar-brand text-center">RESERVATION DES SALLES ET MATERIELS</h1>
                <!-- Bouton pour afficher le menu offcanvas avec l'icon à trois barrettes -->
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#listeLiens"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Menu offcanvas -->
                <div class="offcanvas offcanvas-end text-bg-dark" id="listeLiens">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title">Aller vers:</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">

                        <a href="deconnexion.php" class="btn btn-outline-danger mt-3">Déconnexion</a>
                    </div>
                </div>
            </div>
        </nav>
        <hr>
    </header>
        

    <main>
      <!-- recuperation de du message de bienvenue -->
     <?php if (isset($_GET['ms'])) {  
        echo '<div class="alert alert-success text-center" role="alert"><h3>' .$_GET['ms']. '</h3></div>';  
    }?>
         

    <br><hr>
     <div class=" mt-6 container py-4" >  
  <div class="row align-items-md-stretch">  
    
    <div class="col-md-4" >  
      <div class=" p-2 text-bg-dark rounded-3">  
        <h2>Réserver les <h2 class=" text-success">Salles</h2></h2>  
        <p>Vous pouvez réserver les salles facilement !</p>  
        <a href="salle.php" class="btn btn-success" >Réserver</a>  
      </div>  
    </div>  

    <div class="col-md-4"> 
      <div class="p-2 text-bg-dark rounded-3">  
        <h2>Réserver les <h2 class=" text-success">Matériels</h2></h2>  
        <p>Vous pouvez réserver les matériels facilement !</p>  
        <a href="materiel.php" class="btn btn-success" >Réserver</a>  
      </div>  
    </div>  
    
    <div class="col-md-4" >  
      <div class="p-2  text-bg-dark rounded-3">  
        <h2>Visualiser vos <h2 class=" text-warning">Réservations</h2> </h2>  
        <p>Vous pouvez revoir vos réservations ici !</p>  
        <a href="voirReserves.php" class="btn btn-outline-warning" >Voir</a>  
      </div>  
    </div>  

  </div>  
</div>  <hr>

  </main>

    <footer>

    </footer>
</body>
</html>




