const questions = [
  {
    "question": "Tục ngữ Việt Nam có câu: 'Có công mài sắt, có ngày...'",
    "answers": { "A": "Nên kim", "B": "Nên dao", "C": "Nên sắt", "D": "Nên thép" },
    "correct": "A"
  },
  {
    "question": "Truyện dân gian Việt Nam kể về người con gái lấy chồng ở biển là ai?",
    "answers": { "A": "Tấm", "B": "Thị Kính", "C": "Sơn Tinh", "D": "Lọ Lem" },
    "correct": "B"
  },
  {
    "question": "Trong truyện 'Sơn Tinh - Thủy Tinh', Thủy Tinh tượng trưng cho gì?",
    "answers": { "A": "Lửa", "B": "Nước", "C": "Gió", "D": "Đất" },
    "correct": "B"
  },
  {
    "question": "Tục ngữ: 'Ăn quả nhớ kẻ trồng cây' có ý nghĩa gì?",
    "answers": { "A": "Biết ơn người đã giúp mình", "B": "Ăn uống đúng lúc", "C": "Chia sẻ với người khác", "D": "Làm việc chăm chỉ" },
    "correct": "A"
  },
  {
    "question": "Trong truyện dân gian, ai là người đã cứu công chúa khỏi chằn tinh?",
    "answers": { "A": "Thánh Gióng", "B": "Sơn Tinh", "C": "Chử Đồng Tử", "D": "Từ Thức" },
    "correct": "C"
  },
  {
    "question": "Câu ca dao: 'Anh đi anh nhớ quê nhà, nhớ canh rau muống, nhớ cà dầm tương' nói về gì?",
    "answers": { "A": "Tình yêu quê hương", "B": "Tình yêu đôi lứa", "C": "Nỗi nhớ nhà", "D": "Sự ngọt ngào của món ăn" },
    "correct": "C"
  },
  {
    "question": "Ai là nhân vật chính trong truyện 'Thạch Sanh'?",
    "answers": { "A": "Chử Đồng Tử", "B": "Thạch Sanh", "C": "Lý Thông", "D": "Sơn Tinh" },
    "correct": "B"
  },
  {
    "question": "Tục ngữ: 'Nước chảy đá mòn' muốn nói điều gì?",
    "answers": { "A": "Kiên trì sẽ thành công", "B": "Mọi thứ đều thay đổi", "C": "Sức mạnh tự nhiên", "D": "Thời gian là vàng" },
    "correct": "A"
  },
  {
    "question": "Trong truyện 'Sơn Tinh - Thủy Tinh', Sơn Tinh tượng trưng cho gì?",
    "answers": { "A": "Lửa", "B": "Nước", "C": "Đất", "D": "Gió" },
    "correct": "C"
  },
  {
    "question": "Câu ca dao: 'Trâu ơi ta bảo trâu này, trâu ra ngoài ruộng trâu cày với ta' thể hiện điều gì?",
    "answers": { "A": "Tình bạn", "B": "Công việc đồng áng", "C": "Tình cảm giữa người và trâu", "D": "Sự hài hước" },
    "correct": "C"
  },
  {
    "question": "Truyện dân gian 'Tấm Cám' thuộc thể loại nào?",
    "answers": { "A": "Truyền thuyết", "B": "Truyện cổ tích", "C": "Truyện ngụ ngôn", "D": "Truyện lịch sử" },
    "correct": "B"
  },
  {
    "question": "Ai là nhân vật trong truyền thuyết dựng nước đầu tiên của Việt Nam?",
    "answers": { "A": "Lạc Long Quân", "B": "Thánh Gióng", "C": "Mai An Tiêm", "D": "Chử Đồng Tử" },
    "correct": "A"
  },
  {
    "question": "Tục ngữ: 'Chớ thấy sóng cả mà ngã tay chèo' có ý nghĩa gì?",
    "answers": { "A": "Không được bỏ cuộc khi gặp khó khăn", "B": "Đừng tham lam", "C": "Phải cẩn thận trong công việc", "D": "Sống hòa đồng với mọi người" },
    "correct": "A"
  },
  {
    "question": "Ai là người giúp Thánh Gióng đánh giặc ngoại xâm?",
    "answers": { "A": "Sơn Tinh", "B": "Thánh Gióng", "C": "Chử Đồng Tử", "D": "Mai An Tiêm" },
    "correct": "B"
  },
  {
    "question": "Trong truyện dân gian, Mai An Tiêm nổi tiếng với việc gì?",
    "answers": { "A": "Nuôi trâu", "B": "Trồng dưa hấu", "C": "Chiến đấu với giặc", "D": "Tìm kho báu" },
    "correct": "B"
  },
  {
    "question": "Ca dao: 'Gần mực thì đen, gần đèn thì sáng' muốn nói điều gì?",
    "answers": { "A": "Môi trường ảnh hưởng đến con người", "B": "Cần phải chăm chỉ học hành", "C": "Tình bạn thân thiết", "D": "Sự khác biệt giữa ngày và đêm" },
    "correct": "A"
  },
  {
    "question": "Trong truyện dân gian, nhân vật nào nổi tiếng với sự khôn ngoan và mưu trí?",
    "answers": { "A": "Thánh Gióng", "B": "Chử Đồng Tử", "C": "Lý Thông", "D": "Sơn Tinh" },
    "correct": "C"
  },
  {
    "question": "Tục ngữ: 'Đi một ngày đàng, học một sàng khôn' nói về điều gì?",
    "answers": { "A": "Cần đi xa để học hỏi", "B": "Giá trị của kinh nghiệm", "C": "Học quan trọng hơn chơi", "D": "Nên đi du lịch" },
    "correct": "B"
  },
  {
    "question": "Trong truyện 'Chử Đồng Tử', chàng trai đã kết hôn với ai?",
    "answers": { "A": "Công chúa", "B": "Cô thôn nữ", "C": "Mụ phù thủy", "D": "Người láng giềng" },
    "correct": "A"
  },
  {
    "question": "Câu ca dao: 'Con cò bé bé, bé xinh xinh' nói về gì?",
    "answers": { "A": "Chim cò", "B": "Tình mẫu tử", "C": "Sự nhỏ bé, dễ thương", "D": "Cảnh đồng quê" },
    "correct": "C"
  },
  {
    "question": "Trong truyện dân gian, ai là người con gái chờ chồng trong truyền thuyết?",
    "answers": { "A": "Thị Kính", "B": "Mai An Tiêm", "C": "Lọ Lem", "D": "Tấm" },
    "correct": "A"
  },
  {
    "question": "Tục ngữ: 'Một cây làm chẳng nên non, ba cây chụm lại nên hòn núi cao' nhấn mạnh điều gì?",
    "answers": { "A": "Sức mạnh của đoàn kết", "B": "Làm việc cá nhân hiệu quả", "C": "Trồng cây tốt", "D": "Tầm quan trọng của thiên nhiên" },
    "correct": "A"
  },
  {
    "question": "Ai là nhân vật chính trong truyện 'Thánh Gióng'?",
    "answers": { "A": "Sơn Tinh", "B": "Thánh Gióng", "C": "Mai An Tiêm", "D": "Lý Thông" },
    "correct": "B"
  },
  {
    "question": "Câu tục ngữ: 'Trâu ơi đừng ăn lúa vàng' có ý nghĩa gì?",
    "answers": { "A": "Nhắc nhở giữ gìn tài sản", "B": "Cảnh báo nguy hiểm", "C": "Cảm ơn con trâu", "D": "Khen con trâu" },
    "correct": "A"
  },
  {
    "question": "Ai là người làm ra trống đồng Đông Sơn trong truyền thuyết?",
    "answers": { "A": "Người Việt cổ", "B": "Sơn Tinh", "C": "Thánh Gióng", "D": "Chử Đồng Tử" },
    "correct": "A"
  },
  {
    "question": "Tục ngữ: 'Lắm mối tối nằm không' nói về điều gì?",
    "answers": { "A": "Không biết chọn lựa đúng", "B": "Có nhiều mối quan hệ tốt", "C": "Ngủ ngon giấc", "D": "Sự bận rộn" },
    "correct": "A"
  },
  {
    "question": "Truyện 'Sự tích cây vú sữa' giải thích về điều gì?",
    "answers": { "A": "Nguồn gốc tên cây", "B": "Nguồn gốc mặt trăng", "C": "Nguồn gốc sông", "D": "Nguồn gốc núi" },
    "correct": "A"
  },
  {
    "question": "Ca dao: 'Đi đâu cho thiếp theo cùng' thể hiện điều gì?",
    "answers": { "A": "Tình yêu thủy chung", "B": "Sự ghen tuông", "C": "Tình bạn", "D": "Sự dứt khoát" },
    "correct": "A"
  },
  {
    "question": "Ai là nhân vật trong truyện 'Lọ Lem' của Việt Nam?",
    "answers": { "A": "Tấm", "B": "Lọ Lem", "C": "Mai An Tiêm", "D": "Thị Kính" },
    "correct": "A"
  },
  {
    "question": "Tục ngữ: 'Ăn cỗ đi trước, lội nước theo sau' nói về điều gì?",
    "answers": { "A": "Chọn vị trí thuận lợi", "B": "Sự tham lam", "C": "Làm việc hợp lý", "D": "Không nên đi sau người khác" },
    "correct": "A"
  },
  {
    "question": "Truyện 'Tấm Cám' nói về điều gì?",
    "answers": { "A": "Tình chị em và sự bất công", "B": "Chiến tranh", "C": "Mùa màng", "D": "Tình yêu đôi lứa" },
    "correct": "A"
  },
  {
    "question": "Tục ngữ: 'Cây ngay không sợ chết đứng' nói lên điều gì?",
    "answers": { "A": "Người chính trực không sợ gian khó", "B": "Cây cao lớn", "C": "Sự mạnh mẽ của thiên nhiên", "D": "Chú ý chăm sóc cây" },
    "correct": "A"
  },
  {
    "question": "Trong truyền thuyết, ai là người đã hóa thành rồng bay lên trời?",
    "answers": { "A": "Lạc Long Quân", "B": "Thánh Gióng", "C": "Sơn Tinh", "D": "Chử Đồng Tử" },
    "correct": "A"
  },
  {
    "question": "Câu tục ngữ: 'Nước đổ lá khoai' có nghĩa gì?",
    "answers": { "A": "Việc làm vô ích", "B": "Sự hư hỏng", "C": "Tình cảm gia đình", "D": "Sự trôi chảy của nước" },
    "correct": "A"
  },
  {
    "question": "Ca dao: 'Đèn nhà ai nấy sáng' nói về điều gì?",
    "answers": { "A": "Mỗi người lo việc của mình", "B": "Ánh sáng trong nhà", "C": "Sự đoàn kết", "D": "Sự ấm áp" },
    "correct": "A"
  },
  {
    "question": "Truyện dân gian 'Sơn Tinh - Thủy Tinh' thể hiện điều gì?",
    "answers": { "A": "Sự đối đầu giữa thiên nhiên", "B": "Tình yêu đôi lứa", "C": "Sự khôn ngoan", "D": "Sự đoàn kết" },
    "correct": "A"
  },
  {
    "question": "Tục ngữ: 'Có chí thì nên' nói lên điều gì?",
    "answers": { "A": "Chỉ cần có ý chí là thành công", "B": "Sự may mắn", "C": "Cần giúp đỡ người khác", "D": "Chỉ học hành mới thành công" },
    "correct": "A"
  },
  {
    "question": "Ai là nhân vật chính trong truyện 'Chử Đồng Tử'?",
    "answers": { "A": "Chử Đồng Tử", "B": "Mai An Tiêm", "C": "Thánh Gióng", "D": "Lý Thông" },
    "correct": "A"
  },
  {
    "question": "Câu ca dao: 'Tay cày không quên bờ ruộng' nói về điều gì?",
    "answers": { "A": "Luôn nhớ nơi xuất phát", "B": "Chăm chỉ làm ruộng", "C": "Cần sự cẩn thận", "D": "Tình yêu quê hương" },
    "correct": "A"
  },
  {
    "question": "Truyện dân gian Việt Nam thường truyền đạt điều gì?",
    "answers": { "A": "Bài học đạo lý và truyền thống", "B": "Câu chuyện thần thoại", "C": "Sự kiện lịch sử", "D": "Chuyện tình yêu hiện đại" },
    "correct": "A"
  },
  {
    "question": "Tục ngữ: 'Đói cho sạch, rách cho thơm' nhấn mạnh điều gì?",
    "answers": { "A": "Dù nghèo cũng phải giữ đạo đức", "B": "Ăn uống sạch sẽ", "C": "Ăn mặc đẹp", "D": "Không nên ăn vụng" },
    "correct": "A"
  },
  {
    "question": "Ca dao: 'Trăm năm trong cõi người ta, chữ tài chữ mệnh khéo là ghét nhau' nói về gì?",
    "answers": { "A": "Sự trớ trêu của số phận", "B": "Giá trị của chữ tài", "C": "Ý nghĩa của cuộc sống", "D": "Tình bạn chân thành" },
    "correct": "A"
  },
  {
    "question": "Ai là người được xem là con rồng cháu tiên trong truyền thuyết Việt Nam?",
    "answers": { "A": "Hùng Vương", "B": "Lạc Long Quân", "C": "Mai An Tiêm", "D": "Thánh Gióng" },
    "correct": "B"
  },
  {
    "question": "Tục ngữ: 'Chớ thấy sóng cả mà ngã tay chèo' muốn nói điều gì?",
    "answers": { "A": "Không nên bỏ cuộc khi gặp khó khăn", "B": "Cẩn thận khi đi biển", "C": "Phải học cách bơi", "D": "Biển rất nguy hiểm" },
    "correct": "A"
  },
  {
    "question": "Truyện dân gian 'Sự tích trầu cau' giải thích về điều gì?",
    "answers": { "A": "Nguồn gốc món ăn", "B": "Nguồn gốc tập tục", "C": "Nguồn gốc cây trầu cau", "D": "Nguồn gốc mặt trăng" },
    "correct": "C"
  },
  {
    "question": "Câu ca dao: 'Gần mực thì đen, gần đèn thì sáng' nói về điều gì?",
    "answers": { "A": "Môi trường ảnh hưởng đến con người", "B": "Cần phải chăm chỉ học hành", "C": "Tình bạn thân thiết", "D": "Sự khác biệt giữa ngày và đêm" },
    "correct": "A"
  },
  {
    "question": "Tục ngữ: 'Một con ngựa đau, cả tàu bỏ cỏ' nói lên điều gì?",
    "answers": { "A": "Sự đồng cảm và đoàn kết", "B": "Sự yếu đuối", "C": "Không chăm sóc tập thể", "D": "Sự cô đơn" },
    "correct": "A"
  }
]

