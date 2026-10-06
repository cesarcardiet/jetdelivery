# Copia el logo de la app Flutter a la carpeta web/ (preloader y cabecera HTML antes de cargar Flutter).
# Ejecutar desde la raíz del proyecto:  .\scripts\copy_web_branding.ps1

$root = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)
$srcDir = Join-Path $root "assets\image"
$dstDir = Join-Path $root "web\assets\img"

New-Item -ItemType Directory -Force -Path $dstDir | Out-Null

# Obligatorio para preloader HTML
$logo = Join-Path $srcDir "logo.png"
if (Test-Path $logo) {
    Copy-Item -Path $logo -Destination (Join-Path $dstDir "logo.png") -Force
    Write-Host "OK: logo.png -> web\assets\img\"
} else {
    Write-Warning "Falta: $logo"
}

# Opcional (no se usa en index.html para evitar doble icono)
$name = Join-Path $srcDir "logo_name.png"
if (Test-Path $name) {
    Copy-Item -Path $name -Destination (Join-Path $dstDir "logo_name.png") -Force
    Write-Host "OK: logo_name.png -> web\assets\img\ (opcional)"
}

Write-Host "`nEditas: web\ + lib\  |  Compilas: flutter build web --release  |  Subes: build\web\*"
