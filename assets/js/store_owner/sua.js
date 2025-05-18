document.addEventListener("DOMContentLoaded", () => {
  const suaTab = document.getElementById("sua");
  const title = document.getElementById("edit-title");

  // Tạo hoặc lấy container chứa form chỉnh sửa và card được chọn
  let editContainer = document.getElementById("edit-container");
  if (!editContainer) {
    editContainer = document.createElement("div");
    editContainer.id = "edit-container";
    Object.assign(editContainer.style, {
      display: "none",
      flexWrap: "nowrap",
      gap: "24px",
      justifyContent: "flex-start",
      alignItems: "flex-start",
      marginTop: "40px",
    });
    suaTab.appendChild(editContainer);
  }

  // Tạo hoặc lấy panel info chứa form chỉnh sửa
  let infoPanel = document.getElementById("edit-info-panel");
  if (!infoPanel) {
    infoPanel = document.createElement("div");
    infoPanel.id = "edit-info-panel";
    Object.assign(infoPanel.style, {
      display: "none",
    });
    editContainer.appendChild(infoPanel);
  }

  let currentEditingCard = null;

  // Reset UI về trạng thái ban đầu (hiển thị đầy đủ card, ẩn form)
  function resetEditUI() {
    if (title) title.style.display = "block";
    editContainer.style.display = "none";
    infoPanel.style.display = "none";

    // Show lại tất cả card menu
    suaTab
      .querySelectorAll(".menu-card")
      .forEach((c) => (c.style.display = "block"));

    // Nếu card đang chỉnh sửa đã được di chuyển vào editContainer, trả lại suaTab
    if (currentEditingCard && !suaTab.contains(currentEditingCard)) {
      suaTab.appendChild(currentEditingCard);
      Object.assign(currentEditingCard.style, {
        display: "block",
        transform: "none",
        boxShadow: "none",
        borderRadius: "none",
        maxWidth: "none",
        width: "auto",
        margin: "0",
        transition: "none",
      });
    }

    currentEditingCard = null;
  }

  suaTab.addEventListener("click", (e) => {
    const btn = e.target.closest(".edit-btn");
    if (!btn) return;

    const card = btn.closest(".menu-card");
    if (!card) return;

    currentEditingCard = card;

    // Lấy dữ liệu từ card
    const id = card.dataset.id || "";
    const name = card.querySelector("h4")?.textContent.trim() || "";
    const priceStr =
      card.querySelector("p")?.textContent.replace(/[₫.,\s]/g, "") || "0";
    const price = parseInt(priceStr, 10) || 0;
    const type = card.dataset.type || "food";
    const description = card.dataset.description || "";
    const imageSrc = card.querySelector("img")?.src || "";

    // Ẩn tiêu đề chính nếu có
    if (title) title.style.display = "none";

    // Ẩn tất cả card để chỉ hiển thị card đang chỉnh sửa
    suaTab
      .querySelectorAll(".menu-card")
      .forEach((c) => (c.style.display = "none"));

    // Xóa nội dung cũ của editContainer
    editContainer.innerHTML = "";
    editContainer.style.display = "flex";

    // Style cho card đang edit
    Object.assign(card.style, {
      display: "block",
      transform: "scale(1)",
      boxShadow: "0 2px 8px rgba(0,0,0,0.1)",
      borderRadius: "8px",
      maxWidth: "320px",
      width: "100%",
      margin: "0 20px 0 0",
      transition: "transform 0.3s ease, box-shadow 0.3s ease",
    });

    // Di chuyển card vào editContainer
    editContainer.appendChild(card);

    // Style cho infoPanel (form)
    Object.assign(infoPanel.style, {
      display: "block",
      background: "#fff",
      padding: "24px",
      borderRadius: "12px",
      boxShadow: "0 4px 20px rgba(0,0,0,0.1)",
      flex: "1",
      minWidth: "360px",
      fontFamily: "Segoe UI, sans-serif",
      color: "#333",
    });

    // Tạo form HTML trong infoPanel, pre-fill data từ card
    infoPanel.innerHTML = `
      <h3 style="margin-top: 0; margin-bottom: 15px; text-align:center;">Chỉnh sửa thông tin món</h3>
      ${
        imageSrc
          ? `<img src="${imageSrc}" alt="Hình món" style="width: 250px; height: 200px; object-fit: cover; border-radius: 8px; margin: 0 auto 15px; display: block;">`
          : ""
      }
      <form id="edit-form" enctype="multipart/form-data" style="display: flex; gap: 20px; flex-wrap: wrap;">
        <input type="hidden" name="id" value="${id}">
        <div style="flex: 1 1 45%; display: flex; flex-direction: column; gap: 10px;">
          <label for="name">Tên món:</label>
          <input type="text" name="name" value="${name}" required>
          <label for="price">Giá (VNĐ):</label>
          <input type="number" name="price" value="${price}" required min="0">
          <label for="type">Loại món:</label>
          <select name="type">
            <option value="food" ${
              type === "food" ? "selected" : ""
            }>Món ăn</option>
            <option value="drink" ${
              type === "drink" ? "selected" : ""
            }>Thức uống</option>
          </select>
        </div>
        <div style="flex: 1 1 45%; display: flex; flex-direction: column; gap: 10px;">
          <label for="description">Mô tả:</label>
          <textarea name="description" rows="6">${description}</textarea>
          <label for="image">Hình ảnh mới:</label>
          <input type="file" name="image" accept="image/*">
          <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: auto;">
            <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 5px;">Lưu</button>
            <button type="button" id="cancel-edit" style="background-color: #ccc; border: none; padding: 8px 12px; border-radius: 5px;">Hủy</button>
            <button type="button" id="back-tab" style="background-color: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 5px;">Quay lại</button>
          </div>
        </div>
      </form>
    `;

    const form = document.getElementById("edit-form");
    const cancelBtn = document.getElementById("cancel-edit");
    const backTabBtn = document.getElementById("back-tab");

    // Bỏ đăng ký sự kiện cũ để tránh trùng lặp
    form.onsubmit = null;
    cancelBtn.onclick = null;
    backTabBtn.onclick = null;

    // Xử lý submit form
    form.addEventListener("submit", async (event) => {
      event.preventDefault();

      const submitBtn = form.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.textContent = "Đang lưu...";

      const formData = new FormData(form);

      try {
        const response = await fetch("edit/sua.php", {
          method: "POST",
          body: formData,
        });

        const result = await response.json();

        if (response.ok && result.success) {
          alert("Cập nhật món ăn thành công! 🎉");

          // Cập nhật trực tiếp dữ liệu lên card đang chỉnh sửa
          if (currentEditingCard) {
            currentEditingCard.querySelector("h4").textContent =
              formData.get("name");
            currentEditingCard.querySelector("p").textContent =
              Number(formData.get("price")).toLocaleString("vi-VN") + " ₫";
            currentEditingCard.dataset.type = formData.get("type");
            currentEditingCard.dataset.description =
              formData.get("description");

            if (result.imageUrl) {
              const img = currentEditingCard.querySelector("img");
              if (img) img.src = result.imageUrl;
            }
          }

          // Reset UI về trạng thái ban đầu
          resetEditUI();
        } else {
          alert("Lỗi cập nhật: " + (result.message || "Không xác định"));
        }
      } catch (error) {
        alert("Lỗi kết nối hoặc xử lý: " + error.message);
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = "Lưu";
      }
    });

    // Xử lý nút hủy
    cancelBtn.addEventListener("click", () => {
      resetEditUI();
    });

    // Xử lý nút quay lại
    backTabBtn.addEventListener("click", () => {
      resetEditUI();
    });
  });
});
