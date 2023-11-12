import {
  showConfirmationDialog,
  showSuccessMessage,
} from "./alert-functions.js";
import { formularioAnade } from "./formularios.js";
import { routeGet } from "./path.js";
import { getData } from "./axios-functions.js";
import { route } from "./path.js";

const selects = async (routedata) => {
  try {
    const autores = await getData(`${routedata}`);
    const options = autores.map((el) => {
      const option = document.createElement("option");
      option.text = el.NOMBRE;
      option.value = el.ID;
      return option;
    });
    return options;
  } catch (error) {
    console.error(error);
  }
};

const selectsDatos = async () => {
  try {
    const optionsAutor = await selects(`${route}&autor=all`);
    const optionsHTMLAutor = optionsAutor
      .map((option) => option.outerHTML)
      .join("");

    const optionsEditorial = await selects(`${route}&editorial=all`);
    const optionsHTMLEditorial = optionsEditorial
      .map((option) => option.outerHTML)
      .join("");
    console.log(optionsHTMLEditorial);

    return { optionsHTMLAutor, optionsHTMLEditorial };
  } catch (error) {
    console.error("Error al obtener opciones:", error);
  }
};

export function initializeUI() {
  try {
    const body = document.querySelector("body"),
      sidebar = body.querySelector("nav"),
      toogle = body.querySelector(".toogle"),
      searchBtn = body.querySelector(".search-box"),
      modeSwitch = body.querySelector(".toogle-switch"),
      modeText = body.querySelector(".mode-text");

    if (
      !body ||
      !sidebar ||
      !toogle ||
      !searchBtn ||
      !modeSwitch ||
      !modeText
    ) {
      throw new Error("Elementos DOM no encontrados.");
    }

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
    searchBtn.addEventListener("click", () =>
      sidebar.classList.remove("close")
    );

    const session = document.getElementById("session");

    if (!session) {
      throw new Error("Elemento DOM para sesión no encontrado.");
    }

    session.addEventListener("click", async (e) => {
      e.preventDefault();
      const isConfirmed = await showConfirmationDialog(
        "¿Estás seguro de que quieres cerrar sesión?",
        "No podrás revertir esta acción.",
        "Sí, cerrar sesión"
      );

      if (isConfirmed) {
        showSuccessMessage(
          "Cerrada!",
          "Tu sesión ha sido cerrada exitosamente."
        );
        window.location.href = session.href;
      }
    });
  } catch (error) {
    console.error(`Error en initializeUI: ${error.message}`);
  }
}
// user-module.js
export function initializeUserModule() {
  try {
    // Ocultar opciones según el estado
    const stateElements = document.querySelectorAll(".table-state span");

    Array.from(stateElements).forEach((el) => {
      if (el.textContent.toUpperCase().trim() === "ACTIVO") {
        const optionLink = el
          .closest("tr")
          .querySelector(".option-table ul .option-link.check");
        optionLink.style.display = "none";
        el.style.backgroundColor = "#57c975";
      } else {
        const optionLink = el
          .closest("tr")
          .querySelector(".option-table ul .option-link.alt");
        optionLink.style.display = "none";
        el.style.backgroundColor = "red";
        el.style.color = "white";
      }
    });

    // Prevenir clics en hiperenlacesBorrador
    const hiperenlacesBorrador = document.querySelectorAll("a[data-action]");

    Array.from(hiperenlacesBorrador).forEach((el) =>
      el.addEventListener("click", (e) => e.preventDefault())
    );

    // Configurar opciones de ordenamiento por defecto
    window.addEventListener("DOMContentLoaded", () => {
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
  } catch (error) {
    console.error(`Error en initializeUserModule: ${error.message}`);
  }
}

export const initializeUserInterface = async () => {
  try {
    const anadeUsuario = document.getElementById("anadeUsuario");
    const selectOrdenacion = document.getElementById("ordenarPor");
    const selectBusqueda = document.getElementById("buscarPor");
    const inputFilas = document.getElementById("rows");
    const busquedaForm = document.getElementById("busqueda");

    const { optionsHTMLAutor, optionsHTMLEditorial } = await selectsDatos();

    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    const initializeRow = window.initializeRow;
    const maxFilas = window.maxFilas;

    anadeUsuario.addEventListener("click", (e) => {
      e.preventDefault();
      const action = e.target.dataset.action;
      console.log(action);
      let title = undefined;
      let formulario = undefined;
      let message = undefined;
      let _action = undefined;

      switch (action) {
        case "usuarios":
          title = "Registro de nuevo ";
          _action = "insertarUsuario";
          message = "Usuario";
          formulario = `
        <form id="usuarioForm">
          <label for="name">Nombre</label>
          <input type="text" id="name" class="swal2-input" placeholder="Nombre" required>
      
          <label for="last_name1">Apellido:</label>
          <input type="text" id="last_name1" class="swal2-input" placeholder="Apellido" required>
      
          <label for="last_name2">Segundo Apellido:</label>
          <input type="text" id="last_name2" class="swal2-input" placeholder="Segundo Apellido">
      
          <label for="user">Nombre de Usuario:</label>
          <input type="text" id="user" class="swal2-input" placeholder="Nombre de Usuario" required>
      
          <label for="contrasena">Contraseña:</label>
          <input type="password" id="contrasena" class="swal2-input" placeholder="Contraseña" required>
      
          <label for="email">Correo Electrónico:</label>
          <input type="email" id="email" class="swal2-input" placeholder="Correo Electrónico" required>
      
          <label for="fechaRegistro">Fecha de Registro:</label>
          <input type="date" id="fechaRegistro" class="swal2-input" required style="text-align: center;">
      
          <label for="rol">Rol:</label>
          <select id="rol" class="swal2-select">
            <option value="LECTOR">Lector</option>
            <option value="ADMIN">Admin</option>
          </select>
        </form>
        `;
          console.log("Muestra formulario para añadir autor");
          break;

        case "autores":
          title = "Registro de nuevo autor";
          _action = "insertarAutor";
          message = "Autor";
          formulario = `
          <form id="usuarioForm">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" class="swal2-input" placeholder="Nombre" required>
      
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" class="swal2-input" placeholder="Apellido" required>
      
            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" class="swal2-input" required style="text-align: center;">
          </form>`;
          console.log("Muestra formulario para añadir autor");
          break;

        case "editoriales":
          title = "Registro de un nuevo editorial";
          _action = "insertarEditorial";
          message = "editorial";
          formulario = `
          <form id="usuarioForm">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" class="swal2-input" placeholder="Nombre" required>
      
            <label for="fecha_creacion">Fecha de Creacion:</label>
            <input type="date" id="fecha_creacion" class="swal2-input" required  style="text-align: center;">

          </form>`;
          console.log("Muestra formulario para añadir autor");
          break;

        case "libros":
          title = "Registro de un nuevo editorial";
          _action = "insertarEditorial";
          message = "editorial";
          formulario = `
          <form action="/" method="POST" style="display: flex; flex-direction: column;">
          <label for="Titulo">Titulo:</label>
          <input type="text" id="Titulo" class="swal2-input" placeholder="Nombre" required>
    
            <label for="ID_Autor">Seleccione el autor :</label>
            <select id="ID_Autor" class="swal2-select">
              ${optionsHTMLAutor}
            </select>
            <label for="ID_Editorial">Seleccione la editorial :</label>
            <select id="ID_Editorial" class="swal2-select">
              ${optionsHTMLEditorial}
            </select>
            <label for="Archivo" style="margin-top: 10px;">Seleccione una imagen :</label>
            <input type="file" id="Archivo" name="archivo" accept=".jpg, .wbep, .png" style="margin-bottom: 10px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">

          </form>`;
          break;
      }

      if (formulario !== undefined && _action !== undefined) {
        formularioAnade(title, formulario, _action, message);
      }
    });

    busquedaForm.addEventListener("submit", (e) => {
      e.preventDefault();

      let timerInterval;
      Swal.fire({
        title: `Buscando por ${selectBusqueda.value}`,
        timer: 2000,
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading();
          const timer = Swal.getPopup().querySelector("b");
          timerInterval = setInterval(() => {
            timer.textContent = `${Swal.getTimerLeft()}`;
          }, 100);
        },
        willClose: () => {
          clearInterval(timerInterval);
        },
      }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
          window.location.href = `${e.target.action}${busquedaForm["search"].value}&type=${selectBusqueda.value}`;
        }
      });
    });
    console.log("ups.");

    selectOrdenacion.addEventListener("change", (e) => {
      actualizarURL(e.target.dataset.name);
    });

    inputFilas.addEventListener("input", (e) => {
      console.log(window.location.href);
      if (inputFilas.value > maxFilas || inputFilas.value <= 0) {
        inputFilas.value = initializeRow;
      }
      if (inputFilas.value < 0) {
        inputFilas.value = 1;
      }
      actualizarURL();
    });

    const actualizarURL = (data = routeGet("ruta")) => {
      const ordenSeleccionada = selectOrdenacion.value;
      const filasSeleccionadas = inputFilas.value;
      let newUrl = `?ruta=${data}&row=${filasSeleccionadas}`;

      if (params.get("type") !== null && params.get("search") !== null) {
        newUrl += `&search=${params.get("search")}&type=${params.get("type")}`;
      } else {
        newUrl += `&${ordenSeleccionada}`;
      }
      console.log(newUrl);
      window.location.href = newUrl;
    };
  } catch (error) {
    console.error(`Error en initializeUserInterface: ${error.message}`);
  }
};
