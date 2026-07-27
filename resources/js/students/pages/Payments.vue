<template>
  <div>
    <div class="mb-6">
      <h1 class="text-h4 font-weight-bold text-accent mb-2">الرسوم والدفع</h1>
      <p class="text-body-1 text-grey-darken-1">الموقف المالي وتفاصيل الرسوم الدراسية</p>
    </div>

    <v-row>
      <v-col cols="12" md="4">
        <v-card elevation="2" rounded="xl" class="bg-primary text-white text-center pa-6">
          <v-icon icon="mdi-cash-register" size="48" class="mb-4 opacity-80"></v-icon>
          <div class="text-h6 mb-2">إجمالي الرسوم</div>
          <div class="text-h4 font-weight-bold">250,000 SDG</div>
        </v-card>
      </v-col>
      
      <v-col cols="12" md="4">
        <v-card elevation="2" rounded="xl" class="bg-success text-white text-center pa-6">
          <v-icon icon="mdi-cash-check" size="48" class="mb-4 opacity-80"></v-icon>
          <div class="text-h6 mb-2">المبلغ المسدد</div>
          <div class="text-h4 font-weight-bold">100,000 SDG</div>
        </v-card>
      </v-col>

      <v-col cols="12" md="4">
        <v-card elevation="2" rounded="xl" class="bg-error text-white text-center pa-6 pb-4">
          <v-icon icon="mdi-cash-remove" size="48" class="mb-2 opacity-80"></v-icon>
          <div class="text-subtitle-1 mb-1">المتبقي</div>
          <div class="text-h4 font-weight-bold mb-4">150,000 SDG</div>
          <v-btn color="white" variant="tonal" class="font-weight-bold rounded-lg" block elevation="0">
            دفع الآن
          </v-btn>
        </v-card>
      </v-col>
    </v-row>

    <v-card elevation="2" rounded="xl" class="mt-8 border">
      <v-card-title class="pa-6 font-weight-bold text-grey-darken-3 border-b bg-grey-lighten-4">
        سجل المدفوعات السابقة
      </v-card-title>
      <v-table>
        <thead>
          <tr>
            <th class="text-right text-subtitle-1 font-weight-bold">رقم الإيصال</th>
            <th class="text-right text-subtitle-1 font-weight-bold">التاريخ</th>
            <th class="text-right text-subtitle-1 font-weight-bold">المبلغ (SDG)</th>
            <th class="text-right text-subtitle-1 font-weight-bold">طريقة الدفع</th>
            <th class="text-center text-subtitle-1 font-weight-bold">الحالة</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="payment in paymentsHistory" :key="payment.id">
            <td class="text-right text-primary font-weight-medium">{{ payment.id }}</td>
            <td class="text-right">{{ payment.date }}</td>
            <td class="text-right font-weight-bold">{{ payment.amount }}</td>
            <td class="text-right">{{ payment.method }}</td>
            <td class="text-center">
              <v-chip color="success" size="small" variant="flat">مكتمل</v-chip>
            </td>
          </tr>
        </tbody>
      </v-table>
    </v-card>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const paymentsHistory = ref([
  { id: 'REC-2025-00124', date: '2025-10-01', amount: '50,000', method: 'تطبيق بنكك' },
  { id: 'REC-2025-00089', date: '2025-09-15', amount: '50,000', method: 'دفع إلكتروني (بطاقة)' },
]);
</script>