const moneyLadder = [
  "200.000đ", "400.000đ", "600.000đ", "1.000.000đ", "2.000.000đ",
  "3.000.000đ", "6.000.000đ", "10.000.000đ", "14.000.000đ", "22.000.000đ",
  "30.000.000đ", "40.000.000đ", "60.000.000đ", "85.000.000đ", "150.000.000đ"
];

let currentQuestionIndex = 0;
let correctCount = 0; // số câu đúng hiện tại
let wrongCount = 0;   // số lần trả lời sai

const questionBox = document.getElementById("question");
const answerButtons = document.querySelectorAll(".answer");
const moneyLadderDiv = document.getElementById("money-ladder");

function renderMoneyLadder() {
  moneyLadderDiv.innerHTML = "";
  for(let i = moneyLadder.length - 1; i >= 0; i--) {
    const div = document.createElement("div");
    div.textContent = (i + 1) + ". " + moneyLadder[i];
    if(i === correctCount - 1) {
      div.classList.add("active");
    }
    moneyLadderDiv.appendChild(div);
  }
}

function showQuestion() {
  if (correctCount === 15) {
    // Hoàn thành 15 câu, thắng tuyệt đối, chuyển trang
    alert("Chúc mừng bạn đã vượt qua tất cả các câu hỏi!");
    window.location.href = "mini_game.php";
    return;
  }

  renderMoneyLadder();

  const q = questions[currentQuestionIndex];
  questionBox.textContent = `Câu ${correctCount + 1}: ${q.question}`;

  for(const btn of answerButtons) {
    btn.textContent = btn.id + ". " + q.answers[btn.id];
    btn.classList.remove("correct", "wrong");
    btn.disabled = false;
  }
}

