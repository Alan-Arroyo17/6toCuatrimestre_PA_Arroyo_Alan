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
                $stmt = $conn->prepare("SELECT nombre, posicion, valor_mercado, media_global FROM jugadores WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if ($resultado->num_rows > 0) {
                    http_response_code(200);
                    echo json_encode($resultado->fetch_assoc());
                } else {
                    http_response_code(404);
                    echo json_encode(array("mensaje" => "Jugador no encontrado"));
                }
                $stmt->close();
            }elseif(isset($_GET['nombre'])){
                $nombre = trim($_GET['nombre']);
                $nombre_param = "%$nombre%";
                $stmt = $conn->prepare("SELECT id, nombre, posicion, valor_mercado, media_global FROM jugadores WHERE nombre LIKE ? ORDER BY id");
                $stmt->bind_param("s", $nombre_param);
                $stmt->execute();

                $resultado = $stmt->get_result();
                $jugadores = array();

                while ($row = $resultado->fetch_assoc()) {
                    $jugadores[] = $row;
                }

                http_response_code(200);
                echo json_encode($jugadores);
                $stmt->close();
            }elseif(isset($_GET['posicion'])){
                $posicion = trim($_GET['posicion']);
                $posicion_param = "%$posicion%";
                $stmt = $conn->prepare("SELECT id, nombre, posicion, valor_mercado, media_global FROM jugadores WHERE posicion LIKE ? ORDER BY id");
                $stmt->bind_param("s", $posicion_param);
                $stmt->execute();

                $resultado = $stmt->get_result();
                $jugadores = array();

                while ($row = $resultado->fetch_assoc()) {
                    $jugadores[] = $row;
                }

                http_response_code(200);
                echo json_encode($jugadores);
                $stmt->close();
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);

            if (isset($data['nombre']) && isset($data['posicion']) && isset($data['valor_mercado']) && isset($data['media_global']) && isset($data['equipo_id'])){
                $nombre = trim($data['nombre']);
                $posicion = trim($data['posicion']);
                $valor_mercado = $data['valor_mercado'];
                $media_global = $data['media_global'];
                $equipo_id = $data['equipo_id'];

                $stmt = $conn->prepare("INSERT INTO jugadores(nombre, posicion, valor_mercado, media_global, equipo_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdii", $nombre, $posicion, $valor_mercado, $media_global, $equipo_id);

                if ($stmt->execute()) {
                    http_response_code(201);
                    echo json_encode(array(
                        "mensaje" => "Jugador creado exitosamente",
                        "id" => $stmt->insert_id
                    ));
                } else {
                    http_response_code(500);
                    echo json_encode(array(
                        "mensaje" => "Error al crear el jugador"
                    ));
                }
                $stmt->close();
            } else {
                http_response_code(400);
                echo json_encode(array(
                    "mensaje" => "Datos incompletos para crear el jugador"
                ));
            }
            $conn->close();
            break;
        case 'PUT':
            if(isset($_GET['id'])){
                $id = intval($_GET['id']);
                $data = json_decode(file_get_contents("php://input"), true);
                if (isset($data['id']) && isset($data['nombre']) && isset($data['posicion']) && isset($data['valor_mercado']) && isset($data['media_global']) && isset($data['equipo_id'])){
                    $id = intval($data['id']);
                    $nombre = trim($data['nombre']);
                    $posicion = trim($data['posicion']);
                    $valor_mercado = $data['valor_mercado'];
                    $media_global = $data['media_global'];
                    $equipo_id = $data['equipo_id'];

                    $stmt = $conn->prepare("UPDATE jugadores SET nombre=?, posicion=?, valor_mercado=?, media_global=?, equipo_id=? WHERE id=?");
                    $stmt->bind_param("ssdiii", $nombre, $posicion, $valor_mercado, $media_global, $equipo_id, $id);
                    if ($stmt->execute()) {
                        http_response_code(200);
                        echo json_encode(array("mensaje" => "Jugador actualizado exitosamente"));
                    } else {
                        http_response_code(500);
                        echo json_encode(array("mensaje" => "Error al actualizar el jugador"));
                    }
                    $stmt->close();
                }else{
                    http_response_code(400);
                    echo json_encode(array("mensaje" => "Datos incompletos"));
                }
                $conn->close();
            }
            break;
        case 'DELETE':
            if(isset($_GET['id'])){
                $id = intval($_GET['id']);
                $stmt = $conn->prepare("DELETE FROM jugadores WHERE id = ?");
                $stmt->bind_param("i", $id);
                if($stmt->execute()){
                    if($stmt->affected_rows > 0){
                        http_response_code(200);
                        echo json_encode(array("mensaje" => "Jugador eliminado exitosamente"));
                    }else{
                        http_response_code(404);
                        echo json_encode(array("mensaje" => "Jugador no encontrado"));
                    }
                }else{
                    http_response_code(500);
                    echo json_encode(array("mensaje" => "Error al eliminar el jugador"));
                }
                $stmt->close();
            }else{
                http_response_code(400);
                echo json_encode(array("mensaje" => "ID del jugador no proporcionado"));
            }
            $conn->close();
            break;
        default:
            http_response_code(405);
            echo json_encode(array("mensaje" => "Metodo no permitido"));
            $conn->close();
        break;
    }
?>