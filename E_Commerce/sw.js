const CACHE_NAME = "ventas-pwa-v1";
const urlsToCache = [
  "/",
  "/index.php",
  "/admin_ventas.php",
  "/dashboard.php",
  "/css/estilos.css",
  "/assets/icons/icon-192.png",
  "/assets/icons/icon-192.png",
  "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
];

// 🧩 INSTALAR Y GUARDAR EN CACHÉ
self.addEventListener("install", e => {
  e.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
  );
});

// ♻️ ACTIVAR Y LIMPIAR VERSIONES ANTIGUAS
self.addEventListener("activate", e => {
  e.waitUntil(
    caches.keys().then(keys =>
      Promise.all(keys.map(k => k !== CACHE_NAME && caches.delete(k)))
    )
  );
});

// ⚡ INTERCEPTAR PETICIONES Y SERVIR DESDE CACHÉ
self.addEventListener("fetch", e => {
  e.respondWith(
    caches.match(e.request).then(res => res || fetch(e.request))
  );
});
