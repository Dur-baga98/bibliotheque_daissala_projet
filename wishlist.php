<?php
require_once 'config/db.php';
include 'includes/header.php';

$reader_id = 1;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $book_id = isset($_POST['book_id']) ? (int) $_POST['book_id'] : 0;

    if ($book_id > 0) {
        $sql = "SELECT COUNT(*) FROM livres WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $book_id]);

        if ($stmt->fetchColumn() > 0) {
            try {
                $insert = "INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt) VALUES (:book_id, :reader_id, CURDATE())";
                $stmtInsert = $pdo->prepare($insert);
                $stmtInsert->execute([
                    'book_id' => $book_id,
                    'reader_id' => $reader_id
                ]);
                $message = 'Le livre a été ajouté à votre liste de lecture.';
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false || strpos($e->getMessage(), 'PRIMARY') !== false) {
                    $message = 'Ce livre est déjà dans votre liste de lecture.';
                } else {
                    $message = 'Impossible d’ajouter le livre à la liste de lecture.';
                }
            }
        } else {
            $message = 'Livre introuvable.';
        }
    } else {
        $message = 'Identifiant de livre invalide.';
    }
}

$sql = "SELECT ll.id_livre, lv.titre, lv.auteur, lv.maison_edition, lv.nombre_exemplaire, ll.date_emprunt, ll.date_retour
        FROM liste_lecture ll
        JOIN livres lv ON ll.id_livre = lv.id
        WHERE ll.id_lecteur = :reader_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['reader_id' => $reader_id]);
$wishlist = $stmt->fetchAll();
?>
<section class="wishlist-section" style="margin-top: 20px;">
    <h2>Liste de lecture</h2>
    <?php if ($message): ?>
        <div class="message" style="background: #e9f7ef; color: #1a7f37; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($wishlist)): ?>
        <div class="book-grid">
            <?php foreach ($wishlist as $item): ?>
                <div class="book-card">
                    <h3><?php echo htmlspecialchars($item['titre']); ?></h3>
                    <p><strong>Auteur :</strong> <?php echo htmlspecialchars($item['auteur']); ?></p>
                    <p><strong>Maison d'édition :</strong> <?php echo htmlspecialchars($item['maison_edition']); ?></p>
                    <p><strong>Exemplaires disponibles :</strong> <?php echo htmlspecialchars($item['nombre_exemplaire']); ?></p>
                    <p><strong>Date d'emprunt :</strong> <?php echo htmlspecialchars($item['date_emprunt']); ?></p>
                    <p><strong>Date de retour :</strong> <?php echo !empty($item['date_retour']) ? htmlspecialchars($item['date_retour']) : 'Non encore retourné'; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Votre liste de lecture est actuellement vide.</p>
    <?php endif; ?>

    <p style="margin-top: 20px;"><a href="index.php" class="btn-back">Retour à l'accueil</a></p>
</section>
<?php include 'includes/footer.php'; ?>