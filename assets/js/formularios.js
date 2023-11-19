import { postData, getData } from "./axios-functions.js";
import {
  showErrorMessage,
  showSuccessMessage,
  showInformationMessage,
  showConfirmationDialog,
} from "./alert-functions.js";
import { selects } from "./ui-module.js";
import { route } from "./path.js";

export const enviarImagen = async () => {
  const { value: file } = await Swal.fire({
    title: "Selecciona una imagen",
    input: "file",
    inputAttributes: {
      accept: "image/*",
      "aria-label": "Introduce una imagen; este campo es obligatorio",
    },
  });

  if (file) {
    const formData = new FormData();
    formData.append("Archivo", file);

    try {
      const response = await axios.post(
        `${
          window.parent.location.origin
        }${window.parent.location.pathname.replace(
          /\/index\.php$/,
          ""
        )}/procesa_imagen.inc.php?token=libros`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );

      console.log(response.data);

      if (response.data.success) {
        console.log("Éxito:", response.data.success);
        return response.data.nombreArchivo;
      } else {
        console.error("Error:", response.data.error);
      }
    } catch (error) {
      console.error(error);
    }
  }

  return null;
};

const enviarFormulario = async (formData, _action, message) => {
  console.log("entre a enviar");
  try {
    // Realiza la petición con los datos del formulario
    const response = await postData(`${route}`, formData);

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
      if (formData.action === "insertarLibro") {
        const nombreArchivo = await enviarImagen();
        if (nombreArchivo === null) throw new Error("Imagen no introducida");
        response.data.nombreArchivo = nombreArchivo;
        response.data.action = "insertarLibroSucces";
        await postData(route, response.data);
        showSuccessMessage(`${message} añadido correctamente`);
      } else {
        showSuccessMessage(`${message} añadido correctamente`);
      }
    }
    console.log(response);
  } catch (error) {
    console.error("Error en la petición:", error);
    showErrorMessage("Oops...", "Algo ha salido mal! Vuelve a intentarlo");
  }
};

