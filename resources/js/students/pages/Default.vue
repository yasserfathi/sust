<template>
  <v-locale-provider rtl>
    <v-layout>
      <v-navigation-drawer v-model="drawer" location="right" elevation="2">
        <div class="pa-4 text-center">
          <v-avatar size="64" color="primary" class="mb-2">
            <v-icon icon="mdi-account" color="white"></v-icon>
          </v-avatar>
          <div class="text-subtitle-1 font-weight-bold">{{ authStore.user?.name }}</div>
          <div class="text-caption text-grey">الرقم الجامعي: {{ authStore.user?.id }}</div>
        </div>
        <v-divider></v-divider>
        <v-list nav>
          <v-list-item prepend-icon="mdi-view-dashboard" title="الرئيسية" value="dashboard" :to="{ name: 'StudentDashboard' }" exact></v-list-item>
          <v-list-item prepend-icon="mdi-school" title="النتائج الأكاديمية" value="results" :to="{ name: 'StudentResults' }"></v-list-item>
          <v-list-item prepend-icon="mdi-cash-multiple" title="الرسوم والدفع" value="payments" :to="{ name: 'StudentPayments' }"></v-list-item>
          <v-list-item prepend-icon="mdi-book-education" title="المقررات الدراسية" value="courses"></v-list-item>
        </v-list>
        <template v-slot:append>
          <div class="pa-4">
            <v-btn block color="error" variant="tonal" prepend-icon="mdi-logout" @click="logout">
              تسجيل الخروج
            </v-btn>
          </div>
        </template>
      </v-navigation-drawer>

      <v-app-bar elevation="1" color="white">
        <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title class="font-weight-bold text-primary">بوابة الطالب</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon>
          <v-icon>mdi-bell-outline</v-icon>
        </v-btn>
      </v-app-bar>

      <v-main class="bg-background">
        <v-container fluid class="pa-6">
          <router-view v-slot="{ Component }">
            <transition name="fade-transform" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
        </v-container>
      </v-main>
    </v-layout>
  </v-locale-provider>
</template>

<script setup>
import { useStudentAuthStore } from '../store/index.js';
import { useRouter } from 'vue-router';

const drawer = ref(true);
const authStore = useStudentAuthStore();
const router = useRouter();

const logout = () => {
  authStore.logout();
  router.push({ name: 'StudentLogin' });
};
</script>