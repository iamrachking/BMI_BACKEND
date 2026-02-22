# Module Admin (Administration E-commerce)

Espace d’administration pour la boutique (catégories, produits, commandes).  
**Accès :** administrateurs et gestionnaires (middleware `admin.ecommerce`).  
**Layout :** même dashboard que le module Gestion (`gestion.layouts.dashboard`).

## Contrôleurs

| Contrôleur | Rôle |
|------------|------|
| `AdminDashboardController` | Tableau de bord (stats, commandes récentes) |
| `CategoryAdminController` | CRUD catégories (sauf show) |
| `ProductAdminController` | CRUD produits complet |
| `OrderAdminController` | Liste commandes, détail, mise à jour du statut |

## Routes (préfixe `/admin`)

- `GET /admin` → tableau de bord
- `GET/POST /admin/categories` → index, create, store
- `GET/PATCH/DELETE /admin/categories/{category}` → edit, update, destroy
- `GET/POST /admin/products` → index, create, store
- `GET/PATCH/DELETE /admin/products/{product}` → show, edit, update, destroy
- `GET /admin/orders` → index
- `GET /admin/orders/{order}` → show
- `PATCH /admin/orders/{order}/status` → updateStatus

## Vues

Toutes sous `resources/views/admin/` : `dashboard`, `categories`, `products`, `orders`.
