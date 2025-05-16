document.addEventListener("DOMContentLoaded", () => {
  // Xử lý chuyển tab nội dung
  const tabButtons = document.querySelectorAll(".tab-button");
  const tabContents = document.querySelectorAll(".tab-content");

  tabButtons.forEach((button) => {
    button.addEventListener("click", () => {
      tabButtons.forEach((btn) => btn.classList.remove("active"));
      tabContents.forEach((content) => (content.style.display = "none"));

      button.classList.add("active");
      const target = button.getAttribute("data-target");
      document.getElementById(target).style.display = "block";
    });
  });

  // Xử lý sửa món - hiển thị form sửa và ẩn card khác
  const editLinks = document.querySelectorAll(".action-link");
  const suaTab = document.getElementById("sua");

  editLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();

      // Lấy card chứa món cần sửa
      const selectedCard = e.target.closest(".menu-card");
      if (!selectedCard) return;

      // Ẩn các card khác
      document.querySelectorAll("#sua .menu-card").forEach((card) => {
        if (card !== selectedCard) card.style.display = "none";
      });

      // Di chuyển card sang một bên (ví dụ sang trái)
      selectedCard.style.transition = "transform 0.3s ease";
      selectedCard.style.transform = "translateX(-300px)";

      // Tạo form sửa món mới hoặc hiển thị form sửa trong div hiện có
      let editForm = document.getElementById("edit-form");
      if (!editForm) {
        editForm = document.createElement("form");
        editForm.id = "edit-form";
        editForm.classList.add("form-section");
        editForm.method = "post";
        editForm.action = "edit/sua.php";
        editForm.enctype = "multipart/form-data";
        suaTab.appendChild(editForm);
      }

      // Lấy dữ liệu món từ thẻ card hoặc dataset
      const itemId = link.getAttribute("href").split("id=")[1];
      const itemName = selectedCard.querySelector("h4").textContent;
      const itemPrice = selectedCard
        .querySelector("p")
        .textContent.replace(/[₫.,]/g, "");
      const itemType = selectedCard.getAttribute("data-type") || "";

      editForm.innerHTML = `
        <h3>Sửa món</h3>
        <input type="hidden" name="id" value="${itemId}" />
        
        <label>Tên món:</label>
        <input type="text" name="name" value="${itemName}" required />

        <label>Giá:</label>
        <input type="number" name="price" value="${itemPrice}" required min="0" />

        <label>Loại:</label>
        <select name="type" required>
            <option value="food" ${
              itemType === "food" ? "selected" : ""
            }>Món ăn</option>
            <option value="drink" ${
              itemType === "drink" ? "selected" : ""
            }>Món nước</option>
        </select>

        <label>Mô tả món:</label>
        <textarea name="description" rows="3" placeholder="Mô tả món ăn..." required></textarea>

        <label>Còn hàng không?</label>
        <select name="available" required>
            <option value="1">Còn hàng</option>
            <option value="0">Hết hàng</option>
        </select>

        <label>Ảnh chính:</label>
        <input type="file" name="image_main" accept="image/*" />

        <label>Ảnh mô tả 1:</label>
        <input type="file" name="image_desc1" accept="image/*" />

        <label>Ảnh mô tả 2:</label>
        <input type="file" name="image_desc2" accept="image/*" />

        <button type="submit">Lưu thay đổi</button>
        <button type="button" id="cancel-edit">Hủy</button>
      `;

      // Nút hủy
      document.getElementById("cancel-edit").addEventListener("click", () => {
        // Hiển thị lại tất cả card
        document.querySelectorAll("#sua .menu-card").forEach((card) => {
          card.style.display = "";
          card.style.transform = "";
        });
        editForm.remove();
      });
    });
  });

  // Bổ sung: Xử lý nút Xóa món (tab xoa)
  document.querySelectorAll("#xoa .delete-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const card = btn.closest(".menu-card");
      const id = card.getAttribute("data-id");
      if (confirm("Bạn có chắc muốn xóa món này?")) {
        // Chuyển hướng tới trang xóa với id truyền qua GET
        window.location.href = `edit/xoa.php?id=${id}`;
      }
    });
  });
});
