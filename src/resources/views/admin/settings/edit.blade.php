@extends('layouts.admin')
@section('title', 'Paramètres')
@section('content')
<h1>🔧 Paramètres</h1>
<form method="POST" action="{{ route('admin.settings.update') }}" class="card" style="max-width:640px">@csrf @method('PUT')
    <h3>Tarifs</h3>
    <div class="grid c2">
        <div class="field"><label>Taux de change : 1 $ = … FC</label><input type="number" step="0.01" min="1" name="usd_rate" value="{{ old('usd_rate', $values['usd_rate']) }}" required></div>
        <div class="field"><label>Tarif d'une heure de jeu (FC)</label><input type="number" min="0" name="hour_rate_fc" value="{{ old('hour_rate_fc', $values['hour_rate_fc']) }}" required></div>
    </div>
    <p class="muted small">Le taux sert à convertir tous les prix du catalogue (saisis en dollars) en francs congolais.</p>
    <h3>Horaires</h3>
    <div class="grid c2">
        <div class="field"><label>Heure d'ouverture</label><input type="number" min="0" max="23" name="open_hour" value="{{ old('open_hour', $values['open_hour']) }}" required></div>
        <div class="field"><label>Heure de fermeture</label><input type="number" min="1" max="24" name="close_hour" value="{{ old('close_hour', $values['close_hour']) }}" required></div>
    </div>
    <h3>Coordonnées</h3>
    <div class="field"><label>Adresse</label><input type="text" name="address" value="{{ old('address', $values['address']) }}"></div>
    <div class="grid c2">
        <div class="field"><label>Téléphone</label><input type="text" name="phone" value="{{ old('phone', $values['phone']) }}"></div>
        <div class="field"><label>WhatsApp</label><input type="text" name="whatsapp" value="{{ old('whatsapp', $values['whatsapp']) }}"></div>
    </div>
    <button class="btn" type="submit">Enregistrer</button>
</form>
@endsection
