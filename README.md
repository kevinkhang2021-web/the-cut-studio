# The Cut Studio

Application web de **The Cut Studio**, salle de jeux vidéo située à Kasapa, Lubumbashi (RDC).

L'application prolonge l'activité de la salle en ligne :
- Consultation du catalogue (consoles PS5, jeux PS4) avec conversion automatique en francs congolais (CDF) ;
- Réservation de sessions de jeu par créneaux et par poste (PS5 / PS4) avec gestion anti-conflits ;
- Inscription et participation aux tournois de jeux vidéo avec classement en ligne ;
- Boutique en ligne (commandes avec retrait en salle ou livraison à domicile) ;
- Simulation de paiement mobile money (M-Pesa, Airtel Money, Orange Money) et émission de reçus imprimables ;
- Espace administration pour le gérant (chiffre d'affaires, dépenses, bénéfices, suivi des postes et pannes, clients).

> Projet universitaire – Université Protestante de Lubumbashi (UPL) – Faculté des Sciences informatiques – Année 2025-2026.

---

## Équipe – Groupe UBUNTU 1

| Membre | Rôle |
|---|---|
| Pilato Khang Kevin | **Master** – coordination, centralisation, validation des livrables et du dépôt |
| Wabula Mukenge, Ngoy Kazadi Packy | **Main Dev** – développement de l'application |
| Mapenzi Numbi Deo, Sakisa Mbumbas Blaise | **SysAdmin** – machine de référence, déploiement |
| Mapenzi Numbi Deo | **Design** – maquettes et charte visuelle |

**Encadrement :** Mr Zagabe Yannick (E-commerce) et MSc Héritier Katebula (Modélisation).

---

## Structure du dépôt

```text
The-Cut-Studio/
├── README.md
├── CONTRIBUTING.md
├── docs/
│   ├── backlog/    Backlog produit
│   ├── sprints/    Suivi des sprints
│   ├── roadmap/    Plan de développement
│   ├── rapports/   Rapport de modélisation et catalogue
│   └── design/     Maquettes et ressources graphiques
└── src/            Code source de la plateforme (Laravel)
```

---

## Prérequis

- **PHP** >= 8.2
- **Composer**
- **MySQL** ou MariaDB
- Environnement recommandé sous Windows : **Laragon** (PHP + MySQL + Composer intégrés)

---

## Installation et démarrage rapide

### 1. Cloner le projet
```bash
git clone https://github.com/votre-organisation/The-Cut-Studio.git
cd The-Cut-Studio/src
```

### 2. Installer les dépendances
```bash
composer install
```

### 3. Configurer l'environnement
Copiez le modèle de configuration et générez la clé de sécurité de l'application :

- Sous Windows (CMD / PowerShell) :
  ```cmd
  copy .env.example .env
  ```
- Sous Linux / macOS :
  ```bash
  cp .env.example .env
  ```

Générez la clé d'application :
```bash
php artisan key:generate
```

Vérifiez dans le fichier `.env` que les accès à la base de données correspondent à votre serveur local :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=the_cut_studio
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Initialiser la base de données
1. Créez une base de données MySQL nommée `the_cut_studio` (encodage `utf8mb4`).
2. Exécutez les migrations et chargez le jeu de données initial (produits, postes, tournois, comptes) :
```bash
php artisan migrate:fresh --seed
```

### 5. Lancer l'application
```bash
php artisan serve
```

L'application est accessible à l'adresse : **http://127.0.0.1:8000**  
*(ou via l'hôte virtuel automatique de Laragon si configuré sur `src/public`)*

---

## Comptes de démonstration

| Rôle | Téléphone / Identifiant | Mot de passe |
|---|---|---|
| **Gérant / Administrateur** | `0990000000` | `Admin@2026` |
| **Client** | `0970000000` | `client123` |

L'espace administration est accessible à l'adresse : **http://127.0.0.1:8000/admin**

---

## Contribution

Toutes les modifications doivent respecter le workflow Git décrit dans [CONTRIBUTING.md](CONTRIBUTING.md). Ne travaillez jamais directement sur la branche `main`.