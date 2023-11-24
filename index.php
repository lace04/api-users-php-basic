<?php

require './Config/Database.php';

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
// print_r($method);
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
$searchId = explode('/', $path);
$id = ($path !== '/') ? end($searchId) : null;

$uri = $_SERVER['REQUEST_URI'];

/*
$database = new Database();
$db = $database->getConnection();
if ($db) {
  echo 'Connected';
} else {
  echo 'Not connected';
}*/

switch ($method) {
  case 'GET':
    if (isset($id)) {
      getUserById($id);
    } else {
      getAllUsers();
    }
    break;
  case 'POST':
    createUser();
    break;
  case 'PUT':
    $idToUpdate = $id;
    updateUser($idToUpdate);
    break;
  case 'DELETE':
    deleteUser($id);
    break;
  default:
    echo "Método no soportado";
    break;
}

function getAllUsers()
{
  // conexion a la base de datos
  $database = new Database();
  $db = $database->getConnection();
  // consulta a la base de datos
  $sql = 'SELECT * FROM users';
  // preparar la consulta
  $stmt = $db->prepare($sql);
  // ejecutar la consulta
  $stmt->execute();
  // obtener los resultados
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // retornar los resultados
  echo json_encode($users);
}

function getUserById($id)
{
  // conexion a la base de datos
  $database = new Database();
  $db = $database->getConnection();
  // consulta a la base de datos
  $sql = 'SELECT * FROM users WHERE id = :id';
  // preparar la consulta
  $stmt = $db->prepare($sql);
  // ejecutar la consulta
  $stmt->execute(['id' => $id]);
  // obtener los resultados
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  // retornar los resultados
  echo json_encode($user);
}


// Estrutura de la tabla users
// id
// name


function createUser()
{
  // conexion a la base de datos
  $database = new Database();
  $db = $database->getConnection();
  //Recibir los datos
  $data = json_decode(file_get_contents('php://input'), true);
  $name = $data['name'];
  // consulta a la base de datos
  $sql = 'INSERT INTO users (name) VALUES (:name)';
  // preparar la consulta
  $stmt = $db->prepare($sql);
  // ejecutar la consulta
  if ($stmt->execute(['name' => $name])) {
    $data['id'] = $db->lastInsertId();
    echo json_encode($data);
  } else {
    echo json_encode(['error' => 'No se pudo crear el usuario']);
  }
}

function deleteUser($id)
{
  // conexion a la base de datos
  $database = new Database();
  $db = $database->getConnection();
  // consulta a la base de datos para verificar si el usuario existe
  $checkSql = 'SELECT * FROM users WHERE id = :id';
  // preparar la consulta
  $checkStmt = $db->prepare($checkSql);
  // ejecutar la consulta
  $checkStmt->execute(['id' => $id]);
  // obtener los resultados
  $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
  // si el usuario no existe, retornar un mensaje de error
  if (!$user) {
    echo json_encode(['error' => 'El usuario no existe']);
    return;
  }
  // consulta a la base de datos para eliminar el usuario
  $sql = 'DELETE FROM users WHERE id = :id';
  // preparar la consulta
  $stmt = $db->prepare($sql);
  // ejecutar la consulta
  try {
    if ($stmt->execute(['id' => $id])) {
      echo json_encode(['message' => 'Usuario eliminado']);
    } else {
      echo json_encode(['error' => 'No se pudo eliminar el usuario']);
    }
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
}

function updateUser($id)
{
  // conexión a la base de datos
  $database = new Database();
  $db = $database->getConnection();

  // recibir los datos actualizados del usuario
  $data = json_decode(file_get_contents('php://input'), true);
  $name = $data['name']; // suponiendo que solo actualizas el nombre

  // verificar si el usuario existe
  $checkSql = 'SELECT * FROM users WHERE id = :id';
  $checkStmt = $db->prepare($checkSql);
  $checkStmt->execute(['id' => $id]);
  $user = $checkStmt->fetch(PDO::FETCH_ASSOC);

  // si el usuario no existe, retornar un mensaje de error
  if (!$user) {
    echo json_encode(['error' => 'El usuario no existe']);
    return;
  }

  // consulta para actualizar el nombre del usuario
  $updateSql = 'UPDATE users SET name = :name WHERE id = :id';
  $updateStmt = $db->prepare($updateSql);

  try {
    // ejecutar la actualización
    if ($updateStmt->execute(['name' => $name, 'id' => $id])) {
      echo json_encode(['message' => 'Usuario actualizado']);
    } else {
      echo json_encode(['error' => 'No se pudo actualizar el usuario']);
    }
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
}
