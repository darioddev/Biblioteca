import {
  showConfirmationDialog,
  showSuccessMessage,
  showErrorMessage,
} from "./alert-functions.js";
import { getData } from "./axios-functions.js";

import { route, routeGet } from "./path.js";
import { formularioNuevoUsuario } from "./formularios.js";
/* Navegador */
const body = document.querySelector("body"),
  sidebar = body.querySelector("nav"),
  toogle = body.querySelector(".toogle"),
  searchBtn = body.querySelector(".search-box"),
  modeSwitch = body.querySelector(".toogle-switch"),
  modeText = body.querySelector(".mode-text");

const setMode = (mode) => {
  body.classList.toggle("dark", mode === "Dark mode");
  modeText.innerText = mode;
  localStorage.setItem("background", mode);
};

modeSwitch.addEventListener("click", () => {
  const currentMode = localStorage.getItem("background");
  const newMode = currentMode === "Dark mode" ? "Light mode" : "Dark mode";
  setMode(newMode);
});

document.addEventListener("DOMContentLoaded", () => {
  const storedMode = localStorage.getItem("background");
  if (storedMode) {
    setMode(storedMode);
  }
});

toogle.addEventListener("click", () => sidebar.classList.toggle("close"));
searchBtn.addEventListener("click", () => sidebar.classList.remove("close"));

/* Del navegador */
const session = document.getElementById("session");

session.addEventListener("click", async (e) => {
  e.preventDefault();
  const isConfirmed = await showConfirmationDialog(
    "¿Estás seguro de que quieres cerrar sesión?",
    "No podrás revertir esta acción.",
    "Sí, cerrar sesión"
  );

  if (isConfirmed) {
    showSuccessMessage("Cerrada!", "Tu sesión ha sido cerrada exitosamente.");
    window.location.href = session.href;
  }
});

/* Usuarios */
const state = document.querySelectorAll(".table-state span");

Array.from(state).forEach((el) => {
  if (el.textContent.toUpperCase().trim() === "ACTIVO") {
    const optionLink = el
      .closest("tr")
      .querySelector(".option-table ul .option-link.check");
    optionLink.style.display = "none";
  } else {
    const optionLink = el
      .closest("tr")
      .querySelector(".option-table ul .option-link.alt");
    optionLink.style.display = "none";
  }
});

/* Usuarios */
const hiperenlacesBorrador = document.querySelectorAll("a[data-action]");

Array.from(hiperenlacesBorrador).map((el) =>
  el.addEventListener("click", (e) => e.preventDefault())
);

/* Usuarios*/
window.addEventListener("DOMContentLoaded", (e) => {
  const type = routeGet("type");
  const search = routeGet("search");

  if (type !== undefined && search !== undefined) {
    const selectOrder = document.getElementById("ordenarPor");

    for (const option of selectOrder.options) {
      if (option.value === "column=nombre&order=ASC") {
        option.selected = true;
        break;
      } else {
        option.selected = false;
      }
    }
  }
});

