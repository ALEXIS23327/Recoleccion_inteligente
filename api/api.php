<?php
// api/api.php
header('Content-Type: application/json');

// Configuración de la base de datos
$host = "localhost";
$db   = "gestion_basura";
$user = "tu_usuario";       // Reemplaza por tu usuario de base de datos
$pass = "tu_contraseña";    // Reemplaza por tu contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["error" => "Conexión a la base de datos fallida"]);
    exit;
}

// Endpoint para obtener los sensores
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'getSensors') {
    $stmt = $pdo->prepare("SELECT * FROM Sensores");
    $stmt->execute();
    $sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($sensors);
    exit;
}

// Endpoint para agregar una medición
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'addMeasurement') {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['id_sensor'], $input['nivel_residuos'])) {
        echo json_encode(["error" => "Datos insuficientes"]);
        exit;
    }
    $id_sensor = $input['id_sensor'];
    $nivel_residuos = $input['nivel_residuos'];
    $temperatura = isset($input['temperatura']) ? $input['temperatura'] : null;
    $fecha_medicion = date("Y-m-d H:i:s");
    
    $stmt = $pdo->prepare("INSERT INTO Mediciones (id_sensor, nivel_residuos, temperatura, fecha_medicion) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$id_sensor, $nivel_residuos, $temperatura, $fecha_medicion])) {
        echo json_encode(["success" => true, "message" => "Medición agregada correctamente"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al agregar la medición"]);
    }
    exit;
}

// Aquí puedes agregar más endpoints para manejar rutas, asignaciones, alertas, etc.

echo json_encode(["error" => "Acción no válida"]);
?>
