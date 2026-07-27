<template>
  <v-menu 
    v-model="isMenuOpen" 
    :close-on-content-click="false" 
    :nudge-right="40" 
    transition="scale-transition" 
    offset-y
    min-width="290px"
  >
    <template v-slot:activator="{ props }">
      <v-text-field
        :label="label"
        :model-value="formattedDate"
        :error-messages="errorMessages"
        v-bind="props"
        variant="underlined"
        readonly
        prepend-icon="mdi-calendar"
        clearable
        @click:clear="clearDate"
        inert
      ></v-text-field>
    </template>
    <v-date-picker 
      v-model="selectedDate" 
      hide-header 
      color="#e53935" 
      @update:modelValue="handleDateChange"
      format="yyyy-mm-dd" min="1950-01-01" max="2030-01-01"
    ></v-date-picker>
  </v-menu>
</template>

<script lang="ts" setup>
import { ref, computed, watch } from "vue";

interface Props {
  label?: string;
  modelValue?: string | Date | null;
  errorMessages?: string | string[];
}

const props = withDefaults(defineProps<Props>(), {
  label: '',
  modelValue: null,
  errorMessages: () => []
});

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | null): void
}>();

const isMenuOpen = ref(false);
const selectedDate = ref(props.modelValue);

const formattedDate = computed(() => {
  if(selectedDate.value == undefined) return null;
  return selectedDate.value ? new Date(selectedDate.value).toLocaleDateString('sv-SE')  : "";
});

const clearDate = () => {
  selectedDate.value = null;
  emit('update:modelValue', null);
};

const handleDateChange = (date: string | null) => {
  selectedDate.value = date;
  isMenuOpen.value = false;
  emit('update:modelValue', date);
};

watch(() => props.modelValue, (newValue) => {
  if (newValue !== selectedDate.value) {
    selectedDate.value = newValue;
  }
});
</script>