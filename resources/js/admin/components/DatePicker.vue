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
        variant="outlined"
        readonly
        prepend-icon="mdi-calendar"
        clearable
        @click:clear="clearDate"
      ></v-text-field>
    </template>
    <v-date-picker 
      v-model="internalDate" 
      hide-header 
      color="#e53935" 
      @update:modelValue="handleDateChange"
      min="1950-01-01" max="2030-01-01"
    ></v-date-picker>
  </v-menu>
</template>

<script lang="ts" setup>

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

// Vuetify 3 v-date-picker requires a Date object
const internalDate = ref<Date | null>(
  props.modelValue ? new Date(props.modelValue as string | Date) : null
);

// Format the date as YYYY-MM-DD for the text field
const formattedDate = computed(() => {
  if (!internalDate.value) return "";
  const date = internalDate.value;
  if (isNaN(date.getTime())) return "";
  return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
});

const clearDate = () => {
  internalDate.value = null;
  emit('update:modelValue', null);
};

const handleDateChange = (val: any) => {
  if (val) {
    const newDate = new Date(val);
    if (!isNaN(newDate.getTime())) {
      internalDate.value = newDate;
      const dateStr = `${newDate.getFullYear()}-${String(newDate.getMonth() + 1).padStart(2, '0')}-${String(newDate.getDate()).padStart(2, '0')}`;
      emit('update:modelValue', dateStr);
    }
  } else {
    internalDate.value = null;
    emit('update:modelValue', null);
  }
  isMenuOpen.value = false;
};

// Sync if parent updates the prop
watch(() => props.modelValue, (newValue) => {
  if (!newValue) {
    internalDate.value = null;
  } else {
    const newDate = new Date(newValue as string | Date);
    if (!internalDate.value || internalDate.value.getTime() !== newDate.getTime()) {
      internalDate.value = newDate;
    }
  }
});
</script>