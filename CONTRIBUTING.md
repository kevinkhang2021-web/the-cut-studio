# Règles de contribution

Tout le travail passe par le dépôt officiel et est validé par le **Master**. Aucun travail ne reste isolé sur une machine.

## Branches

- `main` : uniquement des versions validées par le Master. Ne jamais pousser directement dessus.
- `dev/nom-fonctionnalite` : développement du code (ex. `dev/reservation-session`).
- `docs/nom-document` : documents, maquettes, fichiers Excel (ex. `docs/sprint-1`).

## Étapes

1. Mettre à jour son dépôt local : `git pull origin main`
2. Créer sa branche : `git checkout -b dev/nom-fonctionnalite`
3. Travailler, puis valider : `git add .` puis `git commit -m "Message clair"`
4. Envoyer : `git push origin dev/nom-fonctionnalite`
5. Ouvrir une **Pull Request** vers `main` sur GitHub.
6. Le Master relit, demande des corrections si besoin, puis fusionne.

## Messages de commit

Courts et explicites, au présent ou à l'infinitif :
`Ajout de la page catalogue`, `Correction du calcul de durée de session`.

## Rôles

- **Master** : valide et fusionne les Pull Requests, centralise les livrables.
- **Main Dev** : code les fonctionnalités et les fait valider.
- **SysAdmin** : récupère `main`, déploie sur la machine de référence et confirme que ça fonctionne.

## Définition de « Terminé »

- La fonctionnalité marche sur la machine de référence.
- Elle est testée et validée par le Master.
- Elle est fusionnée dans `main`.
- Le fichier Excel du sprint est mis à jour.

## Sécurité

Ne jamais publier de mots de passe, clés d'API ou fichiers `.env`. Ils sont exclus par le `.gitignore`.
