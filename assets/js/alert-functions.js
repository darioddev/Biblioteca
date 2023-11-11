// En tu módulo con las funciones de diálogo
export const showConfirmationDialog = async (
  title,
  text,
  confirmation = "Si"
) => {
  try {
    const result = await Swal.fire({
      title,
      text,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: confirmation,
      cancelButtonText: "Cancelar",
    });

    return result.isConfirmed;
  } catch (error) {
    console.log(error);
    return false; // Devolver false en caso de error
  }
};

export const showSuccessMessage = (title, text = "") => {
  Swal.fire({
    title,
    text,
    icon: "success",
    showCancelButton: false,
    showConfirmButton: false,
    timer: 1500,
  });
};

export const showErrorMessage = (title, html) => {
  Swal.fire({
    title,
    html,
    icon: "error",
  });
};

export const showTime = (title, html = '' ,_timer = 3000) => {
  let timerInterval;
  Swal.fire({
    title,
    html,
    timer: _timer,
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
    }
  })
}