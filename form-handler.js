document.getElementById('submitBtn').addEventListener('click', async function() {
    if (!confirm('Вы уверены, что хотите добавить данные?')) return;

    const form = document.getElementById('costForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('submit.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
            showNotification(result.message, 'success');
            form.reset();
        } else {
            showNotification(result.message, 'error');
        }

    } catch (error) {
        showNotification('Ошибка соединения: ' + error, 'error');
    }
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}