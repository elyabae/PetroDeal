document.addEventListener('DOMContentLoaded', function() {
    loadData();
});

function loadData() {
    fetch('getdata.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка сети');
            }
            return response.json();
        })
        .then(data => {
            displayData(data);
        })
        .catch(error => {
            console.error('Ошибка при загрузке данных:', error);
            alert('Произошла ошибка при загрузке данных');
        });
}

function displayData(data) {
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = ''; // Очищаем таблицу перед добавлением новых данных

    data.forEach(item => {
        const row = document.createElement('tr');

        // Создаем ячейки для каждого поля данных
        const pipelineCell = document.createElement('td');
        pipelineCell.textContent = item.pipeline || 'Нет данных';

        const dateCell = document.createElement('td');
        dateCell.textContent = item.date || 'Нет данных';

        const volumeCell = document.createElement('td');
        volumeCell.textContent = item.volume ? formatNumber(item.volume) : 'Нет данных';

        const seasonCell = document.createElement('td');
        seasonCell.textContent = item.season || 'Нет данных';

        const energyCell = document.createElement('td');
        energyCell.textContent = item.energy_cost ? formatNumber(item.energy_cost) : 'Нет данных';

        const maintenanceCell = document.createElement('td');
        maintenanceCell.textContent = item.maintenance_cost ? formatNumber(item.maintenance_cost) : 'Нет данных';

        // Добавляем ячейку с кнопкой удаления
        const deleteCell = document.createElement('td');
        const deleteButton = document.createElement('button');
        deleteButton.textContent = '❌ Удалить';
        deleteButton.classList.add('delete-btn');
        deleteButton.onclick = () => deleteRecord(item.id); // Передаем ID записи
        deleteCell.appendChild(deleteButton);

        row.appendChild(pipelineCell);
        row.appendChild(dateCell);
        row.appendChild(volumeCell);
        row.appendChild(seasonCell);
        row.appendChild(energyCell);
        row.appendChild(maintenanceCell);
        row.appendChild(deleteCell);

        tableBody.appendChild(row);
    });
}

function deleteRecord(id) {
    if (!confirm('Вы уверены, что хотите удалить эту запись?')) return;

    fetch('delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id }),
    })
        .then(response => {
            if (!response.ok) throw new Error('Ошибка удаления');
            return response.json();
        })
        .then(data => {
            alert(data.message || 'Запись удалена!');
            loadData(); // Перезагружаем таблицу
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Не удалось удалить запись');
        });
}

function formatNumber(num) {
    return Number(num).toLocaleString('ru-RU');
}