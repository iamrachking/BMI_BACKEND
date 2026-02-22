<?php

namespace App\Helpers;

class HelpFaq
{
    /**
     * Retourne les questions/réponses d'aide selon le rôle.
     *
     * @param string $roleName admin|gestionnaire|technicien
     * @return array<int, array{question: string, answer: string}>
     */
    public static function getByRole(string $roleName): array
    {
        return match ($roleName) {
            'admin' => self::adminFaqs(),
            'gestionnaire' => self::gestionnaireFaqs(),
            'technicien' => self::technicienFaqs(),
            default => self::defaultFaqs(),
        };
    }

    protected static function adminFaqs(): array
    {
        return [
            [
                'question' => 'Comment gérer les utilisateurs de la plateforme ?',
                'answer' => 'Allez dans **Utilisateurs** (sidebar). Vous pouvez consulter la liste, créer un nouvel utilisateur (gestionnaire ou technicien), modifier son nom, email, rôle et téléphone. Lors de la création, vous pouvez envoyer un email d\'invitation pour que la personne définisse son mot de passe, ou noter le mot de passe temporaire généré.',
            ],
            [
                'question' => 'Comment ajouter un équipement ?',
                'answer' => 'Allez dans **Équipements** puis cliquez sur **Ajouter un équipement**. Renseignez le nom, la référence, le statut, la marque, le modèle, la date d\'installation, l\'emplacement et une description si besoin. Les équipements sont la base pour planifier les maintenances et enregistrer les pannes.',
            ],
            [
                'question' => 'Comment planifier une maintenance ?',
                'answer' => 'Allez dans **Maintenances** puis **Nouvelle maintenance**. Choisissez l\'équipement, le technicien assigné, le type (préventive, corrective, etc.), la date prévue et le statut. Vous pouvez ajouter une description. Les maintenances en cours apparaissent sur le tableau de bord.',
            ],
            [
                'question' => 'Comment suivre les pannes ?',
                'answer' => 'Allez dans **Historique des pannes**. Vous voyez toutes les pannes avec équipement, gravité, statut et technicien assigné. En cliquant sur une panne, vous pouvez modifier l\'assignation ou le statut. Les pannes assignées déclenchent un email au technicien.',
            ],
            [
                'question' => 'Quelle est la différence entre les rôles ?',
                'answer' => '**Admin** : accès complet (dashboard, équipements, maintenances, pannes, gestion des utilisateurs). **Gestionnaire** : comme l\'admin mais sans la gestion des utilisateurs. **Technicien** : accès aux équipements, maintenances et pannes ; il voit notamment « Mes pannes assignées » sur le dashboard et peut mettre à jour les pannes qui lui sont assignées.',
            ],
        ];
    }

    protected static function gestionnaireFaqs(): array
    {
        return [
            [
                'question' => 'Comment ajouter un équipement ?',
                'answer' => 'Allez dans **Équipements** puis **Ajouter un équipement**. Renseignez le nom, la référence, le statut, la marque, le modèle, la date d\'installation, l\'emplacement et une description. Les équipements servent ensuite pour les maintenances et les pannes.',
            ],
            [
                'question' => 'Comment planifier une maintenance ?',
                'answer' => 'Allez dans **Maintenances** puis **Nouvelle maintenance**. Sélectionnez l\'équipement, le technicien à assigner, le type de maintenance, la date prévue et le statut. Une description optionnelle peut être ajoutée.',
            ],
            [
                'question' => 'Comment déclarer une panne ?',
                'answer' => 'Allez dans **Historique des pannes** puis **Déclarer une panne**. Choisissez l\'équipement, la date de détection, la gravité et optionnellement le technicien à assigner. Une fois enregistrée, le technicien reçoit un email s\'il est assigné.',
            ],
            [
                'question' => 'Comment modifier ou assigner une panne ?',
                'answer' => 'Dans **Historique des pannes**, cliquez sur une panne pour voir le détail. Vous pouvez modifier le technicien assigné et enregistrer. Le technicien assigné sera notifié par email.',
            ],
            [
                'question' => 'Où voir l’état des maintenances et pannes ?',
                'answer' => 'Le **Tableau de bord** affiche un résumé : prochaines maintenances, maintenances en cours, pannes récentes. Les listes complètes sont dans **Maintenances** et **Historique des pannes**.',
            ],
        ];
    }

    protected static function technicienFaqs(): array
    {
        return [
            [
                'question' => 'Où voir mes pannes assignées ?',
                'answer' => 'Sur le **Tableau de bord**, la section « Mes pannes assignées » liste les pannes qui vous sont attribuées. Vous pouvez aussi aller dans **Historique des pannes** et repérer celles où vous êtes assigné.',
            ],
            [
                'question' => 'Comment mettre à jour une panne qui m’est assignée ?',
                'answer' => 'Cliquez sur la panne dans **Historique des pannes** (ou depuis le dashboard). Sur la fiche panne, vous pouvez modifier le technicien assigné ou d’autres champs puis cliquer sur **Enregistrer**.',
            ],
            [
                'question' => 'Comment consulter les équipements ?',
                'answer' => 'Allez dans **Équipements**. Vous pouvez voir la liste et cliquer sur un équipement pour voir son détail, ses maintenances et les pannes associées. Cela vous aide à préparer vos interventions.',
            ],
            [
                'question' => 'Comment voir mes maintenances ?',
                'answer' => 'Le **Tableau de bord** affiche les maintenances à venir et en cours. La liste complète est dans **Maintenances** ; vous pouvez filtrer ou repérer celles où vous êtes assigné.',
            ],
            [
                'question' => 'Comment déclarer une nouvelle panne ?',
                'answer' => 'Allez dans **Historique des pannes** puis **Déclarer une panne**. Renseignez l’équipement, la date de détection, la gravité et une description. Vous pouvez vous assigner vous‑même ou laisser le gestionnaire/admin le faire.',
            ],
        ];
    }

    protected static function defaultFaqs(): array
    {
        return [
            [
                'question' => 'Comment utiliser le tableau de bord ?',
                'answer' => 'Le tableau de bord affiche un résumé de l’activité : maintenances à venir ou en cours, pannes récentes. Utilisez les liens pour accéder aux listes détaillées (équipements, maintenances, pannes).',
            ],
            [
                'question' => 'Où modifier mon profil ?',
                'answer' => 'Cliquez sur votre nom en haut à droite puis **Profil**, ou utilisez **Profil** dans la barre latérale. Vous pouvez modifier votre nom et prénom, votre photo et votre mot de passe.',
            ],
        ];
    }
}
