# Jet Delivery — Configuración de las 3 apps (continuación / referencia)

Tras el corte de luz, esto es el **estado del repo** y lo que **tú** debes hacer en consola/Firebase.

## Ya configurado en el código

| App | Package Android | Nombre visible | Backend (`baseUrl`) |
|-----|-----------------|----------------|----------------------|
| **Usuario** (web + móvil) | `com.jetdeliveryapp.user` | Jet Delivery | `https://admin.jetdeliveryapp.com` |
| **Restaurante** | `com.jetdeliveryapp.restaurante` | Jet Delivery Restaurante | `https://admin.jetdeliveryapp.com` |
| **Reparto** | `com.jetdeliveryapp.delivery` | Jet Delivery Reparto | `https://admin.jetdeliveryapp.com` |

- **`google-services.json`** unificado (las 3 apps) en:
  - `User app and web/android/app/`
  - `Restaurant app/android/app/`
  - `Delivery man app/android/app/`
- **Firebase en Dart**: `Restaurant app/lib/main.dart` y `Delivery man app/lib/main.dart` con proyecto `jet-delivery-a2991`.
- **Web usuario**: `User app and web/lib/main.dart` (Firebase web) + `web/index.html` (meta `google-signin-client_id` con cliente web del JSON).
- **Android**: `namespace` / `applicationId`, manifests, `MainActivity` en el package nuevo; app **Reparto** incluye `BackgroundService` mínimo en `com.jetdeliveryapp.delivery`.
- **Notificaciones**: canal Android `jetdelivery` (manifest + helpers Dart).
- **iOS**: `PRODUCT_BUNDLE_IDENTIFIER` = `com.jetdeliveryapp.restaurante` / `com.jetdeliveryapp.delivery` y nombres en `Info.plist`.

## SHA-1 y SHA-256 (subirlos a Firebase / Play)

En **Firebase Console** → tu proyecto → **Configuración del proyecto** → cada **app Android** → añadir huella.

### Debug (misma máquina para las 3 apps)

```powershell
keytool -list -v -alias androiddebugkey -keystore "$env:USERPROFILE\.android\debug.keystore" -storepass android -keypass android
```

Copia **SHA1** y **SHA256** y regístralos en **cada** app Android (`user`, `restaurante`, `delivery`) si usas el mismo keystore de debug.

### Release (cuando firmes para Play Store)

Sustituye la ruta y alias por tu `upload.keystore` / `key.properties`:

```powershell
keytool -list -v -keystore "C:\ruta\a\tu\release.keystore" -alias TU_ALIAS
```

Vuelve a pegar **SHA1** y **SHA256** en Firebase para **cada** `applicationId` que publiques.

> Sin el SHA-1 de **release**, a veces fallan **Google Sign-In** u otros OAuth en producción.

## iOS — `GoogleService-Info.plist`

Los archivos en:

- `Restaurant app/ios/GoogleService-Info.plist`
- `Delivery man app/ios/GoogleService-Info.plist`

tienen el **proyecto** y **BUNDLE_ID** alineados. El **`GOOGLE_APP_ID`** (y a veces **`CLIENT_ID`**) deben ser los que **Firebase genera** al registrar la app **iOS** con esos bundle IDs.

1. En Firebase: **Añadir app** → **iOS** → bundle `com.jetdeliveryapp.restaurante` (y otra para `com.jetdeliveryapp.delivery`).
2. Descargar el plist oficial y **sustituir** el archivo (o pegar las claves `GOOGLE_APP_ID`, `CLIENT_ID`, `REVERSED_CLIENT_ID`, etc.).

Haz lo mismo para la app **usuario** en iOS si la publicas (`User app and web/ios/…`).

## Logos (`logo.png` / `logo_name.png`)

Las apps **Restaurante** y **Reparto** esperan `assets/image/logo.png` (y `logo_name.png` donde se use).

Copia desde la web del usuario (o tu marca):

```powershell
Copy-Item "C:\Users\cesar\Desktop\Jetdelivery\User app and web\web\logo.png" "C:\Users\cesar\Desktop\Jetdelivery\Restaurant app\assets\image\logo.png" -Force
Copy-Item "C:\Users\cesar\Desktop\Jetdelivery\User app and web\web\logo.png" "C:\Users\cesar\Desktop\Jetdelivery\Restaurant app\assets\image\logo_name.png" -Force
Copy-Item "C:\Users\cesar\Desktop\Jetdelivery\User app and web\web\logo.png" "C:\Users\cesar\Desktop\Jetdelivery\Delivery man app\assets\image\logo.png" -Force
Copy-Item "C:\Users\cesar\Desktop\Jetdelivery\User app and web\web\logo.png" "C:\Users\cesar\Desktop\Jetdelivery\Delivery man app\assets\image\logo_name.png" -Force
```

(Ajusta rutas si tu `logo.png` está en otro sitio.)

## Iconos de launcher (Android/iOS)

En **Usuario** ya hay `flutter_launcher_icons` con `assets/icon/app_icon.png`. Para Restaurante/Reparto puedes añadir la misma sección en cada `pubspec.yaml` y ejecutar:

```bash
dart run flutter_launcher_icons
```

## Comprobar build

```bash
cd "Restaurant app" && flutter pub get && flutter build apk
cd "../Delivery man app" && flutter pub get && flutter build apk
cd "../User app and web" && flutter pub get && flutter build apk
```

---

**Resumen:** La parte de código y Android quedó enlazada a **Jet Delivery** y tu **`google-services.json`**. Falta en tu entorno: **huellas SHA en Firebase**, **plist iOS definitivos** desde la consola, y **copiar logos/iconos** si aún no están en `assets`.
