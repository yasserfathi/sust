<template>
    <v-container fluid class="fill-height bg-grey-lighten-4 pa-0">
        <v-row align="center" justify="center" class="fill-height">
            <v-col cols="12" sm="8" md="6" lg="4">
                <v-card class="pa-8 mx-4" rounded="xl" elevation="4">
                    <div class="text-center mb-6">
                        <v-icon icon="mdi-email-alert-outline" color="warning" size="64" class="mb-4"></v-icon>
                        <h2 class="text-h5 font-weight-bold">تحديث بيانات الاتصال</h2>
                        <p class="text-subtitle-1 text-grey-darken-1 mt-2">
                            يرجى إدخال بريدك الإلكتروني لتأكيد الحساب ومتابعة الدخول إلى البوابة.
                        </p>
                    </div>

                    <v-alert v-if="errorMessage" type="error" variant="tonal"
                        class="mb-4 text-right text-body-1 font-weight-medium">
                        {{ errorMessage }}
                    </v-alert>

                    <v-form @submit.prevent="submitEmail" ref="formRef">
                        <v-text-field v-model="email" label="البريد الإلكتروني" prepend-inner-icon="mdi-email-outline"
                            variant="outlined" color="primary" class="mb-4" type="email" :rules="[
                                v => !!v || 'البريد الإلكتروني مطلوب',
                                v => /.+@.+\..+/.test(v) || 'يرجى إدخال بريد إلكتروني صحيح'
                            ]"></v-text-field>

                        <v-btn :loading="loading" type="submit" block color="primary" size="x-large"
                            class="text-h6 font-weight-bold rounded-lg mt-2">
                            حفظ ومتابعة
                        </v-btn>
                    </v-form>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useStudentAuthStore } from '../store';
import Swal from 'sweetalert2';

const email = ref('');
const loading = ref(false);
const formRef = ref(null);
const errorMessage = ref('');
const router = useRouter();
const authStore = useStudentAuthStore();

const submitEmail = async () => {
    const { valid } = await formRef.value.validate();
    if (!valid) return;

    loading.value = true;
    errorMessage.value = '';

    try {
        const response = await authStore.confirmEmail(email.value);

        // Check the `email_sent` flag from the API response
        const emailSent = response?.data?.email_sent ?? response?.email_sent;

        if (emailSent === false) {
            await Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'تم حفظ بريدك الإلكتروني بنجاح، ولكن تعذر إرسال رسالة التأكيد. يرجى التحقق من صحة البريد أو المحاولة لاحقاً.',
                confirmButtonText: 'متابعة',
                confirmButtonColor: '#ff9800'
            });
        } else {
            await Swal.fire({
                icon: 'success',
                title: 'تم بنجاح',
                text: 'تم حفظ البريد الإلكتروني وإرسال رسالة تأكيد إلى صندوق الوارد الخاص بك.',
                confirmButtonText: 'متابعة',
                confirmButtonColor: '#4caf50'
            });
        }

        router.push({ name: 'StudentDashboard' });
    } catch (error) {
        console.error('Failed to confirm email:', error);

        const validationErrors = error.response?.data?.errors;
        errorMessage.value = validationErrors?.email?.[0] || error.response?.data?.message || 'حدث خطأ أثناء حفظ البريد الإلكتروني.';
    } finally {
        loading.value = false;
    }
};
</script>