/* De la tabla */
document.querySelector("table").addEventListener("click", async (event) => {
  const action = event.target.dataset.action;
  const type = routeGet("type");
  const search = routeGet("search");
  const page = routeGet("page");
  let url = undefined;

  const handleBorrado = async () => {
    url = event.target.href;

    const isConfirmed = await showConfirmationDialog(
      "¿Estás seguro?",
      "¿Deseas eliminar a este usuario?",
      "Si, eliminarlo"
    );

    if (isConfirmed) {
      console.log("pinchado");
      showSuccessMessage("¡Eliminado!", "El usuario ha sido eliminado.");

      if (type && search) {
        url += `&search=${search}&type=${type}`;
        if (page) {
          url += `&page=${page}`;
        }
      }
    }
  };

  const handleModificado = async () => {
    console.log("aqui..");
    const [response] = await getData(event.target.href);

    const { value: formData } = await Swal.fire({
      title: `Datos personales de usuario : ${response.NOMBRE_USUARIO}`,
      html: `
        <form action="/" method="POST"> 
          <label for="NOMBRE">Nombre:</label>
          <input type="text" id="NOMBRE" class="swal2-input" placeholder="Nombre" value=${
            response.NOMBRE
          } required>
    
          <label for="APELLIDO">Apellido:</label>
          <input type="text" id="APELLIDO" class="swal2-input" placeholder="Apellido" value="${
            response.APELLIDO
          }" required>
    
          <label for="APELLIDO2">Segundo Apellido:</label>
          <input type="text" id="APELLIDO2" class="swal2-input" placeholder="Segundo Apellido" value="${
            response.APELLIDO2
          }">
    
          <label for="NOMBRE_USUARIO">Nombre de Usuario:</label>
          <input type="text" id="NOMBRE_USUARIO" class="swal2-input" placeholder="Nombre de Usuario" value="${
            response.NOMBRE_USUARIO
          }" required>
    
          <label for="CORREO_ELECTRONICO">Correo Electrónico:</label>
          <input type="email" id="CORREO_ELECTRONICO" class="swal2-input" placeholder="Correo Electrónico" value="${
            response.CORREO_ELECTRONICO
          }">
    
          <label for="FECHA_REGISTRO">Fecha de Registro:</label>
          <input type="date" id="FECHA_REGISTRO" class="swal2-input" value="${
            response.FECHA_REGISTRO
          }" required>

          <label for="CONTRASENA">Asignar una nueva contraseña : </label>
          <input type="password" id="CONTRASENA" class="swal2-input">

          <label for="FECHA_MODIFICACION">ULTIMA FECHA DE MODIFICACION:</label>
          <input type="text" id="FEHCA_MODIFICACION" class="swal2-input" placeholder="Nombre de Usuario" value="${
            response.FECHA_MODIFICACION
          }" disabled style="text-align: center;">
    
          <label for="ROL">Rol:</label>
          <select id="ROL" class="swal2-select">
          <option value="LECTOR" ${
            response.ROL.toUpperCase() === "LECTOR" ? "selected" : ""
          }>Lector</option>
          <option value="ADMIN" ${
            response.ROL.toUpperCase() === "ADMIN" ? "selected" : ""
          }>Admin</option>
          </select>
        </form>
      `,
      focusConfirm: false,
      showCancelButton: true,
      preConfirm: () => {
        return {
          nombre: document.getElementById("NOMBRE").value,
          apellido: document.getElementById("APELLIDO").value,
          apellido2: document.getElementById("APELLIDO2").value || "",
          nombre_usuario: document.getElementById("NOMBRE_USUARIO").value,
          contrasena: document.getElementById("CONTRASENA").value,
          correo_electronico:
            document.getElementById("CORREO_ELECTRONICO").value,
          fecha_registro: document.getElementById("FECHA_REGISTRO").value,
          rol: document.getElementById("ROL").value,
        };
      },
    });

    if (formData) {
      try {
        const checkData = Object.fromEntries(
          Object.entries(formData).filter(
            ([key, value]) =>
              value !== response[key.toUpperCase()] && key !== "contrasena"
          )
        );

        await Promise.all(
          Object.entries(checkData).map(async ([key, value]) => {
            try {
              const result = await getData(
                `${route}?user=${response.ID}&${key}=${value}`
              );
              console.log(result);

              if (
                result[1] !== undefined &&
                Object.keys(result[1]).length !== 0
              ) {
                throw new Error(result[1].error);
              }

              showSuccessMessage(
                "Modificado!",
                "El usuario ha sido modificado."
              );
              setTimeout(() => {
                location.reload();
              }, 2000);
            } catch (error) {
              console.error(`NO SE HA PODIDO ACTUALIZAR: ${error}`);
              showErrorMessage(
                "Oops...",
                `Algo ha salido mal! Vuelve a intentarlo. <br> ${error}`
              );
            }
          })
        );
      } catch (error) {
        console.error(error);
        showErrorMessage(
          "Oops...",
          `Algo ha salido mal! Vuelve a intentarlo. <br> ${error}`
        );
      }
    }

    if (formData) {
      try {
        /* ... */
      } catch (error) {
        /* ... */
      }
    }
  };

  const handleReactivar = async () => {
    url = event.target.href;

    const isConfirmed = await showConfirmationDialog(
      "¿Estás seguro?",
      "¿Deseas reactivar a este usuario?",
      "Sí, reactivar usuario"
    );

    if (isConfirmed) {
      console.log("Usuario reactivado correctamente.");
      showSuccessMessage("¡Éxito!", "El usuario ha sido reactivado.");

      if (type && search) {
        url += `&search=${search}&type=${type}`;
        if (page) {
          url += `&page=${page}`;
        }
      }
    }
  };

  switch (action) {
    case "borrado":
      await handleBorrado();
      break;

    case "modificado":
      await handleModificado();
      break;

    case "reactivar":
      await handleReactivar();
      break;

    default:
      break;
  }

  if (url !== undefined) {
    window.location.href = url;
  }
});

