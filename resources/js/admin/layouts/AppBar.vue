<template>
  <v-navigation-drawer right elevation="0" app v-model="drawer" :width="320" temporary-on-mobile>
    <div class="pa-2 d-flex justify-center border-b">
      <v-img :src="theme.global.current.value.dark ? '/logo-dark.png' : '/logo-light.png'" height="50" contain></v-img>
    </div>
    <PerfectScrollbar class="scrollnavbar">
      <v-list v-model:opened="open" variant="elevated" density="compact" open-strategy="single" class="border border-5" nav>
        
        <!-- DASHBOARD LINK -->
        <v-list-item to="/dashboard" link v-ripple exact class="mb-2" active-class="custom-active-bg">
          <template v-slot:prepend>
            <v-icon icon="mdi-view-dashboard-outline" size="small" />
          </template>
          <v-list-item-title class="menu-title font-weight-bold">لوحة التحكم</v-list-item-title>
        </v-list-item>

        <!-- PROFILE LINK -->
        <v-list-item to="/profile" link v-ripple exact class="mb-2" active-class="custom-active-bg">
          <template v-slot:prepend>
            <v-icon icon="mdi-account-circle-outline" size="small" />
          </template>
          <v-list-item-title class="menu-title font-weight-bold">الملف الشخصي</v-list-item-title>
        </v-list-item>

        <v-list-group v-for="(item, index) in items" :key="'cat-' + index" :value="'cat-' + (item.title || index)" prepend-icon="mdi-folder-outline"
          base-color="primary">
          <template v-slot:activator="{ props }">
            <v-list-item v-bind="props" :title="item.title" v-ripple base-color="primary"></v-list-item>
          </template>

          <!-- Grouped Pages (Sub-menus) -->
          <v-list-group v-for="(pages, groupName) in item.grouped_pages" :key="'grp-' + index + '-' + groupName" :value="'grp-' + index + '-' + groupName">
            <template v-slot:activator="{ props }">
              <v-list-item v-bind="props" :title="groupName" prepend-icon="mdi-layers-outline"
                class="font-weight-bold menu-title text-primary" color="primary"
                style="background-color: #f7ebe9; border-right: 4px solid #ce6148;"></v-list-item>
            </template>
            <v-list-item v-for="(page, i) in pages" :key="i" :to="page.url" link v-ripple exact color="primary"
              class="ps-8" active-class="custom-active-bg">
              <template v-slot:prepend>
                <v-icon icon="mdi-circle-medium" size="small" class="opacity-70" />
              </template>
              <v-list-item-title class="menu-title">{{ page.title }}</v-list-item-title>
            </v-list-item>
          </v-list-group>

          <!-- Ungrouped Pages (Standard Items) -->
          <v-list-item v-for="(page, i) in item.ungrouped_pages" :key="'u' + i" :to="page.url" link v-ripple exact
            color="primary" class="ps-4" active-class="custom-active-bg">
            <template v-slot:prepend>
              <v-icon icon="mdi-circle-small" size="small" />
            </template>
            <v-list-item-title class="menu-title">{{ page.title }}</v-list-item-title>
          </v-list-item>
        </v-list-group>
      </v-list>
    </PerfectScrollbar>
  </v-navigation-drawer>

  <v-app-bar flat elevation="1" height="60" class="px-2 px-sm-4">
    <template v-slot:prepend>
      <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
    </template>
    <v-spacer class="d-sm-none"></v-spacer>
    <v-btn variant="text" icon="mdi-brightness-4" class="d-inline-flex d-sm-none" @click="toggleTheme" title="تبديل المظهر"></v-btn>
    <v-btn variant="text" append-icon="mdi-brightness-4" class="d-none d-sm-inline-flex" @click="toggleTheme">الشاشة</v-btn>
    <v-divider class="mx-2 d-none d-sm-block" inset vertical></v-divider>
    <v-menu nudge-bottom="3">
      <template v-slot:activator="{ props }">
        <v-btn v-bind="props" variant="text" class="px-2 px-sm-4 text-subtitle-2">
          <span class="d-none d-sm-inline">{{ authStore?.user?.name || 'مستخدم' }}</span>
          <template v-slot:append>
            <v-avatar v-if="authStore?.user?.thumb_img" size="32" class="mr-1 mr-sm-2">
              <v-img :src="BASE_URL + '/' + authStore.user.thumb_img"></v-img>
            </v-avatar>
            <v-icon v-else class="mr-1 mr-sm-2" size="x-large">mdi-account-circle</v-icon>
          </template>
        </v-btn>
      </template>

      <v-list density="compact" nav class="mt-4">
        <v-list-item to="/profile" link v-ripple>
          <v-list-item-title>
            <v-icon>mdi-account-cog-outline</v-icon> الملف الشخصي
          </v-list-item-title>
        </v-list-item>
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
    <v-form ref="form2" lazy-validation @submit.prevent="changePassword">
      <v-card dir="rtl">
        <v-toolbar class="bg-red-darken-1">
          <v-toolbar-title>
            <v-icon end icon="mdi-lock-reset"></v-icon> تغيير كلمة المرور
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon @click="dialog = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar>
        <v-card-text class="pt-6">
          <v-row>
            <v-text-field id="password_change" v-model="password_change" :type="showpassword ? 'text' : 'password'"
              name="password_change" :append-inner-icon="showpassword ? 'mdi-eye' : 'mdi-eye-off'"
              prepend-icon="mdi-lock" label="كلمة المرور" variant="outlined" autocomplete="new-password"
              @click:append-inner="showpassword = !showpassword"></v-text-field>
          </v-row>
          <v-row>
            <v-text-field id="password_change_confirm" v-model="password_change_confirm"
              :type="showpassword ? 'text' : 'password'" name="password_change_confirm"
              :append-inner-icon="showpassword ? 'mdi-eye' : 'mdi-eye-off'" prepend-icon="mdi-lock"
              label="تأكيد كلمة المرور" variant="outlined"></v-text-field>
          </v-row>
          <div class="d-flex w-100 px-0 pt-4 pb-2" dir="rtl" style="justify-content: flex-start; gap: 16px;">
            <v-btn color="green-darken-1" variant="elevated" type="submit" ripple rounded="pill" class="px-8" :loading="loading">
              حفظ
            </v-btn>
            <v-btn color="grey-darken-1" variant="elevated" @click="dialog = false" ripple rounded="pill" class="px-8">
              الغاء
            </v-btn>
          </div>
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
const BASE_URL = window.location.origin;
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
    confirmButtonColor: '#d65440',
    cancelButtonColor: '#424949',
    confirmButtonText: 'نعم، تسجيل الخروج',
    cancelButtonText: 'إلغاء',
    showCancelButton: true,
    showCloseButton: true
  });

  if (result.isConfirmed) {
    try {
      await authStore.logout();
    } catch (error) {
      console.warn("Logout process finished:", error);
    }
  }
}

