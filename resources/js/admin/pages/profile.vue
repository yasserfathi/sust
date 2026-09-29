<template>
  <v-container fluid class="pa-md-5 pa-3">
    <!-- Top Header Banner -->
    <v-card class="mb-5 rounded-lg border-0 overflow-hidden shadow-sm position-relative" elevation="1"
      style="background: linear-gradient(135deg, #ffffff 0%, #fdf2f0 100%); border-right: 5px solid #d65440 !important; border: 1px solid rgba(226, 232, 240, 0.8);">
      <v-card-text class="pa-4 pa-md-5">
        <v-row align="center">
          <v-col cols="12" md="8">
            <div class="d-flex align-center">
              <v-avatar size="80" class="border-2 border-primary elevation-2 bg-white flex-shrink-0"
                style="margin-left: 20px !important;">
                <v-img v-if="previewImage || form.thumb_img || form.img"
                  :src="previewImage || (BASE_URL + '/' + (form.thumb_img || form.img))" cover></v-img>
                <v-icon v-else icon="mdi-account-circle" size="80" color="primary"></v-icon>
              </v-avatar>
              <div>
                <h2 class="text-h5 font-weight-bold text-grey-darken-4 mb-1">{{ form.name || 'الملف الشخصي' }}</h2>
                <div v-if="form.name_en" class="text-subtitle-2 font-weight-medium text-grey-darken-1 mb-2">
                  {{ form.name_en }}
                </div>
                <div class="d-flex align-center gap-2 flex-wrap">
                  <v-chip size="small" color="primary" variant="flat" class="font-weight-bold">
                    {{ userCollegeAndDept }}
                  </v-chip>
                  <v-chip v-if="userGrade" size="small" color="primary" variant="outlined" class="font-weight-medium">
                    {{ userGrade }}
                  </v-chip>
                </div>
              </div>
            </div>
          </v-col>
          <v-col cols="12" md="4" class="text-md-left text-center">
            <v-btn v-if="form.slug && form.slug.toLowerCase() !== 'admin' && form.staff_latest" :href="'/ar/staff/' + form.slug" target="_blank" variant="outlined" color="primary"
              prepend-icon="mdi-web" rounded="pill" size="small" class="px-5 font-weight-bold">
              معاينة الصفحة العامة
            </v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <!-- Main Profile Edit Form -->
    <v-form ref="profileForm" @submit.prevent="saveProfile">
      <v-row>
        <!-- Main Form Column: Personal & Contact Information -->
        <v-col cols="12" md="8">
          <v-card class="rounded-lg border-sm mb-4" elevation="1">
            <v-card-title
              class="font-weight-bold text-subtitle-1 border-b px-6 py-4 d-flex align-center gap-2 bg-grey-lighten-5">
              <v-icon color="primary" icon="mdi-account-details-outline"></v-icon>
              البيانات الأساسية ومعلومات الاتصال
            </v-card-title>

            <v-card-text class="pa-6">
              <v-row>
                <!-- Name Ar -->
                <v-col cols="12" md="6">
                  <v-text-field v-model="form.name" label="الاسم باللغة العربية *" prepend-inner-icon="mdi-account"
                    variant="outlined" density="comfortable" :rules="[v => !v || !!v || 'الاسم بالعربية مطلوب']"
                    :error-messages="fieldErrors.name"></v-text-field>
                </v-col>

                <!-- Name En -->
                <v-col cols="12" md="6">
                  <v-text-field v-model="form.name_en" label="الاسم باللغة الإنجليزية *"
                    prepend-inner-icon="mdi-account-outline" variant="outlined" density="comfortable"
                    :rules="[v => !v || !!v || 'الاسم بالإنجليزية مطلوب']"
                    :error-messages="fieldErrors.name_en"></v-text-field>
                </v-col>

                <!-- Email -->
                <v-col cols="12" md="6">
                  <v-text-field v-model="form.email" label="البريد الإلكتروني *" prepend-inner-icon="mdi-email-outline"
                    variant="outlined" density="comfortable" dir="ltr"
                    :rules="[v => !v || !!v || 'البريد الإلكتروني مطلوب', v => !v || /.+@.+\..+/.test(v) || 'البريد غير صحيح']"
                    :error-messages="fieldErrors.email"></v-text-field>
                </v-col>

                <!-- Phone -->
                <v-col cols="12" md="6">
                  <v-text-field v-model="form.phone" label="رقم الهاتف" prepend-inner-icon="mdi-phone-outline"
                    variant="outlined" density="comfortable" dir="ltr"
                    :error-messages="fieldErrors.phone"></v-text-field>
                </v-col>

                <!-- Univ No (Readonly) -->
                <v-col cols="12" md="6">
                  <v-text-field :model-value="form.univ_no || 'غير محدد'" label="الرقم الجامعي"
                    prepend-inner-icon="mdi-card-account-details" variant="outlined" density="comfortable" readonly
                    disabled></v-text-field>
                </v-col>

                <!-- Department / College (Readonly) -->
                <v-col cols="12" md="6">
                  <v-text-field :model-value="userCollegeAndDept" label="الكلية والقسم"
                    prepend-inner-icon="mdi-school-outline" variant="outlined" density="comfortable" readonly
                    disabled></v-text-field>
                </v-col>
              </v-row>

              <!-- Action Buttons -->
              <div class="d-flex align-center justify-start gap-3 mt-4 pt-4 border-t">
                <v-btn color="primary" variant="flat" type="submit" :loading="loading" rounded="pill"
                  class="px-8 font-weight-bold" prepend-icon="mdi-content-save-check">
                  حفظ التعديلات
                </v-btn>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <!-- Sidebar Column: Avatar & CV Cards -->
        <v-col cols="12" md="4">
          <!-- Profile Photo Card -->
          <v-card class="rounded-lg border-sm mb-5" elevation="1">
            <v-card-title
              class="font-weight-bold text-subtitle-1 border-b px-5 py-3.5 d-flex align-center gap-2 bg-grey-lighten-5">
              <v-icon color="primary" icon="mdi-camera-account" size="small"></v-icon>
              الصورة الشخصية
            </v-card-title>
            <v-card-text class="pa-5 text-center">
              <div class="position-relative d-inline-block mb-4">
                <v-avatar size="90" class="elevation-2 border-2 border-primary">
                  <v-img v-if="previewImage || form.img || form.thumb_img"
                    :src="previewImage || (BASE_URL + '/' + (form.thumb_img || form.img))" cover></v-img>
                  <v-icon v-else icon="mdi-account" size="64" color="grey"></v-icon>
                </v-avatar>
              </div>
              <v-file-input v-model="selectedImage" label="تحديث الصورة الشخصية" prepend-icon="mdi-camera"
                accept="image/jpeg,image/png,image/jpg" variant="outlined" density="comfortable" show-size chips
                hide-details="auto" @update:modelValue="onImageSelected" class="mb-2"></v-file-input>
              <div class="text-caption text-grey opacity-80">يتم دعم صور JPG, PNG بحد أقصى 5 ميجابايت</div>
            </v-card-text>
          </v-card>

          <!-- CV / Resume Uploads Card -->
          <v-card class="rounded-lg border-sm" elevation="1">
            <v-card-title
              class="font-weight-bold text-subtitle-1 border-b px-5 py-3.5 d-flex align-center gap-2 bg-grey-lighten-5">
              <v-icon color="primary" icon="mdi-file-document-outline" size="small"></v-icon>
              السيرة الذاتية (CV)
            </v-card-title>
            <v-card-text class="pa-5">
              <!-- Arabic CV -->
              <div class="mb-4">
                <div
                  class="text-subtitle-2 font-weight-bold mb-2 d-flex align-center justify-between text-grey-darken-2">
                  <span>السيرة الذاتية (عربي)</span>
                  <a v-if="resumeArPath" :href="BASE_URL + '/' + resumeArPath.replace(/^storage\//, '')" target="_blank"
                    class="text-primary text-caption font-weight-bold text-decoration-none d-inline-flex align-center gap-1">
                    <v-icon size="small">mdi-eye</v-icon> عرض الملف
                  </a>
                </div>
                <v-file-input v-model="selectedResumeAr" label="اختر ملف السيرة (PDF / DOC)"
                  prepend-icon="mdi-file-pdf-box" accept=".pdf,.doc,.docx" variant="outlined" density="comfortable"
                  show-size chips hide-details="auto"></v-file-input>
              </div>

              <!-- English CV -->
              <div>
                <div
                  class="text-subtitle-2 font-weight-bold mb-2 d-flex align-center justify-between text-grey-darken-2">
                  <span>السيرة الذاتية (إنجليزي)</span>
                  <a v-if="resumeEnPath" :href="BASE_URL + '/' + resumeEnPath.replace(/^storage\//, '')" target="_blank"
                    class="text-primary text-caption font-weight-bold text-decoration-none d-inline-flex align-center gap-1">
                    <v-icon size="small">mdi-eye</v-icon> عرض الملف
                  </a>
                </div>
                <v-file-input v-model="selectedResumeEn" label="اختر ملف السيرة (PDF / DOC)"
                  prepend-icon="mdi-file-pdf-box" accept=".pdf,.doc,.docx" variant="outlined" density="comfortable"
                  show-size chips hide-details="auto"></v-file-input>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script lang="ts" setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useAuthStore } from '../store/index';
