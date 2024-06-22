function loadChatPage() {
    fetch('listechat1.php')
        .then(response => response.text())
        .then(data => {
            const chatPopup = document.getElementById('chat-popup');
            chatPopup.innerHTML = data;
            chatPopup.style.display = 'block';
        });
}

module.exports = loadChatPage;
