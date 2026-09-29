<template>
  <v-container fluid class="px-md-8 px-4 py-6" dir="rtl">
    <!-- Header Banner -->
    <v-card class="mb-6 rounded-lg elevation-1" color="primary" theme="dark">
      <v-card-text class="pa-6">
        <div class="d-flex align-center justify-space-between flex-wrap gap-4">
          <div>
            <h1 class="text-h5 font-weight-bold mb-1">
              <v-icon icon="mdi-cloud-upload-outline" class="ml-2" />
              نقل الأخبار من الموقع السابق
            </h1>
            <p class="text-subtitle-2 mb-0 opacity-90">
              استيراد الأخبار وتنزيل الصور تلقائياً وحفظها في الألبوم.
            </p>
          </div>
          <v-chip color="white" variant="flat" class="text-primary font-weight-bold">
            الألبوم الافتراضي: أخبار و أحداث الجامعة (2)
          </v-chip>
        </div>
      </v-card-text>
    </v-card>

    <!-- Main Card with Tabs -->
    <v-card class="rounded-lg elevation-2">
      <v-tabs v-model="tab" bg-color="grey-lighten-4" color="primary" grow>
        <v-tab value="single" class="font-weight-bold">
          <v-icon start icon="mdi-file-document-edit-outline" />
          نقل خبر فردي (Single News)
        </v-tab>
        <v-tab value="batch" class="font-weight-bold">
          <v-icon start icon="mdi-database-import-outline" />
          نقل جماعي دفعة واحدة (Bulk / Batch)
        </v-tab>
      </v-tabs>

      <v-window v-model="tab" class="pa-6">
        <!-- ========================================== -->
        <!-- TAB 1: SINGLE NEWS IMPORT                  -->
        <!-- ========================================== -->
        <v-window-item value="single">
          <v-form ref="singleFormRef" @submit.prevent="submitSingle">
            <v-row>
              <!-- Language Mode & College & Date & Active Switch -->
              <v-col cols="12" md="3">
                <v-select v-model="single.college_id" :items="colleges" item-title="name" item-value="id"
                  label="اختر الكلية *" variant="outlined" density="comfortable" prepend-inner-icon="mdi-bank" />
              </v-col>
              <v-col cols="12" md="3">
                <v-select v-model="single.lang_mode" :items="languageModes" item-title="title" item-value="value"
                  label="نمط اللغة *" variant="outlined" density="comfortable" prepend-inner-icon="mdi-translate" />
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field v-model="single.news_date" label="تاريخ الخبر *" variant="outlined" density="comfortable"
                  prepend-inner-icon="mdi-calendar-clock"
                  hint="صيغ مدعومة: August 24, 2025 أو 24 August 2025 أو 2025-08-24" persistent-hint
                  placeholder="August 24, 2025" />
              </v-col>
              <v-col cols="12" md="2" class="d-flex align-center">
                <v-switch v-model="single.active" label="نشر مباشرة" color="success" hide-details inset />
              </v-col>

              <!-- Arabic Title with Duplicate Check Button -->
              <v-col cols="12" :md="single.lang_mode === 'both' ? 6 : 12" v-if="single.lang_mode === 'ar' || single.lang_mode === 'both'">
                <v-text-field
                  v-model="single.title_ar"
                  label="عنوان الخبر (عربي) *"
                  variant="outlined"
                  density="comfortable"
                  prepend-inner-icon="mdi-format-title"
                  :rules="[v => !!v || 'عنوان الخبر العربي مطلوب']"
                  placeholder="مثال: جامعة السودان تدشن مشروعاً..."
                  required
                  @input="titleCheckStatus = null"
                >
                  <template #append>
                    <v-btn
                      color="indigo-darken-1"
                      variant="elevated"
                      prepend-icon="mdi-magnify"
                      :loading="checkingTitle"
                      :disabled="!single.title_ar || single.title_ar.trim() === ''"
                      @click="checkSingleTitle"
                    >
                      فحص تكرار العنوان
                    </v-btn>
                  </template>
                </v-text-field>
              </v-col>

              <!-- English Title with Duplicate Check Button -->
              <v-col cols="12" :md="single.lang_mode === 'both' ? 6 : 12" v-if="single.lang_mode === 'en' || single.lang_mode === 'both'">
                <v-text-field
                  v-model="single.title_en"
                  label="عنوان الخبر (English) *"
                  variant="outlined"
                  density="comfortable"
                  prepend-inner-icon="mdi-format-title"
                  :rules="[v => !!v || 'عنوان الخبر الإنجليزي مطلوب']"
                  placeholder="Example: Sudan University launches..."
                  dir="ltr"
                  required
                  @input="titleCheckStatus = null"
                >
                  <template #append>
                    <v-btn
                      color="indigo-darken-1"
                      variant="elevated"
                      prepend-icon="mdi-magnify"
                      :loading="checkingTitle"
                      :disabled="!single.title_en || single.title_en.trim() === ''"
                      @click="checkSingleTitle"
                    >
                      فحص تكرار العنوان
                    </v-btn>
                  </template>
                </v-text-field>
              </v-col>

              <!-- Duplicate / Availability Feedback Banner -->
              <v-col cols="12" v-if="titleCheckStatus">
                <v-alert :type="titleCheckStatus.exists ? 'warning' : 'success'" variant="tonal" density="compact"
                  class="mt-1" closable @click:close="titleCheckStatus = null">
                  <template #title>
                    <strong>{{ titleCheckStatus.exists ? 'تنبيه: الخبر موجود مسبقاً!' : 'ممتاز: العناوين متاحة وغير مكررة' }}</strong>
                  </template>
                  {{ titleCheckStatus.message }}
                </v-alert>
              </v-col>


              <!-- Image URLs -->
              <v-col cols="12">
                <v-textarea v-model="single.image_urls" label="روابط صور الخبر (URL) *" variant="outlined"
                  density="comfortable" prepend-inner-icon="mdi-image-multiple-outline" rows="3"
                  hint="يمكنك وضع رابط صورة واحدة أو عدة روابط (كل رابط في سطر منفصل). سيتم تحميلها وحفظها في ألبوم أخبار الجامعة تلقائياً."
                  persistent-hint
                  placeholder="https://www.sustech.edu/images/news1.jpg&#10;https://www.sustech.edu/images/news2.jpg" />
              </v-col>

              <!-- Image Live Preview -->
              <v-col cols="12" v-if="parsedSingleImages.length > 0">
                <div class="text-subtitle-2 font-weight-bold mb-2 text-primary">
                  <v-icon icon="mdi-eye-outline" class="ml-1" />
                  معاينة الروابط المكتشفة ({{ parsedSingleImages.length }} صورة):
                </div>
                <v-row dense>
                  <v-col v-for="(imgUrl, idx) in parsedSingleImages" :key="idx" cols="6" sm="4" md="3" lg="2">
                    <v-card class="rounded-lg overflow-hidden border" elevation="1">
                      <v-img :src="imgUrl" aspect-ratio="1.33" cover class="bg-grey-lighten-3">
                        <template #placeholder>
                          <div class="d-flex align-center justify-center fill-height">
                            <v-progress-circular indeterminate size="24" color="primary" />
                          </div>
                        </template>
                        <template #error>
                          <div
                            class="d-flex flex-column align-center justify-center fill-height pa-2 text-center text-caption text-error">
                            <v-icon icon="mdi-alert-circle-outline" size="20" />
                            تعذر المعاينة
                          </div>
                        </template>
                      </v-img>
                      <v-card-actions class="pa-1 justify-space-between bg-grey-lighten-4">
                        <span class="text-caption text-truncate px-1" style="max-width: 120px;">#{{ idx + 1 }}</span>
                        <v-btn icon="mdi-close" size="x-small" color="error" variant="text"
                          @click="removeSingleImageUrl(idx)" title="إزالة هذا الرابط" />
                      </v-card-actions>
                    </v-card>
                  </v-col>
                </v-row>
              </v-col>

              <!-- Keywords -->
              <v-col cols="12" :md="single.lang_mode === 'both' ? 6 : 12" v-if="single.lang_mode === 'ar' || single.lang_mode === 'both'">
                <v-text-field
                  v-model="single.keywords_ar"
                  label="الكلمات المفتاحية (عربي) - اختياري"
                  variant="outlined"
                  density="comfortable"
                  prepend-inner-icon="mdi-tag-multiple-outline"
                  placeholder="جامعة السودان للعلوم والتكنولوجيا"
                  hint="الافتراضي: جامعة السودان للعلوم والتكنولوجيا"
                  persistent-hint
                />
              </v-col>
              <v-col cols="12" :md="single.lang_mode === 'both' ? 6 : 12" v-if="single.lang_mode === 'en' || single.lang_mode === 'both'">
                <v-text-field
                  v-model="single.keywords_en"
                  label="الكلمات المفتاحية (English) - اختياري"
                  variant="outlined"
                  density="comfortable"
                  prepend-inner-icon="mdi-tag-multiple-outline"
                  placeholder="Sudan University Of Science & Technology"
                  hint="الافتراضي: Sudan University Of Science & Technology"
                  dir="ltr"
                  persistent-hint
                />
              </v-col>

              <!-- Detail (WYSIWYG Editor) -->
              <v-col cols="12" :md="single.lang_mode === 'both' ? 6 : 12" v-if="single.lang_mode === 'ar' || single.lang_mode === 'both'">
                <div class="text-subtitle-2 font-weight-bold mb-2">
                  <v-icon icon="mdi-newspaper-variant-outline" class="ml-1" />
                  تفاصيل الخبر (عربي) *
                </div>
                <Editor v-model="single.detail_ar" dir="rtl" />
                <div v-if="singleDetailErrorAr" class="text-caption text-error mt-1">
                  تفاصيل الخبر العربي مطلوبة
                </div>
              </v-col>

              <v-col cols="12" :md="single.lang_mode === 'both' ? 6 : 12" v-if="single.lang_mode === 'en' || single.lang_mode === 'both'">
                <div class="text-subtitle-2 font-weight-bold mb-2 text-right">
                  تفاصيل الخبر (English) *
                  <v-icon icon="mdi-newspaper-variant-outline" class="mr-1" />
                </div>
                <Editor v-model="single.detail_en" dir="ltr" />
                <div v-if="singleDetailErrorEn" class="text-caption text-error mt-1 text-right">
                  تفاصيل الخبر الإنجليزي مطلوبة
                </div>
              </v-col>

              <!-- Submit Button -->
              <v-col cols="12" class="d-flex justify-end gap-3 mt-4">
                <v-btn color="grey" variant="outlined" size="large" @click="resetSingleForm" :disabled="loadingSingle">
                  إعادة تعيين الحقول
                </v-btn>
                <v-btn color="success" variant="elevated" size="large" type="submit" prepend-icon="mdi-cloud-upload"
                  :loading="loadingSingle">
                  نقل وحفظ الخبر الآن
                </v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-window-item>

        <!-- ========================================== -->
        <!-- TAB 2: BULK / BATCH IMPORT                 -->
        <!-- ========================================== -->
        <v-window-item value="batch">
          <v-alert type="info" variant="tonal" class="mb-4" icon="mdi-information-outline">
            يمكنك لصق مصفوفة JSON تحتوي على عدة أخبار لنقلها دفعة واحدة. سيتم تنزيل الصور لكل خبر وربطها تلقائياً.
          </v-alert>

          <!-- Action buttons for JSON Template -->
          <div class="d-flex justify-space-between align-center mb-3 flex-wrap gap-2">
            <span class="text-subtitle-2 font-weight-bold">مصفوفة بيانات الأخبار (JSON Array):</span>
            <div class="d-flex align-center gap-3">
              <v-select v-model="batchDefaultLang" :items="languageOptions" item-title="title" item-value="value"
                label="اللغة الافتراضية للدفعة" variant="outlined" density="compact" hide-details
                style="width: 180px;" />
              <v-btn size="small" variant="outlined" color="primary" prepend-icon="mdi-code-json"
                @click="loadSampleJson">
                تحميل نموذج JSON للتجربة
              </v-btn>
            </div>
          </div>

          <v-textarea v-model="batchJsonText" variant="outlined" rows="10"
            style="font-family: monospace; direction: ltr;" placeholder='[
  {
    "title": "عنوان الخبر الأول",
    "lang": 1,
    "news_date": "August 24, 2025",
    "detail": "<p>تفاصيل الخبر الأول...</p>",
    "image_urls": ["https://example.com/img1.jpg", "https://example.com/img2.jpg"]
  }
]' @input="parseBatchJson" />

          <!-- JSON Status / Parse result -->
          <v-alert v-if="batchParseError" type="error" variant="tonal" class="mt-2 mb-4" density="compact">
            خطأ في تنسيق الـ JSON: {{ batchParseError }}
          </v-alert>

          <!-- Preview Table of parsed items -->
          <div v-if="parsedBatchItems.length > 0" class="mt-4 mb-4">
            <div class="d-flex align-center justify-space-between mb-2">
              <span class="text-subtitle-1 font-weight-bold text-success">
                <v-icon icon="mdi-check-circle" class="ml-1" />
                تم قراءة {{ parsedBatchItems.length }} خبر جاهز للنقل
              </span>
            </div>

            <v-table density="compact" class="border rounded-lg">
              <thead>
                <tr class="bg-grey-lighten-4">
                  <th class="text-right">#</th>
                  <th class="text-right">العنوان</th>
                  <th class="text-right">اللغة</th>
                  <th class="text-right">التاريخ</th>
                  <th class="text-right">عدد الصور</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, idx) in parsedBatchItems" :key="idx">
                  <td>{{ idx + 1 }}</td>
                  <td class="font-weight-medium">{{ item.title }}</td>
                  <td>
                    <v-chip size="x-small" :color="(item.lang || batchDefaultLang) == 1 ? 'teal' : 'indigo'">
                      {{ (item.lang || batchDefaultLang) == 1 ? 'العربية' : 'English' }}
                    </v-chip>
                  </td>
                  <td>{{ item.news_date || 'اليوم' }}</td>
                  <td>
                    <v-chip size="x-small" color="primary">
                      {{ Array.isArray(item.image_urls) ? item.image_urls.length : (item.image_urls ? 1 : 0) }} صور
                    </v-chip>
                  </td>
                </tr>
              </tbody>
            </v-table>
          </div>

          <!-- Batch Progress -->
          <div v-if="loadingBatch" class="mt-4 mb-4">
            <div class="text-subtitle-2 mb-1 text-center font-weight-bold">
              جاري معالجة ونقل الأخبار وتنزيل الصور... يرجى الانتظار
            </div>
            <v-progress-linear indeterminate color="primary" height="8" rounded />
          </div>

          <!-- Start Batch Button -->
          <div class="d-flex justify-end gap-3 mt-6">
            <v-btn color="grey" variant="outlined" size="large" @click="clearBatch" :disabled="loadingBatch">
              مسح
            </v-btn>
            <v-btn color="primary" variant="elevated" size="large" prepend-icon="mdi-database-import"
              :disabled="parsedBatchItems.length === 0 || !!batchParseError || loadingBatch" :loading="loadingBatch"
              @click="submitBatch">
              بدء استيراد {{ parsedBatchItems.length }} خبر دفعة واحدة
            </v-btn>
          </div>
        </v-window-item>
      </v-window>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Editor from '../components/Editor.vue';

