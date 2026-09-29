<template>
  <v-locale-provider rtl>
    <v-layout class="rounded rounded-md">
      <app-bar />
      <v-main style="min-height: 300px;">
        <router-view v-slot="{ Component }">
          <transition name="fade-transform" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </v-main>
    </v-layout>
  </v-locale-provider>
</template>

<script lang="ts" setup>
const AppBar = defineAsyncComponent(() => import('./AppBar.vue'));
</script>