<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Метод не поддерживается']);
    exit;
}

// Получаем ID из тела запроса
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Не указан ID записи']);
    exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'oil_transport_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка подключения к БД: ' . $conn->connect_error]);
    exit;
}

$id = (int)$input['id'];
$sql = "DELETE FROM transport_data WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка подготовки запроса: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['message' => 'Запись успешно удалена']);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Запись не найдена']);
}

$stmt->close();
$conn->close();
?>