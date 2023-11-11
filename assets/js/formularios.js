import { postData } from "./axios-functions.js";
import { showErrorMessage, showSuccessMessage } from "./alert-functions.js";
import { route } from "./path.js";


export const formularioNuevoUsuario = async (e) => {
  const { value: formData } = await Swal.fire({
    title: "Registro de Usuario",
    html: `
          <form>
            <label for="name">Nombre:</label>
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
        name: document.getElementById("name").value,
        last_name1: document.getElementById("last_name1").value,
        last_name2: document.getElementById("last_name2").value || "",
        user: document.getElementById("user").value,
        contrasena: document.getElementById("contrasena").value,
        email: document.getElementById("email").value,
        fechaRegistro: document.getElementById("fechaRegistro").value || null,
        rol: document.getElementById("rol").value,
      };
    },
  });

  if (formData) {
    try {
      const response = await postData(`${route}`, JSON.stringify(formData));

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
        showSuccessMessage("Usuario añadido correctamente");
      }
    } catch (error) {
      console.error("Error en la petición:", error);
      showErrorMessage("Oops...", "Algo ha salido mal! Vuelve a intentarlo");
    }
  }
};

