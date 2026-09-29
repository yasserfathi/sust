<template>
  <v-locale-provider rtl>
    <v-app>
      <router-view />
    </v-app>
  </v-locale-provider>
</template>

<script setup lang="ts">
defineOptions({
  name: 'App'
});
</script>

<style>
.v-file-input .v-chip {
  direction: ltr !important;
}
</style>
