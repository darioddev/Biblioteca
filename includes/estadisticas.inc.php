
<section class="user-statsAD">
      <div class="stats-cardAD">
        <h2 class="stats-card-titleAD">Nuevos usuarios añadidos este mes</h2>
        <i class="fas fa-users user-iconAD fa-3x"></i>
        <p class="stats-card-valueAD"><?php echo get_count_mes()?></p>
      </div>

      <div class="stats-cardAD">
        <h2 class="stats-card-titleAD">Usuarios inactivos </h2>
        <i class="fas fa-arrow-down arrow-downAD fa-3x"></i>
        <p class="stats-card-valueAD"><?php echo get_usuarios_inactivos()?></p>
      </div>

      <div class="stats-cardAD">
        <h2 class="stats-card-titleAD">Numero de prestamos de este ultimo mes </h2>
        <i class="fas fa-book book-iconAD fa-3x"></i>
        <p class="stats-card-valueAD"><?php echo get_count_mes('Prestamos','Fecha_inicio')?></p>
      </div>
    </section>

    <section class="custom-highlightsAD">
      <div class="custom-book-cardAD">
        <img src="<?php echo $masleidos[0]['Imagen']?>" alt="Libro más prestado">
        <h2 class="custom-book-card-titleAD">Libro Más Prestado</h2>
        <p class="custom-book-card-authorAD"><?php echo $masleidos[0]['NombreAutor'] . " " . $masleidos[0]['ApellidoAutor']?></p>
      </div>

      <div class="custom-book-cardAD">
        <img src="<?php echo $masleidos[count($masleidos)-1]['Imagen']?>" alt="Libro menos prestado">
        <h2 class="custom-book-card-titleAD">Libro Menos Prestado</h2>
        <p class="custom-book-card-authorAD"><?php echo $masleidos[count($masleidos)-1]['NombreAutor'] . " " . $masleidos[count($masleidos)-1]['ApellidoAutor']?></p>
      </div>

      <div class="custom-book-cardAD">
        <img src="<?php echo $masleidos[0]['Imagen']?>" alt="Libro destacado">
        <h2 class="custom-book-card-titleAD">Libro Destacado</h2>
        <p class="custom-book-card-authorAD"><?php echo $masleidos[0]['NombreAutor'] . " " . $masleidos[0]['ApellidoAutor']?> </p>
      </div>
    </section>