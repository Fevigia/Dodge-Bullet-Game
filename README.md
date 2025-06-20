# Dodge Bullet Game

A simple browser-based game where you control a blue square ("the player") and dodge falling bullets for as long as possible. Your score increases the longer you survive, and your best score is saved between sessions!

## Features

- Move your player with arrow keys to dodge bullets.
- Bullets spawn randomly and fall from the top.
- Score increases the longer you survive.
- Local high score is saved in `highscore.txt`.
- Simple, retro-inspired design.

## How to Run

### Requirements

- A web server with PHP support (e.g., XAMPP, WAMP, MAMP, or a Linux server with Apache/Nginx and PHP installed).
- Web browser (Chrome, Firefox, Edge, etc.).

### Steps

1. **Download/Clone the repository:**
   ```
   git clone https://github.com/Fevigia/Dodge-Bullet-Game.git
   ```
2. **Move into the project directory:**
   ```
   cd Dodge-Bullet-Game
   ```

3. **(Optional) Check `highscore.txt`:**
   - If it doesn’t exist, the game will create it automatically after first play.

4. **Run the game:**
   - Place the project folder in your local web server's root directory (e.g., `htdocs` for XAMPP).
   - Start your web server and ensure PHP is running.
   - In your browser, go to:  
     ```
     http://localhost/Dodge-Bullet-Game/dodge_game.php
     ```

5. **Play!**
   - Use the arrow keys to move your player.
   - Avoid the red bullets.
   - Try to beat your best score!

## File Structure

```
dodge_game.php     # Main game file (HTML, CSS, JavaScript, PHP)
highscore.txt      # Stores best score (created automatically)
```

## Notes

- The game high score is stored in a plain text file (`highscore.txt`). Make sure your web server has write permission to the project directory.
- To reset the high score, simply delete the `highscore.txt` file.

---

Enjoy dodging bullets!
