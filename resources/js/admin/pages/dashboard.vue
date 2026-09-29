<template>
  <v-container fluid class="pa-md-5 pa-3 dashboard-container">
    <!-- Header -->
    <v-row class="mb-2">
      <v-col cols="12" class="py-1">
        <v-breadcrumbs :items="['الرئيسية', 'لوحة المعلومات']" class="pa-0 mb-2 density-compact">
          <template v-slot:divider>
            <v-icon icon="mdi-chevron-left" size="small"></v-icon>
          </template>
        </v-breadcrumbs>

        <div class="d-flex align-center justify-space-between flex-wrap gap-2 mb-2">
          <div>
            <h1 class="text-h5 font-weight-bold tracking-tight text-grey-darken-4 mb-1">
              لوحة التحكم الرئيسية — <span class="text-primary">{{ authStore.user?.name || 'المستخدم' }}</span>
            </h1>
            <p class="text-subtitle-2 text-medium-emphasis mb-0">
              ملخص المؤشرات الإحصائية وبيانات النظام الرسمية.
            </p>
          </div>
        </div>
        <v-divider class="my-3 opacity-20"></v-divider>
      </v-col>
    </v-row>

    <template v-if="loading">
      <v-row justify="center" class="my-10">
        <v-progress-circular indeterminate color="primary" size="52"></v-progress-circular>
      </v-row>
    </template>
    
    <template v-else-if="sections && sections.length > 0">
      <div v-for="(section, idx) in sections" :key="idx" class="mb-6">
        
        <!-- SECTION TITLE -->
        <div class="d-flex align-center mb-3">
          <v-avatar color="primary-lighten-5" size="36" class="ml-3">
            <v-icon size="20" color="primary">mdi-chart-areaspline</v-icon>
          </v-avatar>
          <div>
            <h2 class="text-subtitle-1 font-weight-bold text-grey-darken-3 mb-0">{{ section.title }}</h2>
          </div>
        </div>

        <!-- CARDS -->
        <v-row class="stats-row mb-4" density="comfortable">
          <v-col v-for="stat in section.cards" :key="stat.label" cols="12" sm="6" md="4" lg="2">
            <v-card class="stat-card border-slate" elevation="1" rounded="lg">
              <v-card-text class="pa-4">
                <div class="d-flex justify-space-between align-center">
                  <div>
                    <div class="text-caption font-weight-medium text-grey-darken-1 mb-1 text-truncate" style="max-width: 110px;">
                      {{ stat.label }}
                    </div>
                    <div class="text-h4 font-weight-bold text-grey-darken-4">
                      {{ stat.value }}
                    </div>
                  </div>
                  <v-avatar :style="{ backgroundColor: (stat.color || '#2563eb') + '15' }" rounded="lg" size="44">
                    <v-icon :icon="stat.icon" :color="stat.color || 'primary'" size="24"></v-icon>
                  </v-avatar>
                </div>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <!-- CHARTS GRID -->
        <v-row v-if="section.charts && section.charts.length > 0" density="comfortable">
          <v-col v-for="(chart, cIdx) in section.charts" :key="cIdx" cols="12" :md="chart.type === 'bar' ? 12 : 6" :lg="chart.type === 'bar' ? 6 : 3">
            <v-card rounded="lg" elevation="2" class="chart-card overflow-hidden h-100">
              <v-card-title class="font-weight-bold bg-grey-lighten-4 py-3 px-4 d-flex align-center text-subtitle-2 border-b">
                <v-icon color="primary" size="small" class="ml-2">mdi-poll</v-icon>
                {{ chart.title }}
              </v-card-title>
              <v-card-text class="pa-4 d-flex align-center justify-center">
                <div style="width: 100%; height: 260px; position: relative;">
                  <Bar v-if="chart.type === 'bar'" :data="getChartData(chart)" :options="getChartOptions(chart)" />
                  <Doughnut v-else-if="chart.type === 'doughnut'" :data="getChartData(chart)" :options="getChartOptions(chart)" />
                  <Pie v-else-if="chart.type === 'pie'" :data="getChartData(chart)" :options="getChartOptions(chart)" />
                  <PolarArea v-else-if="chart.type === 'polarArea'" :data="getChartData(chart)" :options="getChartOptions(chart)" />
                </div>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

      </div>
    </template>
    
    <template v-else>
      <v-row justify="center" class="mt-8">
        <v-alert type="info" variant="tonal" border="start" class="w-50 text-center">
          لا توجد بيانات متاحة لعرضها في لوحة التحكم الخاصة بك حالياً.
        </v-alert>
      </v-row>
    </template>
  </v-container>
