<template>
  <v-no-ssr>
    <v-progress-linear 
      :active="loading" 
      :indeterminate="loading" 
      absolute 
      bottom 
      color="#f04146"
    ></v-progress-linear>
    
    <v-locale-provider rtl v-show="!loading">
      <v-layout class="rounded rounded-md">
        <app-bar />
        <v-main style="min-height: 300px;">
          <router-view />
        </v-main>
      </v-layout>
    </v-locale-provider>
  </v-no-ssr>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import { defineAsyncComponent } from 'vue'

const AppBar = defineAsyncComponent(() => import('./AppBar.vue'));
const loading = ref(true);

onMounted(() => {
  // Simulate app initialization
  setTimeout(() => {
    loading.value = false;
  }, 100);
});
</script>