export const formularioAnade = async (title = "", html, _action, message) => {
  const { value: formData } = await Swal.fire({
    title,
    html,
    focusConfirm: false,
    showCancelButton: true,
    preConfirm: () => {
      const formValues = {};
      const formInputs = document.querySelectorAll(
        "#usuarioForm input, #usuarioForm select"
      );

      formInputs.forEach((input) => {
        const inputId = input.id;

        // Verifica si el campo es de tipo 'file'
        if (input.type === "file") {
          formValues[inputId] = input.files[0]; // Almacena el archivo directamente
        } else {
          formValues[inputId] = input.value;
        }
      });

      return {
        ...formValues,
        action: _action,
      };
    },
  });

  if (formData) {
    console.log(formData);
    await enviarFormulario(formData, _action, message);
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
        }" required style="text-align: center;>

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
    <input type="text" id="nombre" class="swal2-input" placeholder="Nombre" value="${response.NOMBRE}" required>

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" class="swal2-input" placeholder="Apellido" value="${response.APELLIDO}" required>

    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
    <input type="date" id="fecha_nacimiento" class="swal2-input" value="${response.FECHA_NACIMIENTO}" required style="text-align: center;>

    <label for="fecha_creacion">Fecha de Creacion:</label>
    <input type="date" id="fecha_creacion" class="swal2-input" value="${response.FECHA_CREACION}" required style="text-align: center;>

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

export const showLibro = async (response) => {
  const selectAutor = await selects(`${route}&autor=all`, response.ID_Autor);
  const selectEditorial = await selects(
    `${route}&editorial=all`,
    response.ID_Editorial
  );

  const autorSelect = document.createElement("select");
  autorSelect.id = "ID_Autor";
  autorSelect.className = "swal2-select";

  const editorialSelect = document.createElement("select");
  editorialSelect.id = "ID_Editorial";
  editorialSelect.className = "swal2-select";

  selectAutor.map((el) => {
    autorSelect.append(el);
  });

  selectEditorial.map((el) => {
    editorialSelect.append(el);
  });

  return Swal.fire({
    title: `Datos personales del libro : ${response.Titulo} `,
    html: `<form id="usuarioForm">
            <label for="Titulo">Titulo:</label>
            <input type="text" id="Titulo" class="swal2-input" placeholder="Titulo" value="${response.Titulo}"required>

            <label for="ID_Autor">Seleccione el autor :</label>
            ${autorSelect.outerHTML}
    
            <label for="ID_Editorial">Seleccione la editorial :</label>
            ${editorialSelect.outerHTML}

            <div></div>
            <label for="fecha_creacion">Fecha de Creacion:</label>
            <input type="date" id="fecha_creacion" class="swal2-input" value="${response.fecha_creacion}" required style="text-align: center;>
        
            <label for="fecha_modificacion">ULTIMA FECHA DE MODIFICACION:</label>
            <input type="text" id="fecha_modificacion" class="swal2-input" placeholder="Nombre de Usuario" value="${response.Fecha_modificacion}" disabled style="text-align: center;">

          </form>`,

    focusConfirm: false,
    showCancelButton: true,
    preConfirm: () => {
      return {
        Titulo: document.getElementById("Titulo").value,
        ID_Autor: document.getElementById("ID_Autor").value,
        ID_Editorial: document.getElementById("ID_Editorial").value,
        fecha_creacion: document.getElementById("fecha_creacion").value,
      };
    },
  });
};

export const showEditorialForm = (response) => {
  return Swal.fire({
    title: `Datos personales de autor : ${response.NOMBRE} `,
    html: `<form id="usuarioForm">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" class="swal2-input" placeholder="Nombre" value="${response.NOMBRE}"required>

            <label for="fecha_creacion">Fecha de Creacion:</label>
            <input type="date" id="fecha_creacion" class="swal2-input" value="${response.FECHA_CREACION}" required style="text-align: center;>
        
            <label for="fecha_modificacion">ULTIMA FECHA DE MODIFICACION:</label>
            <input type="text" id="fecha_modificacion" class="swal2-input" placeholder="Nombre de Usuario" value="${response.FECHA_MODIFICACION}" disabled style="text-align: center;">



          </form>`,

    focusConfirm: false,
    showCancelButton: true,
    preConfirm: () => {
      return {
        nombre: document.getElementById("nombre").value,
        fecha_creacion: document.getElementById("fecha_creacion").value,
      };
    },
  });
};

export const showPrestamoForm = (response) => {
  return Swal.fire({
    title: `Datos del prestamo sobre el usuario: <strong>${response.UsuarioNombreUsuario}</strong> `,
    html: `<form id="usuarioForm">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" class="swal2-input" placeholder="Nombre" value="${response.NombreUsuario}" disabled>

            <label for="CorreoElectronico">Correo Electrónico:</label>
            <input type="email" id="CorreoElectronico" class="swal2-input" value="${response.CorreoElectronico}" disabled>

            <label for="NombreLibro">Nombre del libro :</label>
            <input type="email" id="NombreLibro" class="swal2-input" value="${response.NombreLibro}" disabled>

            <label for="fecha_creacion">Fecha de inicio:</label>
            <input type="date" id="fecha_creacion" class="swal2-input" value="${response.Fecha_inicio}" disabled style="text-align: center;>
        
            <label for="dias_restantes">Dias restantes :</label>
            <input type="number" id="dias_restantes" class="swal2-input" min="5" max="30" value="${response.dias_restantes}"  style="text-align: center;">



          </form>`,

    focusConfirm: false,
    showCancelButton: true,
    preConfirm: () => {
      return {
        dias_restantes: document.getElementById("dias_restantes").value,
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
            let mensaje;

            const result = await getData(
              `${route}&${paramt}=${response.ID}&${key}=${value}`
            );
            console.log(result);
            if (
              result[1] !== undefined &&
              Object.keys(result[1]).length !== 0
            ) {
              throw new Error(result[1].error);
            }

            if (message !== "") {
              mensaje = `El ${message} ha sido modificado.`;
            } else {
              mensaje = "Los datos han sido modificados";
            }

            showSuccessMessage("Modificado!", mensaje);

            setTimeout(() => {
              location.reload();
            }, 3000);
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
export const formularioDatos = () => {
  try {
    const formularioInformaiconPersonal =
      document.getElementById("formularioPersonal");

    formularioInformaiconPersonal.addEventListener("submit", async (event) => {
      event.preventDefault();
      await showInformationMessage(
        "Antes de modificar tus datos, ten en cuenta que esto conlleva cerrar tu sesión."
      );
      const formData = {
        ID: formularioInformaiconPersonal["ID"].value,
        nombre: formularioInformaiconPersonal["nombre"].value,
        apellido: formularioInformaiconPersonal["apellido"].value,
        apellido2: formularioInformaiconPersonal["apellido2"].value || "",
        nombre_usuario: formularioInformaiconPersonal["nombre_usuario"].value,
        correo_electronico: formularioInformaiconPersonal["correo"].value,
      };
      const confirmaEnvio = await showConfirmationDialog(
        "¿Estás seguro?",
        "¿Deseas modificar tus datos? , esto conlleva cerrar tu session.",
        "Si, modificar"
      );

      if (confirmaEnvio) {
        const [response] = await getData(event.target.action);
        try {
          const respuesta = await showResponse(formData, response, "user", "");
          console.log(respuesta);
          /*setTimeout(() => {
            window.location.href = formularioInformaiconPersonal["url"].value;
          }, 2000);*/
        } catch (error) {
          console.log(error);
        }
      }
    });
  } catch (error) {
    console.log(error);
  }
};

export const createSelect = (id, className, options) => {
  const select = document.createElement("select");
  select.id = id;
  select.className = className;

  options.forEach((el) => {
    select.append(el);
  });

  return select;
};