</template>

<script lang="ts" setup>
import { ref, onBeforeMount } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../store/index';
import { Bar, Doughnut, Pie, PolarArea } from 'vue-chartjs'
import { 
  Chart as ChartJS, 
  Title, 
  Tooltip, 
  Legend, 
  BarElement, 
  CategoryScale, 
  LinearScale, 
  ArcElement,
  RadialLinearScale,
  PointElement,
  LineElement,
  Filler
} from 'chart.js'

ChartJS.register(
  Title, 
  Tooltip, 
  Legend, 
  BarElement, 
  CategoryScale, 
  LinearScale, 
  ArcElement,
  RadialLinearScale,
  PointElement,
  LineElement,
  Filler
)

const authStore = useAuthStore();
const sections = ref<Array<any>>([]);
const loading = ref(true);

const getChartData = (chart: any) => {
  return {
    labels: chart.labels,
    datasets: [
      {
        label: 'الإحصائيات',
        backgroundColor: chart.colors,
        borderRadius: chart.type === 'bar' ? 6 : 0,
        data: chart.data,
        borderWidth: chart.type === 'bar' ? 0 : 2,
        borderColor: '#ffffff'
      }
    ]
  }
};

const getChartOptions = (chart: any) => {
  const isCircular = ['doughnut', 'pie', 'polarArea'].includes(chart.type);
  
  return {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: isCircular,
        position: isCircular ? 'right' as const : 'bottom' as const,
        labels: {
          font: { family: "'Cairo', sans-serif", size: 13 },
          usePointStyle: true,
          padding: 20
        }
      },
      tooltip: {
        titleFont: { family: "'Cairo', sans-serif", size: 14 },
        bodyFont: { family: "'Cairo', sans-serif", size: 14 },
        padding: 12,
        cornerRadius: 8,
      }
    },
    scales: isCircular ? undefined : {
      y: {
        beginAtZero: true,
        grid: {
          color: 'rgba(0,0,0,0.05)',
          drawBorder: false
        }
      },
      x: {
        grid: {
          display: false
        }
      }
    },
    cutout: chart.type === 'doughnut' ? '65%' : undefined,
    animation: {
      animateScale: true,
      animateRotate: true
    }
  };
};

onBeforeMount(async () => {
  try {
    const response = await axios.get('dashboard/stats');
    if (response.data.sections) {
      sections.value = response.data.sections;
    }
  } catch (error) {
    console.error('Failed to load dashboard data:', error);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.dashboard-container {
  background-color: #f8fafc;
  min-height: 100vh;
}

.stat-card {
  transition: all 0.2s ease-in-out;
  border: 1px solid rgba(226, 232, 240, 0.8);
  background-color: #ffffff;
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06) !important;
  border-color: rgba(203, 213, 225, 1);
}

.chart-card {
  transition: box-shadow 0.2s ease-in-out;
  border: 1px solid rgba(226, 232, 240, 0.8);
  background-color: #ffffff;
}

.chart-card:hover {
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06) !important;
}

.v-theme--dark .dashboard-container {
  background-color: #0f172a;
}

.v-theme--dark .stat-card,
.v-theme--dark .chart-card {
  background-color: #1e293b !important;
  border-color: rgba(51, 65, 85, 0.8) !important;
}

.v-theme--dark .chart-card .bg-grey-lighten-4 {
  background-color: #0f172a !important;
}
</style>