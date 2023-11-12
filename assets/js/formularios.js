import { postData, getData } from "./axios-functions.js";
import { showErrorMessage, showSuccessMessage } from "./alert-functions.js";

import { route } from "./path.js";

// Definición de la función
export const formularioAnade = async (title = "", html, _action, message) => {
  const { value: formData } = await Swal.fire({
    title,
    html: html, // Se pasa el HTML proporcionado como argumento
    focusConfirm: false,
    showCancelButton: true,
    preConfirm: () => {
      // Selecciona los valores del formulario dinámicamente
      const formValues = {};
      const formInputs = document.querySelectorAll(
        "#usuarioForm input, #usuarioForm select"
      );
      formInputs.forEach((input) => {
        const inputId = input.id;
        formValues[inputId] = input.value;
      });

      return {
        ...formValues,
        action: _action,
      };
    },
  });

  if (formData) {
    try {
      console.log(formData);
      // Hacer la petición con los datos del formulario
      const response = await postData(`${route}`, JSON.stringify(formData));
      console.log(response);

      if (response.errors && response.errors.length > 0) {
        const errorMessage = response.errors
          .map((err) => `<strong>${err.text}</strong>: ${err.message}`)
          .join("<br>");
        showErrorMessage(
          "Oops...",
          `Algo ha salido mal! Vuelve a intentarlo <br> ${errorMessage}`
        );
      } else if (response.error) {
        showErrorMessage(
          "Oops...",
          `Algo ha salido mal! Vuelve a intentarlo <br> ${response.error}`
        );
      } else {
        showSuccessMessage(`${message} añadido correctamente`);
      }
    } catch (error) {
      console.error("Error en la petición:", error);
      showErrorMessage("Oops...", "Algo ha salido mal! Vuelve a intentarlo");
    }
  }
};

export const showUserForm = (response) => {
  return Swal.fire({
    title: `Datos personales de usuario : ${response.NOMBRE_USUARIO}`,
    html: `
      <form action="/" method="POST"> 
        <label for="NOMBRE">Nombre:</label>
        <input type="text" id="NOMBRE" class="swal2-input" placeholder="Nombre" value="${
          response.NOMBRE
        }" required>
  
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
        correo_electronico: document.getElementById("CORREO_ELECTRONICO").value,
        fecha_registro: document.getElementById("FECHA_REGISTRO").value,
        rol: document.getElementById("ROL").value,
      };
    },
  });
};
export const showAutorForm = (response) => {
  return Swal.fire({
    title: `Datos personales de autor : ${response.NOMBRE} ${response.APELLIDO}`,
    html: `<form id="usuarioForm">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" class="swal2-input" placeholder="Nombre" value="${response.NOMBRE}"required>
      
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" class="swal2-input" placeholder="Apellido" value="${response.APELLIDO}"required>
      
            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" class="swal2-input" value="${response.FECHA_NACIMIENTO}"required>

            <label for="fecha_creacion">Fecha de Creacion:</label>
            <input type="datetime-local" id="fecha_creacion" class="swal2-input" value="${response.FECHA_CREACION}"required>

            <label for="fecha_modificacion">ULTIMA FECHA DE MODIFICACION:</label>
            <input type="text" id="fecha_modificacion" class="swal2-input" placeholder="Nombre de Usuario" value="${response.FECHA_MODIFICACION}" disabled style="text-align: center;">
      
          </form>`,

    focusConfirm: false,
    showCancelButton: true,
    preConfirm: () => {
      return {
        nombre: document.getElementById("nombre").value,
        apellido: document.getElementById("apellido").value,
        fecha_nacimiento: document.getElementById("fecha_nacimiento").value,
        fecha_creacion: document.getElementById("fecha_creacion").value,
      };
    },
  });
};

export const showResponse = async (
  formData,
  response,
  paramt,
  message = ""
) => {
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
              `${route}?${paramt}=${response.ID}&${key}=${value}`
            );
            console.log()
            console.log(result)

            if (
              result[1] !== undefined &&
              Object.keys(result[1]).length !== 0
            ) {
              throw new Error(result[1].error);
            }

            showSuccessMessage(
              "Modificado!",
              `El ${message} ha sido modificado.`
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
};