function chooseAnswer(choice) {
  const q = questions[currentQuestionIndex];

  for(const btn of answerButtons) {
    btn.disabled = true;
    if(btn.id === q.correct) btn.classList.add("correct");
    if(btn.id === choice && choice !== q.correct) btn.classList.add("wrong");
  }

  if(choice === q.correct) {
    correctCount++;
    currentQuestionIndex = (currentQuestionIndex + 1) % questions.length;

    if(correctCount === 10) {
      alert("Bạn đã đạt mốc 10 câu đúng! Có thể tiếp tục chơi đến câu 15 để nhận phần thưởng lớn hơn.");
    }

    if(correctCount > 10) {
      // Chơi tiếp từ câu 11 đến 15
      if(correctCount === 15) {
        alert("Chúc mừng bạn đã hoàn thành cả 15 câu hỏi!");
        saveResultAndRedirect('win');
        return;
      }
    }

    setTimeout(showQuestion, 1500);
  } else {
    // Sai
    wrongCount++;

    if(correctCount < 10) {
      // Sai trước mốc 10: tụt mốc về 1 (đặt lại correctCount)
      correctCount = 0;
      currentQuestionIndex = (currentQuestionIndex + 1) % questions.length;
      alert("Sai rồi! Bạn bị tụt mốc về câu số 0.");
    } else {
      // Sai sau mốc 10 thì không tụt mốc
      currentQuestionIndex = (currentQuestionIndex + 1) % questions.length;
      alert(`Sai rồi! Bạn đã sai ${wrongCount} lần.`);
    }

    if(wrongCount >= 3) {
      alert("Bạn đã sai quá 3 lần, trò chơi kết thúc!");
      saveResultAndRedirect('lose');
      return;
    }

    setTimeout(showQuestion, 1500);
  }
}

function saveResultAndRedirect(result) {
  const userId = 1; // Thay bằng user_id thật của bạn

  fetch('save_result.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      user_id: userId,
      game: 'ailatrieuphu',
      result: result,
      score: correctCount
    })
  })
  .then(res => res.json())
  .then(data => {
    console.log("Kết quả lưu server:", data);
    if(!data.success) alert(data.message);
    window.location.href = "mini_game.php";
  })
  .catch(err => {
    console.error("Lỗi khi lưu kết quả:", err);
    window.location.href = "mini_game.php";
  });
}

showQuestion();
// Hàm tráo mảng (Fisher-Yates shuffle)
function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
}

// Tráo thứ tự câu hỏi và tráo thứ tự câu trả lời trong từng câu
function shuffleQuestionsAndAnswers(questions) {
  // Tráo câu hỏi trước
  shuffleArray(questions);

  // Tráo câu trả lời cho từng câu
  questions.forEach(question => {
    // Đưa answers object thành mảng [ [key, text], ... ]
    const entries = Object.entries(question.answers);

    
  });

  return questions;
}

// Sử dụng
const shuffledQuestions = shuffleQuestionsAndAnswers(questions);
console.log(shuffledQuestions);
