# Images pour les Pages d'Authentification

## 📸 Images Requises

Ce dossier doit contenir les images suivantes pour le bon fonctionnement des pages d'authentification :

### 1. `background.jpg`
- **Description** : Image de fond pour les pages d'authentification
- **Format** : JPG
- **Usage** : Utilisée comme arrière-plan sur toutes les pages d'authentification (login, forgot-password, reset-password)
- **Taille recommandée** : 1920x1080px ou supérieure

### 2. `bmi-logo-removebg.png`
- **Description** : Logo de la plateforme BMI (Bénin Moto Industry)
- **Format** : PNG avec transparence
- **Usage** : Affiché dans la section branding des pages d'authentification
- **Taille recommandée** : Hauteur minimale 200px

## 📋 Instructions

### Copier les Images

1. Copiez `background.jpg` depuis le dossier `assets/` vers `public/images/`
2. Copiez `bmi-logo-removebg.png` depuis le dossier `assets/` vers `public/images/`

### Vérification

Après avoir copié les images, vérifiez que les fichiers existent :
```bash
ls public/images/
```

Vous devriez voir :
- `background.jpg`
- `bmi-logo-removebg.png`

## 🔍 Utilisation dans le Code

Les images sont référencées dans `resources/views/layouts/guest.blade.php` :

```blade
<!-- Background -->
background-image: url('{{ asset('images/background.jpg') }}');

<!-- Logo -->
<img src="{{ asset('images/bmi-logo-removebg.png') }}" alt="BMI Logo">
```

## ⚠️ Important

- Les images doivent être dans `public/images/` pour être accessibles
- Le dossier `public/` est accessible publiquement
- Assurez-vous que les images sont optimisées pour le web (taille raisonnable)
