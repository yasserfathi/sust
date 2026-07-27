import { createApp } from "vue";
import { createPinia } from "pinia";
import { ZiggyVue } from "ziggy-js";
import { useAuthStore } from "./store/index";
import { Ziggy } from "./ziggy.js";
import axios from "axios";
import router from "./router";
import App from "./App.vue";
import { ar } from 'vuetify/locale'

import { aliases, mdi } from 'vuetify/iconsets/mdi';
import "vuetify/styles";
import "../../css/app.scss";
// Axios Setup
function configureAxios(pinia) {
  axios.defaults.baseURL = "/api";
  axios.defaults.headers.common = {
    "Content-Type": "application/json",
    Accept: "application/json",
    "X-Requested-With": "XMLHttpRequest",
  };
  axios.defaults.withCredentials = true;
  axios.defaults.timeout = 10000;

  axios.interceptors.request.use((config) => {
    const token = localStorage.getItem("authToken");
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
  });

  let isRedirecting = false;
  axios.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response && (error.response.status === 401 || error.response.status === 419) && !isRedirecting) {
        isRedirecting = true; // Prevent multiple redirects

        const authStore = useAuthStore(pinia);
        authStore.clearAuth();
        localStorage.removeItem("authToken");

        // Only set redirectUrl if we're not currently stuck on the login page itself
        if (router.currentRoute.value.name !== 'Login' && router.currentRoute.value.path !== '/') {
          localStorage.setItem("redirectUrl", router.currentRoute.value.fullPath);
        }

        router.push({ name: 'Login' }).finally(() => {
          isRedirecting = false; // Reset the flag so future 401s are caught!
        });
      }
      return Promise.reject(error);
    }
  );
}

// Vuetify Setup
async function loadVuetify() {
  const { createVuetify } = await import("vuetify");
  const components = await import("vuetify/components");
  const directives = await import("vuetify/directives");

  return createVuetify({
    components,
    directives,
    locale: {
      locale: 'ar',
      fallback: 'ar',
      messages: { ar },
    },
    icons: {
      defaultSet: 'mdi',
      aliases,
      sets: {
        mdi,
      },
    },
    theme: {
      defaultTheme: "light",
      themes: {
        light: {
          colors: {
            primary: "#d65440",
            secondary: "#424949",
            error: "#ff5252",
            info: "#2196F3",
            success: "#4CAF50",
            warning: "#FFC107",
          },
        },
        dark: {
          colors: {
            primary: "#d65440",
            secondary: "#5CBBF6",
          },
        },
      },
    },
  });
}

// App Bootstrap
async function initializeApp() {
  try {
    const app = createApp(App);
    const pinia = createPinia();
    const vuetify = await loadVuetify();

    configureAxios(pinia);

    // Decouple Ziggy from the build URL by using the runtime origin
    Ziggy.url = window.location.origin;
    Ziggy.port = null;
    window.Ziggy = Ziggy; // Make it globally available so route() works in the Pinia store

    // Initialize the auth store before mounting the app
    const authStore = useAuthStore(pinia);
    await authStore.initialize();

    app.use(pinia).use(router).use(vuetify).use(ZiggyVue, Ziggy);
    app.mount("#app");
  } catch (error) {
    console.error("App initialization failed:", error);
    // Display a user-friendly error message
    document.getElementById("app").innerHTML = `
      <div style="padding:2rem; text-align:center; color:#b71c1c;">
        <h1>🚨 Application Error</h1>
        <p>فشل تحميل التطبيق. الرجاء المحاولة لاحقًا.</p>
      </div>
    `;
  }
}

initializeApp();