document.addEventListener("DOMContentLoaded", () => {
document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll(".category-tabs .tab");
  const menuCards = document.querySelectorAll(".menu-card");

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      tabs.forEach((t) => t.classList.remove("active"));
      tab.classList.add("active");

      const type = tab.getAttribute("data-type");

      menuCards.forEach((card) => {
        if (type === "all") {
          card.style.display = "flex";
        } else {
          card.style.display =
            card.getAttribute("data-type") === type ? "flex" : "none";
        }
      });
    });
  });

  // Xử lý sidebar load trang vào iframe
  const sidebarLinks = document.querySelectorAll(".sidebar a");
  const iframe = document.getElementById("main-frame");

  sidebarLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const page = link.getAttribute("onclick") ? null : link.getAttribute("href");
      if (page && iframe) {
        iframe.src = page;
      }
    });
  });
});
