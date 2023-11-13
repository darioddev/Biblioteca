import {
  showConfirmationDialog,
  showSuccessMessage,
  showErrorMessage,
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
} from "./formularios.js";
/* Navegador */
initializeUI();

initializeUserModule();

document.querySelector("table").addEventListener("click", async (event) => {
  const action = event.target.dataset.action;
  const typeAction = event.target.dataset.name;

  const type = routeGet("type");
  const search = routeGet("search");
  const page = routeGet("page");
  let url = undefined;

  const handleConfirmation = async (url, type, search, page) => {
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
    } else {
      url = null;
    }

    return url;
  };

  const handleBorrado = async () => {
    url = event.target.href;
    const id_borrado = event.target.dataset.id;
    const key = event.target.dataset.foreignkey;
    const state = event.target.dataset.state;

    try {
      if (state === "verificaEstado") {
        const response = await postData(`${route}`, {
          id: id_borrado,
          ForeignKey: key,
          action: state,
          keyBD: `L.${key}`,
        });
    
        if (response.libros !== undefined && response.libros.length > 0) {
          url = null 
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
        }else {
          url = await handleConfirmation(url, type, search, page);
        }
      } else {
        url = await handleConfirmation(url, type, search, page);
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
        console.log(formData);
        break;
      }
    }
  };

  const handleReactivar = async () => {
    //url = event.target.href;
    try{
      const reponse = await postData(route,{
        id : 2,
        ForeignKey :1,
        action:'verificaReactivacion'
      })
      console.log(reponse)
    }catch(err){
      console.log(err)
    }
  }



    /*
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
    } else {
      url = null;
    }
  };*/

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

    default:
      break;
  }

  if(url !== null && url !== undefined) {
    window.location.href = url 
  }
});

initializeUserInterface();
