document.addEventListener("DOMContentLoaded", () => {
  const tabButtons = document.querySelectorAll(".tab-button");
  const tabContents = document.querySelectorAll(".tab-content");

  // Toast thông báo
  let toastTimeout;
  window.showToast = function (msg = "Xóa món thành công!") {
    let toast = document.getElementById("toast");
    if (!toast) {
      toast = document.createElement("div");
      toast.id = "toast";
      toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #4BB543;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        font-weight: 600;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 9999;
      `;
      document.body.appendChild(toast);
    }
    clearTimeout(toastTimeout);
    toast.textContent = msg;
    toast.style.opacity = "1";
    toastTimeout = setTimeout(() => {
      toast.style.opacity = "0";
    }, 3000);
  };

  // Kích hoạt tab theo index
  function activateTab(index = 0) {
    tabButtons.forEach((btn, i) => {
      btn.classList.toggle("active", i === index);
      tabContents[i].style.display = i === index ? "block" : "none";
    });
  }

  // Khởi tạo tab ban đầu
  const activeIndex = [...tabButtons].findIndex((btn) =>
    btn.classList.contains("active")
  );
  activateTab(activeIndex >= 0 ? activeIndex : 0);

  // Gắn sự kiện click tab
  tabButtons.forEach((btn, i) => {
    btn.addEventListener("click", () => activateTab(i));
  });

  window.activateTab = activateTab; // để file khác có thể dùng
});