// Tab selection
const tab = ref<'single' | 'batch'>('single');

const languageModes = [
  { title: 'عربي فقط', value: 'ar' },
  { title: 'إنجليزي فقط', value: 'en' },
  { title: 'كلاهما معاً', value: 'both' }
];

const colleges = ref<any[]>([]);

onMounted(async () => {
  try {
    const res = await axios.get(route('colleges.list'));
    if (res.data && res.data.colleges) {
      colleges.value = res.data.colleges;
    }
  } catch (err) {
    console.error("Failed to fetch colleges", err);
  }
});

// ==========================================
// SINGLE IMPORT STATE
// ==========================================
const singleFormRef = ref<any>(null);
const loadingSingle = ref(false);
const singleDetailErrorAr = ref(false);
const singleDetailErrorEn = ref(false);
const checkingTitle = ref(false);
const titleCheckStatus = ref<{ exists: boolean; message: string; } | null>(null);

const single = ref({
  college_id: 1,
  lang_mode: 'ar',
  title_ar: '',
  detail_ar: '',
  title_en: '',
  detail_en: '',
  news_date: 'August 24, 2025',
  image_urls: '',
  active: true,
  keywords_ar: '',
  keywords_en: ''
});

// Check if title already exists in DB
async function checkSingleTitle() {
  const currentTitleAr = single.value.title_ar?.trim();
  const currentTitleEn = single.value.title_en?.trim();

  if (single.value.lang_mode === 'ar' && !currentTitleAr) return;
  if (single.value.lang_mode === 'en' && !currentTitleEn) return;
  if (single.value.lang_mode === 'both' && (!currentTitleAr && !currentTitleEn)) return;

  checkingTitle.value = true;
  titleCheckStatus.value = null;

  try {
    const response = await axios.post('/news/check-title', {
      lang_mode: single.value.lang_mode,
      title_ar: currentTitleAr,
      title_en: currentTitleEn
    });
    const data = response.data;
    titleCheckStatus.value = {
      exists: !!data.exists,
      message: data.message
    };

    if (data.exists) {
      Swal.fire({
        icon: 'warning',
        title: 'الخبر موجود مسبقاً!',
        text: data.message,
        confirmButtonText: 'حسناً'
      });
    } else {
      Swal.fire({
        icon: 'success',
        title: 'العنوان متاح',
        text: 'هذا الخبر غير مكرر ويمكنك المتابعة بنقله.',
        timer: 2000,
        showConfirmButton: false
      });
    }
  } catch (err: any) {
    const msg = err.response?.data?.message || 'حدث خطأ أثناء فحص العنوان';
    Swal.fire({
      icon: 'error',
      title: 'خطأ',
      text: msg,
      confirmButtonText: 'حسناً'
    });
  } finally {
    checkingTitle.value = false;
  }
}

