<?php
require_once 'config/db.php';
include 'includes/header.php';

$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$book = null;

if ($book_id > 0) {

    $sql = "SELECT id, titre, auteur, description, maison_edition, nombre_exemplaire 
            FROM livres 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $book_id]);
    $book = $stmt->fetch();
}

if (!$book) {
    echo "<div style='margin-top:20px;'>
            <p>Livre introuvable ou spécifié de manière incorrecte.</p>
            <a href='index.php'>Retourner à l'accueil</a>
          </div>";
    include 'includes/footer.php';
    exit;
}
?>

<section class="book-details" style="margin-top: 20px;">
    <h1><?php echo htmlspecialchars($book['titre']); ?></h1>
    <p><strong>Auteur :</strong> <?php echo htmlspecialchars($book['auteur']); ?></p>
    <p><strong>Maison d'édition :</strong> <?php echo htmlspecialchars($book['maison_edition']); ?></p>
    <p><strong>Exemplaires disponibles :</strong> <?php echo htmlspecialchars($book['nombre_exemplaire']); ?></p>
    
    <div class="description-box" style="background: #f9f9f9; padding: 15px; border-left: 4px solid #333; margin: 20px 0;">
        <h3>Description :</h3>
        <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
    </div>

    <form action="wishlist.php" method="POST" style="display: inline-block;">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
        <button type="submit" style="background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer;">
             Ajouter à ma liste de lecture
        </button>
    </form>

    <div style="margin-top: 20px;">
        <a href="index.php" style="color: #555;">← Faire une nouvelle recherche</a>
    </div>
</section>

<?php
include 'includes/footer.php';
?>