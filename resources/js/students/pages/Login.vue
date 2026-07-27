<template>
  <v-container fluid class="fill-height pa-0">
    <v-row no-gutters class="fill-height">
      <v-col cols="12" md="6" class="d-none d-md-flex position-relative">
        <v-img src="/images/gallery/students.jpg" cover class="fill-height w-100">
          <div class="fill-height d-flex flex-column justify-center align-center text-white"
            style="background: linear-gradient(to bottom, rgba(214, 84, 64, 0.52), rgba(0, 0, 0, 0.6))">
            <v-icon icon="mdi-school" size="100" class="mb-4 text-white"></v-icon>
            <h1 class="text-h2 font-weight-black mb-2">بوابة الطالب</h1>
            <p class="text-h5 font-weight-light opacity-90">جامعة السودان للعلوم والتكنولوجيا</p>
          </div>
        </v-img>
      </v-col>

      <v-col cols="12" md="6" class="d-flex align-center justify-center bg-grey-lighten-4">
        <v-card flat class="w-100 pa-8 pa-md-16 mx-auto" max-width="600" rounded="xl">
          <div class="text-center mb-10">
            <v-img src="/images/gallery/sust-logo.png" height="80" class="mb-4"></v-img>
            <h2 class="font-weight-black color-primary">تسجيل دخول الطلاب</h2>
            <p class="text-subtitle-1 text-grey-darken-1">أدخل اسم المستخدم لمتابعة مسيرتك الأكاديمية</p>
          </div>

          <div class="text-center mb-8 d-md-none">
            <v-icon icon="mdi-school" size="48" color="primary" class="mb-2"></v-icon>
            <h1 class="text-h4 font-weight-bold text-primary">بوابة الطالب</h1>
          </div>

          <v-alert v-if="errorMessage" type="error" variant="tonal"
            class="mb-4 text-right text-body-1 font-weight-medium">
            {{ errorMessage }}
          </v-alert>

          <v-form @submit.prevent="handleLogin" ref="formRef">
            <v-text-field v-model="studentId" label="اسم المستخدم" prepend-inner-icon="mdi-account" variant="outlined"
              color="primary" class="mb-4" :rules="[v => !!v || 'اسم المستخدم مطلوب']"></v-text-field>

            <v-text-field v-model="password" label="كلمة المرور" prepend-inner-icon="mdi-lock" variant="outlined"
              color="primary" class="mb-2" :type="showPassword ? 'text' : 'password'"
              :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
              @click:append-inner="showPassword = !showPassword"
              :rules="[v => !!v || 'كلمة المرور مطلوبة']"></v-text-field>

            <v-btn :loading="loading" type="submit" block color="primary" size="x-large"
              class="text-h6 font-weight-bold rounded-lg py-4" elevation="4">
              تسجيل الدخول
            </v-btn>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue';
import { useStudentAuthStore } from '../store';
import { useRouter } from 'vue-router';

const studentId = ref('');
const password = ref('');
const showPassword = ref(false);
const loading = ref(false);
const formRef = ref(null);
const errorMessage = ref('');

const authStore = useStudentAuthStore();
const router = useRouter();

const handleLogin = async () => {
  const { valid } = await formRef.value.validate();
  if (!valid) return;

  loading.value = true;
  errorMessage.value = ''; // تصفير الخطأ عند محاولة الدخول مجدداً

  try {
    // Make the actual API call via the store
    await authStore.login(studentId.value, password.value);

    // Check if the student's email is empty
    if (authStore.user && !authStore.user.email) {
      // Redirect to a specific view to force the user to enter their email
      router.push({ name: 'StudentSetupEmail' });
    } else {
      // Email exists, proceed to dashboard
      router.push({ name: 'StudentDashboard' });
    }
  } catch (error) {
    console.error("Login Failed:", error);
    errorMessage.value = error.response?.data?.message || authStore.error || "بيانات الدخول غير صحيحة أو هناك خطأ في الاتصال بالمنصة.";
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.v-btn {
  transition: transform 0.2s ease-in-out;
}

.v-btn:hover {
  transform: translateY(-2px);
}
</style>