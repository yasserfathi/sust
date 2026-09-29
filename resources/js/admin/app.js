import { createApp, defineAsyncComponent } from "vue";
import { createPinia } from "pinia";
import { ZiggyVue, route } from "ziggy-js";
import { useAuthStore } from "./store/index";
import { Ziggy } from "./ziggy.js";
import axios from "axios";
import router from "./router";
import App from "./App.vue";
const Editor = defineAsyncComponent(() => import("./components/Editor.vue"));
const Table = defineAsyncComponent(() => import("./components/Table.vue"));
import { createVuetify } from "vuetify";
import { ar } from 'vuetify/locale';
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
  axios.defaults.timeout = 60000;

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

// Vuetify Setup with Tree-Shaking
function loadVuetify() {
  return createVuetify({
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
    defaults: {
      global: {
        ripple: true,
      },
      VTextField: {
        variant: 'outlined',
        density: 'comfortable',
        color: 'primary',
        rounded: 'lg',
      },
      VSelect: {
        variant: 'outlined',
        density: 'comfortable',
        color: 'primary',
        rounded: 'lg',
      },
      VCombobox: {
        variant: 'outlined',
        density: 'comfortable',
        color: 'primary',
        rounded: 'lg',
      },
      VTextarea: {
        variant: 'outlined',
        density: 'comfortable',
        color: 'primary',
        rounded: 'lg',
      },
      VBtn: {
        rounded: 'pill',
        elevation: 0,
        fontWeight: 'bold',
      },
      VCard: {
        rounded: 'xl',
      }
    },
    theme: {
      defaultTheme: "light",
      themes: {
        light: {
          colors: {
            primary: "#d65440",
            secondary: "#1e293b", // Switched to a sleeker, deeper secondary
            error: "#ef4444",     // Modernized error red
            info: "#3b82f6",      // Modernized info blue
            success: "#10b981",   // Modernized success green
            warning: "#f59e0b",   // Modernized warning yellow
            background: "#f8fafc",
          },
        },
        dark: {
          colors: {
            primary: "#d65440",
            secondary: "#38bdf8",
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
    const vuetify = loadVuetify();

    configureAxios(pinia);

    // Decouple Ziggy from the build URL by using the runtime origin
    Ziggy.url = window.location.origin;
    Ziggy.port = null;
    window.Ziggy = Ziggy; // Make it globally available so route() works in the Pinia store
    window.route = route;

    // Initialize the auth store before mounting the app
    const authStore = useAuthStore(pinia);
    await authStore.initialize();

    app.component("Editor", Editor);
    app.component("Table", Table);

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