// Computed live preview of images from URLs
const parsedSingleImages = computed(() => {
  if (!single.value.image_urls) return [];
  const text = single.value.image_urls;
  const matches = text.match(/https?:\/\/[^\s,;"'<>]+/gi);
  if (!matches) return [];
  return Array.from(new Set(matches.map(u => u.trim())));
});

function removeSingleImageUrl(index: number) {
  const currentUrls = parsedSingleImages.value;
  if (index >= 0 && index < currentUrls.length) {
    const toRemove = currentUrls[index];
    const updated = currentUrls.filter((_, i) => i !== index);
    single.value.image_urls = updated.join('\n');
  }
}

function resetSingleForm() {
  single.value = {
    college_id: single.value.college_id || 1,
    lang_mode: 'ar',
    title_ar: '',
    detail_ar: '',
    title_en: '',
    detail_en: '',
    news_date: 'August 24, 2025',
    image_urls: '',
    active: true,
    keywords_ar: '',
    keywords_en: ''
  };
  singleDetailErrorAr.value = false;
  singleDetailErrorEn.value = false;
  titleCheckStatus.value = null;
}

async function submitSingle() {
  singleDetailErrorAr.value = false;
  singleDetailErrorEn.value = false;

  if ((single.value.lang_mode === 'ar' || single.value.lang_mode === 'both') && (!single.value.title_ar || single.value.title_ar.trim() === '')) {
    Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'يرجى إدخال عنوان الخبر العربي', confirmButtonText: 'حسناً' });
    return;
  }
  if ((single.value.lang_mode === 'en' || single.value.lang_mode === 'both') && (!single.value.title_en || single.value.title_en.trim() === '')) {
    Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'يرجى إدخال عنوان الخبر الإنجليزي', confirmButtonText: 'حسناً' });
    return;
  }

  if ((single.value.lang_mode === 'ar' || single.value.lang_mode === 'both') && (!single.value.detail_ar || single.value.detail_ar.trim() === '')) {
    singleDetailErrorAr.value = true;
    Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'يرجى إدخال تفاصيل الخبر العربي', confirmButtonText: 'حسناً' });
    return;
  }
  if ((single.value.lang_mode === 'en' || single.value.lang_mode === 'both') && (!single.value.detail_en || single.value.detail_en.trim() === '')) {
    singleDetailErrorEn.value = true;
    Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'يرجى إدخال تفاصيل الخبر الإنجليزي', confirmButtonText: 'حسناً' });
    return;
  }

  loadingSingle.value = true;

  try {
    const payload = {
      college_id: single.value.college_id,
      lang_mode: single.value.lang_mode,
      title_ar: single.value.title_ar,
      detail_ar: single.value.detail_ar,
      title_en: single.value.title_en,
      detail_en: single.value.detail_en,
      news_date: single.value.news_date,
      image_urls: parsedSingleImages.value,
      active: single.value.active ? 1 : 0,
      keywords_ar: single.value.keywords_ar,
      keywords_en: single.value.keywords_en
    };

    const response = await axios.post('/news/migrate-single', payload);

    if (response.data.status === 201) {
      Swal.fire({
        icon: 'success',
        title: 'تم النقل بنجاح!',
        html: `تم حفظ الخبر، وتم تحميل وتجهيز <b>${response.data.photos_count || 0}</b> صورة.`,
        confirmButtonText: 'تم'
      });
      resetSingleForm();
    } else {
      throw new Error(response.data.message || 'حدث خطأ غير متوقع');
    }
  } catch (error: any) {
    if (error.response?.status === 409) {
      Swal.fire({
        icon: 'warning',
        title: 'الخبر مكرر',
        text: error.response?.data?.message || 'هذا الخبر موجود مسبقاً بنفس العنوان في قاعدة البيانات.',
        confirmButtonText: 'حسناً'
      });
    } else {
      const msg = error.response?.data?.message || error.message || 'فشل نقل الخبر';
      Swal.fire({
        icon: 'error',
        title: 'خطأ',
        text: msg,
        confirmButtonText: 'حسناً'
      });
    }
  } finally {
    loadingSingle.value = false;
  }
}

