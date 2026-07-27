<script lang="ts" setup>
import { ref } from 'vue'
const showDialog = ref(false);
defineExpose({
  showDialog
})
</script>
<template>
  <v-dialog
      v-model="showDialog"
      width="800"
    >
      <slot />
    </v-dialog>
</template>
