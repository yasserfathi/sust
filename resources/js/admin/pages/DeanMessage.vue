<template>
  <Head :title="isArabic ? 'كلمة العميد' : 'Dean Message'" />
  <v-container fluid class="px-md-8 px-4">
    <v-card class="mx-auto my-5" elevation="2">
      <!-- Header Image with College Name -->
      <v-img
        class="align-end text-white"
        height="250"
        :src="college.cover_url || '/images/college-cover.jpg'"
        cover
        gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.6)"
      >
        <v-card-title class="text-h3 font-weight-bold mb-2 px-6">
          {{ isArabic ? 'كلمة العميد' : "Dean's Message" }}
        </v-card-title>
        <v-card-subtitle class="text-h5 font-weight-medium opacity-90 mb-6 px-6">
          {{ isArabic ? college.name_ar : college.name_en }}
        </v-card-subtitle>
      </v-img>

      <v-card-text class="pa-6">
        <v-row>
          <!-- Dean's Profile Section -->
          <v-col cols="12" md="3" class="text-center border-e-md">
            <v-avatar size="180" class="elevation-5 mb-4">
              <v-img 
                :src="dean?.photo_url || '/images/default-user.png'" 
                :alt="isArabic ? dean?.name_ar : dean?.name_en"
                cover
              ></v-img>
            </v-avatar>
            <h2 class="text-h5 font-weight-bold text-primary">
              {{ isArabic ? dean?.name_ar : dean?.name_en }}
            </h2>
            <v-chip class="mt-3" color="secondary" variant="flat" size="small">
              {{ isArabic ? 'عميد الكلية' : 'Dean of College' }}
            </v-chip>
          </v-col>

          <!-- Message Content Section -->
          <v-col cols="12" md="9" class="ps-md-8">
            <div class="d-flex align-center mb-4">
              <v-icon icon="mdi-format-quote-open" color="primary" size="40" class="me-3 opacity-50"></v-icon>
              <h3 class="text-h5 font-weight-regular text-grey-darken-3">
                {{ isArabic ? 'الرسالة الترحيبية' : 'Welcome Message' }}
              </h3>
            </div>

            <div class="text-body-1 text-justify" style="line-height: 1.8; white-space: pre-wrap;">
              {{ isArabic ? message?.content_ar : message?.content_en }}
            </div>
            
            <div class="d-flex justify-end mt-10">
              <div class="text-center">
                <v-img 
                  v-if="dean?.signature_url" 
                  :src="dean.signature_url" 
                  width="150" 
                  class="mb-2"
                ></v-img>
                <div class="font-weight-bold text-subtitle-1">
                  {{ isArabic ? dean?.name_ar : dean?.name_en }}
                </div>
              </div>
            </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps<{
  college: {
    name_ar: string;
    name_en: string;
    cover_url?: string;
  };
  dean: {
    name_ar: string;
    name_en: string;
    photo_url?: string;
    signature_url?: string;
  };
  message: {
    content_ar: string;
    content_en: string;
  };
}>();

const page = usePage();
const isArabic = computed(() => page.props.locale === 'ar');
</script>

<style scoped>
.border-e-md {
  border-right: 1px solid rgba(0, 0, 0, 0.12);
}
/* Adjust border for RTL layout */
[dir="rtl"] .border-e-md {
  border-right: none;
  border-left: 1px solid rgba(0, 0, 0, 0.12);
}
</style>