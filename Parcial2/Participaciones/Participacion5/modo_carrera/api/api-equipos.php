<?php
    require_once('../config/connection.php');
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'OPTIONS') {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Preflight OK"));
        $conn->close();
        exit;
    }

    switch($method){
        case 'GET':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $stmt = $conn->prepare("SELECT nombre, liga, presupuesto, creado_en FROM equipos WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if ($resultado->num_rows > 0) {
                    http_response_code(200);
                    echo json_encode($resultado->fetch_assoc());
                } else {
                    http_response_code(404);
                    echo json_encode(array("mensaje" => "Equipo no encontrado"));
                }
                $stmt->close();
            }elseif(isset($_GET['nombre'])){
                $nombre = trim($_GET['nombre']);
                $nombre_param = "%$nombre%";
                $stmt = $conn->prepare("SELECT id, nombre, liga, presupuesto, creado_en FROM equipos WHERE nombre LIKE ? ORDER BY id");
                $stmt->bind_param("s", $nombre_param);
                $stmt->execute();

                $resultado = $stmt->get_result();
                $equipos = array();

                while ($row = $resultado->fetch_assoc()) {
                    $equipos[] = $row;
                }

                http_response_code(200);
                echo json_encode($equipos);
                $stmt->close();
            }elseif(isset($_GET["liga"])){
                $liga = trim($_GET["liga"]);
                $liga_param = "%$liga%";
                $stmt = $conn->prepare("SELECT id, nombre, liga, presupuesto, creado_en FROM equipos WHERE liga LIKE ? ORDER BY id");
                $stmt->bind_param("s", $liga_param);
                $stmt->execute();

                $resultado = $stmt->get_result();
                $equipos = array();

                while ($row = $resultado->fetch_assoc()) {
                    $equipos[] = $row;
                }

                http_response_code(200);
                echo json_encode($equipos);
                $stmt->close();
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);

            if(isset($data['nombre']) && isset($data['liga']) && isset($data['presupuesto'])){
                $nombre = trim($data['nombre']);
                $liga = trim($data['liga']);
                $presupuesto = floatval($data['presupuesto']);

                $stmt = $conn->prepare("INSERT INTO equipos (nombre, liga, presupuesto) VALUES (?, ?, ?)");
                $stmt->bind_param("ssd", $nombre, $liga, $presupuesto);

                if($stmt->execute()){
                    http_response_code(201);
                    echo json_encode(array("mensaje" => "Equipo creado exitosamente"));
                }else{
                    http_response_code(500);
                    echo json_encode(array("mensaje" => "Error al crear el equipo"));
                }
                $stmt->close();
            }
            break;
        case 'DELETE':
            if(isset($_GET['id'])){
                $id = intval($_GET['id']);
                $stmt = $conn->prepare("DELETE FROM equipos WHERE id = ?");
                $stmt->bind_param("i", $id);

                if($stmt->execute()){
                    if($stmt->affected_rows > 0){
                        http_response_code(200);
                        echo json_encode(array("mensaje" => "Equipo eliminado exitosamente"));
                    }else{
                        http_response_code(404);
                        echo json_encode(array(
                            "mensaje" => "Equipo no encontrado"
                        ));
                    }
                }else{
                    http_response_code(500);
                    echo json_encode(array(
                        "mensaje" => "Error al eliminar el equipo"
                    ));
                }
                $stmt->close();
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(array("mensaje" => "Método no permitido"));
            break;
    }
?>