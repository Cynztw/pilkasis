const CACHE_NAME = 'pilkasis-v1';
const STATIC_ASSETS = [
  '/webprosm2/pilkasis/',
  '/webprosm2/pilkasis/index.php',
  '/webprosm2/pilkasis/public/css/style.css',
  '/webprosm2/pilkasis/public/js/main.js',
  '/webprosm2/pilkasis/public/js/app.js',
];

// Install event - cache assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(STATIC_ASSETS).catch((err) => {
        console.log('Cache addAll error:', err);
        return Promise.resolve();
      });
    })
  );
  self.skipWaiting();
});

// Activate event - clean old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch event - cache first, fallback to network
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip cross-domain requests
  if (url.origin !== location.origin) {
    return;
  }

  // Skip API requests, POST/PUT/DELETE
  if (request.method !== 'GET') {
    return;
  }

  // Network first for HTML documents
  if (request.destination === 'document') {
    event.respondWith(
      fetch(request)
        .then((response) => {
          const responseClone = response.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, responseClone);
          });
          return response;
        })
        .catch(() => {
          return caches.match(request).then((cachedResponse) => {
            return cachedResponse || new Response('Offline - Halaman tidak tersedia');
          });
        })
    );
    return;
  }

  // Cache first for other assets
  event.respondWith(
    caches.match(request).then((cachedResponse) => {
      if (cachedResponse) {
        return cachedResponse;
      }

      return fetch(request)
        .then((response) => {
          if (!response || response.status !== 200 || response.type === 'error') {
            return response;
          }

          const responseClone = response.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, responseClone);
          });

          return response;
        })
        .catch(() => {
          return new Response('Offline - Resource tidak tersedia', { status: 503 });
        });
    })
  );
});

// Background sync untuk submit vote saat online
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-vote') {
    event.waitUntil(
      fetch('/webprosm2/pilkasis/api/sync-vote.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
      })
        .then(() => {
          console.log('Vote berhasil disinkronkan');
        })
        .catch((err) => {
          console.log('Sync vote gagal:', err);
          return Promise.reject();
        })
    );
  }
});