import axios from 'axios';
import Swal from 'sweetalert2';

const authStore = useAuthStore();
const BASE_URL = window.location.origin;

const loading = ref(false);
const profileForm = ref<any>(null);

const form = reactive({
  id: null as number | null,
  name: '',
  name_en: '',
  email: '',
  phone: '',
  univ_no: '',
  slug: '',
  img: '',
  thumb_img: '',
  staff_latest: null as any
});

const selectedImage = ref<any>(null);
const previewImage = ref<string | null>(null);

const selectedResumeAr = ref<any>(null);
const selectedResumeEn = ref<any>(null);
const resumeArPath = ref('');
const resumeEnPath = ref('');

const fieldErrors = reactive<Record<string, string>>({});

const userCollegeAndDept = computed(() => {
  const dept = form.staff_latest?.department?.name;
  const college = form.staff_latest?.department?.college?.name;
  if (college && dept) return `${college} - ${dept}`;
  if (college) return college;
  if (dept) return dept;
  return 'جامعة السودان للعلوم والتكنولوجيا';
});

const userGrade = computed(() => {
  return form.staff_latest?.grade || '';
});

function onImageSelected(file: any) {
  const f = Array.isArray(file) ? file[0] : file;
  if (f && f instanceof Blob) {
    previewImage.value = URL.createObjectURL(f);
  } else {
    previewImage.value = null;
  }
}

