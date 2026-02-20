# AI4BMI - Backend Laravel

Plateforme complète de gestion des équipements industriels et e-commerce pour l'usine **Bénin Moto Industry (BMI)**.


##  Architecture

### Stack Technique

- **Backend** : Laravel
- **Base de données** : MySQL
- **Authentification** : Laravel Breeze (Blade)
- **API** : REST API (pour l'application mobile)
- **Frontend** : Blade Templates (pour les modules Gestion et Admin)

### Structure du Projet

Le projet est organisé en modules clairs :

```
app/
├── Http/Controllers/
│   ├── Api/          # Controllers API (E-commerce)
│   ├── Gestion/      # Controllers Blade (Gestion équipements)
│   ├── Admin/        # Controllers Blade (Admin produits)
│   └── Auth/         # Controllers Breeze (Authentification)
├── Models/
│   ├── Auth/         # User, Role
│   ├── Gestion/      # Equipment, Maintenance, Failure
│   └── Ecommerce/    # Category, Product, Cart, Order, etc.
└── Services/         # Logique métier réutilisable

resources/views/
├── auth/             # Vues Breeze (login, register, etc.)
├── gestion/          # Vues module Gestion
├── admin/            # Vues module Admin
└── api/              # Documentation API

routes/
│   ├── api.php                   # Routes API
│   ├── web.php                   # Routes Web
│   └── auth.php                  # Routes Auth (Breeze)
```

## 🚀 Installation

### Prérequis

- **PHP** >= 8.2
- **Composer**
- **MySQL** >= 5.7
- **Node.js** >= 18 et **npm**

### Étapes d'Installation

1. **Cloner le projet**
```bash
git clone https://github.com/IFRI-Hackaton-L3-2025-2026/GL-Hack2026-Groupe_5_Backend.git
cd GL-Hack2026-Groupe_5_Backend
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances Node.js**
```bash
npm install
```

4. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurer MySQL dans `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai4bmi
DB_USERNAME=root
DB_PASSWORD=
```

6. **Exécuter les migrations**
```bash
php artisan migrate
```

7. **Exécuter les seeders (création des rôles)**
```bash
php artisan db:seed
```

9. **Compiler les assets**
```bash
npm run build
```

10. **Démarrer le serveur**
```bash
php artisan serve
```

## Membre du groupe

- AMADOU Hik Math
- HOUNGA Nehme
- LAWINGNI Abdoul