// ==========================================
// BATCH IMPORT STATE
// ==========================================
// ... (rest remains consistent)

const batchDefaultLang = ref<number>(1);
const batchJsonText = ref('');
const batchParseError = ref('');
const parsedBatchItems = ref<any[]>([]);
const loadingBatch = ref(false);

function parseBatchJson() {
  batchParseError.value = '';
  if (!batchJsonText.value.trim()) {
    parsedBatchItems.value = [];
    return;
  }

  try {
    const data = JSON.parse(batchJsonText.value);
    if (!Array.isArray(data)) {
      batchParseError.value = 'يجب أن يكون الـ JSON عبارة عن مصفوفة (Array) تحتوي على عناصر الأخبار [ ... ]';
      parsedBatchItems.value = [];
      return;
    }
    parsedBatchItems.value = data;
  } catch (e: any) {
    batchParseError.value = e.message;
    parsedBatchItems.value = [];
  }
}

function loadSampleJson() {
  const sample = [
    {
      title: "تدشين المعمل البحثي المتقدم لتقانة النانو بجامعة السودان",
      lang: 1,
      news_date: "August 24, 2025",
      detail: "<p>دشنت جامعة السودان للعلوم والتكنولوجيا المعمل البحثي المتقدم لدعم البحوث العلمية والابتكار التكنولوجي بمشاركة عدد من الأساتذة والخبراء.</p>",
      detail_portion: "دشنت جامعة السودان للعلوم والتكنولوجيا المعمل البحثي المتقدم...",
      image_urls: [
        "https://picsum.photos/800/600",
        "https://picsum.photos/800/601"
      ]
    },
    {
      title: "Sudan University signs academic cooperation MoU with regional partners",
      lang: 2,
      news_date: "September 05, 2025",
      detail: "<p>Sudan University of Science and Technology signed an academic memorandum of understanding today to foster research exchange.</p>",
      detail_portion: "Sudan University of Science and Technology signed an academic memorandum...",
      image_urls: [
        "https://picsum.photos/800/602"
      ]
    }
  ];

  batchJsonText.value = JSON.stringify(sample, null, 2);
  parseBatchJson();
}

