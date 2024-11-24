Table des Matières
Pré-requis
Endpoints Disponibles
Format des Requêtes
Endpoints
1. Enregistrement d'un Utilisateur
2. Connexion d'un Utilisateur
3. Deconnexion
Pré-requis
Un client HTTP comme Postman, Insomnia, ou cURL.
L’API utilise JSON pour les échanges de données. Assurez-vous d’envoyer les requêtes avec l’en-tête :
http
Content-Type: application/json
Endpoints Disponibles
Méthode	Endpoint	Description
POST	/api/user/register	Permet de créer un nouvel utilisateur.
POST	/api/user/login	Permet de connecter un utilisateur.
POST	/api/user/logout	Permet de déconnecter un utilisateur.
Format des Requêtes
Toutes les requêtes doivent être envoyées avec les en-têtes suivants :
http
Content-Type: application/json
Accept: application/json
Endpoints
1. Enregistrement d'un Utilisateur
Description
Permet de créer un utilisateur avec un rôle (locataire ou proprietaire).

URL
POST /api/user/register

Paramètres du Corps
json
{
  "nom": "Dupont",
  "prenom": "Jean",
  "tel": "0123456789",
  "role": "proprietaire",
  "email": "jean.dupont@example.com",
  "password": "motdepasse123",
  "password_confirmation": "motdepasse123"
}
Réponses
Succès (201 Created) :
json
Copier le code
{
  "message": "Utilisateur enregistré avec succès",
  "user": {
    "id": 1,
    "nom": "Dupont",
    "prenom": "Jean",
    "tel": "0123456789",
    "role": "proprietaire",
    "email": "jean.dupont@example.com"
  }
}
Échec (422 Validation Error) :
json
{
  "success": false,
  "status_code": 422,
  "error": true,
  "message": "Erreur de validation",
  "errorList": {
    "email": ["Cette adresse email existe déjà."],
    "password": ["Le mot de passe doit comporter au moins 8 caractères."]
  }
}
2. Connexion d'un Utilisateur
Description
Permet de connecter un utilisateur en utilisant son nom, email, ou numéro de téléphone comme identifiant.

URL
POST /api/user/login

Paramètres du Corps
json
{
  "identifiant": "jean.dupont@example.com",
  "password": "motdepasse123"
}
Réponses
Succès (200 OK) :
json
Copier le code
{
  "success": true,
  "message": "Connexion réussie.",
  "user": {
    "id": 1,
    "nom": "Dupont",
    "prenom": "Jean",
    "tel": "0123456789",
    "role": "proprietaire",
    "email": "jean.dupont@example.com"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
Échec (401 Unauthorized) :
json
{
  "success": false,
  "message": "Identifiant ou mot de passe incorrect."
}
3. Déconnexion d'un Utilisateur
Description
Permet de révoquer le token d'accès actuel ou tous les tokens associés à un utilisateur.

URL
POST /api/user/logout

En-têtes Requis
Authorization : Inclure le token dans l'en-tête de la requête.
makefile
Copier le code
Authorization: Bearer <token>
Réponses
Succès (200 OK) :
{
  "success": true,
  "message": "Déconnecté avec succès."
}
documentation_api:domain./docs/api
example:http://127.0.0.1:8000/docs/api/
