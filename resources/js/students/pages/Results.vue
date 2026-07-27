<template>
  <div>
    <div class="mb-6">
      <h1 class="text-h4 font-weight-bold text-primary mb-2">النتائج الأكاديمية</h1>
      <p class="text-body-1 text-grey-darken-1">سجل الدرجات والنتائج للفصول الدراسية</p>
    </div>

    <v-card elevation="2" rounded="xl" class="overflow-hidden mb-6 border">
      <v-card-item class="bg-blue-lighten-5 pa-4">
        <v-card-title class="text-h6 font-weight-bold text-primary">الفصل الدراسي الأول 2025/2026</v-card-title>
      </v-card-item>

      <v-table class="bg-white">
        <thead>
          <tr>
            <th class="text-right font-weight-bold text-subtitle-1">رمز المقرر</th>
            <th class="text-right font-weight-bold text-subtitle-1">اسم المقرر</th>
            <th class="text-center font-weight-bold text-subtitle-1">الساعات</th>
            <th class="text-center font-weight-bold text-subtitle-1">الدرجة</th>
            <th class="text-center font-weight-bold text-subtitle-1">التقدير</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="course in semesterResults" :key="course.code">
            <td class="text-right font-weight-medium">{{ course.code }}</td>
            <td class="text-right">{{ course.name }}</td>
            <td class="text-center">{{ course.credits }}</td>
            <td class="text-center font-weight-bold">{{ course.score }}</td>
            <td class="text-center">
              <v-chip :color="getGradeColor(course.grade)" size="small" class="font-weight-bold" variant="flat">
                {{ course.grade }}
              </v-chip>
            </td>
          </tr>
        </tbody>
      </v-table>
    </v-card>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const semesterResults = ref([
  { code: 'CS101', name: 'مقدمة في علوم الحاسوب', credits: 3, score: 85, grade: 'A' },
  { code: 'MATH101', name: 'الرياضيات التفاضلية', credits: 3, score: 72, grade: 'B' },
  { code: 'ENG101', name: 'اللغة الإنجليزية', credits: 2, score: 90, grade: 'A+' },
  { code: 'PHYS101', name: 'الفيزياء العامة', credits: 4, score: 65, grade: 'C' },
]);

const getGradeColor = (grade) => {
  if (grade.includes('A')) return 'success';
  if (grade.includes('B')) return 'primary';
  if (grade.includes('C')) return 'warning';
  return 'error';
};
</script>