const anadeUsuario = document.getElementById("anadeUsuario");

anadeUsuario.addEventListener("click", (e) => {
  formularioNuevoUsuario(e);
});


/* Otros..*/

// index.js

const selectOrdenacion = document.getElementById("ordenarPor");
const selectBusqueda = document.getElementById("buscarPor");

const inputFilas = document.getElementById("rows");
const busqueda = document.getElementById('busqueda');

const url = new URL(window.location.href);

const params = new URLSearchParams(url.search);

// Utiliza las variables de PHP en tu código JavaScript
const initializeRow = window.initializeRow;
const maxFilas = window.maxFilas;



busqueda.addEventListener("submit", (e) => {
    e.preventDefault()

    let timerInterval;
    // Mostrar un mensaje de "Buscando" con temporizador y barra de progreso
    Swal.fire({
        title: `Buscando por ${selectBusqueda.value}`, // Título del mensaje
        timer: 2000, // Duración del temporizador en milisegundos
        timerProgressBar: true, // Mostrar una barra de progreso durante el temporizador
        didOpen: () => {
            // Acciones que se ejecutan cuando se abre la alerta
            Swal.showLoading(); // Mostrar el indicador de carga
            const timer = Swal.getPopup().querySelector("b"); // Obtener el elemento del temporizador
            timerInterval = setInterval(() => {
                timer.textContent = `${Swal.getTimerLeft()}`; // Actualizar el temporizador en la barra de progreso
            }, 100);
        },
        willClose: () => {
            // Acciones que se ejecutan justo antes de cerrar la alerta
            clearInterval(timerInterval); // Limpiar el intervalo del temporizador
        }
    }).then((result) => {
        // Manejar el resultado después de cerrar la alerta
        /* Leer más sobre cómo manejar el cierre de la alerta a continuación */
        if (result.dismiss === Swal.DismissReason.timer) {
            // Redireccionar a la página de resultados de búsqueda si la alerta se cerró debido al temporizador
            window.location.href = `${e.target.action}${busqueda['search'].value}&type=${selectBusqueda.value}`;
        }
    });
});

selectOrdenacion.addEventListener('change', (e) => {
    actualizarURL();
});

inputFilas.addEventListener("input", (e) => {
    if (e.target.value > maxFilas || e.target.value <= 0) {
        e.target.value = initializeRow;
    }
    actualizarURL();
});

function actualizarURL() {
    const ordenSeleccionada = selectOrdenacion.value;
    const filasSeleccionadas = inputFilas.value;

    let url = `?ruta=usuarios&row=${filasSeleccionadas}`;

    if (params.get('type') !== null && params.get('search') !== null) {
        url += `&search=${params.get('search')}&type=${params.get('type')}`
    } else {
        url += `&${ordenSeleccionada}`
    }

    window.location.href = url;
}
