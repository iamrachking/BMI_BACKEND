# AI4BMI - Backend Laravel

Plateforme complète de gestion des équipements industriels et e-commerce pour l'usine **Bénin Moto Industry (BMI)**.


## Stack

- **Backend** : Laravel
- **Base de données** : MySQL
- **Auth** : Laravel Breeze (Blade)
- **Frontend** : Blade (Gestion, Admin)

## Installation

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

7. **Exécuter les seeders**
```bash
php artisan db:seed
```

8. **Compiler les assets**
```bash
npm run build
```

9. **Démarrer le serveur**
```bash
php artisan serve
```

## Membre du groupe

- AMADOU Hik Math
- HOUNGA Nehme
- LAWINGNI Abdoul

