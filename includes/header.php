<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque DAISSALA</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        const userTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = userTheme || (systemPrefersDark ? 'dark' : 'light');
        if (initialTheme === 'dark') {
            document.documentElement.classList.add('dark-theme');
        }

        function updateThemeButton(theme) {
            const button = document.getElementById('theme-toggle');
            if (button) {
                button.textContent = theme === 'dark' ? '☀️' : '🌙';
            }
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark-theme');
            const theme = isDark ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
            updateThemeButton(theme);
        }

        window.addEventListener('DOMContentLoaded', () => {
            updateThemeButton(initialTheme);
            const button = document.getElementById('theme-toggle');
            if (button) {
                button.addEventListener('click', toggleTheme);
            }
        });
    </script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="index.php">Bibliothèque DAISSALA</a>
            </div>
            <div class="nav-actions">
                <ul class="nav-link">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="gestion_livres.php">Gestion des livres</a></li>
                    <li><a href="wishlist.php">Liste de lecture</a></li>
                </ul>
                <button id="theme-toggle" type="button" aria-label="Basculer le thème clair/sombre">🌙</button>
            </div>
        </nav>
    </header>
    <main class="container">
