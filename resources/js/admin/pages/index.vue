<template>
  <v-app>
    <v-main id="login" color="primary">
      <v-container class="mt-16" fluid>
        <v-row density="comfortable" no-gutters justify="center" class="my-16 pt-10">
          <v-col id="content-wrapper" cols="12" md="8" lg="4">
            <v-card>
              <v-form @submit.prevent="submit">
                <v-card-text class="px-sm-8 px-4 pb-8">
                  <div class="d-flex justify-center mt-4 mb-8">
                    <v-img src="/logo.png" height="90" contain />
                  </div>

                  <v-text-field v-model="email" :error-messages="emailErrors" name="email"
                    prepend-inner-icon="mdi-email-outline" label="البريد الالكتروني" @blur="validate('email')"
                    variant="outlined" color="red-darken-4" rounded="lg" class="mb-3" density="comfortable" />

                  <v-text-field v-model="password" :error-messages="passwordErrors"
                    :type="showPassword ? 'text' : 'password'" name="password"
                    :append-inner-icon="showPassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
                    prepend-inner-icon="mdi-lock-outline" label="كلمة المرور" @blur="validate('password')"
                    @click:append-inner="togglePasswordVisibility" variant="outlined" color="red-darken-4" rounded="lg"
                    class="mb-5" density="comfortable" />

                  <v-btn :loading="authStore.loading" type="submit" block variant="elevated" color="grey-darken-4"
                    class="text-white font-weight-bold text-subtitle-1" height="52" rounded="lg" elevation="4">
                    تسجيل الدخول
                    <v-icon end icon="mdi-login" class="ms-2"></v-icon>
                  </v-btn>
                </v-card-text>
              </v-form>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <v-snackbar v-model="showError" class="no-shadow" location="top" color="red" timeout="2500">
        يرجى التحقق من معلومات تسجيل الدخول
      </v-snackbar>

      <v-dialog v-model="showPasswordDialog" width="800px" persistent>
        <v-form ref="formPassword" lazy-validation @submit.prevent="changePassword">
          <v-card dir="rtl">
            <v-toolbar class="bg-red-darken-1">
              <v-toolbar-title>
                <v-icon end icon="mdi-lock-reset"></v-icon> تغيير كلمة المرور الافتراضية
              </v-toolbar-title>
            </v-toolbar>
            <v-card-text class="pt-6">
              <v-alert class="mb-6 font-weight-bold" border="start" border-color="orange-darken-4"
                color="orange-lighten-5" icon="mdi-alert-circle-outline"
                style="color: #e65100 !important; font-size: 1.1rem; line-height: 1.6;">
                يجب تغيير كلمة المرور الافتراضية الخاصة بك قبل الدخول إلى النظام للحفاظ على أمان حسابك.
              </v-alert>
              <v-row>
                <v-text-field id="password_change" v-model="password_change" :type="showpassword ? 'text' : 'password'"
                  name="password_change" :append-inner-icon="showpassword ? 'mdi-eye-off' : 'mdi-eye'"
                  prepend-icon="mdi-lock" label="كلمة المرور الجديدة" variant="outlined" autocomplete="new-password"
                  @click:append-inner="showpassword = !showpassword"></v-text-field>


                <v-text-field id="password_change_confirm" v-model="password_change_confirm"
                  :type="showpassword ? 'text' : 'password'" name="password_change_confirm"
                  :append-inner-icon="showpassword ? 'mdi-eye-off' : 'mdi-eye'" prepend-icon="mdi-lock"
                  label="تأكيد كلمة المرور الجديدة" variant="outlined"></v-text-field>
              </v-row>
              <div class="d-flex w-100 px-0 pt-4 pb-2" dir="rtl" style="justify-content: flex-start; gap: 16px;">
                <v-btn color="green-darken-1" variant="elevated" type="submit" ripple rounded="pill" class="px-8"
                  :loading="passwordLoading">
                  تغيير كلمة المرور والدخول
                </v-btn>
              </div>
            </v-card-text>
          </v-card>
        </v-form>
      </v-dialog>
    </v-main>
  </v-app>
