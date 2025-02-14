# My Istymo WordPress Plugin

Plugin officiel My Istymo intégrant les fonctionnalités DPE et SCI pour WordPress.

## Description

My Istymo est un plugin WordPress qui permet d'intégrer facilement des fonctionnalités de consultation des DPE (Diagnostics de Performance Énergétique) et des SCI (Sociétés Civiles Immobilières) sur votre site web.

## Fonctionnalités

### 1. Module DPE

- Consultation des DPE par code postal
- Filtrage des résultats par mot-clé
- Affichage des informations détaillées :
  - Type de bâtiment
  - Date du DPE
  - Adresse complète
  - Surface habitable
  - Étiquette DPE
  - Géolocalisation Google Maps

### 2. Module SCI

- Consultation des SCI par code postal
- Filtrage des résultats par mot-clé
- Affichage des informations détaillées :
  - Nom et prénom du dirigeant
  - Adresse complète
  - Dénomination
  - Géolocalisation Google Maps

## Installation

1. Téléchargez le plugin
2. Décompressez-le dans le dossier `/wp-content/plugins/`
3. Activez le plugin dans le menu "Extensions" de WordPress

## Prérequis

- WordPress 5.0 ou supérieur
- PHP 7.4 ou supérieur
- Un champ ACF 'code_postal_user' configuré dans le profil utilisateur

## Utilisation

### Configuration des codes postaux

1. Assurez-vous que chaque utilisateur a renseigné son/ses code(s) postal(aux) dans son profil
2. Les codes postaux doivent être séparés par des points-virgules (;)

### Intégration des modules

Utilisez les shortcodes suivants pour intégrer les modules dans vos pages :

- Module DPE : `[my_istymo_dpe]`
- Module SCI : `[my_istymo_sci]`

## Structure du plugin

```
my-istymo/
├── admin/
│   ├── css/
│   ├── js/
│   ├── partials/
│   └── class-my-istymo-admin.php
├── includes/
│   ├── features/
│   │   ├── class-my-istymo-dpe.php
│   │   └── class-my-istymo-sci.php
│   ├── class-my-istymo.php
│   └── class-my-istymo-loader.php
├── public/
│   ├── css/
│   ├── js/
│   └── class-my-istymo-public.php
├── README.md
└── my-istymo.php
```

## Support

Pour toute question ou assistance :
- Site web : https://myistymo.com
- Email : support@myistymo.com

## Changelog

### 1.0.0
- Version initiale du plugin
- Intégration du module DPE
- Intégration du module SCI
- Interface d'administration
- Système de filtrage par code postal
- Système de recherche par mot-clé

## Licence

Ce plugin est sous licence propriétaire. Tous droits réservés © 2025 My Istymo.