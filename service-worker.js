const CACHE_NAME = 'nefteprognoz-v1';
const urlsToCache = [
    '/',
    '/index.html',
    '/web.html',
    '/analitic.html',
    '/contact.html',
    '/main.css',
    '/web.css',
    '/an.css',
    '/con.css',
    '/logo_gubkin95_vertical.svg',
    '/form-handler.js',
    '/loaddata.js',
    '/icon-192x192.png',
    '/icon-512x512.png'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(urlsToCache))
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request)
            .then((response) => response || fetch(event.request))
    );
});