importScripts("https://www.gstatic.com/firebasejs/10.12.4/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.12.4/firebase-messaging-compat.js");

firebase.initializeApp({
  apiKey: "AIzaSyCH4XzfOgXcty45oLzXW0tBuCQuPpNtIlU",
  authDomain: "kandura-system.firebaseapp.com",
  projectId: "kandura-system",
  storageBucket: "kandura-system.firebasestorage.app",
  messagingSenderId: "459778160847",
  appId: "1:459778160847:web:bf88f5684760559f839fba",
});

const messaging = firebase.messaging();

// ✅ Background notifications only (service worker)
messaging.onBackgroundMessage((payload) => {
  console.log("[SW] bg message:", payload);

  const title = payload?.notification?.title || payload?.data?.title || "New Notification";
  const options = {
    body: payload?.notification?.body || payload?.data?.body || "",
    icon: "/favicon.ico",
    data: payload?.data || {},
  };

  self.registration.showNotification(title, options);
});

self.addEventListener("notificationclick", (event) => {
  event.notification.close();
  const url = event.notification?.data?.url || "/dashboard/notifications";
  event.waitUntil(clients.openWindow(url));
});
