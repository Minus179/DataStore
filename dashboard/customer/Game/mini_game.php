<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../assets/css/customer/game.css?v=<?=time()?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Game Vui Nhộn</title>
</head>
<body>
    <h1>Danh sách Game Vui Nhộn</h1>
    <div class="game-menu">
        <button onclick="filterGames('all')">Tất cả</button>
        <button onclick="filterGames('tri tue')">Trí tuệ</button>
        <button onclick="filterGames('dua xe')">Đua xe</button>
        <button onclick="filterGames('do vui')">Đố vui</button>
        <button onclick="filterGames('mario')">Mario-style</button>
    </div>
    <div class="game-wrapper">
    <div class="game-container" id="gameContainer">
        <div class="game-card" data-category="tri tue">
              <div class="game-card-image">
                <img src="../../../assets/images/game/caro.jpg" alt="Game Image" />
              </div>
            <h2>Caro 5x5</h2>
            <p>Chơi caro phiên bản 5x5, chọn hàng khiếp 5 ô liên tiếp.</p>
            <button onclick="startGame('caro')">Chơi</button>
        </div>
        <div class="game-card" data-category="tri tue">
               <div class="game-card-image">
                <img src="../../../assets/images/game/trieu_phu.jpg" alt="Game Image" />
              </div>
            <h2>Ai là triệu phú</h2>
            <p>Thử tài trí tuệ với các câu hỏi trí tuệ.</p>
            <button onclick="startGame('trivia')">Chơi</button>
        </div>
        <div class="game-card" data-category="dua xe">
            <div class="game-card-image">
             <img src="../../../assets/images/game/dia_hinh.jpg" alt="Game Image" />
            </div>
            <h2>Đua xe</h2>
            <p>Lái xe né chướng ngại vật để đạt điểm cao.</p>
            <button onclick="startGame('race')">Chơi</button>
        </div>
        <div class="game-card" data-category="do vui">
            <div class="game-card-image">
                <img src="../../../assets/images/game/do_vui.jpg" alt="Game Image" />
            </div>
            <h2>Game đồ vui</h2>
            <p>Câu đố hài hước, đổi khiếu lưu. Bạn dám thử?</p>
            <button onclick="startGame('fun')">Chơi</button>
        </div>
        <div class="game-card" data-category="mario">
            <div class="game-card-image">
                <img src="../../../assets/images/game/mario_quaivat.jpg" alt="Game Image" />
            </div>
            <h2>Game Mario-style</h2>
            <p>Đi cảnh nhảy lên đầu quái vật với phong cách Mario.</p>
            <button onclick="startGame('mario')">Chơi</button>
        </div>
      </div>
    </div>

    <!-- Game Overlay -->
    <div class="game-overlay" id="gameOverlay">
        <div class="game-content" id="gameContent">
            <button class="close-btn" onclick="closeGame()">X</button>
            <div id="gameArea"></div>
        </div>
    </div>

    <script>
        let currentGame = '';

        // Lọc game theo thể loại
        function filterGames(category) {
            const games = document.getElementsByClassName('game-card');
            for (let game of games) {
                const gameCategory = game.getAttribute('data-category');
                if (category === 'all' || gameCategory === category) {
                    game.style.display = 'block';
                } else {
                    game.style.display = 'none';
                }
            }
        }


        // Khởi động game
        function startGame(gameType) {
            document.getElementById('gameOverlay').style.display = 'flex';
            const gameArea = document.getElementById('gameArea');
            gameArea.innerHTML = '';
            currentGame = gameType;

            if (gameType === 'caro') {
                gameArea.innerHTML = `
                    <h2>Caro 5x5</h2>
                    <div class="caro-board" id="caroBoard"></div>
                    <p id="caroStatus">Lượt của bạn (X)</p>
                `;
                startCaroGame();
            } else if (gameType === 'trivia') {
                gameArea.innerHTML = `
                    <h2>Ai là triệu phú</h2>
                    <div class="quiz-question" id="quizQuestion"></div>
                    <div id="quizOptions"></div>
                `;
                startTriviaGame();
            } else if (gameType === 'race') {
                gameArea.innerHTML = `
                    <h2>Đua xe</h2>
                    <p>Score: <span id="raceScore">0</span></p>
                    <div class="race-game" id="raceGame">
                        <div class="car" id="raceCar"></div>
                    </div>
                    <p>Dùng phím mũi tên để di chuyển</p>
                `;
                startRaceGame();
            } else if (gameType === 'fun') {
                gameArea.innerHTML = `
                    <h2>Game đồ vui</h2>
                    <p id="funQuestion">Câu 1: Trời mưa ướt áo, trời gì ướt tóc?</p>
                    <button class="quiz-option" onclick="checkFunAnswer('trời tối')">Trời tối</button>
                    <button class="quiz-option" onclick="checkFunAnswer('trời nắng')">Trời nắng</button>
                `;
            } else if (gameType === 'mario') {
                gameArea.innerHTML = `
                    <h2>Game Mario-style</h2>
                    <p>Score: <span id="marioScore">0</span></p>
                    <div class="mario-game" id="marioGame">
                        <div class="mario" id="mario"></div>
                    </div>
                    <p>Dùng phím mũi tên để di chuyển, phím Space để nhảy</p>
                `;
                startMarioGame();
            }
        }

        // Đóng game
        function closeGame() {
            document.getElementById('gameOverlay').style.display = 'none';
            stopAllGames();
        }

        // Dừng tất cả game
        function stopAllGames() {
            clearInterval(raceInterval);
            clearInterval(marioInterval);
        }

        // Game Caro 5x5
        let caroBoard = Array(25).fill('');
        let caroGameOver = false;
        function startCaroGame() {
            const board = document.getElementById('caroBoard');
            board.innerHTML = '';
            caroBoard = Array(25).fill('');
            caroGameOver = false;
            for (let i = 0; i < 25; i++) {
                const cell = document.createElement('div');
                cell.className = 'caro-cell';
                cell.dataset.index = i;
                cell.onclick = () => makeCaroMove(i);
                board.appendChild(cell);
            }
        }
        function makeCaroMove(index) {
            if (caroBoard[index] !== '' || caroGameOver) return;
            caroBoard[index] = 'X';
            document.querySelector(`[data-index="${index}"]`).innerText = 'X';
            if (checkCaroWin('X')) {
                document.getElementById('caroStatus').innerText = 'Bạn thắng!';
                caroGameOver = true;
                return;
            }
            if (!caroBoard.includes('')) {
                document.getElementById('caroStatus').innerText = 'Hòa!';
                caroGameOver = true;
                return;
            }
            let aiMove = Math.floor(Math.random() * 25);
            while (caroBoard[aiMove] !== '') {
                aiMove = Math.floor(Math.random() * 25);
            }
            caroBoard[aiMove] = 'O';
            document.querySelector(`[data-index="${aiMove}"]`).innerText = 'O';
            if (checkCaroWin('O')) {
                document.getElementById('caroStatus').innerText = 'Máy thắng!';
                caroGameOver = true;
                return;
            }
            if (!caroBoard.includes('')) {
                document.getElementById('caroStatus').innerText = 'Hòa!';
                caroGameOver = true;
                return;
            }
            document.getElementById('caroStatus').innerText = 'Lượt của bạn (X)';
        }
        function checkCaroWin(player) {
            for (let i = 0; i < 5; i++) {
                for (let j = 0; j < 5; j++) {
                    let index = i * 5 + j;
                    if (caroBoard[index] === player) {
                        if (
                            checkDirection(index, 0, 1, 5) || 
                            checkDirection(index, 1, 0, 5) ||
                            checkDirection(index, 1, 1, 5) ||
                            checkDirection(index, 1, -1, 5)   
                        ) {
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        function checkDirection(start, dx, dy, len) {
            let count = 1;
            let x = Math.floor(start / 5);
            let y = start % 5;
            for (let i = 1; i < len; i++) {
                let newX = x + i * dx;
                let newY = y + i * dy;
                if (newX >= 0 && newX < 5 && newY >= 0 && newY < 5) {
                    let index = newX * 5 + newY;
                    if (caroBoard[index] === caroBoard[start]) {
                        count++;
                        if (count === 5) return true;
                    } else {
                        break;
                    }
                }
            }
            for (let i = 1; i < len; i++) {
                let newX = x - i * dx;
                let newY = y - i * dy;
                if (newX >= 0 && newX < 5 && newY >= 0 && newY < 5) {
                    let index = newX * 5 + newY;
                    if (caroBoard[index] === caroBoard[start]) {
                        count++;
                        if (count === 5) return true;
                    } else {
                        break;
                    }
                }
            }
            return count >= 5;
        }

        // Game Ai là triệu phú
        const triviaQuestions = [
            { question: 'Thủ đô Việt Nam là?', options: ['Hà Nội', 'TP.HCM', 'Đà Nẵng'], answer: 'Hà Nội' },
            { question: '1+1 bằng bao nhiêu?', options: ['1', '2', '3'], answer: '2' }
        ];
        let currentQuestionIndex = 0;
        function startTriviaGame() {
            showTriviaQuestion();
        }
        function showTriviaQuestion() {
            const question = triviaQuestions[currentQuestionIndex];
            document.getElementById('quizQuestion').innerText = question.question;
            const optionsEl = document.getElementById('quizOptions');
            optionsEl.innerHTML = '';
            question.options.forEach(option => {
                const btn = document.createElement('button');
                btn.className = 'quiz-option';
                btn.innerText = option;
                btn.onclick = () => checkTriviaAnswer(option);
                optionsEl.appendChild(btn);
            });
        }
        function checkTriviaAnswer(option) {
            if (option === triviaQuestions[currentQuestionIndex].answer) {
                alert('Đúng rồi!');
                currentQuestionIndex++;
                if (currentQuestionIndex < triviaQuestions.length) {
                    showTriviaQuestion();
                } else {
                    alert('Chúc mừng bạn đã hoàn thành!');
                    closeGame();
                }
            } else {
                alert('Sai rồi! Trò chơi kết thúc.');
                closeGame();
            }
        }

        // Game Đua xe
        let raceScore = 0;
        let raceInterval;
        function startRaceGame() {
            raceScore = 0;
            document.getElementById('raceScore').innerText = raceScore;
            const car = document.getElementById('raceCar');
            let carPos = 180;
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft' && carPos > 0) carPos -= 10;
                if (e.key === 'ArrowRight' && carPos < 360) carPos += 10;
                car.style.left = carPos + 'px';
            });
            raceInterval = setInterval(() => {
                const game = document.getElementById('raceGame');
                const obstacle = document.createElement('div');
                obstacle.className = 'obstacle';
                obstacle.style.left = Math.random() * 380 + 'px';
                obstacle.style.top = '0px';
                game.appendChild(obstacle);
                let obstaclePos = 0;
                const moveObstacle = setInterval(() => {
                    obstaclePos += 5;
                    obstacle.style.top = obstaclePos + 'px';
                    if (obstaclePos > 140) {
                        const carRect = car.getBoundingClientRect();
                        const obsRect = obstacle.getBoundingClientRect();
                        if (carRect.left < obsRect.right && carRect.right > obsRect.left &&
                            carRect.top < obsRect.bottom && carRect.bottom > obsRect.top) {
                            clearInterval(moveObstacle);
                            clearInterval(raceInterval);
                            alert(`Trò chơi kết thúc! Điểm: ${raceScore}`);
                            closeGame();
                        }
                    }
                    if (obstaclePos > 200) {
                        obstacle.remove();
                        raceScore++;
                        document.getElementById('raceScore').innerText = raceScore;
                        clearInterval(moveObstacle);
                    }
                }, 50);
            }, 1000);
        }

        // Game Đồ vui
        function checkFunAnswer(answer) {
            if (answer === 'trời tối') {
                alert('Đúng rồi! Trời mưa ướt áo, trời tối ướt tóc.');
            } else {
                alert('Sai rồi! Đáp án là trời tối.');
            }
            closeGame();
        }

        // Game Mario-style
        let marioScore = 0;
        let marioInterval;
        function startMarioGame() {
            marioScore = 0;
            document.getElementById('marioScore').innerText = marioScore;
            const mario = document.getElementById('mario');
            let marioPos = 180;
            let isJumping = false;
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft' && marioPos > 0) marioPos -= 10;
                if (e.key === 'ArrowRight' && marioPos < 360) marioPos += 10;
                if (e.key === ' ' && !isJumping) {
                    isJumping = true;
                    let jumpHeight = 0;
                    const jumpInterval = setInterval(() => {
                        if (jumpHeight < 50) {
                            mario.style.bottom = (10 + jumpHeight) + 'px';
                            jumpHeight += 5;
                        } else {
                            clearInterval(jumpInterval);
                            const fallInterval = setInterval(() => {
                                if (jumpHeight > 0) {
                                    mario.style.bottom = (10 + jumpHeight) + 'px';
                                    jumpHeight -= 5;
                                } else {
                                    clearInterval(fallInterval);
                                    isJumping = false;
                                    mario.style.bottom = '10px';
                                }
                            }, 20);
                        }
                    }, 20);
                }
                mario.style.left = marioPos + 'px';
            });
            marioInterval = setInterval(() => {
                const game = document.getElementById('marioGame');
                const obstacle = document.createElement('div');
                obstacle.className = 'obstacle';
                obstacle.style.left = Math.random() * 380 + 'px';
                obstacle.style.top = '0px';
                game.appendChild(obstacle);
                let obstaclePos = 0;
                const moveObstacle = setInterval(() => {
                    obstaclePos += 5;
                    obstacle.style.top = obstaclePos + 'px';
                    if (obstaclePos > 240) {
                        const marioRect = mario.getBoundingClientRect();
                        const obsRect = obstacle.getBoundingClientRect();
                        if (marioRect.left < obsRect.right && marioRect.right > obsRect.left &&
                            marioRect.top < obsRect.bottom && marioRect.bottom > obsRect.top) {
                            clearInterval(moveObstacle);
                            clearInterval(marioInterval);
                            alert(`Trò chơi kết thúc! Điểm: ${marioScore}`);
                            closeGame();
                        }
                    }
                    if (obstaclePos > 300) {
                        obstacle.remove();
                        marioScore++;
                        document.getElementById('marioScore').innerText = marioScore;
                        clearInterval(moveObstacle);
                    }
                }, 50);
            }, 1000);
        }
    </script>
</body>
</html>