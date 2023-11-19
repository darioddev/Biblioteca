import {
  showConfirmationDialog,
  showSuccessMessage,
  showErrorMessage,
  handleConfirmation,
  showInformationMessage,
} from "./alert-functions.js";
import { getData, postData } from "./axios-functions.js";

import { route, routeGet } from "./path.js";
import {
  initializeUI,
  initializeUserModule,
  initializeUserInterface,
} from "./ui-module.js";
import {
  showUserForm,
  showResponse,
  showAutorForm,
  showEditorialForm,
  showLibro,
  enviarImagen,
  showPrestamoForm,
  formularioDatos,
} from "./formularios.js";
/* Navegador */
initializeUI();

initializeUserModule();

initializeUserInterface();

formularioDatos();

// Función para mostrar el formulario de registro de usuario

try {
  document.querySelector("table").addEventListener("click", async (event) => {
    const action = event.target.dataset.action;
    const typeAction = event.target.dataset.name;

    const id_borrado = event.target.dataset.id;
    const key = event.target.dataset.foreignkey;
    const state = event.target.dataset.state;

    const type = routeGet("type");
    const search = routeGet("search");
    const page = routeGet("page");
    let url = undefined;

    const handleBorrado = async () => {
      console.log(id_borrado);
      url = event.target.href;

      try {
        if (state === "verificaEstado") {
          const response = await postData(`${route}`, {
            id: id_borrado,
            ForeignKey: key,
            action: state,
            keyBD: `L.${key}`,
          });

          console.log(response);
          if (response.libros !== undefined && response.libros.length > 0) {
            url = null;
            const librosHtml = response.libros
              .map(
                (el) => `
              <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #ccc;">
                <p style="font-weight: bold; margin-bottom: 5px;">Detalles del libro encontrado con el autor:</p>
                <p><strong>Libro con título:</strong> ${el.Titulo}</p>
                <p><strong>Editorial:</strong> ${el.NombreEditorial}</p>
                <p><strong>Autor:</strong> ${el.NombreAutor}</p>
              </div>
            `
              )
              .join(""); // Agregado join("") para convertir el array en una cadena

            // Mostrar el mensaje de error solo una vez
            const errorMessage = `
            <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #ccc;">
              <p style="font-weight: bold; margin-bottom: 5px;">Ha ocurrido un problema.</p>
              <p>No puedes eliminar un ${typeAction} si tiene libros relacionados con estado activo:</p>
              ${librosHtml}
            </div>
          `;

            showErrorMessage("", errorMessage);
          } else if (
            response.prestamos !== undefined &&
            response.prestamos.length > 0
          ) {
            url = null;
            const PrestamosHtml = response.prestamos
              .map(
                (el) => `
              <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #ccc;">
                <p style="font-weight: bold; margin-bottom: 5px;">Detalles del prestamo relacionado con los libros prestados:</p>
                <p><strong>Libro con título:</strong> ${el.NombreLibro}</p>
                <p><strong>Usuario que tiene este libro :</strong> ${el.UsuarioNombreUsuario}</p>
                <p><strong>Dias restantes :</strong> ${el.dias_restantes}</p>
              </div>
            `
              )
              .join(""); // Agregado join("") para convertir el array en una cadena

            // Mostrar el mensaje de error solo una vez
            const errorMessage = `
            <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #ccc;">
              <p style="font-weight: bold; margin-bottom: 5px;">Ha ocurrido un problema.</p>
              <p>No puedes eliminar un ${typeAction} si tiene prestamos activos :</p>
              ${PrestamosHtml}
            </div>
          `;
            showErrorMessage("", errorMessage);
          } else {
            url = await handleConfirmation(url, type, search, page, typeAction);
          }
        } else if (state === "verificaPrestamo") {
          const response = await postData(`${route}`, {
            id: id_borrado,
            ForeignKey: key,
            action: state,
            keyBD: `Prestamos.${key}`,
          });

          if (
            response.prestamos !== undefined &&
            response.prestamos.length > 0
          ) {
            url = null;

            const prestamosHtml = response.prestamos
              .map(
                (el) => `
              <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #ccc;">
                <p style="font-weight: bold; margin-bottom: 5px;">Detalles de los libros activos que tiene el usuario ${el.UsuarioNombreUsuario}:</p>
                <p><strong>Nombre del libro :</strong> ${el.NombreLibro}</p>
                <p><strong>Inicio del prestamo :</strong> ${el.Fecha_inicio}</p>
                <p><strong>Dias restantes :</strong> ${el.dias_restantes}</p>
              </div>
            `
              )
              .join(""); // Agregado join("") para convertir el array en una cadena

            // Mostrar el mensaje de error solo una vez
            const errorMessage = `
            <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #ccc;">
              <p style="font-weight: bold; margin-bottom: 5px;">Ha ocurrido un problema.</p>
              <p>No puedes eliminar un ${typeAction} si tiene prestamos activos:</p>
              ${prestamosHtml}
            </div>
          `;

            showErrorMessage("", errorMessage);
          } else {
            url = await handleConfirmation(url, type, search, page, typeAction);
          }
        } else {
          url = await handleConfirmation(url, type, search, page, typeAction);
        }
      } catch (error) {
        console.log(error);
      }
    };

    const handleModificado = async (_action) => {
      console.log("Se pulso en modificacion");
      console.log(event.target.href);
      const [response] = await getData(event.target.href);
      console.log(response);
      console.log(_action);
      switch (_action) {
        case "usuarios": {
          const { value: formData } = await showUserForm(response);
          showResponse(formData, response, "user", "usuario");
          break;
        }
        case "autores": {
          const { value: formData } = await showAutorForm(response);

          showResponse(formData, response, "autor", "autor");
          break;
        }
        case "editoriales": {
          const { value: formData } = await showEditorialForm(response);
          showResponse(formData, response, "editorial", "editorial");
          break;
        }
        case "libros": {
          const { value: formData } = await showLibro(response);
          showResponse(formData, response, "libro", "libro");
          break;
        }
        case "prestamos": {
          const { value: formData } = await showPrestamoForm(response);
          showResponse(formData, response, "prestamos", "prestamo");
          break;
        }
        default:
          console.log("no definido");
          break;
      }
    };

    const handleReactivar = async () => {
      url = event.target.href;

      const error = [];

      switch (state) {
        case "verificaReactivacion":
          try {
            const split_libro = event.target.href.split("=");
            const id_libro = split_libro[split_libro.length - 1];

            const { errorAutor, errorEditorial, prestamos } = await postData(
              route,
              {
                id: id_borrado,
                ForeignKey: key,
                ID_Libro: id_libro,
                action: "verificaReactivacion",
              }
            );
            console.log(prestamos);

            if (errorAutor !== undefined) error.push(errorAutor);
            if (errorEditorial !== undefined) error.push(errorEditorial);
            if (prestamos.length !== 0) throw new Error("prestamos activos");
            if (error.length !== 0) {
              throw new Error("Autor y/o editorial estan inactivos");
            }

            url = await handleConfirmation(
              url,
              type,
              search,
              page,
              typeAction,
              "reactivar",
              "Exito",
              "modificado"
            );
          } catch (err) {
            url = undefined;
            console.error(err);

            const librosHtml = error
              .map(
                (el) => `
      <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #ccc;">
        <p style="font-weight: bold; margin-bottom: 5px;">${el}</p>
      </div>
    `
              )
              .join(""); // Agregado join("") para convertir el array en una cadena

            // Mostrar el mensaje de error solo una vez
            let errorMessage = `
            <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #dc3545; background-color: #f8d7da; color: #721c24; border-radius: 5px;">
            <p style="font-weight: bold; margin-bottom: 5px;">Ha ocurrido un problema.</p>
      <p>No puedes eliminar un libro si el <strong>autor</strong> y/o <strong>editorial</strong> estan inactivos. </p>
      ${librosHtml}
    </div>
  `;

            if (err.message === "prestamos activos") {
              errorMessage = `
              <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #dc3545; background-color: #f8d7da; color: #721c24; border-radius: 5px;">
                  <p style="font-weight: bold; margin-bottom: 5px;">Ha ocurrido un problema.</p>
                  <p>No puedes volver a activar este libro porque está actualmente prestado por un usuario. Asegúrate de que el libro haya sido devuelto antes de intentar activarlo nuevamente.</p>
              </div>
          `;
            }

            showErrorMessage("", errorMessage);

            break;
          }
        default:
          url = await handleConfirmation(
            url,
            type,
            search,
            page,
            typeAction,
            "reactivar",
            "Exito",
            "modificado"
          );
          break;
      }
    };

    const handleCambiarImagen = async () => {
      const reponse = await enviarImagen();
      if (reponse !== undefined && reponse !== null) {
        await getData(`${route}&libro=${id_borrado}&Imagen=${reponse}`);
        setTimeout(() => location.reload(), 1000);
      }
    };

    switch (action) {
      case "borrado":
        await handleBorrado();
        break;

      case "modificado":
        await handleModificado(typeAction);
        break;

      case "reactivar":
        await handleReactivar();
        break;
      case "cambiarImagen":
        await handleCambiarImagen();
        break;
      case "reservarLibro":
        const confirmacion = await showConfirmationDialog(
          "¿Estás seguro?",
          "Deseas obtener este libro",
          "Confirmar"
        );

        if (confirmacion) {
          url = event.target.href;
        }
        break;
      case "devolverLibro":
        const confirmacionDevolver = await showConfirmationDialog(
          "¿Estás seguro?",
          "Deseas devolver este libro",
          "Confirmar"
        );

        if (confirmacionDevolver) {
          url = event.target.href;
        }
        break;
      default:
        break;
    }

    if (url !== null && url !== undefined) {
      console.log(url);
      setTimeout(() => {
        window.location.href = url;
      }, 1000);
    }
  });
} catch (error) {
  console.log(error);
}
