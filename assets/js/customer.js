// Khi người dùng click vào "Giỏ hàng", hiển thị thông báo nếu có sản phẩm trong giỏ
document.addEventListener("DOMContentLoaded", function () {
  const cartButton = document.querySelector('.bottom-nav a[href="cart.php"]');
  const cartCount = document.querySelector(".cart-count");

  // Kiểm tra xem có sản phẩm trong giỏ không
  if (cartCount && parseInt(cartCount.innerText) > 0) {
    // Thêm thông báo vào biểu tượng giỏ hàng nếu có sản phẩm
    cartButton.classList.add("has-items");
  }

  // Hiệu ứng hover cho các nút trong menu
  const featureLinks = document.querySelectorAll(".feature");
  featureLinks.forEach((link) => {
    link.addEventListener("mouseover", function () {
      link.classList.add("highlight");
    });
    link.addEventListener("mouseout", function () {
      link.classList.remove("highlight");
    });
  });

  // Hiệu ứng tìm kiếm
  const searchButton = document.querySelector(".search-button");
  if (searchButton) {
    searchButton.addEventListener("click", function (event) {
      event.preventDefault();
      alert("Tính năng tìm kiếm sắp ra mắt!");
    });
  }
});

// Thêm hiệu ứng highlight cho giỏ hàng khi có sản phẩm
document.addEventListener("DOMContentLoaded", function () {
  const cartButton = document.querySelector('.bottom-nav a[href="cart.php"]');
  const cartCount = document.querySelector(".cart-count");

  if (cartCount && parseInt(cartCount.textContent) > 0) {
    cartButton.classList.add("highlight");
  }
});
