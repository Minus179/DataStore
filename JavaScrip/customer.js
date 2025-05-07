// JavaScript to add interactivity

document.addEventListener("DOMContentLoaded", function () {
  // Banner carousel
  const banners = document.querySelectorAll(".banner img");
  let currentBanner = 0;

  function showNextBanner() {
    banners[currentBanner].style.display = "none";
    currentBanner = (currentBanner + 1) % banners.length;
    banners[currentBanner].style.display = "block";
  }

  setInterval(showNextBanner, 3000);
  banners[currentBanner].style.display = "block";

  // Hover effect for collection cards
  const collectionCards = document.querySelectorAll(".collection-card");

  collectionCards.forEach((card) => {
    card.addEventListener("mouseover", function () {
      card.style.transform = "scale(1.05)";
      card.style.transition = "transform 0.3s ease";
    });

    card.addEventListener("mouseout", function () {
      card.style.transform = "scale(1)";
    });
  });

  // Cart add effect
  const addToCartButtons = document.querySelectorAll(".btn-add-cart");

  addToCartButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const cartCountElement = document.querySelector(".cart-count");
      let currentCount = parseInt(cartCountElement.textContent) || 0;
      cartCountElement.textContent = currentCount + 1;
      button.textContent = "Đã thêm";
      button.disabled = true;
      button.style.backgroundColor = "#ccc";
    });
  });

  // Scroll to top button
  const scrollToTopButton = document.createElement("button");
  scrollToTopButton.textContent = "↑";
  scrollToTopButton.classList.add("scroll-to-top");
  document.body.appendChild(scrollToTopButton);

  scrollToTopButton.addEventListener("click", function () {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });

  window.addEventListener("scroll", function () {
    if (window.scrollY > 300) {
      scrollToTopButton.style.display = "block";
    } else {
      scrollToTopButton.style.display = "none";
    }
  });
});
