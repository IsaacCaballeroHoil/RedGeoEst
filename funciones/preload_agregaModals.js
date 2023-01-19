   // Loading message init...
      $('body').loadingModal({
          animation: 'cubeGrid',
          text: '<b>Loading...</b>',
          backgroundColor : 'blue',
          eText: ['Proceso iniciando...','Procesando informacion...','Estamos trabajando...','Espere un momento...', 'un momento por favor...', 'Disculpe, ésto está tardando un poco más de lo normal...<br>Un momento por favor...', 'Seguimos trabajando...','Sea paciente..','¡Algo ha salido muy mal!<br>Disculpe, por favor recargue la página y vuelva a intentarlo...']
      });
      // Autostart function set...
      window.onload = (function () { $('body').loadingModal('hide'); });

// agregar modal
async function addFooter() {
    const resp = await fetch("funciones/modals.html");
    const html = await resp.text();
    document.body.insertAdjacentHTML("beforeend", html);
} 

async function addFooter2() {
    const resp = await fetch("funciones/modals.html");
    const html = await resp.text();
    document.body.insertAdjacentHTML("beforeend", html);
} 
// beforeend beforebegin

window.addEventListener("DOMContentLoaded", function () {
  addFooter();
  // addFooter2();
});