<script lang="ts" setup>
interface Props {
  width?: number | string;
  persistent?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  width: 800,
  persistent: false
});

const showDialog = ref(false);
defineExpose({
  showDialog
});
</script>

<template>
  <v-dialog
    v-model="showDialog"
    :width="props.width"
    :persistent="props.persistent"
  >
    <slot />
  </v-dialog>
</template>