onBeforeMount(async () => {
  const cachedPages = localStorage.getItem('userPages');
  if (cachedPages) {
    try {
      items.value = JSON.parse(cachedPages);
    } catch (e) {}
  }
  try {
    const response = await axios.get(route("userPages"));
    items.value = response.data.result;
    localStorage.setItem('userPages', JSON.stringify(response.data.result));
  } catch (error) {
    console.error("Failed to load navigation items:", error);
  }
});

const form2 = ref(null);
const loading = ref(false);

async function changePassword() {
  const { valid } = await form2.value.validate();
  if (!valid) return;

  if (password_change.value !== password_change_confirm.value) {
    Swal.fire("خطأ", "كلمة المرور غير متطابقة", "error");
    return;
  }

  try {
    loading.value = true;
    const response = await axios.post('/user/change-password', {
      password_change: password_change.value,
      password_change_confirm: password_change_confirm.value
    });
    if (response.data.success) {
      Swal.fire("نجاح", response.data.message, "success");
      if (authStore.user) {
        authStore.user.force_password_change = false;
      }
      dialog.value = false;
      password_change.value = '';
      password_change_confirm.value = '';
    }
  } catch (error) {
    Swal.fire("خطأ", error.response?.data?.message || "حدث خطأ", "error");
  } finally {
    loading.value = false;
  }
}



</script>

<style scoped>
.custom-active-bg {
  background-color: #f3c4bd !important;
  color: #111111 !important;
  /* Dark color to replace the unreadable white text */
}

.menu-title {
  font-size: 13px !important;
  font-weight: 600 !important;
  letter-spacing: 0 !important;
}
</style>