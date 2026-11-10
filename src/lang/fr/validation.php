<?php

return [
    'accepted' => 'Le champ :attribute doit être accepté.',
    'array' => 'Le champ :attribute doit être une liste.',
    'between' => [
        'numeric' => 'Le champ :attribute doit être compris entre :min et :max.',
        'string' => 'Le champ :attribute doit contenir entre :min et :max caractères.',
        'array' => 'Le champ :attribute doit contenir entre :min et :max éléments.',
    ],
    'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
    'date' => 'Le champ :attribute n\'est pas une date valide.',
    'after_or_equal' => 'Le champ :attribute doit être une date égale ou postérieure à :date.',
    'email' => 'Le champ :attribute doit être une adresse email valide.',
    'exists' => 'La valeur choisie pour :attribute est invalide.',
    'gt' => [
        'numeric' => 'Le champ :attribute doit être supérieur à :value.',
        'string' => 'Le champ :attribute doit contenir plus de :value caractères.',
    ],
    'image' => 'Le champ :attribute doit être une image.',
    'in' => 'La valeur choisie pour :attribute est invalide.',
    'integer' => 'Le champ :attribute doit être un nombre entier.',
    'max' => [
        'numeric' => 'Le champ :attribute ne peut pas dépasser :max.',
        'file' => 'Le fichier :attribute ne peut pas dépasser :max Ko.',
        'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
    ],
    'min' => [
        'numeric' => 'Le champ :attribute doit être au moins égal à :min.',
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'numeric' => 'Le champ :attribute doit être un nombre.',
    'regex' => 'Le format du champ :attribute est invalide.',
    'required' => 'Le champ :attribute est obligatoire.',
    'required_if' => 'Le champ :attribute est obligatoire dans ce cas.',
    'string' => 'Le champ :attribute doit être un texte.',
    'unique' => 'Cette valeur de :attribute est déjà utilisée.',

    'attributes' => [
        'name' => 'nom', 'phone' => 'téléphone', 'email' => 'email', 'password' => 'mot de passe',
        'identifier' => 'identifiant', 'address' => 'adresse', 'delivery_method' => 'mode de réception',
        'date' => 'date', 'equipment_id' => 'poste', 'start_hour' => 'heure de début', 'duration' => 'durée',
        'gamer_tag' => 'pseudo', 'message' => 'message', 'rating' => 'note', 'method' => 'moyen de paiement',
        'price_usd' => 'prix (USD)', 'stock' => 'stock', 'category' => 'catégorie', 'title' => 'titre',
        'game' => 'jeu', 'starts_at' => 'date et heure', 'fee_fc' => 'frais d\'inscription',
        'max_players' => 'nombre de places', 'label' => 'libellé', 'amount_fc' => 'montant', 'spent_on' => 'date',
        'usd_rate' => 'taux USD/FC', 'hour_rate_fc' => 'tarif horaire', 'open_hour' => 'heure d\'ouverture',
        'close_hour' => 'heure de fermeture', 'description' => 'description', 'body' => 'texte', 'type' => 'type',
        'status' => 'statut', 'response' => 'réponse', 'image' => 'image',
    ],
];
