
const body = document.querySelector("body"),
  sidebar = body.querySelector("nav"),
  toogle = body.querySelector(".toogle"),
  searchBtn = body.querySelector(".search-box"),
  modeSwitch = body.querySelector(".toogle-switch"),
  modeText = body.querySelector(".mode-text");

  toogle.addEventListener('click',() => sidebar.classList.toggle("close"))

  searchBtn.addEventListener('click',() => sidebar.classList.remove("close"))

  modeSwitch.addEventListener('click',() => {
    body.classList.toggle("dark");
      if(body.classList.contains("dark")) {
        modeText.innerText = "Light mode"
      }else {
        modeText.innerText = "Dark mode"
      }
  })







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


