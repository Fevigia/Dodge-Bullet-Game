<?php
session_start();
$highScoreFile = 'highscore.txt';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = intval($_POST['score']);
    $savedHigh = file_exists($highScoreFile) ? intval(file_get_contents($highScoreFile)) : 0;

    if ($score > $savedHigh) {
        file_put_contents($highScoreFile, $score);
        echo json_encode(['newHigh' => true, 'highScore' => $score]);
    } else {
        echo json_encode(['newHigh' => false, 'highScore' => $savedHigh]);
    }
    exit;
}

$bestScore = file_exists($highScoreFile) ? intval(file_get_contents($highScoreFile)) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dodge the Bullets 🔫</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: monospace;
      background-color: #111;
      color: #0f0;
      overflow: hidden;
    }
    #game {
      position: relative;
      width: 100vw;
      height: 100vh;
      background: linear-gradient(to top right, #000, #222);
    }
    #player {
      position: absolute;
      width: 40px;
      height: 40px;
      background-color: #00f;
      border: 2px solid #fff;
      bottom: 50px;
      left: 50px;
      border-radius: 5px;
    }
    .bullet {
      position: absolute;
      width: 30px;
      height: 10px;
      background-color: red;
      top: 0;
      animation: bulletMove 3s linear forwards;
    }
    @keyframes bulletMove {
      100% { top: 100vh; }
    }
    #score {
      position: absolute;
      top: 10px;
      left: 10px;
      font-size: 1.5rem;
    }
    #gameOver {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      background-color: rgba(0,0,0,0.85);
      color: red;
      display: none;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      font-size: 2rem;
    }
    #gameOver button {
      margin-top: 20px;
      padding: 10px 20px;
      background: #222;
      color: #0f0;
      border: 2px solid #0f0;
      cursor: pointer;
      border-radius: 10px;
    }
    #bestScore {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 1.5rem;
      color: orange;
    }
  </style>
</head>
<body>
  <div id="game"> 
    <div id="player"></div>
    <div id="score">Score: <span id="scoreValue">0</span></div>
    <div id="bestScore">🏆 Best: <span id="bestValue"><?= $bestScore ?></span></div>
    <div id="gameOver">
      💀 GAME OVER 💀<br>
    <div style="display: inline-block;">  
      Score: <span id="finalScore">0</span><br>
      Best: <span id="bestScoreFinal"><?= $bestScore ?></span>
  </div>  
      <button onclick="restartGame()">Play Again</button>
  </div>  
  </div>

  <script>
    const player = document.getElementById("player");
    const game = document.getElementById("game");
    const scoreValue = document.getElementById("scoreValue");
    const gameOverScreen = document.getElementById("gameOver");
    const finalScore = document.getElementById("finalScore");
    const bestValue = document.getElementById("bestValue");
    const bestScoreFinal = document.getElementById("bestScoreFinal");

    let score = 0;
    let gameRunning = true;

    document.addEventListener("keydown", (e) => {
      const step = 20;
      const gameRect = game.getBoundingClientRect();
      const playerRect = player.getBoundingClientRect();
      if (!gameRunning) return;

      switch (e.key) {
        case "ArrowLeft":
          if (player.offsetLeft - step > 0)
            player.style.left = player.offsetLeft - step + "px";
          break;
        case "ArrowRight":
          if (player.offsetLeft + playerRect.width + step < gameRect.width)
            player.style.left = player.offsetLeft + step + "px";
          break;
        case "ArrowUp":
          if (player.offsetTop - step > 0)
            player.style.top = player.offsetTop - step + "px";
          break;
        case "ArrowDown":
          if (player.offsetTop + playerRect.height + step < gameRect.height)
            player.style.top = player.offsetTop + step + "px";
          break;
      }
    });

    function spawnBullet() {
      if (!gameRunning) return;

      const bullet = document.createElement("div");
      bullet.classList.add("bullet");
      bullet.style.left = Math.random() * (window.innerWidth - 30) + "px";
      game.appendChild(bullet);

      const interval = setInterval(() => {
        if (!bullet.parentNode) return clearInterval(interval);
        const bulletRect = bullet.getBoundingClientRect();
        const playerRect = player.getBoundingClientRect();

        if (
          bulletRect.top + bulletRect.height >= playerRect.top &&
          bulletRect.left < playerRect.right &&
          bulletRect.right > playerRect.left
        ) {
          gameOver();
          clearInterval(interval);
        }
      }, 30);

      setTimeout(() => {
        if (bullet.parentNode) bullet.remove();
      }, 3000);
    }

    function updateScore() {
      if (!gameRunning) return;
      score++;
      scoreValue.textContent = score;
    }

    function gameOver() {
      gameRunning = false;
      finalScore.textContent = score;

      fetch("dodge_game.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "score=" + score
      })
      .then(res => res.json())
      .then(data => {
        bestScoreFinal.textContent = data.highScore;
        bestValue.textContent = data.highScore;
      });

      gameOverScreen.style.display = "flex";
    }

    function restartGame() {
      document.querySelectorAll(".bullet").forEach(b => b.remove());
      player.style.left = "50px";
      player.style.top = "";
      score = 0;
      scoreValue.textContent = score;
      gameRunning = true;
      gameOverScreen.style.display = "none";
    }

    setInterval(() => { if (gameRunning) spawnBullet(); }, 600);
    setInterval(() => { if (gameRunning) updateScore(); }, 1000);
  </script>
</body>
</html>

