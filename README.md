## project-shopping-online
Projet e-commerce créé avec l'architecture Symfony MVC. 
- 1 Téléchargez ou clonez le dossier
- 2 Tapez les commandes suivantes :
  -  composer install 
  -  php bin/console doctrine:database:create
  -  php bin/console make:migration
  -  php bin/console doctrine:migrations:migrate 
  -  pour protéger l'accès au back-office, décommentez
        #- { path: ^/admin, roles: ROLE_ADMIN } dans le fichier security.yaml
  -  Si l'utilisateur possède un rôle admin peut modifier le rôle d'autre utilisateur

## Sections du site Web

Création de compte, connexion, déconnexion, modification des informations utilisateur (adresse et mot de passe) : 
# User profil : 
- Réinitialisation du mot de passe par e-mail, modification du mot de passe depuis la page du compte. 
- Système de commentaire
- Liste de souhaits. 
- Lien d'administration dans le profil utilisateur si l'utilisateur a un rôle d'administrateur dans la  
  base de données afin qu'il accède au back-office d'administration
- Mes commandes  : possibilité de générer Facture en PDF

# Page produits :
- Filtre par catégorie ou recherche partielle. 
- Trier des produits par nom ou par prix. 
- Fiche produit

# Panier :
- Tunnel d'achat
- Recap de commande
- Validation & Paiement par Stripe.

# Back-office: 
- Easy admin 
- Page de commande Agir sur la commande après un paiement réussi Commande en cours, commande expédiée, commande annulée dans la page  d'administrationPage de commande 

# Mail: 
- Envoi d'e-mails par Mailjet. 

# Contact: 
- Envoyer des e-mails aux administrations

# Carousel: 
- choisir les imgages de carousel de le back-office :mage de fond
- URL du button : categorie/ le nome de la category 
- Titre du button

# FAQ : 
- Page pour les Fraquantly asked question : posibilité de repondre coté adminstration

# Carrousel
- Ajouter des images au carrousel depuis la section Header d'administration