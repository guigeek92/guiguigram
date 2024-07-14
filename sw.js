const CACHE_NAME = 'v1';
const urlsToCache = [
  '/',
  '/index.php',
  '/affichage_publication.php',
  '/conversations.php',
  '/databaseconnect.php',
  '/isConnect.php',
  '/header.php',
  '/footer.php',
  '/profil.php',
  '/profilinit.php',
  '/profil_affichage.php',
  '/functions.php',
  '/variables.php',
  '/like.php',
  '/dislike.php',
  '/mysql.php',
  '/recherche_utilisateur.php',
  '/publication.php',
  '/login.php',
  '/submit_login.php',
  '/logout.php',
  '/post_profil.php',
  '/post-publication.php',
  '/post_subscribe.php',
  '/y.PNG',
  '/like.png',
  '/likevide.png',
];

// Installer le service worker et mettre en cache les fichiers
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache).catch(error => {
          console.error('Failed to cache:', error);
        });
      })
  );
});

// Intercepter les requêtes et retourner les fichiers mis en cache
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request).then(
          response => {
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }
            const responseToCache = response.clone();
            caches.open(CACHE_NAME)
              .then(cache => {
                cache.put(event.request, responseToCache);
              });
            return response;
          }
        ).catch(error => {
          console.error('Fetch failed:', error);
        });
      })
  );
});

// Activer le nouveau service worker et supprimer les anciens caches
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (!cacheWhitelist.includes(cacheName)) {
            console.log('Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});
