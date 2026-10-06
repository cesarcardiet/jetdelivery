importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js");

firebase.initializeApp({
  apiKey: "AIzaSyBnbWoGbmEy3cUdBOjoenMVjLgrEw3leQQ",
  authDomain: "jet-delivery-fbffa.firebaseapp.com",
  projectId: "jet-delivery-fbffa",
  storageBucket: "jet-delivery-fbffa.firebasestorage.app",
  messagingSenderId: "716561771391",
  appId: "1:716561771391:web:2bc6110988c06c8bf8084a",
  measurementId: "G-L2YYD63RL3",
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    const promiseChain = clients
        .matchAll({
            type: "window",
            includeUncontrolled: true
        })
        .then(windowClients => {
            for (let i = 0; i < windowClients.length; i++) {
                const windowClient = windowClients[i];
                windowClient.postMessage(payload);
            }
        })
        .then(() => {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.score
              };
            return registration.showNotification(title, options);
        });
    return promiseChain;
});
self.addEventListener('notificationclick', function (event) {
    console.log('notification received: ', event)
});