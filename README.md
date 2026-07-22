# Site Freelance - Projet complet (Frontend + Backend)

## Structure du projet

```
siteFreelance/
├── index.html              Page publique (accueil, services, contact)
├── admin.html               Espace administrateur
├── test_contact.json        Fichier utilitaire pour tester contact.php via curl
├── assets/
│   ├── css/
│   │   └── style.css        Mise en forme (site public + espace admin)
│   ├── js/
│   │   ├── script.js        Logique JS de index.html (relie F1, F2, F3)
│   │   └── admin.js         Logique JS de admin.html (relie F2/F4)
│   └── images/
│       ├── hero.jpeg        Fond de la section d'accueil
│       ├── hero2.jpeg       Fond de la section contact
│       └── hero3.jpeg       Fond du pied de page
├── backend/
│   ├── config.php            Connexion MySQL (utilisee par tous)
│   ├── auth.php               Verifie qu'un admin est connecte
│   ├── accueil.php            F1 - Page d'accueil (donnees fixes)
│   ├── contact.php            F3 - Formulaire de contact -> table messages
│   ├── services.php           F2 (consulter) + F4 (gerer) -> table services
│   ├── login.php               F4 - Connexion admin -> table administrateur
│   ├── logout.php              F4 - Deconnexion admin
│   ├── messages_admin.php      F4 - Consultation des messages -> table messages
│   └── creer_admin.php         Utilitaire : cree le 1er compte admin
└── database/
    └── database.sql            Script de creation de la BDD (3 tables)
```

Architecture "monolithe modulaire" (conforme au cahier des charges) :
un seul serveur PHP sert TOUT le dossier `siteFreelance/`, mais chaque
partie est rangee dans son propre sous-dossier (frontend a la racine
et dans `assets/`, backend dans `backend/`, BDD dans `database/`).

Le frontend appelle donc le backend avec le chemin `backend/...`
(ex: `fetch('backend/services.php')`), car `index.html` et `admin.html`
sont a la racine du serveur alors que les fichiers PHP sont dans
`backend/`.

## Demarrage (Ubuntu, terminal)

```bash
cd ~/Bureau/siteFreelance

# 1. Importer la base de donnees (si pas deja fait)
mysql -u freelance_user -p < database/database.sql

# 2. Verifier les identifiants dans backend/config.php
#    ($utilisateur, $mot_de_passe)

# 3. Creer le compte admin (une seule fois, si pas deja fait)
php backend/creer_admin.php

# 4. Lancer le serveur PHP local DEPUIS LA RACINE du projet
php -S localhost:8000
```

⚠️ Important : lance `php -S localhost:8000` depuis le dossier racine
`siteFreelance/` (pas depuis `backend/`), sinon `index.html` et
`admin.html` ne seront pas accessibles.

## Tests via le NAVIGATEUR

1. Ouvre `http://localhost:8000/index.html`
   - Le titre du haut de page et les coordonnees doivent s'afficher (F1)
   - Les 4 cartes de services doivent s'afficher automatiquement (F2)
   - Remplis le formulaire de contact et envoie-le (F3) → message vert
     de confirmation
2. Ouvre `http://localhost:8000/admin.html`
   - Connecte-toi avec `admin@freelance-exemple.com` / `admin123`
     (ou le compte que tu as cree avec creer_admin.php)
   - Ajoute / modifie / supprime un service (F2/F4)
   - Consulte la liste des messages recus en bas de page (F4)
   - Le bouton "Deconnexion" doit ramener a l'ecran de connexion

## Verifier directement en base (optionnel)

```bash
mysql -u freelance_user -p site_freelance -e "SELECT * FROM messages; SELECT * FROM services; SELECT * FROM administrateur;"
```

## En cas de probleme

- Images non affichees (fonds sombres du hero/contact/footer absents)
  → verifie que le dossier `assets/images/` contient bien
    `hero.jpeg`, `hero2.jpeg`, `hero3.jpeg`
- Erreur JSON / rien ne s'affiche (F12 > Console dans le navigateur)
  → verifie que le serveur PHP tourne bien, lance depuis la racine
    du projet (`php -S localhost:8000`)
- "Acces refuse" en boucle sur admin.html apres connexion
  → verifie que les cookies ne sont pas bloques par le navigateur
- Aucun service ne s'affiche
  → verifie que la table "services" contient bien des donnees
