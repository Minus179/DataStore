<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Liên hệ hỗ trợ - ShopeeFood style</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff;
            margin: 0;
            padding: 20px 20px 40px 20px;
            color: #424242;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
            position: relative;
        }

        /* Nút quay lại */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            position: fixed;
            top: 15px;
            left: 15px;
            background-color: #388e85;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 3px 6px rgb(56 142 133 / 0.4);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            z-index: 1000;
            font-size: 15px;
            user-select: none;
        }
        .back-btn:hover {
            background-color: #2f6f69;
            box-shadow: 0 5px 10px rgb(47 111 105 / 0.6);
        }

        .back-btn svg {
            fill: white;
            width: 16px;
            height: 16px;
            user-select: none;
        }

        h1, h2 {
            color: #ff5722;
            margin-bottom: 12px;
            user-select: none;
        }

        h1 {
            border-bottom: 3px solid #ff5722;
            padding-bottom: 6px;
            font-weight: 700;
            font-size: 28px;
            margin-top: 0;
            text-align: center;
        }

        h2 {
            font-size: 22px;
            margin-top: 30px;
        }

        p {
            margin: 8px 0 15px 0;
            font-size: 16px;
        }

        ul {
            padding-left: 20px;
            margin: 8px 0 15px 0;
        }

        li {
            margin-bottom: 8px;
            font-weight: 600;
        }

        .note {
            background: #fff4e5;
            border-left: 5px solid #ff5722;
            padding: 12px 16px;
            margin: 20px 0;
            border-radius: 8px;
            font-style: italic;
            color: #bf360c;
        }

        a.email-link {
            color: #ff5722;
            font-weight: 700;
            text-decoration: none;
        }
        a.email-link:hover {
            text-decoration: underline;
        }

        .office {
            background: #f9f9f9;
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 10px;
            box-shadow: 0 1px 6px rgba(255,87,34,0.15);
        }

        /* Responsive */
        @media (max-width: 480px) {
            body {
                padding: 15px 10px;
                font-size: 14px;
            }
            h1 {
                font-size: 24px;
            }
            h2 {
                font-size: 18px;
            }
            .back-btn {
                font-size: 13px;
                padding: 6px 10px;
                top: 10px;
                left: 10px;
            }
            .back-btn svg {
                width: 14px;
                height: 14px;
            }
        }
    </style>
</head>
<body>

    <a href="../../dashboard/customer/home.php" class="back-btn" title="Quay lại trang trước">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Quay lại
    </a>

    <h1>📞 Tổng đài hỗ trợ ShopeeFood</h1>
    <p><strong>Số điện thoại:</strong> <span style="color:#ff5722; font-weight: 700;">085 755 1919</span></p>
    <p><strong>Thời gian hoạt động:</strong> 24/7</p>
    <p><strong>Cước phí:</strong> 1.000 VNĐ/phút</p>

    <h2>Phím lựa chọn:</h2>
    <ul>
        <li>Phím 1: Hỗ trợ khách hàng (người mua)</li>
        <li>Phím 2: Hỗ trợ nhà hàng (merchant)</li>
        <li>Phím 3: Hỗ trợ tài xế ShopeeFood</li>
    </ul>

    <div class="note">
        Lưu ý: Tổng đài này hoạt động tự động, bạn sẽ không gặp trực tiếp nhân viên hỗ trợ.
    </div>

    <h2>📧 Email hỗ trợ</h2>
    <p>Địa chỉ email: <a href="mailto:volengocson19@gmail.com" class="email-link">volengocson19@gmail.com</a></p>
    <p>Phương thức này phù hợp để giải quyết các vấn đề không quá gấp hoặc cần thời gian xử lý lâu hơn.</p>

    <h2>🧑‍💻 Trung tâm trợ giúp trực tuyến</h2>
    <p>Website: <a href="https://help.cs.shopeefood.vn" target="_blank" class="email-link">help.cs.shopeefood.vn</a></p>
    <p>Tại đây, bạn có thể gửi báo cáo hoặc yêu cầu hỗ trợ liên quan đến đơn hàng. Để sử dụng, hãy đăng nhập vào ứng dụng Shopee, chọn mục ShopeeFood, sau đó vào "Đơn hàng" và chọn đơn hàng cần hỗ trợ. Tiếp theo, chọn "Trung tâm trợ giúp" để gửi yêu cầu.</p>

    <h2>🏢 Văn phòng DataStore Food</h2>
    <div class="office">
        <p><strong>Quy Nhơn :</strong> Tầng 3, tòa nhà A3, 170 An Dương Vương, Quy Nhơn, Bình Định, Việt Nam.</p>
    </div>

    <div class="note">
        Đây là các địa điểm bạn có thể đến trực tiếp để trình bày vấn đề và nhận hỗ trợ.
    </div>

</body>
</html>