async function fetchProfile() {
  try {
    const response = await axios.get('/user/profile');
    const userData = response.data.user;
    const resumeData = response.data.resume;

    if (userData) {
      form.id = userData.id;
      form.name = userData.name || '';
      form.name_en = userData.name_en || '';
      form.email = userData.email || '';
      form.phone = userData.phone || '';
      form.univ_no = userData.univ_no || '';
      form.slug = userData.slug || '';
      form.img = userData.img || '';
      form.thumb_img = userData.thumb_img || '';
      form.staff_latest = userData.staff_latest || null;
    }

    if (resumeData) {
      resumeArPath.value = resumeData.file || '';
      resumeEnPath.value = resumeData.file_en || '';
    }
  } catch (error) {
    console.error('Failed to load profile:', error);
  }
}

async function saveProfile() {
  Object.keys(fieldErrors).forEach(k => delete fieldErrors[k]);

  const { valid } = await profileForm.value?.validate();
  if (!valid) return;

  loading.value = true;
  const formData = new FormData();

  formData.append('name', form.name);
  formData.append('name_en', form.name_en);
  formData.append('email', form.email);
  formData.append('phone', form.phone || '');

  if (selectedImage.value) {
    const img = Array.isArray(selectedImage.value) ? selectedImage.value[0] : selectedImage.value;
    if (img) formData.append('img', img);
  }

  if (selectedResumeAr.value) {
    const ar = Array.isArray(selectedResumeAr.value) ? selectedResumeAr.value[0] : selectedResumeAr.value;
    if (ar) formData.append('resume_ar', ar);
  }

  if (selectedResumeEn.value) {
    const en = Array.isArray(selectedResumeEn.value) ? selectedResumeEn.value[0] : selectedResumeEn.value;
    if (en) formData.append('resume_en', en);
  }

  try {
    const response = await axios.post('/user/profile', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    if (response.data.status === 200) {
      Swal.fire({
        title: 'تم الحفظ بنجاح',
        text: 'تم تحديث بيانات ملفك الشخصي بنجاح',
        icon: 'success',
        confirmButtonColor: '#198754',
        confirmButtonText: 'حسناً',
        timer: 2000
      });

      // Update local storage and auth store
      if (response.data.user) {
        authStore.user = response.data.user;
        form.img = response.data.user.img || '';
        form.thumb_img = response.data.user.thumb_img || '';
      }
      if (response.data.resume) {
        resumeArPath.value = response.data.resume.file || '';
        resumeEnPath.value = response.data.resume.file_en || '';
      }

      selectedImage.value = null;
      selectedResumeAr.value = null;
      selectedResumeEn.value = null;
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      Object.entries(error.response.data.errors).forEach(([field, messages]: [string, any]) => {
        fieldErrors[field] = Array.isArray(messages) ? messages[0] : messages;
      });
    } else {
      Swal.fire({
        title: 'خطأ',
        text: error.response?.data?.message || 'تعذر تحديث البيانات',
        icon: 'error',
        confirmButtonColor: '#d33',
        confirmButtonText: 'حسناً'
      });
    }
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  fetchProfile();
});
</script>

<style scoped>
.hover-lift {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(206, 97, 72, 0.3) !important;
}
</style>
