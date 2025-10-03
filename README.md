# Steam Profile & Game Viewer

[![Language](https://img.shields.io/badge/language-PHP-blue.svg)](https://www.php.net/)
[![API](https://img.shields.io/badge/API-Steam_Web_API-orange.svg)](https://steamcommunity.com/dev/apikey)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](https://opensource.org/licenses/MIT)

A simple, straightforward PHP script designed to fetch and display essential information about a Steam user's profile and game library using the Steam Web API. Just enter a Steam ID, and instantly view recently played games, most played titles, and total accumulated playtime.

✨ **Features**

*   **User Profile Display:** Shows the Steam user's persona name and avatar.
*   **Recently Played Games:** Lists games played in the last two weeks with their respective playtime.
*   **Most Played Games:** Highlights the top 7 games by total playtime.
*   **Total Playtime Calculation:** Aggregates and displays the total hours played across all owned games.
*   **Dynamic Game Image Fetching:** Attempts to fetch game logos/icons from Steam Community, with a fallback to Steam Store header images if community assets are unavailable.
*   **Simple Web Interface:** An intuitive HTML form to input the Steam ID.

📚 **Tech Stack**

*   **Backend:** PHP (Vanilla)
*   **Frontend:** HTML, CSS
*   **API:** Steam Web API

🚀 **Installation**

To get this project up and running on your local machine, follow these steps:

1.  **Download the `steam.php` file:**
    You can either clone the repository (if applicable) or simply download the `steam.php` file directly.

2.  **Obtain a Steam Web API Key:**
    *   Visit the [Steam Web API Key registration page](https://steamcommunity.com/dev/apikey).
    *   Log in with your Steam account and register a new API key. You can use any domain name, even `localhost` if you're running it locally.

3.  **Configure the API Key:**
    Open the `steam.php` file and locate the `$steamApiKey` variable. Replace the empty string with your newly obtained Steam Web API Key.

    ```php
    // In steam.php
    $steamApiKey = 'YOUR_STEAM_WEB_API_KEY_HERE'; // <-- Replace this line with your actual API key
    ```

4.  **Set up a Web Server with PHP:**
    You'll need a web server (like Apache or Nginx) with PHP installed and configured.
    *   Place the `steam.php` file in your web server's document root (e.g., `htdocs` for Apache, `www` for Nginx).

    Alternatively, for quick local testing, you can use PHP's built-in web server:
    ```bash
    php -S localhost:8000
    ```
    Then, navigate to `http://localhost:8000/steam.php` in your web browser.

▶️ **Usage**

1.  Once the `steam.php` file is accessible via your web server, open it in your browser.
2.  You will see a simple form asking for a Steam ID.
3.  Enter a valid [SteamID64](https://steamid.io/) (a 17-digit number) or a profile link into the input field.
4.  Click "Senden" (Submit) to view the profile and game statistics.

    *Example SteamID64:* `76561197960435530` (Valve's Robin Walker)
    *Example Profile Link:* `[76561197960435530](https://steamcommunity.com/profiles/76561197960435530/)` (Valve's Robin Walker)

🤝 **Contributing**

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

📝 **License**

Distributed under the MIT License. Consider adding a `LICENSE` file to your project for full details.
