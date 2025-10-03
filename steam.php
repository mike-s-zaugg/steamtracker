<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Meine Steam-Spiele</title>
<style>
  body { font-family: Arial, sans-serif; background: #121212; color: #eee; padding: 20px; }
  h2 { margin-top: 40px; }
  ul { list-style: none; padding: 0; display: flex; flex-wrap: wrap; gap: 15px; }
  li { background: #1e1e1e; padding: 10px; border-radius: 8px; display: flex; align-items: center; width: 220px; }
  img { width: 50px; height: 50px; margin-right: 10px; border-radius: 4px; }
  .game-info { display: flex; flex-direction: column; }
  .game-name { font-weight: bold; }
  .playtime { font-size: 0.9em; color: #aaa; }
</style>
</head>
<form method="post">
  Steam-ID: <input type="text" name="steamid">
  <input type="submit" value="Senden">
</form>
<body>

<?php

$steamApiKey = '';

if (isset($_POST['steamid'])) {
    $steamId = $_POST['steamid']; // Wert in Variable speichern




 $profileUrl = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key={$steamApiKey}&steamids={$steamId}";
    $profileData = json_decode(@file_get_contents($profileUrl), true);
    $player = $profileData['response']['players'][0] ?? null;

    if ($player) {
        $avatar = $player['avatarfull'] ?? '';
        $name   = $player['personaname'] ?? 'Unbekannt';
        echo "<h2>Profil von {$name}</h2>";
        if ($avatar) {
            echo "<img src='{$avatar}' alt='{$name}' class='avatar'>";
        }
    } else {
        echo "<p>Profil nicht gefunden oder privat.</p>";
    }


// helper: baut die steam-community image-url
function steam_image_url($appid, $hash) {
    return $hash ? "https://media.steampowered.com/steamcommunity/public/images/apps/{$appid}/{$hash}.jpg" : null;
}

// helper: prüft, ob URL existiert (HEAD)
function url_exists($url) {
    $headers = @get_headers($url);
    return is_array($headers) && preg_match('#HTTP/\d+\.\d+\s+2\d\d#', $headers[0] ?? '');
}

// fallback: holt header image aus Store API
function store_header_image($appid) {
    $json = @file_get_contents("https://store.steampowered.com/api/appdetails?appids={$appid}&l=de");
    if (!$json) return null;
    $data = json_decode($json, true);
    if (!isset($data[$appid]['success']) || !$data[$appid]['success']) return null;
    return $data[$appid]['data']['header_image'] ?? null;
}

// rendert ein Game-Item (Name + Bild + Zeit)
function render_game_li($appid, $name, $hash, $altHours = '') {
    $img = steam_image_url($appid, $hash);
    if (!$img || !url_exists($img)) {
        // versuch icon hash (falls hash war logo). $hash kann beides sein, aber wir prüfen nochmal nichts gefunden -> store header
        $storeHeader = store_header_image($appid);
        $img = $storeHeader ?: 'https://via.placeholder.com/150?text=No+Image';
    }
    $hours = $altHours !== '' ? " - $altHours h" : '';
    echo "<li><img src=\"" . htmlspecialchars($img) . "\" alt=\"" . htmlspecialchars($name) . "\" /><div class='game-info'><span class='game-name'>" . htmlspecialchars($name) . "</span><span class='playtime'>{$hours}</span></div></li>";
}

// --- Zuletzt gespielt ---
$recentUrl = "https://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v1/?key={$steamApiKey}&steamid={$steamId}&count=7";
$recentJson = @file_get_contents($recentUrl);
$recentData = $recentJson ? json_decode($recentJson, true) : null;

echo "<h2>Zuletzt gespielt</h2><ul>";
if (!empty($recentData['response']['games'])) {
    foreach ($recentData['response']['games'] as $game) {
        $name = $game['name'] ?? 'Unbekannt';
        $playtime = isset($game['playtime_2weeks']) ? round($game['playtime_2weeks']/60,1) : '';
        // img_logo_url kann leer sein — nutze img_logo_url oder img_icon_url
        $hash = $game['img_logo_url'] ?? ($game['img_icon_url'] ?? null);
        render_game_li($game['appid'], $name, $hash, $playtime);
    }
} else {
    echo "<li>Keine Daten (Profil evtl. privat oder Fehler)</li>";
}
echo "</ul>";

// --- Meistgespielt ---
$ownedUrl = "https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key={$steamApiKey}&steamid={$steamId}&include_appinfo=1&include_played_free_games=1";
$ownedJson = @file_get_contents($ownedUrl);
$ownedData = $ownedJson ? json_decode($ownedJson, true) : null;

$games = $ownedData['response']['games'] ?? [];
usort($games, fn($a,$b) => ($b['playtime_forever'] ?? 0) <=> ($a['playtime_forever'] ?? 0));

echo "<h2>Meistgespielt</h2><ul>";
foreach (array_slice($games, 0, 7) as $game) {
    $name = $game['name'] ?? 'Unbekannt';
    $playtime = isset($game['playtime_forever']) ? round($game['playtime_forever']/60,1) : '';
    $hash = $game['img_logo_url'] ?? ($game['img_icon_url'] ?? null);
    render_game_li($game['appid'], $name, $hash, $playtime);
}
echo "</ul>";


$ownedUrl = "https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key={$steamApiKey}&steamid={$steamId}&include_played_free_games=1";
$ownedData = json_decode(file_get_contents($ownedUrl), true);

$totalMinutes = 0;
if (!empty($ownedData['response']['games'])) {
    foreach ($ownedData['response']['games'] as $game) {
        $totalMinutes += $game['playtime_forever'] ?? 0; // Minuten
    }
}

$totalHours = round($totalMinutes / 60, 1);
$totalHoursOutput = "Insgesamt gespielt: {$totalHours} Stunden";
echo "<h2>$totalHoursOutput</h2>";

} else {
    echo "<h1>Gebe die Steam-ID an</h1>";
}
?>

</body>
</html>
