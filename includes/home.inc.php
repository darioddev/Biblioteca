<link rel="stylesheet" href="./assets/css/nav.css">
<link rel="stylesheet" href="./assets/css/usuarios.css">

<nav class="sidebar close">
  <header>
    <div class="text logo">
      <span class="name">
        <?php  echo $_SESSION['user'] ?>
      </span>
      <span class="rol">
        <?php  echo sql_get_rol($_SESSION['user'])["ROL"] ?>
      </span>
    </div>
    <i class="bx bx-menu toogle"></i>
  </header>

  <div class="menu-bar">
    <div class="menu">
      <li class="search-box">
        <i class="bx bx-search icon"></i>
        <input type="text" name="" id="" placeholder="Buscar..." />
      </li>

      <ul class="menu-links">
        <li class="nav-link">
          <a href="">
            <i class="bx bx-home-alt icon"></i>
            <span class="text nav-text">Home</span>
          </a>
        </li>

        <li class="nav-link">
          <a href="">
            <i class="bx bx-user icon"></i>
            <span class="text nav-text">Usuarios</span>
          </a>
        </li>

        <li class="nav-link">
          <a href="">
            <i class="bx bx-book-open icon"></i>
            <span class="text nav-text">Libros</span>
          </a>
        </li>

        <li class="nav-link">
          <a href="">
            <i class="bx bx-home-alt icon"></i>
            <span class="text nav-text">Autores</span>
          </a>
        </li>

        <li class="nav-link">
          <a href="">
            <i class="bx bx-bookmark-alt-minus icon"></i>
            <span class="text nav-text">Editoriales</span>
          </a>
        </li>

        <li class="nav-link">
          <a href="?ruta=logout.inc.php">
            <i class="bx bx-home-alt icon"></i>
            <span class="text nav-text">Reportes</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="bottom-content">
      <li class="class">
        <a href="<?php echo $_SERVER["PHP_SELF"] . "?ruta=logout" ?>" id="session">
          <i class="bx bx-log-out icon"></i>
          <span class="text nav-text">Cerrar Session</span>
        </a>
      </li>
      <li class="mode">
        <div class="sun-moon">
          <i class="bx bx-moon icon moon"></i>
          <i class="bx bx-sun icon sun"></i>
        </div>
        <span class="mode-text text">Modo Oscuro</span>
        <div class="toogle-switch">
          <span class="switch"></span>
        </div>
      </li>
    </div>
  </div>
</nav>

<section class="home">
  <div class="text">
    PANEL DE USUARIOS
  </div>
  <?php
  $initalize = (isset($_GET["row"]) && !empty(trim($_GET["row"]) && $_GET["row"] >= 0) ? $_GET["row"] : 5);
  $usuarios = sql_get_all_usuarios(0, $initalize);
  $maxFilas = 20;

  if (count($usuarios) < $initalize) {
    $initalize = $maxFilas;
  }

  if (isset($_GET["remove"]) && !empty(trim($_GET["remove"]))) {

  }
  ;
  ?>
  <div class="table-options">
    <div class="table-options-row">
      <span>Mostrar
        <input type="number" name="rows" id="rows" value="<?php echo $initalize ?>" min="0" max="30" maxlength="2">
        filas
      </span>
      </span>

      <script>
        document.getElementById("rows").addEventListener("change", (e) => {
          if (e.target.value > <?php echo $maxFilas; ?> || e.target.value <= 0) {
            e.target.value = <?php echo $initalize; ?>;
          }
          window.location.href = `?ruta=home&row=${e.target.value}`;
        });
      </script>

    </div>

    <div class="table-options-order">
      <div>
        <select id="ordenarPor">
          <option value="NINGUNO">Ordenar por...</option>
          <option value="NOMBRE">NOMBRE</option>
          <option value="NOMBRE_USUARIO">NOMBRE USUARIO</option>
          <option value="CORREO_ELECTRONICO">CORREO ELECTRONICO</option>
          <option value="FECHA_REGISTRO">FECHA REGISTRO</option>
          <option value="ROL">ROL</option>
          <option value="ESTADO">ESTADO</option>
        </select>
      </div>
    </div>

    <div class="table-options-search">
      <form class="form">
        <button>
          <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img"
            aria-labelledby="search">
            <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9"
              stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
        </button>
        <input class="input" placeholder="Buscar ... " required="" type="text" />
        <button class="reset" type="reset">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </form>
    </div>
  </div>

  <div class="add">
    <div class="add-link">
      <a href="#" id="anadeUsuario">
        <i class="bx bx-user-plus "></i>
      </a>
    </div>
  </div>

  </div>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>NOMBRE</th>
        <th>NOMBRE USUARIO</th>
        <th>CORREO ELECTRONICO</th>
        <th>FECHA REGISTRO</th>
        <th>ROL</th>
        <th>ESTADO</th>
        <th>ACCIONES</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($usuarios as $usuario) {
        ?>
        <tr>
          <?php
          foreach ($usuario as $propiedad => $value) {
            if ($propiedad == "ESTADO") {
              $value = $value ? "Activo" : "Inactivo";
              ?>
              <td class="table-state">
                <span>
                  <?php echo $value ?>
                </span>
              </td>
              <?php
            } else {
              ?>
              <td>
                <?php echo $value ?>
              </td>
              <?php
            }
            ?>
            <?php
          }
          ?>
          <td class="option-table">
            <ul>
              <li class="option-link cog">
                <a href="?ruta=home&remove=<?php echo $usuario["ID"] ?>">
                  <i class="fas fa-user-cog"></i>
                </a>
              </li>
              <li class="option-link alt">
                <a href="?ruta=home&remove=<?php echo $usuario["ID"] ?>">
                  <i class="fas fa-trash-alt"></i>
                </a>
              </li>
            </ul>
          </td>
          <?php
      }

      ?>
    </tbody>
  </table>
  </div>

