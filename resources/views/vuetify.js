import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import { ar } from 'vuetify/locale';
import '@mdi/font/css/materialdesignicons.css';

export default createVuetify({
  components,
  directives,
  locale: {
    locale: 'ar',
    fallback: 'ar',
    messages: { ar },
    rtl: { ar: true },
  },
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        colors: {
          primary: '#0a58ca', // لون عصري يناسب الجامعة
          secondary: '#ce6148',
          accent: '#fd7e14',
          background: '#f8fafc',
        },
      },
    },
  },
});