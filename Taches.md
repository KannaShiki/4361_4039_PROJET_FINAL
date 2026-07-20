# Taches

## Informations du projet
- Titre : Systeme d operateur Mobile Money
- Langage : PHP avec CodeIgniter 4
- Base de donnees : SQLite embarque
- Frontend : HTML, CSS, JS, Bootstrap ou equivalent
- URL Git : https://github.com/KannaShiki/4361_4039_PROJET_FINAL.git
- Binome : Miangola / Sandria

## Livraison v1

### Base de donnees (base.sql) [Miangola] OK

- Creer la table `operator_prefixes` pour les prefixes autorises (ex. : `033`, `037`).
- Creer la table `operation_types` pour les types d operations (`depot`, `retrait`, `transfert`).
- Creer la table `fee_brackets` pour les baremes de frais par tranche de montant.
- Creer la table `clients` pour stocker les clients avec `phone_number` et `balance`.
- Creer la table `transactions` pour l historique complet des operations.
- Inserer les donnees initiales pour les prefixes, les types d operations et des exemples de frais.

### Cote operateur (Admin) [Sandria] OK

- Implementer le CRUD des prefixes operateurs (ajout / suppression).
- Implementer le CRUD des types d operations.
- Implementer le CRUD des baremes de frais (tranches modifiables) - tres important.
- Ajouter une page de vue globale "Situation des comptes clients" avec la liste des clients et leurs soldes.

### Cote client [Miangola]OK

- Creer une page d accueil permettant un login automatique avec le numero de telephone.
- Pas d inscription au prealable.
- Verifier que le prefixe du numero est valide.
- Creer automatiquement le compte client s il n existe pas.

#### Dashboard client [Miamgola] OK

- Voir le solde actuel.
- Faire un depot (simulation automatique).
- Faire un retrait (verification du solde + application des frais).
- Faire un transfert vers un autre numero (application des frais).
- Voir l historique des operations.

### Fonctionnalites transversales [Sandria] OK

- Calcul automatique des frais selon les baremes pour les retraits et les transferts.
- Mise a jour du solde apres chaque operation.
- Enregistrement de toutes les operations dans `transactions`.
- Gestion des erreurs : solde insuffisant, numero invalide, etc.
- Interface responsive avec Bootstrap.

## Livraison v2

### Base de donnees (base.sql) [Sandria]

- Creer la table `other_operator_prefixes` pour les prefixes d autres operateurs (ex. : `032`, `031`).
- Modifier la table `fee_brackets` : ajouter champ `is_other_operator` (booleen) pour differencier les frais inter-operateurs.
- Creer la table `operator_commissions` pour les commissions supplementaires par operateur.
- Modifier la table `transactions` : ajouter champs `include_withdrawal_fee`, `is_multi_send`, `operator_id`.
- Creer la table `multi_send_recipients` pour gerer les destinataires multiples d un envoi.
- Inserer les donnees initiales pour les prefixes d autres operateurs et les commissions.

### Cote operateur (Admin) [Miangola]

- Implementer le CRUD des prefixes d autres operateurs (ajout / suppression).
- Implementer le CRUD des commissions inter-operateurs (configurer les frais supplementaires).
- Modifier la page "Situation des comptes clients" pour inclure des filtres par operateur.
- Creer une page "Situation gain via les differents frais" avec separation gains propres vs autres operateurs.
- Creer une page "Situation des montants a envoyer" montrant les sommes a transferer a chaque operateur.
- Ajouter des rapports financiers detailles par operateur et par type d operation.

### Cote client [Sandria]

- Ajouter une option dans le formulaire de transfert pour inclure les frais de retrait dans le montant envoye.
- Modifier le formulaire de transfert pour permettre l envoi vers plusieurs numeros (multi-envoi).
- Implementer la logique de division automatique du montant entre plusieurs destinataires.
- Ajouter la validation pour verifier que le montant est divisible par le nombre de destinataires.
- Modifier l historique des operations pour afficher les details des multi-envois.
- Afficher clairement dans le dashboard les frais appliques et les commissions inter-operateurs.

### Fonctionnalites transversales [Miangola]

- Implementer l algorithme de calcul des commissions inter-operateurs.
- Implementer la logique de separation des gains (operateur propre vs autres operateurs).
- Calculer automatiquement les montants a envoyer a chaque operateur selon les transactions effectuees.
- Gerer les transactions multi-envois de maniere atomique (tout ou rien).
- Mettre a jour les rapports financiers en temps reel.
- Ameliorer la gestion des erreurs pour les scenarios multi-envois et inter-operateurs.
