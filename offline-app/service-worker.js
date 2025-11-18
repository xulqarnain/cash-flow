const CACHE_NAME = 'cash-record-offline-v1';
const OFFLINE_URL = '/offline.html';

// Cache all static assets for offline use
const urlsToCache = [
  '/',
  '/index.html',
  '/app.js',
  '/manifest.json',
  '/icon-192.png',
  '/icon-512.png',
  '/offline.html',
  // CDN resources
  'https://cdn.tailwindcss.com',
  'https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js'
];

// Install event - cache all resources
self.addEventListener('install', event => {
  console.log('[Service Worker] Installing...');

  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('[Service Worker] Caching app shell');
        // Don't fail if CDN caching fails
        return cache.addAll(urlsToCache.slice(0, -2))
          .catch(err => {
            console.warn('[Service Worker] Failed to cache some resources:', err);
            // Cache what we can
            return Promise.all(
              urlsToCache.map(url => {
                return cache.add(url).catch(err => {
                  console.warn('[Service Worker] Failed to cache:', url);
                });
              })
            );
          });
      })
      .then(() => self.skipWaiting())
  );
});

// Activate event - clean old caches
self.addEventListener('activate', event => {
  console.log('[Service Worker] Activating...');

  event.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            if (cacheName !== CACHE_NAME) {
              console.log('[Service Worker] Deleting old cache:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => self.clients.claim())
  );
});

// Fetch event - offline-first strategy
self.addEventListener('fetch', event => {
  // Skip non-GET requests
  if (event.request.method !== 'GET') {
    return;
  }

  // Skip chrome-extension and other non-http(s) requests
  if (!event.request.url.startsWith('http')) {
    return;
  }

  event.respondWith(
    caches.match(event.request)
      .then(cachedResponse => {
        // Return cached version if available
        if (cachedResponse) {
          console.log('[Service Worker] Serving from cache:', event.request.url);
          return cachedResponse;
        }

        // Otherwise fetch from network
        return fetch(event.request)
          .then(response => {
            // Cache successful responses (only same-origin or CORS-enabled)
            if (response && response.status === 200) {
              // Clone the response
              const responseToCache = response.clone();

              // Don't cache if it's not from our origin or a CDN we trust
              const url = new URL(event.request.url);
              if (url.origin === location.origin ||
                  url.hostname.includes('cdn.tailwindcss.com') ||
                  url.hostname.includes('cdn.jsdelivr.net')) {

                caches.open(CACHE_NAME)
                  .then(cache => {
                    cache.put(event.request, responseToCache);
                    console.log('[Service Worker] Cached new resource:', event.request.url);
                  });
              }
            }

            return response;
          })
          .catch(error => {
            console.error('[Service Worker] Fetch failed:', error);

            // If offline and no cache, show offline page
            if (event.request.mode === 'navigate') {
              return caches.match(OFFLINE_URL);
            }

            // For other requests, just fail
            return new Response('Offline', {
              status: 503,
              statusText: 'Service Unavailable'
            });
          });
      })
  );
});

// Handle messages from the main app
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
