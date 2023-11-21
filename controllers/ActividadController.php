<?php

require_once "../db/conecction.php";

if (isset($_POST) && isset($_POST['action'])) {
    if ($_POST['action'] == 'registrar'){
        registrarDatos(
            $_POST['nombre'],
            $_POST['apellido'],
            $_POST['instagram'],
            $_POST['seguidores'],
            $_POST['telefono'],
            $_POST['ciudad']
        );
    }
    if ($_POST['action'] == 'actualizar') updateDatos();
    if ($_POST['action'] == 'carga') echo json_encode(["mensaje" => "perraaaa"]);
}


function registrarDatos($nombre,$apellido,$instagram,$seguidores,$telefono,$ciudad)
{
    try {
        //code...
        $conexion = new ConecDB();
        $pdo = $conexion->getConnection();
        $query = "INSERT INTO datos (  nombre,  apellido,  instagram,  seguidores,  telefono,  ciudad  ) VALUES (?, ?, ?, ?, ?, ?);";
        $st = $pdo->prepare($query);
        $result = $st->execute([$nombre,$apellido,$instagram,$seguidores,$telefono,$ciudad]);
        
        $accionExitosa = false;
        if($result) $accionExitosa = true;
        else $accionExitosa = false;
        $conexion->closeConnection();
        
        header("Location: ../views/dashboard/actividad.php?exito=" . ($accionExitosa ? '1' : '0'));
        exit();
    } catch (\Throwable $th) {
        //throw $th;
        $accionExitosa = false;
        header("Location: ../views/dashboard/actividad.php?exito=" . ($accionExitosa ? '1' : '0'));
        exit();
    }

}

function updateDatos()
{
}

function carga()
{
    try {
        $conexion = new ConecDB();
        $pdo = $conexion->getConnection();

        $query = "UPDATE datos set instagram = instagram + 1;";
        $st = $pdo->prepare($query);
        $result = $st->execute();

        if($result){
            $stDatos = $pdo->prepare("SELECT 
                id, 
                nombre,
                apellido,
                instagram,
                seguidores,
                telefono,
                ciudad FROM datos;"
            );
            $stDatos->execute();
            $datos = $stDatos->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($datos);
            
            $conexion->closeConnection();
            
            exit();
        }else{
            header('Content-Type: application/json');
            echo json_encode(['mensaje' => 'Error en la peticion']);
        }

    } catch (\Throwable $th) {
        echo json_encode(['mensaje' => 'Error en la peticion']);
    }
}
