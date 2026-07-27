<template>
  <v-container fluid class="pa-md-6 pa-4">
    <v-row>
      <v-col cols="12">
        <v-breadcrumbs :items="['الرئيسية', 'لوحة المعلومات']" class="pa-0 mb-4">
          <template v-slot:divider>
            <v-icon icon="mdi-chevron-left"></v-icon>
          </template>
        </v-breadcrumbs>

        <div>
          <h1 class="text-h4 font-weight-bold">
            أهلاً بعودتك، {{ authStore.user?.name || 'المستخدم' }}!
          </h1>
          <p class="text-medium-emphasis mt-1">
            إليك نظرة سريعة على بيانات النظام اليوم.
          </p>
        </div>
        <v-divider class="my-4"></v-divider>
      </v-col>
    </v-row>

    <v-row class="stats-row">
      <v-col
        v-for="stat in stats"
        :key="stat.label"
        cols="12"
        sm="6"
        md="3"
      >
        <v-card variant="tonal" :color="stat.color" rounded="lg">
          <v-card-text>
            <div class="d-flex align-center">
              <v-avatar :color="stat.color" rounded="lg" size="56" class="me-4">
                <v-icon :icon="stat.icon" color="white" size="32"></v-icon>
              </v-avatar>
              <div>
                <div class="text-caption text-medium-emphasis">
                  {{ stat.label }}
                </div>
                <div class="text-h4 font-weight-bold">
                  {{ stat.value }}
                </div>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import { onBeforeMount, ref, computed } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../store/index';

// @ts-ignore
// This assumes you have a global 'route' function (like Ziggy in Laravel)
// If not, replace route('...') with string paths like '/api/users/counts'
declare function route(name: string): string;

const authStore = useAuthStore();

// Raw counts
const users_counts = ref(0);
const colleges_count = ref(0);
const centers_count = ref(0);
const deanships_count = ref(0);

// Computed stats array
const stats = computed(() => [
  {
    label: 'الكليات',
    icon: 'mdi-bank',
    value: colleges_count.value,
    color: 'primary',
  },
  {
    label: 'المراكز والمعاهد',
    icon: 'mdi-domain',
    value: centers_count.value,
    color: 'success',
  },
  {
    label: 'العمادات',
    icon: 'mdi-office-building',
    value: deanships_count.value,
    color: 'info',
  },
  {
    label: 'المستخدمين',
    icon: 'mdi-account-group',
    value: users_counts.value,
    color: 'warning',
  },
]);

onBeforeMount(async () => {
  try {
    const [users, colleges] = await Promise.all([
      axios.get(route('users.counts')),
      axios.get(route('college.counts')),
    ]);

    users_counts.value = users.data.result;
    colleges_count.value = colleges.data.colleges_count;
    centers_count.value = colleges.data.centers_count;
    deanships_count.value = colleges.data.deanships_count;
  } catch (error) {
    console.error('Failed to load dashboard data:', error);
  }
});
</script>

<style scoped>
.chart-placeholder {
  min-height: 300px;
  border-radius: 8px;
}

/* This targets the light theme */
.v-theme--light .chart-placeholder {
  background-color: #f7f7f7;
}

/* This targets the dark theme */
.v-theme--dark .chart-placeholder {
  background-color: #2e2e2e;
}
</style>