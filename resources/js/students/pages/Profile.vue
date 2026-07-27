<template>
  <div>
    <h1>Student Profile</h1>
    <p>This is your profile page.</p>
    <router-link :to="{ name: 'dashboard' }">Go to Dashboard</router-link>
  </div>
</template>

<script setup>
</script>

<style scoped>
div { padding: 2rem; text-align: center; }
</style>