document.addEventListener("DOMContentLoaded", () => {
  const suaTab = document.getElementById("sua");
  const xoaTab = document.getElementById("xoa");

  let editForm = document.getElementById("edit-form");
  if (!editForm) {
    editForm = document.createElement("form");
    editForm.id = "edit-form";
    editForm.classList.add("form-section");
    editForm.method = "post";
    editForm.action = "edit/sua.php";
    editForm.enctype = "multipart/form-data";
    editForm.style.marginTop = "20px";
    suaTab.appendChild(editForm);
  }

  let currentEditingCard = null;

  // === SỬA MÓN ===
  suaTab.addEventListener("click", (e) => {
    const link = e.target.closest(".edit-link");

    if (!link) {
      console.log("Không tìm thấy .edit-link");
      return;
    }
    e.preventDefault();

    const selectedCard = link.closest(".menu-card");
    if (!selectedCard) {
      console.log("Không tìm thấy .menu-card");
      return;
    }

    currentEditingCard = selectedCard;

    suaTab.querySelectorAll(".menu-card").forEach((card) => {
      card.style.display = card === selectedCard ? "block" : "none";
      card.style.transform = "";
      card.style.transition = "";
    });

    selectedCard.style.transition = "transform 0.3s ease";
    selectedCard.style.transform = "translateX(-300px)";

    // === LẤY DỮ LIỆU ===
    const itemId = link.getAttribute("href")?.split("id=")[1] || "";
    const itemName = selectedCard.querySelector("h4")?.textContent.trim() || "";
    const itemPrice =
      selectedCard.querySelector("p")?.textContent.replace(/[₫.,\s]/g, "") ||
      "0";
    const itemType = selectedCard.dataset.type || "food";
    const itemDescription = selectedCard.dataset.description || "";
    const itemAvailable = selectedCard.dataset.available || "1";

    // === GÁN HTML VÀO FORM ===
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
      <textarea name="description" rows="3" required>${itemDescription}</textarea>
      <label>Còn hàng không?</label>
      <select name="available" required>
          <option value="1" ${
            itemAvailable === "1" ? "selected" : ""
          }>Còn hàng</option>
          <option value="0" ${
            itemAvailable === "0" ? "selected" : ""
          }>Hết hàng</option>
      </select>
      <label>Ảnh chính:</label>
      <input type="file" name="image_main" accept="image/*" />
      <label>Ảnh mô tả 1:</label>
      <input type="file" name="image_desc1" accept="image/*" />
      <label>Ảnh mô tả 2:</label>
      <input type="file" name="image_desc2" accept="image/*" />
      <div style="margin-top: 10px;">
        <button type="submit">Lưu thay đổi</button>
        <button type="button" id="cancel-edit">Hủy</button>
      </div>
    `;

    // === TỰ CUỘN TỚI FORM ===
    editForm.scrollIntoView({ behavior: "smooth" });
  });

  // === XÁC NHẬN LƯU SỬA ===
  editForm.addEventListener("submit", (ev) => {
    if (!confirm("Bạn có chắc chắn muốn lưu thay đổi cho món này?")) {
      ev.preventDefault();
    }
  });

  // === HỦY SỬA ===
  editForm.addEventListener("click", (ev) => {
    if (ev.target.id === "cancel-edit") {
      suaTab.querySelectorAll(".menu-card").forEach((card) => {
        card.style.display = "";
        card.style.transform = "";
        card.style.transition = "";
      });
      editForm.innerHTML = "";
      currentEditingCard = null;
    }
  });

  // === XÓA MÓN ===
  xoaTab.addEventListener("click", (e) => {
    const btn = e.target.closest(".delete-btn");
    if (!btn) return;

    const card = btn.closest(".menu-card");
    const id = card?.dataset.id;
    if (!id) return;

    if (confirm("Bạn có chắc chắn muốn xóa món này?")) {
      fetch(`edit/xoa.php?id=${id}`, { method: "POST" })
        .then((res) => res.text())
        .then((data) => {
          if (data.trim() === "success") {
            showToast();
            card.style.transition = "opacity 0.3s ease";
            card.style.opacity = "0";
            setTimeout(() => card.remove(), 300);
          } else {
            alert("Xóa món thất bại: " + data);
          }
        })
        .catch(() => alert("Lỗi kết nối khi xóa món"));
    }
  });
});
