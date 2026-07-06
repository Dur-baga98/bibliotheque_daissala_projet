<?php
require_once 'config/db.php';
include 'includes/header.php';

$message = '';
$message_type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;

        if ($book_id > 0) {
            $stmt = $pdo->prepare('DELETE FROM livres WHERE id = :id');
            $stmt->execute(['id' => $book_id]);
            $message = 'Le livre a été supprimé avec succès.';
        } else {
            $message = 'Identifiant de livre invalide.';
            $message_type = 'error';
        }
    } elseif ($action === 'save') {
        $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
        $titre = trim($_POST['titre'] ?? '');
        $auteur = trim($_POST['auteur'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $maison_edition = trim($_POST['maison_edition'] ?? '');
        $nombre_exemplaire = isset($_POST['nombre_exemplaire']) ? max(0, (int)$_POST['nombre_exemplaire']) : 0;

        if ($titre === '' || $auteur === '') {
            $message = 'Le titre et l’auteur sont obligatoires.';
            $message_type = 'error';
        } else {
            if ($book_id > 0) {
                $stmt = $pdo->prepare('UPDATE livres SET titre = :titre, auteur = :auteur, description = :description, maison_edition = :maison_edition, nombre_exemplaire = :nombre_exemplaire WHERE id = :id');
                $stmt->execute([
                    'titre' => $titre,
                    'auteur' => $auteur,
                    'description' => $description,
                    'maison_edition' => $maison_edition,
                    'nombre_exemplaire' => $nombre_exemplaire,
                    'id' => $book_id
                ]);
                $message = 'Le livre a été modifié avec succès.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO livres (titre, auteur, description, maison_edition, nombre_exemplaire) VALUES (:titre, :auteur, :description, :maison_edition, :nombre_exemplaire)');
                $stmt->execute([
                    'titre' => $titre,
                    'auteur' => $auteur,
                    'description' => $description,
                    'maison_edition' => $maison_edition,
                    'nombre_exemplaire' => $nombre_exemplaire
                ]);
                $message = 'Le livre a été ajouté avec succès.';
            }
        }
    }
}

$edit_book = null;
$edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
if ($edit_id > 0) {
    $stmt = $pdo->prepare('SELECT id, titre, auteur, description, maison_edition, nombre_exemplaire FROM livres WHERE id = :id');
    $stmt->execute(['id' => $edit_id]);
    $edit_book = $stmt->fetch();
}

$stmt = $pdo->query('SELECT id, titre, auteur, maison_edition, nombre_exemplaire FROM livres ORDER BY titre ASC');
$books = $stmt->fetchAll();
?>

<section class="management-section">
    <h1>Gestion de la collection</h1>
    <p>Ajoutez, modifiez ou supprimez des livres depuis cette page.</p>

    <?php if ($message): ?>
        <div class="message js-auto-hide <?php echo $message_type === 'error' ? 'error-message' : ''; ?>" style="margin-bottom: 20px;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div style="margin-bottom: 1rem;">
        <label for="book-filter">Filtrer les livres</label>
        <input type="text" id="book-filter" class="js-book-filter" placeholder="Rechercher un titre ou un auteur...">
    </div>

    <form action="gestion_livres.php" method="POST" class="search-form" style="margin-bottom: 2rem;">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="book_id" value="<?php echo $edit_book ? (int)$edit_book['id'] : ''; ?>">

        <div class="form-grid" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
            <div>
                <label for="titre">Titre</label>
                <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($edit_book['titre'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="auteur">Auteur</label>
                <input type="text" id="auteur" name="auteur" value="<?php echo htmlspecialchars($edit_book['auteur'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="maison_edition">Maison d’édition</label>
                <input type="text" id="maison_edition" name="maison_edition" value="<?php echo htmlspecialchars($edit_book['maison_edition'] ?? ''); ?>">
            </div>
            <div>
                <label for="nombre_exemplaire">Nombre d’exemplaires</label>
                <input type="number" id="nombre_exemplaire" name="nombre_exemplaire" min="0" value="<?php echo htmlspecialchars((string)($edit_book['nombre_exemplaire'] ?? 0)); ?>">
            </div>
        </div>

        <div style="margin-top: 1rem;">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" style="width: 100%; padding: 1rem; border-radius: 14px; border: 1px solid #d1d5db;"><?php echo htmlspecialchars($edit_book['description'] ?? ''); ?></textarea>
        </div>

        <div style="margin-top: 1rem; display: flex; gap: 1rem; flex-wrap: wrap;">
            <button type="submit" class="btn-search">
                <?php echo $edit_book ? 'Enregistrer les modifications' : 'Ajouter le livre'; ?>
            </button>
            <?php if ($edit_book): ?>
                <a href="gestion_livres.php" class="btn-back">Annuler</a>
            <?php endif; ?>
        </div>
    </form>

    <h2>Livres présents dans la collection</h2>
    <?php if (!empty($books)): ?>
        <div class="book-grid js-book-list">
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <h3><?php echo htmlspecialchars($book['titre']); ?></h3>
                    <p><strong>Auteur :</strong> <?php echo htmlspecialchars($book['auteur']); ?></p>
                    <p><strong>Maison d’édition :</strong> <?php echo htmlspecialchars($book['maison_edition']); ?></p>
                    <p><strong>Exemplaires :</strong> <?php echo htmlspecialchars((string)$book['nombre_exemplaire']); ?></p>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1rem;">
                        <a href="gestion_livres.php?edit=<?php echo (int)$book['id']; ?>" class="btn-details">Modifier</a>
                        <form action="gestion_livres.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="book_id" value="<?php echo (int)$book['id']; ?>">
                            <button type="submit" class="btn-back" data-confirm-delete="true">Supprimer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucun livre enregistré pour le moment.</p>
    <?php endif; ?>
    <p class="js-empty-state" style="display:none;">Aucun livre ne correspond à votre recherche.</p>
</section>

<?php include 'includes/footer.php'; ?>
