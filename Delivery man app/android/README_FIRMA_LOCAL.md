# Firma Android — Reparto / Delivery (solo en tu PC)

**No subas** `key.properties`, `.jks` ni `.keystore` a Git ni los compartas.

## 1. Generar el keystore (PowerShell)

Desde la carpeta `Delivery man app/android`:

```powershell
keytool -genkey -v -keystore upload-delivery.jks -keyalg RSA -keysize 2048 -validity 10000 -alias upload `
  -storetype JKS
```

Datos ejemplo: ciudad **Caracas**, país **VE**. Guarda contraseñas solo para ti.

## 2. Crear `key.properties`

Copia `key.properties.example` a `key.properties` en esta misma carpeta `android/` y rellena contraseñas y alias:

```properties
storePassword=TU_CONTRASEÑA_STORE
keyPassword=TU_CONTRASEÑA_KEY
keyAlias=upload
storeFile=upload-delivery.jks
```

El `app/build.gradle.kts` ya usa `key.properties` si existe; si no, release sigue con firma debug.

## 3. SHA para Firebase

```powershell
keytool -list -v -keystore ".\upload-delivery.jks" -alias upload
```

Registra huellas en Firebase → app **`com.jetdeliveryapp.delivery`**.

## 4. Build (Play Store: AAB)

```powershell
cd ..\..
flutter build appbundle --release
```

Para APK local: `flutter build apk --release`.
