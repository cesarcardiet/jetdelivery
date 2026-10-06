# Jet Delivery — Web de usuario (Flutter): carpetas y subida al servidor

## Dos carpetas distintas (importante)

| Carpeta | Qué es | ¿La subes al servidor? |
|--------|--------|-------------------------|
| **`web/`** | Plantilla HTML/CSS/JS **fuente**: `index.html`, `style.css`, `manifest.json`, `assets/img/`, etc. Flutter **la copia y mezcla** al compilar. | **No** subes solo esta carpeta tal cual. |
| **`build/web/`** | **Salida** de `flutter build web --release`: Dart compilado + archivos de `web/` ya procesados. | **Sí.** Esto es lo que subes con **FileZilla** (todo el contenido de `build/web/`). |

Flujo correcto:

1. Editas lo que necesites en **`web/`** y en **`lib/`**.
2. (Opcional) `.\scripts\copy_web_branding.ps1` → copia `logo.png` a `web/assets/img/` para el preloader del `index.html`.
3. **`flutter build web --release`**
4. Subes **solo** los archivos dentro de **`build/web/`** al hosting (raíz del sitio o subcarpeta, según tu Nginx).

La web que “lanza” el usuario en producción es la de **`build/web/`**, no la carpeta `web/` suelta.

---

## Logo doble (cuadrado + cuadrado)

En muchos proyectos, `logo.png` y `logo_name.png` son **dos iconos parecidos**. Mostrar los dos juntos parecía **doble logo**.

- En **`web/index.html`** (preloader y cabecera) ahora solo se usa **`logo.png`**.
- En la app (**menú web, splash, login, footer**) también quedó **un solo** `Images.logo`.

Si más adelante tienes un **`logo_name.png` en horizontal** (solo texto “Jet Delivery”), se puede volver a mostrar junto al icono solo en las pantallas que quieras.

---

## Iconos PWA / favicon

```bash
dart run flutter_launcher_icons
```

## Compilar

```bash
flutter build web --release
```

## Probar en local

```bash
flutter run -d chrome
```

## API

`lib/util/app_constants.dart` → `baseUrl` (ej. `https://admin.jetdeliveryapp.com`).
