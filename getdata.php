<?php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'oil_transport_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => "Ошибка подключения к базе данных: " . $conn->connect_error]);
    exit;
}

$conn->set_charset("utf8");

$sql = "SELECT id, pipeline, date, volume, season, energy_cost, maintenance_cost 
        FROM transport_data 
        ORDER BY date DESC, id DESC";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => "Ошибка выполнения запроса: " . $conn->error]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    if ($row['volume'] !== null) $row['volume'] = (float)$row['volume'];
    if ($row['energy_cost'] !== null) $row['energy_cost'] = (float)$row['energy_cost'];
    if ($row['maintenance_cost'] !== null) $row['maintenance_cost'] = (float)$row['maintenance_cost'];

    $data[] = $row;
}

$conn->close();

echo json_encode($data, JSON_UNESCAPED_UNICODE);
exit;
?>