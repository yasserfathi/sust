import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';
import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite';
import viteCompression from 'vite-plugin-compression';
import path from 'path';
import fs from 'fs';

const generateVersion = () => {
    const version = Date.now().toString();
    fs.writeFileSync('public/version.json', JSON.stringify({ version }));
    return version;
};

export default defineConfig({
    server: {
        host: '127.0.0.1',
        port: 5173,
    },
    plugins: [
        laravel({
            input: ['resources/js/admin/app.js', 'resources/js/students/app.js'],
            refresh: true,
        }),
        AutoImport({
            imports: [
                {
                    'vue': [
                        'ref',
                        'reactive',
                        'computed',
                        'watch',
                        'watchEffect',
                        'onMounted',
                        'onBeforeMount',
                        'onUnmounted',
                        'nextTick',
                        'provide',
                        'inject',
                        'toRef',
                        'toRefs',
                        'shallowRef',
                        'markRaw',
                        'defineAsyncComponent',
                        'toRaw',
                    ],
                    'vue-router': ['useRoute', 'useRouter'],
                    'pinia': ['storeToRefs', 'defineStore'],
                    'ziggy-js': ['route'],
                },
            ],
            dts: false,
        }),
        Components({
            dirs: ['resources/js/admin/components', 'resources/js/students/components'],
            dts: false,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vuetify({ autoImport: true }),
        viteCompression({
            algorithm: 'gzip',
            ext: '.gz',
            threshold: 1024,
        }),
    ],
    define: {
        '__VUE_OPTIONS_API__': JSON.stringify(true),
        '__VUE_PROD_DEVTOOLS__': JSON.stringify(false),
        '__VUE_PROD_HYDRATION_MISMATCH_DETAILS__': JSON.stringify(false),
        '__APP_VERSION__': JSON.stringify(generateVersion()),
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            'ziggy-js': path.resolve('vendor/tightenco/ziggy'),
            '@': path.resolve(import.meta.dirname, 'resources/js'),
            lodash: 'lodash-es',
        },
    },
    build: {
        chunkSizeWarningLimit: 2000,
        rollupOptions: {
            treeshake: {
                moduleSideEffects: 'no-external',
                propertyReadSideEffects: false,
            },
            onwarn(warning, warn) {
                // تجاهل التحذير غير الضار الخاص بـ dynamic import 
                if (warning.message.includes('dynamic import will not move module into another chunk')) return;
                warn(warning);
            },
            output: {
                manualChunks(id) {
                    if (id.includes('ckeditor5') || id.includes('@ckeditor')) {
                        return 'vendor-ckeditor';
                    }
                    if (id.includes('chart.js') || id.includes('vue-chartjs')) {
                        return 'vendor-chart';
                    }
                    if (id.includes('sweetalert2')) {
                        return 'vendor-sweetalert';
                    }
                    if (id.includes('node_modules/vuetify/')) {
                        return 'vendor-vuetify';
                    }
                    if (id.includes('node_modules/vue/') || id.includes('node_modules/@vue/') || id.includes('node_modules/vue-router/') || id.includes('node_modules/pinia/')) {
                        return 'vendor-vue-core';
                    }
                }
            }
        }
    },
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler',
            },
        },
    },
    optimizeDeps: {
        include: [
            'lodash-es',
            'ckeditor5',
            '@ckeditor/ckeditor5-vue',
            'vue',
            'vue-router',
            'pinia'
        ]
    }
});