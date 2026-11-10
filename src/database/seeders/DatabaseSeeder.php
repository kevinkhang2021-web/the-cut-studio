<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Equipment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Tournament;
use App\Models\User;
use App\Support\Studio;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Paramètres par défaut (modifiables dans Admin > Paramètres)
        foreach (Studio::DEFAULTS as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // Comptes de démonstration — CHANGEZ LES MOTS DE PASSE avant toute mise en ligne
        User::firstOrCreate(['phone' => '0990000000'], [
            'name' => 'Administrateur', 'email' => 'admin@thecutstudio.cd',
            'password' => 'Admin@2026', 'is_admin' => true,
        ]);
        User::firstOrCreate(['phone' => '0970000000'], [
            'name' => 'Client Démo', 'email' => 'client@thecutstudio.cd',
            'password' => 'client123', 'is_admin' => false,
        ]);

        // Postes de jeu (à adapter à la salle réelle depuis Admin > Postes)
        foreach (['PS5' => 3, 'PS4' => 3] as $type => $count) {
            for ($i = 1; $i <= $count; $i++) {
                Equipment::firstOrCreate(['name' => "Poste $type $i"], ['type' => $type, 'is_active' => true]);
            }
        }

        // Consoles PS5 (prix du catalogue, en USD)
        $consoles = [
            ['PS5 Slim + manette DualSense', 660, 'images/products/ps5-slim-manette.jpg'],
            ['PS5 Slim Digital 825 Go (Astro Bot + Gran Turismo 7)', 950, 'images/products/ps5-slim-digital-825.jpg'],
            ['PS5 Slim 1 To (Astro Bot + Gran Turismo 7)', 660, 'images/products/ps5-slim-1to.jpg'],
            ['PS5 Pro Digital (sans lecteur de disque)', 850, 'images/products/ps5-pro-digital.jpg'],
        ];
        foreach ($consoles as [$name, $usd, $img]) {
            Product::firstOrCreate(['name' => $name], [
                'category' => 'console', 'platform' => 'PS5', 'price_usd' => $usd,
                'stock' => 3, 'image' => $img, 'is_active' => true,
                'description' => 'Console PlayStation 5 – prix indicatif du catalogue.',
            ]);
        }

        // Jeux PS4 (planches 1 à 10 du catalogue). Prix en USD ; le prix en FC = USD x taux.
        $games = [
            1  => [['Call of Duty : Black Ops III', 30], ['Call of Duty : Infinite Warfare', 35], ['The Order : 1886', 40]],
            2  => [['Rayman Legends', 50], ['Cars 3 : Driven to Win', 50], ['Team Sonic Racing', 50]],
            3  => [['The Sims 4', 50], ['Just Dance 2020', 75], ['LEGO Les Indestructibles', 50]],
            4  => [['Uncharted 4 : A Thief\'s End', 35], ['FIFA 19', 35], ['NBA 2K19', 40]],
            5  => [['Resident Evil 7 : Biohazard', 40], ['Naruto Shippuden : Storm 4', 50], ['Naruto : Storm Trilogy', 50]],
            6  => [['Jumanji : Le Jeu vidéo', 60], ['The Last of Us Remastered', 50], ['Saints Row : The Third Remastered', 50]],
            7  => [['Star Wars Jedi : Fallen Order', 75], ['Final Fantasy VII Remake', 75], ['My Hero One\'s Justice 2', 65]],
            8  => [['Ghost Recon : Breakpoint', 65], ['Nioh 2', 75], ['Narcos : Rise of the Cartels', 60]],
            9  => [['Attack on Titan 2 : Final Battle', 55], ['Dragon Ball FighterZ', 50], ['Dragon Ball Z : Kakarot', 75]],
            10 => [['Need for Speed : Rivals', 40], ['Need for Speed : Heat', 75], ['Need for Speed : Payback', 50]],
        ];
        foreach ($games as $shelf => $list) {
            foreach ($list as [$name, $usd]) {
                Product::firstOrCreate(['name' => $name], [
                    'category' => 'jeu', 'platform' => 'PS4', 'price_usd' => $usd, 'stock' => 5,
                    'is_active' => true, 'description' => "Jeu PlayStation 4 (catalogue, planche $shelf).",
                ]);
            }
        }

        if (Tournament::count() === 0) {
            Tournament::create([
                'title' => 'Tournoi d\'ouverture FIFA', 'game' => 'FIFA 19',
                'starts_at' => now()->addDays(10)->setTime(15, 0), 'fee_fc' => 5000, 'max_players' => 16,
                'description' => 'Élimination directe. Venez avec votre pseudo de joueur !', 'status' => 'open',
            ]);
        }
        if (Announcement::count() === 0) {
            Announcement::create([
                'title' => 'Bienvenue sur The Cut Studio en ligne',
                'body' => 'Réservez votre poste, inscrivez-vous aux tournois et commandez vos jeux depuis votre téléphone.',
            ]);
        }
    }
}
