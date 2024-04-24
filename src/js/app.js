document.addEventListener("DOMContentLoaded", function () {
  eventListeners();
  darkMode();
  limpiarErrores();
});

function darkMode() {
  const prefiereDarkMode = window.matchMedia("(prefers-color-scheme: dark)");

  // console.log(prefiereDarkMode.matches);

  if (prefiereDarkMode.matches) {
    document.body.classList.add("dark-mode");
  } else {
    document.body.classList.remove("dark-mode");
  }

  prefiereDarkMode.addEventListener("change", function () {
    if (prefiereDarkMode.matches) {
      document.body.classList.add("dark-mode");
    } else {
      document.body.classList.remove("dark-mode");
    }
  });

  const botonDarkMode = document.querySelector(".dark-mode-boton");
  botonDarkMode.addEventListener("click", function () {
    document.body.classList.toggle("dark-mode");
  });
}

function eventListeners() {
  const mobileMenu = document.querySelector(".mobile-menu");

  mobileMenu.addEventListener("click", navegacionResponsive);

  //Muestra campos condicionales
  const metodoContacto = document.querySelectorAll(
    'input[name="contacto[contacto]"]'
  );
  metodoContacto.forEach((input) =>
    input.addEventListener("click", mostrarMetodosContacto)
  );
}

function navegacionResponsive() {
  const navegacion = document.querySelector(".navegacion");

  navegacion.classList.toggle("mostrar");
}

//eliminar msj

document.addEventListener("DOMContentLoaded", function () {
  eventListeners();
  if (window.innerWidth <= 768) {
    temporaryClass(
      document.querySelector(".navegacion"),
      "visibilidadTemporal",
      500
    );
  }

  //Eliminar texto de confirmación de CRUD en admin/index.php
  borraMensaje();
});

function limpiarErrores() {
  const errores = document.querySelectorAll(".alerta");

  if (errores.length !== null) {
    errores.forEach((error) => {
      setTimeout(() => {
        error.remove();
      }, 5000);
    });
  }
}

function mostrarMetodosContacto(e) {
  const contactoDiv = document.querySelector("#contacto");
  if (e.target.value === "telefono") {
    contactoDiv.innerHTML = `

            <label for="telefono">Número de Teléfono</label>
        <input type="tel" placeholder="Tu Teléfono" id="telefono" name="contacto[telefono]">
        <p>Elija la fecha y hora para la llamada</p> 
        <label for="fecha">Fecha</label>
        <input type="date"  id="fecha" name="contacto[fecha]">
        <label for="hora">Hora</label>
        <input type="time"  id="hora" min="09:00" max="18:00" name="contacto[hora]">
    `;
  } else {
    contactoDiv.innerHTML = `
        <label for="email">E-mail</label>
        <input type="email" placeholder="Tu E-mail" id="email" name="contacto[email]" required>
        `;
  }
}
