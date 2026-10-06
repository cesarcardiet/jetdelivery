package com.jetdeliveryapp.delivery

import android.app.Service
import android.content.Intent
import android.os.IBinder

/**
 * Declarado en AndroidManifest; implementación mínima para que compile tras el cambio de package.
 * Si un plugin requería lógica extra en el servicio antiguo, amplía aquí según la documentación del plugin.
 */
class BackgroundService : Service() {
  override fun onBind(intent: Intent?): IBinder? = null
}
