# AI4BMI — Backend Laravel

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![Vite](https://img.shields.io/badge/Vite-646CFF?style=flat-square&logo=vite&logoColor=white)](https://vitejs.dev)

Backend de la plateforme **Bénin Moto Industry (BMI)** : gestion des équipements industriels, maintenance, pannes et **e-commerce** (API pour l’app mobile).

---

## Liens utiles

| | Lien |
|---|------|
| **Backend (prod)** | https://ai4bmi.cabinet-xaviertermeau.com |
| **API (prod)** | https://ai4bmi.cabinet-xaviertermeau.com/api |
| **Documentation API (Swagger)** | https://ai4bmi.cabinet-xaviertermeau.com/api-docs |


## Accès de test (back-office)

Après `php artisan db:seed`, un compte administrateur est créé pour accéder au dashboard :

| | Valeur |
|---|--------|
| **URL connexion** | https://ai4bmi.cabinet-xaviertermeau.com/login |
| **Email** | `abdoulrachard@gmail.com` |
| **Mot de passe** | `password` |

![Connexion](docs/images/login.png)  
*Écran de connexion au back-office.*

![Dashboard admin](docs/images/admin_dash.png)  
*Tableau de bord administrateur.*

![Dashboard gestionnaire](docs/images/gestionnaire_dash.png)  
*Tableau de bord gestionnaire.*

![Dashboard technicien](docs/images/technicien_dash.png)  
*Tableau de bord technicien.*

![Admin e-commerce](docs/images/dashboard_produits.png)  
*Admin e-commerce — produits et commandes.*

---

## Stack

- **Backend** : Laravel
- **Base de données** : MySQL
- **Auth** : Laravel Breeze (Blade) + Sanctum (API mobile)
- **Frontend back-office** : Blade (gestion équipements, maintenances, pannes, admin e-commerce)

---

## Installation locale

**Prérequis** : PHP >= 8.2, Composer, MySQL >= 5.7, Node.js >= 18

```bash
git clone https://github.com/iamrachking/BMI_BACKEND.git
cd BMI-BACKEND
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configurer la base dans `.env` (DB_DATABASE=ai4bmi, etc.), puis :

```bash
php artisan migrate
php artisan db:seed
npm run build
php artisan serve
```

- **Back-office** : http://localhost:8000 (login avec le compte admin ci-dessus)
- **Swagger** : http://localhost:8000/api-docs

---

## API mobile (e-commerce)

L’API utilisée par l’app mobile (auth, catalogue, panier, commandes, paiement FedaPay) est documentée ici :

- **App mobile** : [BMI Mobile](https://github.com/iamrachking/BMI_MOBILE_APP) — dépôt de l’application mobile consommant cette API
- **Swagger** : `/api-docs` (générer avec `composer run swagger` après modification des contrôleurs API)

![App mobile](docs/images/admin_office_app_mobile.png)  
*Application mobile BMI — back-office et interface utilisateur.*