</section>

<script>
  const route = `${window.parent.location.origin}${window.parent.location.pathname}`

  async function mostrarFormulario(e) {
    const { value: formData } = await Swal.fire({
      title: "Registro de Usuario",
      html: `
          <form action="/" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" class="swal2-input" placeholder="Nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" class="swal2-input" placeholder="Apellido" required>

            <label for="apellido2">Segundo Apellido:</label>
            <input type="text" id="apellido2" class="swal2-input" placeholder="Segundo Apellido">

            <label for="nombreUsuario">Nombre de Usuario:</label>
            <input type="text" id="nombreUsuario" class="swal2-input" placeholder="Nombre de Usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" class="swal2-input" placeholder="Contraseña" required>

            <label for="correoElectronico">Correo Electrónico:</label>
            <input type="email" id="correoElectronico" class="swal2-input" placeholder="Correo Electrónico">

            <label for="fechaRegistro">Fecha de Registro:</label>
            <input type="date" id="fechaRegistro" class="swal2-input" required>

            <label for="rol">Rol:</label>
            <select id="rol" class="swal2-select">
              <option value="LECTOR">Lector</option>
              <option value="ADMIN">Admin</option>
            </select>
          </form>
        `,
      focusConfirm: false,
      showCancelButton: true,
      preConfirm: () => {
        return {
          action: 'insertarUsuario',
          nombre: document.getElementById('nombre').value,
          apellido: document.getElementById('apellido').value,
          apellido2: document.getElementById('apellido2').value || null,
          nombreUsuario: document.getElementById('nombreUsuario').value,
          contrasena: document.getElementById('contrasena').value,
          correoElectronico: document.getElementById('correoElectronico').value || null,
          fechaRegistro: document.getElementById('fechaRegistro').value || null,
          rol: document.getElementById('rol').value
        };
      }
    });

    if (formData) {
      try {
        await axios.post(`${route}?ruta=procesa`, JSON.stringify(formData))
        Swal.fire({
          position: "center",
          icon: "success",
          title: "Usuario registrado correctamente",
          showConfirmButton: false,
          timer: 1500
        });
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo ha salido mal! Vuelve a intentarlo.",
        });
      }
    }
  }


  const anadeUsuario = document.getElementById('anadeUsuario')

  anadeUsuario.addEventListener('click', (e) => {
    mostrarFormulario(e)
  })



  const session = document.getElementById("session")

  session.addEventListener('click', (e) => {
    e.preventDefault()

    console.log(session.href)
    Swal.fire({
      title: "¿Estás seguro de que quieres cerrar sesión?",
      text: "No podrás revertir esta acción.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, cerrar sesión",
    }).then((result) => {
      if (result.isConfirmed) {
        console.log('pinchaste..')
        Swal.fire(
          "¡Cerrada!",
          "Tu sesión ha sido cerrada exitosamente.",
          "success"
        );
        window.location.href = session.href;
      }
    });
  })

</script>

<script src="./assets/js/nav.js"></script>