<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Theo dõi vị trí shipper</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <button class="back-btn" onclick="history.back()">⬅️ Quay lại</button>

    <main>
        <h2>Theo dõi vị trí shipper</h2>
        <p>Giờ bạn có thể theo dõi vị trí của shipper trong thời gian thực!</p>

        <!-- Dùng Google Maps hoặc API bản đồ để hiển thị vị trí của shipper -->
        <div id="map" style="height: 400px; width: 100%;"></div>

        <script>
            function initMap() {
                var shipperLocation = { lat: 10.8231, lng: 106.6297 }; // Tọa độ mẫu, thay thế bằng tọa độ thực tế của shipper

                var map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 14,
                    center: shipperLocation,
                });

                var marker = new google.maps.Marker({
                    position: shipperLocation,
                    map: map,
                    title: "Vị trí của shipper",
                });
            }

            // Load Google Maps API
            var script = document.createElement('script');
            script.src = "https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap";
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        </script>
    </main>
</body>
</html>
