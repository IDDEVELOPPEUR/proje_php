<?php  
// Inclusion du fichier de connexion à la base de données  
include 'connexion.php';  
session_start(); // Démarrage de la session pour gérer les utilisateurs  

// Récupérer les réservations de salles  
$stmtSalles = $con->prepare("  
    SELECT r.numReservation, r.dateRS, r.heureRS, s.nomSalle, p.prenom, p.nom   
    FROM reserverSalle r   
    JOIN salle s ON r.FK_Salle = s.idSalle   
    JOIN personne p ON r.FK_personne = p.idPersonne  
");  
$stmtSalles->execute();  
$reservationsSalles = $stmtSalles->fetchAll(PDO::FETCH_ASSOC); // Récupération des résultats  

// Récupérer les réservations de matériel  
$stmtMateriel = $con->prepare("  
    SELECT r.numReservation, r.dateRS, r.heureRS, m.nomMateriel, p.prenom, p.nom   
    FROM reserverMateriel r   
    JOIN materiel m ON r.FK_materiel = m.idMateriel   
    JOIN personne p ON r.FK_personne = p.idPersonne  
");  
$stmtMateriel->execute();  
$reservationsMateriel = $stmtMateriel->fetchAll(PDO::FETCH_ASSOC); // Récupération des résultats  
?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Liste des Réservations</title>  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">  
</head>  
<body>  
    <div class="container">  
        <h1 class="text-center">Liste des Réservations</h1>  
        
        <h2>Réservations de Salles</h2>  
        <table class="table table-bordered">  
            <thead>  
                <tr>  
                    <th>ID</th>  
                    <th>Date de Réservation</th>  
                    <th>Heure de Réservation</th>  
                    <th>Nom de la Salle</th>  
                    <th>Nom de l'Enseignant</th>  
                    <th>Action</th>  
                </tr>  
            </thead>  
            <tbody>  
                <?php foreach ($reservationsSalles as $reservation): ?>  
                    <tr>  
                        <td><?php echo $reservation['numReservation']; ?></td>  
                        <td><?php echo $reservation['dateRS']; ?></td>  
                        <td><?php echo $reservation['heureRS']; ?></td>  
                        <td><?php echo $reservation['nomSalle']; ?></td>  
                        <td><?php echo $reservation['prenom'] . ' ' . $reservation['nom']; ?></td>  
                        <td>  
                            <a href="modifierReservationSalle.php?id=<?php echo $reservation['numReservation']; ?>" class="btn btn-primary">Modifier</a>  
                        </td>  
                    </tr>  
                <?php endforeach; ?>  
            </tbody>  
        </table>  

        <h2>Réservations de Matériel</h2>  
        <table class="table table-bordered">  
            <thead>  
                <tr>  
                    <th>ID</th>  
                    <th>Date de Réservation</th>  
                    <th>Heure de Réservation</th>  
                    <th>Nom du Matériel</th>  
                    <th>Nom de l'Enseignant</th>   
                </tr>  
            </thead>  
            <tbody>  
                <?php foreach ($reservationsMateriel as $reservation): ?>  
                    <tr>  
                        <td><?php echo $reservation['numReservation']; ?></td>  
                        <td><?php echo $reservation['dateRS']; ?></td>  
                        <td><?php echo $reservation['heureRS']; ?></td>  
                        <td><?php echo $reservation['nomMateriel']; ?></td>  
                        <td><?php echo $reservation['prenom'] . ' ' . $reservation['nom']; ?></td>  
                        <td>  
                            <a href="modifierReservationMateriel.php?id=<?php echo $reservation['numReservation']; ?>" class="btn btn-primary">Modifier</a>  
                        </td>  
                    </tr>  
                <?php endforeach; ?>  
            </tbody>  
        </table>  
    </div>  
</body>  
</html>  