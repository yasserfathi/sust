<template>
  <v-app>
    <v-main id="login" color="primary">
      <v-container class="mt-16" fluid>
        <v-row dense no-gutters justify="center" class="my-16 pt-10">
          <v-col id="content-wrapper" cols="12" md="8" lg="4">
            <v-card>
              <v-form @submit.prevent="submit">
                <v-card-text>
                  <div class="mx-auto layout column align-center">
                    <v-img :src="logoUrl" height="85" />
                  </div>

                  <v-divider class="my-3 border-opacity-50" />

                  <v-text-field
                    v-model="email"
                    :error-messages="emailErrors"
                    name="email"
                    variant="underlined"
                    prepend-icon="mdi-account"
                    placeholder="البريد الالكتروني"
                    @blur="validate('email')"
                  />

                  <v-text-field
                    v-model="password"
                    :error-messages="passwordErrors"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    variant="underlined"
                    :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    prepend-icon="mdi-lock"
                    placeholder="كلمة المرور"
                    @blur="validate('password')"
                    @click:append-inner="togglePasswordVisibility"
                  />

                  <v-btn
                    :loading="authStore.loading"
                    type="submit"
                    block
                    variant="elevated"
                    color="grey-darken-3"
                    class="py-0 mt-8"
                  >
                    تسجيل الدخول
                  </v-btn>
                </v-card-text>
              </v-form>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <v-snackbar
        v-model="showError"
        class="no-shadow"
        location="top"
        color="red"
        timeout="2500"
      >
        يرجى التحقق من معلومات تسجيل الدخول
      </v-snackbar>
    </v-main>
  </v-app>
</template>

<script lang="ts" setup>
import { ref, computed, watch } from 'vue';
import * as zod from 'zod';
import type { ZodType } from 'zod';
import { useAuthStore } from '../store/index';
import router from '../router';
const authStore = useAuthStore();
const logoUrl = `${import.meta.env.VITE_BASE_URL}/logo.png`;

const email = ref('');
const password = ref('');
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

  await authStore.login(email.value, password.value);
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