</template>

<script lang="ts" setup>
import * as zod from 'zod';
import type { ZodType } from 'zod';
import { useAuthStore } from '../store/index';
import router from '../router';
import axios from 'axios';
import Swal from 'sweetalert2';
const authStore = useAuthStore();

const email = ref('');
const password = ref('');

const showPasswordDialog = ref(false);
const showpassword = ref(false);
const password_change = ref('');
const password_change_confirm = ref('');
const formPassword = ref(null);
const passwordLoading = ref(false);

onMounted(() => {
  const savedEmail = sessionStorage.getItem('temp_login_email');
  const savedPassword = sessionStorage.getItem('temp_login_password');
  if (savedEmail) email.value = savedEmail;
  if (savedPassword) password.value = savedPassword;
});

async function changePassword() {
  const { valid } = await formPassword.value.validate();
  if (!valid) return;

  if (password_change.value !== password_change_confirm.value) {
    Swal.fire("خطأ", "كلمة المرور غير متطابقة", "error");
    return;
  }

  try {
    passwordLoading.value = true;
    const response = await axios.post('/user/change-password', {
      password_change: password_change.value,
      password_change_confirm: password_change_confirm.value
    });
    if (response.data.success) {
      Swal.fire("نجاح", response.data.message, "success");
      if (authStore.user) {
        authStore.user.force_password_change = false;
      }
      showPasswordDialog.value = false;
      sessionStorage.removeItem('temp_login_email');
      sessionStorage.removeItem('temp_login_password');

      const redirectUrl = localStorage.getItem('redirectUrl') || '/dashboard';
      localStorage.removeItem('redirectUrl');
      router.replace(redirectUrl).catch(() => router.replace('/dashboard').catch(() => { }));
    }
  } catch (error) {
    Swal.fire("خطأ", error.response?.data?.message || "حدث خطأ", "error");
  } finally {
    passwordLoading.value = false;
  }
}
const emailErrors = ref<string[]>([]);
const passwordErrors = ref<string[]>([]);

const showPassword = ref(false);
const showError = computed(() => !!authStore.error);

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

const emailSchema = zod
  .string()
  .min(1, { message: 'ادخل البريد الالكتروني' })
  .email({ message: 'البريد الالكتروني غير صحيح' });

const passwordSchema = zod
  .string()
  .min(6, { message: 'كلمة المرور يجب أن تتكون من 6 أحرف على الأقل' });

const validationSchemas: Record<string, { schema: ZodType, value: typeof email | typeof password, errors: typeof emailErrors | typeof passwordErrors }> = {
  email: { schema: emailSchema, value: email, errors: emailErrors },
  password: { schema: passwordSchema, value: password, errors: passwordErrors },
};

const validate = (field: 'email' | 'password'): boolean => {
  const { schema, value, errors } = validationSchemas[field];
  const result = schema.safeParse(value.value);
  errors.value = result.success ? [] : result.error.errors.map(e => e.message);
  return result.success;
};

// Watch for input changes to clear errors once the field becomes valid
watch(email, () => {
  if (emailErrors.value.length > 0) validate('email');
});

watch(password, () => {
  if (passwordErrors.value.length > 0) validate('password');
});

const submit = async () => {
  const emailValid = validate('email');
  const passwordValid = validate('password');
  if (!emailValid || !passwordValid) return;

  sessionStorage.setItem('temp_login_email', email.value);
  sessionStorage.setItem('temp_login_password', password.value);

  const result = await authStore.login(email.value, password.value);

  if (result && result.requires_password_change) {
    showPasswordDialog.value = true;
  }
};
</script>

<style scoped>
#login {
  height: 50%;
  width: 100%;
  background-image: linear-gradient(to bottom right, #d65440, #d69c93);
  position: absolute;
  top: 0;
  left: 0;
}
</style>
