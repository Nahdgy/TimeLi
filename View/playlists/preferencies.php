<form action="">
    <h1>Fais le choix de tes préférences</h1>
    <div class="form-group">
        <label for="mood">Quel mood souhaites-tu ?</label>
        <div class="d-flex flex-wrap gap-3 mt-3">
            <div class="card mood-card" style="width: 120px;">
                <input type="radio" name="mood" id="happy" value="happy" class="d-none">
                <label for="happy" class="card-body text-center">
                    <div class="display-5">😊</div>
                    <div class="mt-2">Happy</div>
                </label>
            </div>
            <div class="card mood-card" style="width: 120px;">
                <input type="radio" name="mood" id="lover" value="lover" class="d-none">
                <label for="lover" class="card-body text-center">
                    <div class="display-5">❤️</div>
                    <div class="mt-2">Lover</div>
                </label>
            </div>
            <div class="card mood-card" style="width: 120px;">
                <input type="radio" name="mood" id="sad" value="sad" class="d-none">
                <label for="sad" class="card-body text-center">
                    <div class="display-5">😢</div>
                    <div class="mt-2">Sad</div>
                </label>
            </div>
            <div class="card mood-card" style="width: 120px;">
                <input type="radio" name="mood" id="party" value="party" class="d-none">
                <label for="party" class="card-body text-center">
                    <div class="display-5">🎉</div>
                    <div class="mt-2">Party</div>
                </label>
            </div>
            <div class="card mood-card" style="width: 120px;">
                <input type="radio" name="mood" id="angry" value="angry" class="d-none">
                <label for="angry" class="card-body text-center">
                    <div class="display-5">😠</div>
                    <div class="mt-2">Angry</div>
                </label>
            </div>
            <div class="card mood-card" style="width: 120px;">
                <input type="radio" name="mood" id="chill" value="chill" class="d-none">
                <label for="chill" class="card-body text-center">
                    <div class="display-5">😌</div>
                    <div class="mt-2">Chill</div>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="genreSearch">Choisis jusqu'à 10 genres</label>
        <div class="row">
            <div class="col-md-6">
                <div class="position-relative">
                    <input type="text" id="genreSearch" class="form-control" placeholder="Rechercher un genre">
                    <div id="genreResults" class="position-absolute w-100 bg-dark" style="z-index: 1000; display: none;"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-dark">
                    <div class="card-header">
                        Genres sélectionnés (<span id="genreCount">0</span>/10)
                    </div>
                    <div class="card-body">
                        <div id="selectedGenres" class="d-flex flex-wrap">
                            <!-- Les genres sélectionnés seront affichés ici -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group mb-4">
    <label for="country">Choisis ton pays de référence musicale</label>
    <select name="country" id="country" class="form-control">
        <option value="FR">France 🇫🇷</option>
        <option value="US">États-Unis 🇺🇸</option>
        <option value="GB">Royaume-Uni 🇬🇧</option>
        <option value="DE">Allemagne 🇩🇪</option>
        <option value="ES">Espagne 🇪🇸</option>
        <option value="IT">Italie 🇮🇹</option>
        <option value="JP">Japon 🇯🇵</option>
        <option value="BR">Brésil 🇧🇷</option>
        <option value="KR">Corée du Sud 🇰🇷</option>
        <option value="CA">Canada 🇨🇦</option>
        <option value="AU">Australie 🇦🇺</option>
        <option value="NL">Pays-Bas 🇳🇱</option>
        <option value="SE">Suède 🇸🇪</option>
        <option value="MX">Mexique 🇲🇽</option>
        <option value="IN">Inde 🇮🇳</option>
        <option value="BE">Belgique 🇧🇪</option>
        <option value="AR">Argentine 🇦🇷</option>
        <option value="CL">Chili 🇨🇱</option>
        <option value="CO">Colombie 🇨🇴</option>
        <option value="DK">Danemark 🇩🇰</option>
        <option value="FI">Finlande 🇫🇮</option>
        <option value="GR">Grèce 🇬🇷</option>
        <option value="ID">Indonésie 🇮🇩</option>
        <option value="IE">Irlande 🇮🇪</option>
        <option value="NO">Norvège 🇳🇴</option>
        <option value="NZ">Nouvelle-Zélande 🇳🇿</option>
        <option value="PL">Pologne 🇵🇱</option>
        <option value="PT">Portugal 🇵🇹</option>
        <option value="RU">Russie 🇷🇺</option>
        <option value="SG">Singapour 🇸🇬</option>
        <option value="TR">Turquie 🇹🇷</option>
        <option value="ZA">Afrique du Sud 🇿🇦</option>
    </select>
</div>
<div class="form-group mb-4">
    <label>Calcule ton temps de trajet</label>
    <div class="route-calculator">
        <input type="text" id="origin" class="form-control mb-2" placeholder="Point de départ">
        <input type="text" id="destination" class="form-control mb-2" placeholder="Destination">
        <div id="map" style="height: 300px; width: 100%; margin-bottom: 10px;"></div>
        <div id="duration" class="text-white mb-2"></div>
        <input type="hidden" name="travel_time" id="travel_time" value="">
    </div>
</div>
    <button type="submit">Valider</button>
</form>

<script src="assets/JS/genreSearch.js"></script>