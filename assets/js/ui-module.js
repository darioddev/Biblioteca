import {
  showConfirmationDialog,
  showSuccessMessage,
} from "./alert-functions.js";
import { formularioAnade, createSelect } from "./formularios.js";
import { routeGet } from "./path.js";
import { getData, postData } from "./axios-functions.js";
import { route } from "./path.js";

export const selects = async (routedata, defaultValue = "") => {
  try {
    const autores = await getData(`${routedata}`);
    const options = autores.map((el) => {
      const option = document.createElement("option");
      option.text = el.NOMBRE;
      option.value = el.ID;

      // Verificar si el valor actual es igual al valor predeterminado
      if (Number(el.ID) === Number(defaultValue)) {
        option.defaultSelected = true;
      }

      return option;
    });
    return options;
  } catch (error) {
    console.error(error);
  }
};

const selectsMap = (datos, defaultValue = "", tipo = "nombre") => {
  return datos.map((el) => {
    const option = document.createElement("option");
    option.text = el[tipo];
    option.value = el.id;

    if (Number(el.id) === Number(defaultValue)) {
      option.defaultSelected = true;
    }

    return option;
  });
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
    console.log(stateElements);

    Array.from(stateElements).forEach((el) => {
      if (el.textContent.toUpperCase().trim() === "ACTIVO") {
        const optionLink = el
          .closest("tr")
          .querySelector(".option-table ul .option-link.check");
        if (optionLink) {
          optionLink.style.display = "none";
        }
        el.style.backgroundColor = "#57c975";
      } else {
        const optionLink = el
          .closest("tr")
          .querySelector(".option-table ul .option-link.alt");
        if (optionLink) {
          optionLink.style.display = "none";
        }
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

    const optionsAutor = await selects(`${route}&autor=all`);
    const optionsEditorial = await selects(`${route}&editorial=all`);

    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    const initializeRow = window.initializeRow;
    const maxFilas = window.maxFilas;

    if (anadeUsuario) {
      anadeUsuario.addEventListener("click", async (e) => {
        e.preventDefault();
        const action = e.target.dataset.action;
        console.log(action);

        let title = undefined;
        let formulario = undefined;
        let message = undefined;
        let _action = undefined;

        const autorSelect = createSelect(
          "ID_Autor",
          "swal2-select",
          optionsAutor
        );
        const editorialSelect = createSelect(
          "ID_Editorial",
          "swal2-select",
          optionsEditorial
        );

        const { usuarios, libros } = await getData(`${route}&all=all`);

        const UsuarioSelect = createSelect(
          "ID_Usuario",
          "swal2-select",
          selectsMap(usuarios,"","nombre_usuario")
        );
        const LibroSelect = createSelect(
          "ID_Usuario",
          "swal2-select",
          selectsMap(libros, "", "Titulo")
        );

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
            <input type="date" id="fecha_creacion" class="swal2-input" required  style="text-align: center;" >

          </form>`;
            break;

          case "libros":
            title = "Registro de un nuevo libro";
            _action = "insertarLibro";
            message = "libro";

            formulario = `
          <form id="usuarioForm">
              <label for="Titulo">Titulo:</label>
              <input type="text" id="Titulo" class="swal2-input" placeholder="Nombre" required>
      
              <label for="ID_Autor">Seleccione el autor :</label>
              ${autorSelect.outerHTML}
      
              <label for="ID_Editorial">Seleccione la editorial :</label>
              ${editorialSelect.outerHTML}
          </form>`;
            break;
          case "prestamos":
            title = "Registro de un prestamo";
            _action = "insertarPrestamo";
            message = "prestamo";
            formulario = `
            <form id="usuarioForm">
        
                <label for="ID_Usuario">Seleccione el usuario :</label>
                ${UsuarioSelect.outerHTML}
        
                <label for="ID_Libro">Seleccione el libro :</label>
                ${LibroSelect.outerHTML}
                <div></div>
                <label for="Fecha_inicio">Fecha de inicio:</label>
                <div></div>
                <input type="date" id="Fecha_inicio" class="swal2-input" placeholder="Por defecto el dia de hoy" required  style="text-align: center;" >

                <label for="dias_restantes">Dias para devolver:</label>
                <input type="number" id="dias_restantes" class="swal2-input" value="15" min="5" max="30"required  style="text-align: center;" >
            </form>`;
            break;
        }

        if (formulario !== undefined && _action !== undefined) {
          formularioAnade(title, formulario, _action, message);
        }
      });
    }

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
