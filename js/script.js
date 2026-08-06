// TeamUpp - basit chat AJAX yenileme + gonderme
(function () {
    const form = document.getElementById('chat-form');
    const box = document.getElementById('chat-messages');
    const input = document.getElementById('chat-text');
    if (!form || typeof friendId === 'undefined') return;

    function scrollBottom() {
        box.scrollTop = box.scrollHeight;
    }
    scrollBottom();

    function refresh() {
        fetch('messages_partial.php?id=' + friendId)
            .then(r => r.text())
            .then(html => {
                const nearBottom = box.scrollHeight - box.scrollTop - box.clientHeight < 60;
                box.innerHTML = html;
                if (nearBottom) scrollBottom();
            });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;
        const data = new URLSearchParams();
        data.append('text', text);
        data.append('ajax', '1');
        fetch('chat.php?id=' + friendId, { method: 'POST', body: data })
            .then(() => {
                input.value = '';
                refresh();
            });
    });

    // Her 3 saniyede bir yeni mesaj kontrolu
    setInterval(refresh, 3000);
})();
