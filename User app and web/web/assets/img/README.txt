Para el preloader del index.html (antes de cargar Flutter):

  logo.png  — obligatorio (mismo que assets/image/logo.png)

  logo_name.png — opcional; ya no se usa en el HTML para evitar “doble logo” si ambos son cuadrados.

Copiar desde la raíz del proyecto:

  .\scripts\copy_web_branding.ps1

Subir al servidor: el contenido de build/web/ después de flutter build web --release
(no subir solo esta carpeta web/ suelta).
