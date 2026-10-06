# Firma Android — Restaurante (solo en tu PC)

**No subas** `key.properties`, `.jks` ni `.keystore` a Git ni los compartas.  
Las contraseñas **nunca** deben ir en el código ni en chats.

## 1. Generar el keystore (PowerShell)

Desde la carpeta `Restaurant app/android` (ajusta alias y nombres):

```powershell
keytool -genkey -v -keystore upload-restaurante.jks -keyalg RSA -keysize 2048 -validity 10000 -alias upload `
  -storetype JKS
```

Te pedirá contraseña del keystore y del key, y datos (nombre, organización, ciudad **Caracas**, país **VE**, etc.).  
**Guarda las contraseñas** en un gestor seguro (no en el repositorio).

## 2. Crear `key.properties` (misma carpeta `android/`)

Copia `key.properties.example` a `key.properties` y rellena con **tus** valores reales:

```properties
storePassword=TU_CONTRASEÑA_STORE
keyPassword=TU_CONTRASEÑA_KEY
keyAlias=upload
storeFile=upload-restaurante.jks
```

Si el `.jks` está en `android/`, `storeFile` puede ser solo el nombre del archivo.

## 3. SHA-1 y SHA-256 para Firebase

```powershell
keytool -list -v -keystore ".\upload-restaurante.jks" -alias upload
```

Pega **SHA1** y **SHA256** en Firebase → app Android **`com.jetdeliveryapp.restaurante`**.

## 4. Compilar

```powershell
cd ..\..
flutter build apk --release
```

El `build.gradle.kts` ya usa `key.properties` si existe; si no, sigue usando firma debug.
