<template>
    <v-navigation-drawer right elevation="0" app v-model="drawer" width="350">
        <div class="pa-1">
            <v-img :src="theme.global.current.value.dark ? '/logo-dark.png' : '/logo-light.png'" height="55"></v-img>
        </div>
        <PerfectScrollbar class="scrollnavbar">
            <v-list v-model:opened="open" variant="elevated" density="compact" open-strategy="single"
                class="border border-5" nav>
                <v-list-group v-for="(item, index) in items" :key="index" prepend-icon="mdi-folder-outline"
                    base-color="primary">
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props" :title="item.title" v-ripple base-color="primary"></v-list-item>
                    </template>

                    <v-list-item v-for="(page, i) in item.category_page" :key="i" :to="page.url" link v-ripple exact
                        class="d-flex align-center pa-0 pe-2" color="secondary">
                        <template v-slot:prepend>
                            <v-icon icon="mdi-circle" size="x-small" class="ms-1" />
                        </template>
                        <v-list-item-title class="ma-0 pa-0">{{ page.title }}</v-list-item-title>
                    </v-list-item>
                </v-list-group>
            </v-list>
        </PerfectScrollbar>
    </v-navigation-drawer>

    <v-app-bar flat elevation="1" height="60">
        <template v-slot:prepend>
            <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        </template>
        <v-btn variant="text" append-icon="mdi-brightness-4" @click="toggleTheme">الشاشة</v-btn>
        <v-divider class="ml-3" inset vertical></v-divider>
        <v-menu nudge-bottom="3">
            <template v-slot:activator="{ props }">
                <v-btn v-bind="props" variant="text" class="ml-4 text-subtitle-2" append-icon="mdi-account">
                    جامعة السودان للعلوم والتكنولوجيا
                </v-btn>
            </template>

            <v-list density="compact" nav class="mt-4">
                <v-list-item v-ripple @click="dialog = true">
                    <v-list-item-title>
                        <v-icon>mdi-lock-reset</v-icon> تغيير كلمة المرور
                    </v-list-item-title>
                </v-list-item>
                <v-divider></v-divider>
                <v-list-item v-ripple class="border border-2" @click="logout">
                    <v-list-item-title>
                        <v-icon>mdi-logout</v-icon> تسجيل الخروج
                    </v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </v-app-bar>

    <v-dialog v-model="dialog" width="800px" persistent>
        <v-form ref="form2" lazy-validation>
            <v-card>
                <v-toolbar class="bg-red-darken-1">
                    <v-toolbar-title>
                        <v-icon end icon="mdi-lock-reset"></v-icon> تغيير كلمة المرور
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon @click="dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>
                    <v-col class="align-center justify-space-between" cols="12">
                        <v-row>
                            <v-text-field id="password_change" v-model="password_change"
                                :type="showpassword ? 'text' : 'password'" name="password_change"
                                :append-inner-icon="showpassword ? 'mdi-eye' : 'mdi-eye-off'" prepend-icon="mdi-lock"
                                label="كلمة المرور" variant="underlined" autocomplete="new-password"
                                @click:append-inner="showpassword = !showpassword"></v-text-field>
                        </v-row>
                        <v-row>
                            <v-text-field id="password_change_confirm" v-model="password_change_confirm"
                                :type="showpassword ? 'text' : 'password'" name="password_change_confirm"
                                :append-inner-icon="showpassword ? 'mdi-eye' : 'mdi-eye-off'"
                                @click:append-inner="showpassword = !showpassword" prepend-icon="mdi-lock"
                                label="تأكيد كلمة المرور" variant="underlined"></v-text-field>
                        </v-row>
                        <v-row>
                            <v-btn color="green-darken-1" variant="flat" type="submit" class="ml-1">
                                حفظ
                            </v-btn>
                            <v-btn color="grey-darken-1" variant="flat" @click="dialog = false">
                                الغاء
                            </v-btn>
                        </v-row>
                    </v-col>
                </v-card-text>
            </v-card>
        </v-form>
    </v-dialog>
</template>

<script lang="ts" setup>
import { ref, onBeforeMount, computed } from 'vue';
import { useAuthStore } from '../store/index';
import { useTheme } from 'vuetify';
import axios from 'axios';
import Swal from 'sweetalert2';
import { PerfectScrollbar } from 'vue3-perfect-scrollbar';
import router from '../router';

const authStore = useAuthStore();
const theme = useTheme();

// Refs for UI state
const drawer = ref(true);
const open = ref([]);
const items: any = ref([]);

const dialog = ref(false);
const showpassword = ref(false);

// Refs for form models
const password_change = ref('');
const password_change_confirm = ref('');

function toggleTheme() {
    theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark';
}

async function logout() {
    const result = await Swal.fire({
        title: "تأكيد تسجيل الخروج ؟",
        icon: 'warning',
        confirmButtonColor: '#198754',
        cancelButtonColor: '#d33',
        confirmButtonText: 'موافق',
        cancelButtonText: 'الغاء',
        showCancelButton: true,
        showCloseButton: true
    });

    if (result.isConfirmed) {
        try {
            await authStore.logout();
            router.push('/');
        } catch (error) {
            console.error("Logout failed:", error);
            Swal.fire("Error", "Failed to logout", "error");
        }
    }
}

onBeforeMount(async () => {
    try {
        const response = await axios.get(route("userPages"));
        items.value = response.data.result;
    } catch (error) {
        console.error("Failed to load navigation items:", error);
    }
});
</script>