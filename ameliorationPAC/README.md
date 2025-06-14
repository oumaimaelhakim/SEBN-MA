# SEBN-MA PAC System

Système de suivi des indicateurs clés de performance (KPI) pour les processus de production de SEBN-MA.

## Prérequis

- PHP 8.0 ou supérieur
- MySQL 8.0 ou supérieur
- XAMPP (recommandé)
- Composer (pour les dépendances PHP)

## Installation

1. Clonez le repository dans votre dossier htdocs de XAMPP :
   ```
   cd C:/xampp/htdocs/
   git clone [URL_DU_REPO]
   ```

2. Créez la base de données en important le fichier `database.sql` :
   - Ouvrez phpMyAdmin (http://localhost/phpmyadmin)
   - Créez une nouvelle base de données nommée `pac_system`
   - Importez le fichier `database.sql`

3. Configurez la connexion à la base de données :
   - Ouvrez `config/database.php`
   - Modifiez les paramètres de connexion si nécessaire

4. Accédez à l'application :
   - Ouvrez votre navigateur
   - Accédez à http://localhost/ameliorationPAC

## Identifiants par défaut

- **Administrateur**
  - Username: admin
  - Password: admin123

## Fonctionnalités

- Authentification des utilisateurs
- Tableau de bord avec KPI
- Visualisation des données par processus
- Gestion des utilisateurs (admin)
- Import automatique des données Excel

## Structure du projet

```
ameliorationPAC/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── config/
│   ├── database.php
│   └── config.php
├── includes/
│   ├── auth.php
│   ├── functions.php
│   └── header.php
├── modules/
│   ├── admin/
│   ├── production/
│   └── logistics/
├── data/
│   └── excel_import/
├── api/
└── index.php
```

## Sécurité

- Tous les mots de passe sont hashés avec bcrypt
- Protection contre les injections SQL
- Validation des entrées utilisateur
- Gestion des sessions sécurisée

## Support

Pour toute question ou problème, veuillez contacter l'administrateur système.

## Licence

Ce projet est propriétaire et confidentiel. Tous droits réservés. 