function clearBatch() {
  batchJsonText.value = '';
  batchParseError.value = '';
  parsedBatchItems.value = [];
}

async function submitBatch() {
  if (parsedBatchItems.value.length === 0) return;

  const confirm = await Swal.fire({
    icon: 'question',
    title: 'تأكيد الاستيراد الجماعي',
    text: `هل أنت متأكد من رغبتك في استيراد ${parsedBatchItems.value.length} خبر دفعة واحدة مع تنزيل كافة الصور المرفقة؟`,
    showCancelButton: true,
    confirmButtonText: 'نعم، ابدأ الاستيراد',
    cancelButtonText: 'إلغاء'
  });

  if (!confirm.isConfirmed) return;

  loadingBatch.value = true;

  try {
    const response = await axios.post('/news/migrate-batch', {
      lang: batchDefaultLang.value,
      items: parsedBatchItems.value.map(item => ({
        ...item,
        lang: item.lang || batchDefaultLang.value
      }))
    });

    const data = response.data;
    Swal.fire({
      icon: (data.failed_count > 0 || data.skipped_count > 0) ? 'info' : 'success',
      title: 'نتيجة الاستيراد الجماعي',
      html: `
        <div class="font-weight-bold mb-2">${data.message}</div>
        ${data.skipped_titles && data.skipped_titles.length > 0 ? `
          <div class="mt-2 text-warning text-caption text-right" style="max-height:120px;overflow-y:auto;background:#fff8e1;padding:8px;border-radius:4px;">
            <b>الأخبار التي تم تخطيها لتكرار العنوان (${data.skipped_titles.length}):</b><br>
            - ${data.skipped_titles.join('<br>- ')}
          </div>
        ` : ''}
        ${data.errors && data.errors.length > 0 ? `
          <div class="mt-2 text-error text-caption text-right" style="max-height:120px;overflow-y:auto;background:#ffebee;padding:8px;border-radius:4px;">
            <b>الأخطاء (${data.errors.length}):</b><br>
            ${data.errors.join('<br>')}
          </div>
        ` : ''}
      `,
      confirmButtonText: 'حسناً'
    });

    if (data.success_count > 0 && data.failed_count === 0) {
      clearBatch();
    }
  } catch (error: any) {
    const msg = error.response?.data?.message || error.message || 'فشلت عملية الاستيراد الجماعي';
    Swal.fire({
      icon: 'error',
      title: 'خطأ',
      text: msg,
      confirmButtonText: 'حسناً'
    });
  } finally {
    loadingBatch.value = false;
  }
}
</script>

<style scoped>
.gap-3 {
  gap: 12px;
}

.gap-4 {
  gap: 16px;
}
</style>
