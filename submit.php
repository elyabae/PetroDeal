<?php
header('Content-Type: application/json');

// Проверяем, что запрос POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Ошибка: данные должны отправляться через POST-запрос.");
}

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'oil_transport_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

// Проверяем, что все поля переданы
$requiredFields = ['pipelineSection', 'inputDate', 'volume', 'season', 'EnergyCost', 'MaintenanceCost'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        die("Ошибка: не передано поле '$field'.");
    }
}

$pipeline = $_POST['pipelineSection'];
$date = $_POST['inputDate'];
$volume = (float)$_POST['volume'];
$season = $_POST['season'];
$energy = (float)$_POST['EnergyCost'];
$maintenance = (float)$_POST['MaintenanceCost'];

// Проверяем, что дата в корректном формате
if (!strtotime($date)) {
    die("Ошибка: неверный формат даты.");
}

$sql = "INSERT INTO transport_data (pipeline, date, volume, season, energy_cost, maintenance_cost) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Ошибка подготовки запроса: " . $conn->error);
}

$stmt->bind_param("ssdsdd", $pipeline, $date, $volume, $season, $energy, $maintenance);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Запись успешно добавлена!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка при добавлении данных']);
}
?>