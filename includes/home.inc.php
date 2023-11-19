<?php
$numerosLibrosLeidos = sql_get_prestamos_by_id($_SESSION['id'], "Usuarios.ID");
$numerosLibrosPendinetes = sql_get_prestamos_by_id($_SESSION['id'], "Usuarios.ID", true);
$librosNovedades = sql_get_all_libros(0, 4, null, null, 'fecha_creacion', 'DESC', null);
$masleidos = get_ranking_mas_leidos();

?>

<?php
require_once("./includes/nav.inc.php");
?>
<link rel="stylesheet" href="./assets/css/home.css">
<link rel="stylesheet" href="./assets/css/estadisticas.css">
<section class="home">
  <div class="text">
    Home
  </div>
  <?php
  if (isset($_SESSION['rol']) && $_SESSION['rol'] == "ADMIN") {
    require_once("./includes/estadisticas.inc.php");
  } else {
    ?>
    <section class="user-stats">
      <div class="card">
        <h2>Numero de libros Leidos </h2>
        <p>
          <?php echo count($numerosLibrosLeidos) ?>
        </p>
      </div>

      <div class="card">
        <h2>Numero de prestamos pendientes </h2>
        <p>
          <?php echo count($numerosLibrosPendinetes) ?>
        </p>
      </div>
    </section>
  <?php } ?>

  <section class="highlights">
    <h2>Novedades</h2>
    <div class="linea"></div>
    <div class="container-card">
      <div class="book-card">
        <img src="<?php echo $librosNovedades[0]['Imagen'] ?>" alt="<?php echo $librosNovedades[0]['Titulo'] ?>">
        <h3>
          <?php echo $librosNovedades[0]['Titulo'] ?>
        </h3>
        <i class="fas fa-star"></i> <span>4.5</span>
      </div>

      <div class="book-card">
        <img src="<?php echo $librosNovedades[1]['Imagen'] ?>" alt="<?php echo $librosNovedades[1]['Titulo'] ?>">
        <h3>
          <?php echo $librosNovedades[1]['Titulo'] ?>
        </h3>
        <i class="fas fa-star"></i> <span>3.8</span>
      </div>

      <div class="book-card">
        <img src="<?php echo $librosNovedades[2]['Imagen'] ?>" alt="<?php echo $librosNovedades[2]['Titulo'] ?>">
        <h3>
          <?php echo $librosNovedades[2]['Titulo'] ?>
        </h3>
        <i class="fas fa-star"></i> <span>4.2</span>
      </div>

      <div class="book-card">
        <img src="<?php echo $librosNovedades[3]['Imagen'] ?>" alt="<?php echo $librosNovedades[3]['Titulo'] ?>">
        <h3>
          <?php echo $librosNovedades[3]['Titulo'] ?>
        </h3>
        <i class="fas fa-star"></i> <span>4.2</span>
      </div>

    </div>
  </section>

  <section class="rankings">
    <h2>Ranking de Libros Más Leídos</h2>
    <div class="linea"></div>

    <ol>
      <li>
        <img src="<?php echo $masleidos[0]['Imagen'] ?>" alt="<?php echo $masleidos[0]['Titulo'] ?>">
        <p>
          <?php echo $masleidos[0]['Titulo'] ?>
        </p>
      </li>

      <li>
        <img src="<?php echo $masleidos[1]['Imagen'] ?>" alt="<?php echo $masleidos[1]['Titulo'] ?>">
        <p>
          <?php echo $masleidos[1]['Titulo'] ?>
        </p>
      </li>

      <li>
        <img src="<?php echo $masleidos[2]['Imagen'] ?>" alt="<?php echo $masleidos[2]['Titulo'] ?>">
        <p>
          <?php echo $masleidos[2]['Titulo'] ?>
        </p>
      </li>

    </ol>
  </section>

</section>