# Gestion des Utilisateurs avec l'Architecture MVC, PHP et MySQL

## Description
Cette application web sécurisée est destinée à centraliser la gestion des utilisateurs d'une entreprise. Conçue en utilisant l'architecture MVC avec PHP et MySQL, elle permet une gestion fluide et sécurisée des comptes des utilisateurs, en offrant des fonctionnalités adaptées aux administrateurs et aux clients.

---

## Fonctionnalités

### **1. Authentification et Sécurité**
- Système d'inscription et de connexion sécurisé (hashage des mots de passe avec bcrypt).
- Gestion des sessions avec restrictions d'accès basées sur les rôles.
- Validation des entrées et protection contre XSS, CSRF, et injections SQL.

### **2. Gestion des Profils**
#### **Administrateurs**
- Tableau de bord avec aperçu des utilisateurs.
- Création, modification, suppression et activation/désactivation des comptes.
- Consultation des logs de connexion.

#### **Clients**
- Inscription et connexion.
- Gestion et modification de leur profil personnel.
- Consultation de l'historique des connexions.

---

## Architecture du Projet
Le projet suit une structure MVC (Model-View-Controller) :
- **Controllers** : Gèrent la logique métier.
- **Models** : Manipulent les données et interagissent avec la base de données.
- **Views** : Présentent les données à l'utilisateur.

Répertoires principaux :
```
/app
  ├── controllers/      # Contrôleurs
  ├── models/           # Modèles
  └── views/            # Vues
/public
  ├── css/              # Styles CSS
  ├── js/               # Scripts JS
  ├── index.php         # Point d'entrée
  └── .htaccess         # Configuration serveur
/config                 # Fichiers de configuration
/tests                  # Tests unitaires
/logs                   # Logs de l'application
```

---

## Base de Données
La base de données comprend trois tables principales :
1. **roles** : Gère les rôles (administrateurs, clients).
2. **users** : Stocke les informations des utilisateurs (username, email, etc.).
3. **sessions** : Historique des connexions des utilisateurs.

### Relations entre les tables
- Un utilisateur a un rôle (users.role_id → roles.id).
- Un utilisateur peut avoir plusieurs connexions (sessions.user_id → users.id).

---

## Installation
1. Clonez le dépôt :
   ```bash
   git clone <https://github.com/Theprowess-Darly/Brief3.git>
   ```
2. Configurez la base de données dans `/config/config.php`.
3. Assurez-vous que le fichier `.htaccess` est correctement configuré.
4. Lancez le projet via un serveur local (Apache).

---

## Contribution
1. Forkez le projet.
2. Créez une branche feature :
   ```bash
   git checkout -b feature/nom-de-la-feature
   ```
3. Effectuez un pull request.

---

## Auteur
Développé par 
[DARLY TCHATCHOUANG - TheProwess] - Développeur Web Full Stack

