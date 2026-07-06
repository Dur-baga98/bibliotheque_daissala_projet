
CREATE TABLE IF NOT EXISTS `livres` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `titre` VARCHAR(100) NOT NULL,
    `auteur` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `maison_edition` VARCHAR(100),
    `nombre_exemplaire` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `lecteurs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `prenom` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `liste_lecture` (
    `id_livre` INT,
    `id_lecteur` INT,
    `date_emprunt` DATE NOT NULL,
    `date_retour` DATE,
    PRIMARY KEY (`id_livre`, `id_lecteur`),
    FOREIGN KEY (`id_livre`) REFERENCES `livres`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_lecteur`) REFERENCES `lecteurs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `livres` (`titre`, `auteur`, `description`, `maison_edition`, `nombre_exemplaire`) VALUES
('Republique a vendre', 'Issac Tedambe', 'Un roman philosophique sur la gournance chaotique au Tchad', 'Harmattan', 5),
('Une si longue lettre', 'Mariama Bâ', 'Une œuvre majeure sur la condition des femmes en Afrique.', 'Le Serpent à Plumes', 3);

INSERT INTO `lecteurs` (`nom`, `prenom`, `email`) VALUES
('daissala', 'dourbaga', 'dourbaga.daissala@email.com');