<?php
require_once 'config/db.php';
include 'includes/header.php';
?>
<section class="welcome-section">
    <h1>Bienvenue sur votre Bibliothèque DAISSALA en ligne.</h1>
    <p>Recherchez, consultez et gérez votre collection de livres en quelques clics.</p>
</section>
<section class="search-section">
    <h2>Trouvez un livre</h2>
    <form action="results.php" method="GET" class="search-form">
        <div class="form-group">
            <input type="text" name="query" id="query" placeholder="Entrez un titre ou un auteur..." required>
        </div>
        <button type="submit" class="btn-search">Rechercher</button>
    </form>
</section>
<?php
include 'includes/footer.php';
?>