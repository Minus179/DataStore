const rows = 15;  // số hàng (dọc)
const cols = 20;  // số cột (ngang)
const winLength = 5;

const board = document.getElementById('board');
const status = document.getElementById('status');
let cells = [];
let isPlayerTurn = true;
let gameOver = false;

function createBoard() {
    board.innerHTML = '';
    cells = [];
    gameOver = false;
    isPlayerTurn = true;
    status.textContent = "Lượt của bạn (X)";
    board.style.gridTemplateColumns = `repeat(${cols}, 40px)`;
    board.style.gridTemplateRows = `repeat(${rows}, 40px)`;

    for (let i = 0; i < rows * cols; i++) {
        const cell = document.createElement('div');
        cell.classList.add('cell');
        cell.dataset.index = i;
        cell.addEventListener('click', handleMove);
        board.appendChild(cell);
        cells.push(cell);
    }
}

function generateWinLines() {
    let lines = [];

    // Hàng ngang
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c <= cols - winLength; c++) {
            let line = [];
            for (let k = 0; k < winLength; k++) {
                line.push(r * cols + (c + k));
            }
            lines.push(line);
        }
    }

    // Cột dọc
    for (let c = 0; c < cols; c++) {
        for (let r = 0; r <= rows - winLength; r++) {
            let line = [];
            for (let k = 0; k < winLength; k++) {
                line.push((r + k) * cols + c);
            }
            lines.push(line);
        }
    }

    // Chéo chính (\)
    for (let r = 0; r <= rows - winLength; r++) {
        for (let c = 0; c <= cols - winLength; c++) {
            let line = [];
            for (let k = 0; k < winLength; k++) {
                line.push((r + k) * cols + (c + k));
            }
            lines.push(line);
        }
    }

    // Chéo phụ (/)
    for (let r = 0; r <= rows - winLength; r++) {
        for (let c = winLength - 1; c < cols; c++) {
            let line = [];
            for (let k = 0; k < winLength; k++) {
                line.push((r + k) * cols + (c - k));
            }
            lines.push(line);
        }
    }

    return lines;
}

const winLines = generateWinLines();

function checkWinner(symbol) {
    return winLines.some(line => line.every(i => cells[i].textContent === symbol));
}

function handleMove() {
    if (!isPlayerTurn || gameOver) return;
    if (this.textContent !== '') return;

    this.textContent = 'X';
    if (checkWinner('X')) {
    status.textContent = "Bạn thắng!";
    gameOver = true;

    // Gửi kết quả thắng lên server để lưu và cộng điểm
    fetch('save_result.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ result: 'win' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            status.textContent += ` +${data.points_awarded} điểm!`;
        } else {
            status.textContent += ' (' + data.message + ')';
        }
    })
    .catch(error => {
        console.error('Lỗi gửi kết quả:', error);
        status.textContent += ' (Lỗi kết nối server)';
    });

    return;
}

    isPlayerTurn = false;
    status.textContent = "Đang chờ máy...";
    setTimeout(aiMove, 300);
}

function getScoreForLine(line, symbol) {
    let countSymbol = 0;
    let countEmpty = 0;
    for (let i of line) {
        const cellContent = cells[i].textContent;
        if (cellContent === symbol) countSymbol++;
        else if (cellContent === '') countEmpty++;
        else return 0;
    }
    if (countEmpty + countSymbol !== winLength) return 0;
    switch(countSymbol) {
        case 5: return 100000;
        case 4: return 1000;
        case 3: return 100;
        case 2: return 10;
        case 1: return 1;
        default: return 0;
    }
}

function evaluateMove(index, symbol) {
    let score = 0;
    for (let line of winLines) {
        if (line.includes(Number(index))) {
            score += getScoreForLine(line, symbol);
        }
    }
    return score;
}

function aiMove() {
    if (gameOver) return;

    // Máy tìm nước đi thắng ngay
    for (let cell of cells) {
        if (cell.textContent === '') {
            cell.textContent = 'O';
            if (checkWinner('O')) {
                status.textContent = "Máy thắng!";
                gameOver = true;
                return;
            }
            cell.textContent = '';
        }
    }

    // Máy chặn người chơi thắng
    for (let cell of cells) {
        if (cell.textContent === '') {
            cell.textContent = 'X';
            if (checkWinner('X')) {
                cell.textContent = 'O';
                isPlayerTurn = true;
                status.textContent = "Lượt của bạn (X)";
                return;
            }
            cell.textContent = '';
        }
    }

    // Tính điểm và chọn nước đi tốt nhất
    let bestScore = -Infinity;
    let bestMove = null;

    for (let cell of cells) {
        if (cell.textContent === '') {
            let idx = cell.dataset.index;
            let attackScore = evaluateMove(idx, 'O');
            let defenseScore = evaluateMove(idx, 'X');
            let totalScore = attackScore + defenseScore * 0.9;

            if (totalScore > bestScore) {
                bestScore = totalScore;
                bestMove = cell;
            }
        }
    }

    if (bestMove) {
        bestMove.textContent = 'O';
        if (checkWinner('O')) {
            status.textContent = "Máy thắng!";
            gameOver = true;
            return;
        }
        isPlayerTurn = true;
        status.textContent = "Lượt của bạn (X)";
    } else {
        // Hòa
        status.textContent = "Hòa!";
        gameOver = true;
    }
}

function resetGame() {
    createBoard();
}

createBoard();
