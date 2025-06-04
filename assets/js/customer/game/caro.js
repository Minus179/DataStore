let caroBoard = Array(25).fill("");
let caroGameOver = false;

function startCaroGame() {
  const board = document.getElementById("caroBoard");
  board.innerHTML = "";
  caroBoard = Array(25).fill("");
  caroGameOver = false;
  for (let i = 0; i < 25; i++) {
    const cell = document.createElement("div");
    cell.className = "caro-cell";
    cell.dataset.index = i;
    cell.onclick = () => makeCaroMove(i);
    board.appendChild(cell);
  }
}

function makeCaroMove(index) {
  if (caroBoard[index] !== "" || caroGameOver) return;
  caroBoard[index] = "X";
  document.querySelector(`[data-index="${index}"]`).innerText = "X";
  if (checkCaroWin("X")) {
    document.getElementById("caroStatus").innerText = "Bạn thắng!";
    caroGameOver = true;
    return;
  }
  if (!caroBoard.includes("")) {
    document.getElementById("caroStatus").innerText = "Hòa!";
    caroGameOver = true;
    return;
  }
  let aiMove = Math.floor(Math.random() * 25);
  while (caroBoard[aiMove] !== "") {
    aiMove = Math.floor(Math.random() * 25);
  }
  caroBoard[aiMove] = "O";
  document.querySelector(`[data-index="${aiMove}"]`).innerText = "O";
  if (checkCaroWin("O")) {
    document.getElementById("caroStatus").innerText = "Máy thắng!";
    caroGameOver = true;
    return;
  }
  if (!caroBoard.includes("")) {
    document.getElementById("caroStatus").innerText = "Hòa!";
    caroGameOver = true;
    return;
  }
  document.getElementById("caroStatus").innerText = "Lượt của bạn (X)";
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

function checkDirection(startIndex, dx, dy, size) {
  let count = 0;
  let x = Math.floor(startIndex / size);
  let y = startIndex % size;

  for (let step = 0; step < 3; step++) {
    let nx = x + dx * step;
    let ny = y + dy * step;
    let idx = nx * size + ny;
    if (
      nx >= 0 &&
      nx < size &&
      ny >= 0 &&
      ny < size &&
      caroBoard[idx] === caroBoard[startIndex]
    ) {
      count++;
    } else break;
  }
  return count === 3;
}
