body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    padding: 20px;
    background: #f8f9fa;
}

.order-list {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
}

.order-card {
    width: 320px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.2s;
}

.order-card:hover {
    transform: scale(1.02);
}

.thumb {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.info {
    padding: 15px;
}

.popup {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99;
}

.popup.hidden {
    display: none;
}

.popup-content {
    background: white;
    padding: 25px;
    border-radius: 10px;
    width: 400px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 10px; right: 15px;
    font-size: 24px;
    cursor: pointer;
}

.help-button {
    display: inline-block;
    margin-top: 15px;
    text-decoration: none;
    background: #007bff;
    color: white;
    padding: 10px 18px;
    border-radius: 6px;
    float: right;
}
