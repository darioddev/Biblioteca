<?php
$numerosLibrosLeidos = sql_get_prestamos_by_id($_SESSION['id'], "Usuarios.ID");
$numerosLibrosPendinetes = sql_get_prestamos_by_id($_SESSION['id'], "Usuarios.ID" , true);
?>

<?php
require_once("./includes/nav.inc.php");
?>
<link rel="stylesheet" href="./assets/css/home.css">
<section class="home">
  <div class="text">
    Home
  </div>
  <section class="user-stats">
    <div class="card">
      <h2>Numero de libros Leidos </h2>
      <p>
        <?php echo count($numerosLibrosLeidos) ?>
      </p>
    </div>

    <div class="card">
      <h2>Numero de prestamos pendientes </h2>
      <p><?php echo count($numerosLibrosPendinetes)?></p>
    </div>
  </section>

  <section class="highlights">
    <h2>Novedades</h2>

    <div class="book-card">
      <img src="libro1.jpg" alt="Libro 1">
      <h3>Libro 1</h3>
      <p>Descripción corta del libro 1.</p>
      <i class="fas fa-star"></i> <span>4.5</span>
    </div>

    <div class="book-card">
      <img src="libro2.jpg" alt="Libro 2">
      <h3>Libro 2</h3>
      <p>Descripción corta del libro 2.</p>
      <i class="fas fa-star"></i> <span>3.8</span>
    </div>

    <div class="book-card">
      <img src="libro3.jpg" alt="Libro 3">
      <h3>Libro 3</h3>
      <p>Descripción corta del libro 3.</p>
      <i class="fas fa-star"></i> <span>4.2</span>
    </div>
  </section>

  <section class="rankings">
    <h2>Ranking de Libros Más Leídos</h2>

    <ol>
      <li>
        <img src="libro4.jpg" alt="Libro 4">
        Nombre del libro 4
      </li>
      <li>
        <img src="libro5.jpg" alt="Libro 5">
        Nombre del libro 5
      </li>
      <li>
        <img src="libro6.jpg" alt="Libro 6">
        Nombre del libro 6
      </li>
      <!-- Añade más elementos según sea necesario -->
    </ol>
  </section>

</section>