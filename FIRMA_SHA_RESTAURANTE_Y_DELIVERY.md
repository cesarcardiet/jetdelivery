# SHA-1 y SHA-256 para Firebase — Restaurante y luego Delivery

En Firebase debes registrar las huellas en **cada app Android** por separado:

| App        | Package name                    |
|-----------|----------------------------------|
| Restaurante | `com.jetdeliveryapp.restaurante` |
| Reparto     | `com.jetdeliveryapp.delivery`    |

Ruta en consola: **Firebase → ⚙️ Configuración del proyecto → Tus apps → [app Android] → Añadir huella digital**.

---

## 1) Restaurante — huellas que usa tu APK hoy

### A) Si compilas **release** sin `key.properties` (como hasta ahora)

El APK release está firmado con el keystore de **debug**. Obtén SHA-1 y SHA-256:

**PowerShell (Windows):**

```powershell
keytool -list -v -alias androiddebugkey -keystore "$env:USERPROFILE\.android\debug.keystore" -storepass android -keypass android
```

Busca en la salida las líneas **SHA1:** y **SHA256:** y cópialas en Firebase en la app **Restaurante** (`com.jetdeliveryapp.restaurante`).

### B) Cuando uses keystore de **publicación** (Play Store)

1. Crea `Restaurant app/android/key.properties` (plantilla: `key.properties.example`).
2. Genera el APK/AAB con `flutter build apk --release` o `flutter build appbundle`.
3. Lista huellas del **mismo** `.jks` / `.keystore`:

```powershell
keytool -list -v -keystore "C:\RUTA\A\tu-archivo.jks" -alias TU_ALIAS
```

(Pide la contraseña del keystore si no la pusiste en el comando.)

Registra **ese** SHA-1 y SHA-256 en Firebase para **Restaurante**.

> Importante: las huellas de **debug** y de **release** son distintas. En Firebase puedes tener **varias** huellas en la misma app Android (añade debug para pruebas y release para producción).

---

## 2) Delivery (Reparto) — después del Restaurante

Mismo proceso, pero en la app Android **`com.jetdeliveryapp.delivery`** en Firebase.

- Si **Delivery** también firma release con **debug** (revisa `Delivery man app/android/app/build.gradle.kts`), el comando del **debug.keystore** es el **mismo**; aun así debes **pegar las huellas en la segunda app** en Firebase (cada package es una app distinta).
- Si cada proyecto usa su propio `key.properties`, ejecuta `keytool` contra el keystore correspondiente.

Comando debug (por si lo necesitas otra vez):

```powershell
keytool -list -v -alias androiddebugkey -keystore "$env:USERPROFILE\.android\debug.keystore" -storepass android -keypass android
```

---

## 3) Comprobar que Gradle use tu keystore de release (Restaurante)

En el repo ya está configurado: si existe `Restaurant app/android/key.properties`, el build **release** usa ese keystore; si no existe, sigue usando **debug** para no romper el build.

---

## Resumen rápido

1. Ejecuta `keytool` (debug o tu `.jks`).
2. Copia **SHA-1** y **SHA-256**.
3. Firebase → app **Restaurante** → Añadir huella digital → pegar ambas.
4. Repite en la app **Delivery** cuando toque.

No hace falta “subir” el SHA al código: solo a la **consola de Firebase**.
