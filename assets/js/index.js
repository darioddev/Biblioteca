import {
  showConfirmationDialog,
  showSuccessMessage,
  showErrorMessage,
} from "./alert-functions.js";
import { getData } from "./axios-functions.js";

import { routeGet } from "./path.js";
import {
  initializeUI,
  initializeUserModule,
  initializeUserInterface,
} from "./ui-module.js";
import { showUserForm ,showResponse , showAutorForm , showEditorialForm} from "./formularios.js";
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
    } else {
      url = null;
    }
  };

  const handleModificado = async (_action) => {
    console.log("Se pulso en modificacion");
    const [response] = await getData(event.target.href);
    console.log(response)
    switch(_action) {
        case "usuarios" : {
          const { value: formData } = await showUserForm(response);
          showResponse(formData, response, "user", "usuario");
          break;
        }
        case "autores" : {
          const { value: formData } = await showAutorForm(response);

          showResponse(formData,response,"autor","autor")
          break;
        }
        case "editoriales" : {
          const { value: formData } = await showEditorialForm(response);
          showResponse(formData,response,"editorial","editorial")
          break;
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
    } else {
      url = null;
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

    default:
      break;
  }
  
  if(url !== null && url !== undefined) {
    window.location.href = url 
  }
});

initializeUserInterface();
