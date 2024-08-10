# Devoir-symfonyStubborn 
**Description**
Stubborn est une plateforme moderne de commerce électronique construite avec Symfony. Ce projet permet aux utilisateurs de parcourir les produits, de les ajouter à un panier et de procéder au paiement via Stripe. La plateforme inclut également une section back-office pour que les administrateurs puissent gérer les produits et les stocks.


**Fonctionnalités :**

Authentification des utilisateurs : Les utilisateurs peuvent s'inscrire, se connecter et gérer leur profil.
Gestion des produits : Les administrateurs peuvent ajouter, modifier et supprimer des produits.
Panier d'achat : Les utilisateurs peuvent ajouter des produits à leur panier, gérer les quantités et procéder au paiement.
Intégration Stripe : Traitement des paiements sécurisé via Stripe.
Design réactif : Le site est réactif et fonctionne sur tous les appareils.
Gestion des stocks : La plateforme gère les niveaux de stock des produits, empêchant les achats lorsque le stock est insuffisant.


**Technologies utilisées :**

Symfony : Framework PHP pour applications web
Doctrine : ORM pour la gestion de la base de données
Twig : Moteur de templates
Stripe : Passerelle de paiement
MySQL : Base de données


**Installation:**

**Prérequis:**

PHP 8.0 ou supérieur
Composer
MySQL
Node.js et npm (pour les assets frontend)
Symfony CLI (optionnel, mais recommandé)


**Étapes:**

**1.Cloner le dépôt:**

git clone https://github.com/Aliiixxx/Devoir-symfony.git
cd Devoir-symfony


**2.Installer les dépendances:**

composer install
npm install


**3.Configurer les variables d'environnement:**

Copiez le fichier .env et ajustez les paramètres nécessaires, notamment les informations de base de données et les clés Stripe.


Configurez vos identifiants de base de données dans .env.local :

DATABASE_URL="mysql://root:password@127.0.0.1:3306/stubborn_db"


Configurez vos clés Stripe dans .env.local :


STRIPE_SECRET_KEY=votre_clé_stripe_secrète
STRIPE_PUBLIC_KEY=votre_clé_stripe_publique


**4.Configurer la base de données:**

Créez la base de données et exécutez les migrations :

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate


**5.Lancer le serveur Symfony:**

symfony server:start


Utilisation:

Rendez-vous sur http://localhost:8000 pour voir le site.
Accédez à http://localhost:8000/admin/back-office pour gérer les produits (admin uniquement).
