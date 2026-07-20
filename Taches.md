# Taches

# Taches

## Informations du projet
- Titre : Systeme d operateur Mobile Money
- Langage : PHP avec CodeIgniter 4
- Base de donnees : SQLite embarque
- Frontend : HTML, CSS, JS, Bootstrap ou equivalent
- URL Git : https://github.com/KannaShiki/4361_4039_PROJET_FINAL.git
- Binome : Miangola / Sandria

## Livraison v1

### Base de donnees (base.sql)

- Creer la table `operator_prefixes` pour les prefixes autorises (ex. : `033`, `037`).
- Creer la table `operation_types` pour les types d operations (`depot`, `retrait`, `transfert`).
- Creer la table `fee_brackets` pour les baremes de frais par tranche de montant.
- Creer la table `clients` pour stocker les clients avec `phone_number` et `balance`.
- Creer la table `transactions` pour l historique complet des operations.
- Inserer les donnees initiales pour les prefixes, les types d operations et des exemples de frais.

### Cote operateur (Admin)

- Implementer le CRUD des prefixes operateurs (ajout / suppression).
- Implementer le CRUD des types d operations.
- Implementer le CRUD des baremes de frais (tranches modifiables) - tres important.
- Ajouter une page de vue globale "Situation des comptes clients" avec la liste des clients et leurs soldes.

### Cote client

- Creer une page d accueil permettant un login automatique avec le numero de telephone.
- Pas d inscription au prealable.
- Verifier que le prefixe du numero est valide.
- Creer automatiquement le compte client s il n existe pas.

#### Dashboard client

- Voir le solde actuel.
- Faire un depot (simulation automatique).
- Faire un retrait (verification du solde + application des frais).
- Faire un transfert vers un autre numero (application des frais).
- Voir l historique des operations.

### Fonctionnalites transversales

- Calcul automatique des frais selon les baremes pour les retraits et les transferts.
- Mise a jour du solde apres chaque operation.
- Enregistrement de toutes les operations dans `transactions`.
- Gestion des erreurs : solde insuffisant, numero invalide, etc.
- Interface responsive avec Bootstrap.

### Requetes / base.sql

- Le fichier `base.sql` doit contenir un seul script a la racine du projet.
- Ce fichier doit inclure la creation des tables, des vues et des donnees initiales.
- Ce fichier est requis pour repondre au point "base.sql" du sujet.

### Travaux par etudiant

- Etudiant 1 : initialisation du schema SQLite, creation des tables et insertion des donnees initiales.
- Etudiant 2 : definition des types d operations, baremes de frais, et mise en place du suivi des comptes clients.

### Suivi des livraisons

- A chaque livraison, ajouter dans ce fichier les nouveaux travaux effectues par chaque etudiant.
- Bien indiquer les modifications realisees pour v1, v2, v3.

### Livraison

- Mettre a jour `Taches.md` avec les travaux effectues.
- Faire un dernier commit clair.
- Creer le tag `v1` : `git tag v1`.
- Pousser le tag : `git push origin v1`.
- La version finale doit etre sur la branche `main`.
