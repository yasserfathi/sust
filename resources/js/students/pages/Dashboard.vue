<template>
  <div>
    <div class="mb-6">
      <h1 class="text-h4 font-weight-bold text-grey-darken-3 mb-2">لوحة التحكم</h1>
      <p class="text-body-1 text-grey-darken-1">نظرة عامة على أدائك الأكاديمي وحالتك المالية</p>
    </div>

    <v-row>
      <!-- البطاقة الأكاديمية -->
      <v-col cols="12" md="6">
        <v-card elevation="2" rounded="xl" class="h-100 overflow-hidden transition-swing hover-card">
          <v-card-item class="bg-primary text-white pa-6">
            <template v-slot:prepend>
              <v-icon size="x-large" class="me-4 opacity-80">mdi-certificate</v-icon>
            </template>
            <v-card-title class="text-h5 font-weight-bold">النتائج الأكاديمية</v-card-title>
            <v-card-subtitle class="text-white opacity-80 text-body-1">الفصل الدراسي الأول 2025/2026</v-card-subtitle>
          </v-card-item>
          <v-card-text class="pa-6">
            <v-row class="text-center">
              <v-col cols="6">
                <div class="text-h3 font-weight-black text-primary mb-1">3.45</div>
                <div class="text-body-2 text-grey-darken-1 font-weight-medium">المعدل التراكمي (CGPA)</div>
              </v-col>
              <v-col cols="6">
                <div class="text-h3 font-weight-black text-success mb-1">120</div>
                <div class="text-body-2 text-grey-darken-1 font-weight-medium">الساعات المكتسبة</div>
              </v-col>
            </v-row>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions class="pa-4">
            <v-btn variant="text" color="primary" prepend-icon="mdi-eye" class="font-weight-bold" :to="{ name: 'StudentResults' }">
              عرض تفاصيل النتيجة
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>

      <!-- بطاقة الرسوم -->
      <v-col cols="12" md="6">
        <v-card elevation="2" rounded="xl" class="h-100 overflow-hidden transition-swing hover-card">
          <v-card-item class="bg-accent text-white pa-6">
            <template v-slot:prepend>
              <v-icon size="x-large" class="me-4 opacity-80">mdi-cash-multiple</v-icon>
            </template>
            <v-card-title class="text-h5 font-weight-bold">الرسوم والدفع</v-card-title>
            <v-card-subtitle class="text-white opacity-80 text-body-1">الموقف المالي الحالي</v-card-subtitle>
          </v-card-item>
          <v-card-text class="pa-6">
            <v-row class="text-center">
              <v-col cols="6">
                <div class="text-h4 font-weight-black text-grey-darken-3 mb-1">150,000</div>
                <div class="text-body-2 text-grey-darken-1 font-weight-medium">المبلغ المطلوب (SDG)</div>
              </v-col>
              <v-col cols="6">
                <v-chip color="error" variant="flat" size="large" class="font-weight-bold mt-2">
                  غير مسدد
                </v-chip>
              </v-col>
            </v-row>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions class="pa-4 d-flex justify-space-between">
            <v-btn variant="flat" color="accent" prepend-icon="mdi-credit-card-outline" class="font-weight-bold px-6 rounded-lg" :to="{ name: 'StudentPayments' }">
              دفع الآن
            </v-btn>
            <v-btn variant="text" color="grey-darken-2" class="font-weight-medium" :to="{ name: 'StudentPayments' }">
              سجل المدفوعات
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<style scoped>
.hover-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}
</style>