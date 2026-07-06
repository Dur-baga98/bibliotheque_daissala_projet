<?php 
require_once 'config/db.php';
include 'includes/header.php';
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$books = [];
if (!empty($search_query)) {
    $sql = "SELECT id, titre, auteur, maison_edition
    FROM livres
    WHERE titre LIKE :queryTitre OR auteur LIKE :queryAuteur";
    $stmt = $pdo->prepare($sql);
    $search_param = "%" . $search_query . "%";
    $stmt->execute([
        'queryTitre' => $search_param,
        'queryAuteur' => $search_param
    ]);
    $books = $stmt->fetchAll();
}
?>
<section class="results-section">
    <h2>Résultats de la recherche pour :</h2>
    <div class="search-meta">Vous avez recherché : "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</div>

    <?php if (!empty($books)): ?>
        <p class="success-count"><?php echo count($books); ?> livre(s) trouvé(s).</p>
        <div class="book-grid">
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <h3><?php echo htmlspecialchars($book['titre']); ?></h3>
                    <p class="Author">Par: <?php echo htmlspecialchars($book['auteur']); ?></p>
                    <p class="édition">Maison d'édition: <?php echo htmlspecialchars($book['maison_edition']); ?></p>
                    <a href="details.php?id=<?php echo $book['id']; ?>" class="btn-details">Voir les détails</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-results">
            <p>Aucun livre trouvé pour votre recherche.</p>
            <a href="index.php" class="btn-back">Faire une nouvelle recherche</a>
        </div>
    <?php endif; ?>
</section>
<?php include 'includes/footer.php'; ?>