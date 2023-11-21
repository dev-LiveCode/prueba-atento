<?php
require_once "../../db/conecction.php";

session_start();
if (!isset($_SESSION['id'])) {
  header('Location: ../auth/login.php');
} else {
  $conexion = new ConecDB();
  $pdo = $conexion->getConnection();

  $stPreguntas = $pdo->prepare("SELECT id, pregunta, respuesta FROM preguntas;");
  $stPreguntas->execute();
  $conteoPreguntas = $stPreguntas->rowCount();
  $preguntas = $stPreguntas->fetchAll(PDO::FETCH_ASSOC);

  $stDatos = $pdo->prepare("SELECT 
  id, 
  nombre,
	apellido,
	instagram,
	seguidores,
	telefono,
	ciudad  FROM datos;");
  $stDatos->execute();
  $conteoDatos = $stDatos->rowCount();
  $datos = $stDatos->fetchAll(PDO::FETCH_ASSOC);

  $conexion->closeConnection();
  $alert = false;
  $htmlAlert = "";
  if (isset($_GET['exito'])) {
    $alert = true;
    if ($_GET['exito'] == 1 || $_GET['exito'] == '1') {
      $htmlAlert = '
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Hey!</strong> Se han registrado sus datossatisfactoriamente
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      ';
    } else {
      $htmlAlert = '
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Oh oh!</strong> Ocurrio un fallo al registrar su formulario
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      ';
    }
  }
}

require_once("C://wamp64/www/Atento/views/statics/header.php");

?>

<body id="page-top">


  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php require_once("C://wamp64/www/Atento/views/statics/sidebar-html.php") ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php require_once("C://wamp64/www/Atento/views/statics/topbar-html.php") ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Actividad</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Descargar .xls</a>
          </div>

          <!-- Content Row -->

          <div class="row">
            <!-- From card -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Formulario de registro</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <!-- Formulario -->
                  <form method="POST" action="../../controllers/ActividadController.php">
                    <div class="row">
                      <div class="col-md-12" style="margin-bottom: 10px">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                      </div>
                      <div class="col-md-12" style="margin-bottom: 10px">
                        <label for="validationCustom02" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido">
                      </div>
                      <div class="col-md-12" style="margin-bottom: 10px">
                        <label for="instagram" class="form-label">instagram *</label>
                        <div class="input-group has-validation">
                          <span class="input-group-text" id="inputGroupPrepend">@</span>
                          <input type="text" class="form-control" id="instagram" name="instagram" aria-describedby="inputGroupPrepend" required>
                        </div>
                      </div>

                      <div class="col-md-12" style="margin-bottom: 10px">
                        <label for="seguidores" class="form-label"># seguidores *</label>
                        <input type="number" class="form-control" id="seguidores" name="seguidores" required>
                      </div>

                      <div class="col-md-12" style="margin-bottom: 10px">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad">
                      </div>

                      <div class="col-md-12" style="margin-bottom: 10px">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                      </div>
                    </div>
                    <input type="hidden" name="action" value="registrar">

                    <?php
                    if ($alert == true) {
                    ?>
                      <div class="row">
                        <div class="col-md-12" style="margin-bottom: 10px">
                          <?= $htmlAlert ?>
                        </div>
                      </div>
                    <?php
                    }
                    ?>

                    <div class="row" style="margin-top: 10px;">
                      <div class="col-12">
                        <button class="btn btn-primary" type="submit">Registrar</button>
                      </div>
                    </div>
                  </form>
                  <!-- / Formulario -->
                </div>
              </div>
            </div>

            <!-- Table card -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Tabla de datos</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <th>#</th>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Telefono</th>
                      <th>Ciudad</th>
                      <th>Instagram</th>
                      <th>Seguidores</th>
                      <th>Acciones</th>
                    </thead>
                    <tbody>
                      <?php
                      $cont = 0;
                      foreach ($datos as $item) {
                        $cont++;
                      ?>
                        <tr>
                          <td><?= $cont ?></td>
                          <td><?= $item['nombre'] ?></td>
                          <td><?= $item['apellido'] ?></td>
                          <td><?= $item['telefono'] ?></td>
                          <td><?= $item['ciudad'] ?></td>
                          <td><?= $item['instagram'] ?></td>
                          <td><?= $item['seguidores'] ?></td>
                          <td><?= 'En desarrollo' ?></td>
                        </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->
          <div class="row">

            <div class="col-lg-6 mb-4">
              <!-- Preguntas -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Preguntas</h6>
                </div>
                <div class="card-body">
                  <p>En está sección encontraran las preguntas planteadas en el recurso entregado (Prueba Conocimientos PHP.pdf)</p>
                  <p class="mb-0">Está parte de la aplicación está desarrollada como un CRUD adicional manejando una tabla en la base de datos</p>
                </div>
              </div>
              <?php
              foreach ($preguntas as $item) {
              ?>

                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $item['pregunta'] ?></h6>
                  </div>
                  <div class="card-body">
                    <p><?= $item['respuesta'] ?></p>
                  </div>
                </div>
              <?php
              }
              ?>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
      require_once("C://wamp64/www/Atento/views/statics/footer-html.php");
      ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scripts -->
  <?php
  require_once("C://wamp64/www/Atento/views/statics/import-scripts.php");
  ?>
  <!-- end Scripts -->

  <script>
    // Inicializar DataTable
    $(document).ready(function() {
      $('#dataTable').DataTable();
    });

    // Ejemplo con fetch
    function realizarActualizacion() {

      var opcionesFetch = {
        method: 'POST', // Método de la petición
        headers: {
          'Content-Type': 'application/json', // Tipo de contenido
        },
        body: 'action=' + encodeURIComponent('carga')
      };
      fetch('../../controllers/ActividadController.php', opcionesFetch)
        .then(response => response.json())
        .then(data => {
          // Procesa la respuesta y actualiza DataTable
          console.log(data);
          actualizarDataTable(data);
        })
        .catch(error => console.error('Error:', error));
    }

    function actualizarDataTable(data) {
      // Limpia la tabla
      $('#dataTable').DataTable().clear().draw();

      // Agrega las nuevas filas según los datos recibidos
      var contador = 0;
      data.forEach(function(fila) {
        contador++;
        $('#dataTable').DataTable().row.add([
          contador,
          fila.nombre,
          fila.apellido,
          fila.instagram,
          fila.seguidores,
          fila.telefono,
          fila.ciudad,
          '<button>En desarrollo</button>'
          // Agrega más columnas según sea necesario
        ]).draw();
      });
    }

    // Llama a la función cada x segundos
    setInterval(realizarActualizacion, 5000); // Ejecuta cada 5 segundos
  </script>

</body>

</html>