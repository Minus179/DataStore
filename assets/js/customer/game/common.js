let currentGame = "";
let raceInterval;
let marioInterval;

function filterGames(category) {
  const games = document.getElementsByClassName("game-card");
  for (let game of games) {
    const gameCategory = game.getAttribute("data-category");
    game.style.display =
      category === "all" || gameCategory === category ? "block" : "none";
  }
}

function startGame(gameType) {
  document.getElementById("gameOverlay").style.display = "flex";
  const gameArea = document.getElementById("gameArea");
  gameArea.innerHTML = "";
  currentGame = gameType;

  switch (gameType) {
    case "caro":
      gameArea.innerHTML = `
                <h2>Caro 5x5</h2>
                <div class="caro-board" id="caroBoard"></div>
                <p id="caroStatus">Lượt của bạn (X)</p>
            `;
      startCaroGame();
      break;
    case "trivia":
      gameArea.innerHTML = `
                <h2>Ai là triệu phú</h2>
                <div class="quiz-question" id="quizQuestion"></div>
                <div id="quizOptions"></div>
            `;
      startTriviaGame();
      break;
    case "race":
      gameArea.innerHTML = `
                <h2>Đua xe</h2>
                <p>Score: <span id="raceScore">0</span></p>
                <div class="race-game" id="raceGame">
                    <div class="car" id="raceCar"></div>
                </div>
                <p>Dùng phím mũi tên để di chuyển</p>
            `;
      startRaceGame();
      break;
    case "fun":
      gameArea.innerHTML = `
                <h2>Game đồ vui</h2>
                <p id="funQuestion">Câu 1: Trời mưa ướt áo, trời gì ướt tóc?</p>
                <button class="quiz-option" onclick="checkFunAnswer('trời tối')">Trời tối</button>
                <button class="quiz-option" onclick="checkFunAnswer('trời nắng')">Trời nắng</button>
            `;
      break;
    case "mario":
      gameArea.innerHTML = `
                <h2>Game Mario-style</h2>
                <p>Score: <span id="marioScore">0</span></p>
                <div class="mario-game" id="marioGame">
                    <div class="mario" id="mario"></div>
                </div>
                <p>Dùng phím mũi tên để di chuyển, phím Space để nhảy</p>
            `;
      startMarioGame();
      break;
  }
}

function closeGame() {
  document.getElementById("gameOverlay").style.display = "none";
  stopAllGames();
}

function stopAllGames() {
  clearInterval(raceInterval);
  clearInterval(marioInterval);
}
