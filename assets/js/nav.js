const body = document.querySelector("body"),
  sidebar = body.querySelector("nav"),
  toogle = body.querySelector(".toogle"),
  searchBtn = body.querySelector(".search-box"),
  modeSwitch = body.querySelector(".toogle-switch"),
  modeText = body.querySelector(".mode-text");

toogle.addEventListener("click", () => sidebar.classList.toggle("close"));

searchBtn.addEventListener("click", () => sidebar.classList.remove("close"));

modeSwitch.addEventListener("click", () => {
  body.classList.toggle("dark");
  if (body.classList.contains("dark")) {
    modeText.innerText = "Light mode";
  } else {
    modeText.innerText = "Dark mode";
  }
});
const route = `${window.parent.location.origin}${window.parent.location.pathname}`;
const hiperenlacesBorrador = document.querySelectorAll("a[data-action]");

Array.from(hiperenlacesBorrador).map((el) =>
  el.addEventListener("click", (e) => e.preventDefault())
);

document.querySelector("table").addEventListener("click", async (event) => {
  console.log(event.target)
  const action = event.target.dataset.action;
  if (action === "borrado") {
    console.log("si..");
    Swal.fire({
      title: "¿Estás seguro?",
      text: "¿Deseas eliminar a este usuario?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, eliminarlo",
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: "¡Eliminado!",
          text: "El usuario ha sido eliminado.",
          icon: "success",
        });

        window.location.href = event.target.href;
      }
    });
  } else if (action === "modificado") {
    console.log(event.target.href);
    const reponse = await axios.get(`${event.target.href}`);
    const datos = await reponse.data;

    const {
      ID,
      NOMBRE,
      APELLIDO,
      APELLIDO2,
      NOMBRE_USUARIO,
      CORREO_ELECTRONICO,
      ESTADO,
      FECHA_REGISTRO,
      ROL,
    } = datos;

    const { value: formData } = await Swal.fire({
      title: "Registro de Usuario",
      html: `
  <form action="/" method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" class="swal2-input" placeholder="Nombre" value=${NOMBRE} required>

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" class="swal2-input" placeholder="Apellido" value="${APELLIDO}" required>

    <label for="apellido2">Segundo Apellido:</label>
    <input type="text" id="apellido2" class="swal2-input" placeholder="Segundo Apellido" value="${APELLIDO2}">

    <label for="nombreUsuario">Nombre de Usuario:</label>
    <input type="text" id="nombreUsuario" class="swal2-input" placeholder="Nombre de Usuario" value="${NOMBRE_USUARIO}" required>

    <label for="correoElectronico">Correo Electrónico:</label>
    <input type="email" id="correoElectronico" class="swal2-input" placeholder="Correo Electrónico" value="${CORREO_ELECTRONICO}">

    <label for="fechaRegistro">Fecha de Registro:</label>
    <input type="date" id="fechaRegistro" class="swal2-input" value="${FECHA_REGISTRO}" required>

    <label for="rol">Rol:</label>
    <select id="rol" class="swal2-select" value="${ROL}">
      <option value="LECTOR">Lector</option>
      <option value="ADMIN">Admin</option>
    </select>
  </form>
`,
      focusConfirm: false,
      showCancelButton: true,
      preConfirm: () => {
        return {
          action: "insertarUsuario",
          nombre: document.getElementById("nombre").value,
          apellido: document.getElementById("apellido").value,
          apellido2: document.getElementById("apellido2").value || null,
          nombreUsuario: document.getElementById("nombreUsuario").value,
          contrasena: document.getElementById("contrasena").value,
          correoElectronico:
            document.getElementById("correoElectronico").value || null,
          fechaRegistro: document.getElementById("fechaRegistro").value || null,
          rol: document.getElementById("rol").value,
        };
      },
    });

    if (formData) {
      try {
        await axios.post(`${route}?ruta=procesa`, JSON.stringify(formData));
        Swal.fire({
          position: "center",
          icon: "success",
          title: "Usuario registrado correctamente",
          showConfirmButton: false,
          timer: 1500,
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
});

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
        action: "insertarUsuario",
        nombre: document.getElementById("nombre").value,
        apellido: document.getElementById("apellido").value,
        apellido2: document.getElementById("apellido2").value || null,
        nombreUsuario: document.getElementById("nombreUsuario").value,
        contrasena: document.getElementById("contrasena").value,
        correoElectronico:
          document.getElementById("correoElectronico").value || null,
        fechaRegistro: document.getElementById("fechaRegistro").value || null,
        rol: document.getElementById("rol").value,
      };
    },
  });

  if (formData) {
    try {
      await axios.post(`${route}?ruta=procesa`, JSON.stringify(formData));
      Swal.fire({
        position: "center",
        icon: "success",
        title: "Usuario registrado correctamente",
        showConfirmButton: false,
        timer: 1500,
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

const anadeUsuario = document.getElementById("anadeUsuario");

anadeUsuario.addEventListener("click", (e) => {
  mostrarFormulario(e);
});

const session = document.getElementById("session");

session.addEventListener("click", (e) => {
  e.preventDefault();

  console.log(session.href);
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
      console.log("pinchaste..");
      Swal.fire(
        "¡Cerrada!",
        "Tu sesión ha sido cerrada exitosamente.",
        "success"
      );
      window.location.href = session.href;
    }
  });
});

/*
document.addEventListener("click", (event) => {
  if (event.target.dataset.url !== undefined) {
    if (
      event.target.dataset.logout !== undefined &&
      event.target.dataset.logout
    ) {
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
          Swal.fire(
            "¡Cerrada!",
            "Tu sesión ha sido cerrada exitosamente.",
            "success"
          );
          window.location.href = event.target.dataset.url;
        }
      });
    } else {
      window.location.href = event.target.dataset.url;
    }
  }
});
*/
