<script lang="ts" setup>
import { ref, watch } from 'vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { component as CKEditor } from '@ckeditor/ckeditor5-vue';
import '@ckeditor/ckeditor5-build-classic/build/translations/ar';

interface Props {
  modelValue?: string;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
}>();

const editorData = ref(props.modelValue || '');
const editorConfig = ref({
  toolbar: ['heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'insertTable', '|', 'undo', 'redo'],
  language: 'ar',
  pasteFromWordRemoveFontStyles: false,
});

// 1. Sync Parent -> Child (The Fix)
// When axios updates the data in the parent, this updates the editor content
watch(() => props.modelValue, (newVal) => {
  if (newVal !== editorData.value) {
    editorData.value = newVal || '';
  }
});

watch(editorData, (newValue) => {
  emit('update:modelValue', newValue);
});
</script>

<template>
  <div class="fg-black" style="--ck-border-radius: 0.25rem">
    <CKEditor :editor="ClassicEditor" v-model="editorData" :config="editorConfig" />
  </div>
</template>

<style>
.ck-editor__editable {
  min-height: 100px;
  font-weight: normal;
}
.ck-content ul,
.ck-content ol {
  padding: 0 1em;
}
</style>