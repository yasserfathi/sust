<template>
  <v-container>
    <v-card>
  <v-data-table :headers="headers" :items="photos" :items-per-page-options="itemsPerPagelist" items-per-page="5"
    :loading="tableLoading" transition="dialog-bottom-transition" hide-default-footer disable-pagination hide-no-data
    hover>
    <template v-slot:top>
      <v-toolbar>
        <v-toolbar-title><v-icon end icon="mdi-menu"></v-icon> صور معرض الصفحة الرئيسية</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" persistent max-width="1100px">
          <template v-slot:activator="{ props }">
            <v-btn class="mb-2 bg-red" prepend-icon="mdi-plus" v-bind="props" ripple rounded="xl">
              اضافة بيانات
            </v-btn>
          </template>
          <v-card>
            <v-toolbar class="bg-red-darken-1" density="compact">
              <v-toolbar-title><v-icon end :icon="formIcon"></v-icon> {{ formTitle }}</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn icon @click="close" aria-label="Close dialog">
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-toolbar>
            <v-card-text>
              <v-container>
                <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
                  <v-row>
                    <v-col cols="12" md="4">
                      <v-text-field id="title" name="title" label="الوصف باللغة العربية" v-model="title"
                        variant="underlined" :error-messages="errors.title"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-text-field id="title_en" name="title_en" label="الوصف باللغة الانجليزية" v-model="title_en"
                        variant="underlined" :error-messages="errors.title_en"></v-text-field>
                    </v-col>
                    <v-col cols="12" :md="imagePath != undefined ? 3 : 4">
                      <v-file-input type="file" id="img" name="img" ref="imgInput" show-size chips
                        accept='image/*' @change="onSelectImage" label="اختر ملف الصورة" variant="underlined"
                        :error-messages="errors.img"></v-file-input>
                    </v-col>
                    <v-col v-if="imagePath != undefined" cols="12" md="1">
                      <v-img :src="BASE_URL + '/' + imageThumbPath" :lazy-src="BASE_URL + '/' + imageThumbPath"
                        :height="48" style="cursor: pointer" @click="showImageDialog(imagePath)">
                        <template v-slot:placeholder>
                          <v-row class="fill-height ma-0" align="center" justify="center">
                            <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                          </v-row>
                        </template>
                      </v-img>
                    </v-col>
                  </v-row>
                </Form>
                <div class="mt-5" cols="12">
                  <v-btn color="green-darken-1" variant="elevated" type="submit" :loading="SubmitLoading" @click="save"
                    ripple rounded="xl">
                    {{ btnText }}
                  </v-btn>
                  <v-btn color="grey-darken-1" class="mr-1" variant="elevated" @click="close" ripple rounded="xl">
                    الغاء
                  </v-btn>
                </div>
              </v-container>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>

    <template v-slot:[`item.actions`]="{ item, index }">
      <v-icon size="small" color="success" class="me-2" @click="editItem(item)" :disabled="disableEditButton"
        :key="index" icon="mdi-pencil-outline" />
      <v-icon size="small" color="red" @click="deleteItem(item['id'])" icon="mdi-trash-can-outline" />
    </template>

    <template v-slot:[`item.thumb_img`]="{ item }">
      <v-avatar>
        <v-img :src="BASE_URL + '/' + item['thumb_img']" :lazy-src="BASE_URL + '/' + item['thumb_img']"
          @click="showImageDialog(item['img'], item['title'])">
        </v-img>
      </v-avatar>
    </template>
  </v-data-table>

  <!-- Standalone Dialog for Image Preview -->
  <v-dialog v-model="showImage" width="800">
    <v-card>
      <v-card-text class="pa-3">
        <v-img :src="photo" :lazy-src="photo"></v-img>
        <div class="text-body-1 mt-2">{{ img_title }}</div>
      </v-card-text>
    </v-card>
  </v-dialog>
</v-card>
</v-container>
</template>
<script lang="ts" setup>
import { nextTick, onBeforeMount, computed, ref, toRaw, onMounted, watch } from 'vue';
import { Form, useField, useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import axios from 'axios';
import Swal from 'sweetalert2';

const BASE_URL = import.meta.env.VITE_BASE_URL;

const album_id = 1;

const url = route('album_photos.index');
const headers = [
  { title: 'الوصف باللغة العربية', key: 'title' },
  { title: 'الوصف باللغة الانجليزية', key: 'title_en' },
  { title: 'الصورة', key: 'thumb_img' },
  { title: '', key: 'actions', sortable: false },
];

const itemsPerPagelist = ref([
  { value: 5, title: '5' },
  { value: 10, title: '10' },
  { value: 25, title: '25' },
  { value: 50, title: '50' },
  { value: 100, title: '100' },
  { value: -1, title: '$vuetify.dataFooter.itemsPerPageAll' }
]);

const img_title = ref('');
const imgInput = ref();
const imageThumbPath = ref('');
const imagePath = ref('');
const photo = ref('');
const showImage = ref(false);

interface FormFields {
  id: number;
  title: string;
  title_en: string;
  img: any;
}

const ACCEPTED_IMAGE_TYPES = ["image/jpeg", "image/jpg", "image/png"];

const form_items_types = zod.object({
  title: zod.string({ required_error: "اختر الوصف باللغة العربية" }).min(1, { message: "اختر الوصف باللغة العربية" }),
  title_en: zod.string({ required_error: "اختر الوصف باللغة الانجليزية" }).min(1, { message: "اختر الوصف باللغة الانجليزية" })
});

const validationSchema = toTypedSchema(zod.union([
  form_items_types.merge(zod.object({
    img: zod.any().refine((files) => files?.length == 1, "اختر الصورة")
          .refine((files) => ACCEPTED_IMAGE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .jpg, .jpeg and .png")
         .refine((files) => files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`),
    id: zod.number().negative()
  })),
  form_items_types.merge(zod.object({
    img: zod.any().refine((files) => files?.length == 1, "اختر الصورة")
          .refine((files) => ACCEPTED_IMAGE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .jpg, .jpeg and .png")
         .refine((files) => files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`).nullish(),
    id: zod.number().negative().nullish()
  })),
]));

const { handleSubmit, resetForm, errors, setFieldError, setValues } = useForm<FormFields>({ validationSchema });

const id = ref(-1);
let photos = ref([]);

const dialog = ref(false);
const disableEditButton = ref(false);
const SubmitLoading = ref(false);
const tableLoading = ref(false);

const { value: title } = useField('title');
const { value: title_en } = useField('title_en');
const { value: img } = useField('img');
const { value: method } = useField('method');

const save = handleSubmit(async (values) => {
  let errorDetected = false;

  const formData = new FormData();
  formData.append("album_id", album_id);
  formData.append("title", values.title.toString());
  formData.append("title_en", values.title_en.toString());

  if (imgInput.value?.files?.[0] != undefined) {
    formData.append("img", imgInput.value.files[0]);
  }

  SubmitLoading.value = true;
  try {
    if (id.value > -1) {
      formData.append('_method', 'put');
      const result = await axios.post(url + "/" + id.value, formData, { headers: { 'content-type': 'multipart/form-data' } });
      if (result.data.message) {
        Object.entries(result.data.message).forEach(([key, value]) => {
          if (key === 'img') {
            errorDetected = true;
            setFieldError('img', value);
          }
        });
      }
    } else {
      const result = await axios.post(url, formData, { headers: { 'content-type': 'multipart/form-data' } });
      if (result.data.message) {
        Object.entries(result.data.message).forEach(([key, value]) => {
          if (key === 'img') {
            errorDetected = true;
            setFieldError('img', value);
          }
        });
      }
    }
  } catch (error) {
    errorDetected = true;
    Swal.fire({ title: 'حدث خطأ أثناء العملية', icon: 'error', confirmButtonColor: '#198754', confirmButtonText: "موافق", timer: 1500 });
  } finally {
    SubmitLoading.value = false;
  }

  if (!errorDetected) {
    getAlbumPhotos();
    Swal.fire({ title: 'تمت العملية بنجاح', icon: 'success', confirmButtonColor: '#198754', confirmButtonText: "موافق", timer: 1500 });
    close();
  }
});

function editItem(item: any) {
  item = toRaw(item);
  console.log(item)
  disableEditButton.value = true;
  id.value = item.id;
  axios.get(url + '/' + id.value).then(response => {
    item = response.data.result;
    imageThumbPath.value = item.thumb_img;
    imagePath.value = item.img;
    setValues({
      title: item.title,
      title_en: item.title_en,
    });
    disableEditButton.value = false;
    dialog.value = true;
  });
}

function deleteItem(id: number) {
  Swal.fire({
    title: 'تأكيد عملية الحذف ؟',
    icon: 'warning',
    confirmButtonColor: '#198754',
    cancelButtonColor: '#d33',
    confirmButtonText: 'موافق',
    cancelButtonText: 'الغاء',
    showCancelButton: true,
    showCloseButton: true
  }).then((result: { isConfirmed: any; }) => {
    if (result.isConfirmed) {
      axios.post(url + '/' + id, { _method: 'DELETE' }).then(() => {
        getAlbumPhotos();
        Swal.fire({ title: 'تمت العملية بنجاح', icon: 'success', confirmButtonColor: '#198754', confirmButtonText: "موافق", timer: 1500 });
      });
    }
  });
}

function close() {
  nextTick(() => {
    resetForm();
    id.value = -1
    method.value = 'post'
  });
  dialog.value = false
}

function showImageDialog(imgVal: string, titleVal: string) {
  photo.value = BASE_URL + '/' + imgVal;
  img_title.value = titleVal;
  showImage.value = true;
}

function onSelectImage() {
  img.value = imgInput.value.files
}

async function getAlbumPhotos() {
  try {
    const response = await axios.get(route('albums.show', album_id));
    photos.value = response.data.result.photos;
  } catch (error) {
    console.error('Error fetching album photos:', error);
  }
}

const formIcon = computed(() => {
  return id.value === -1 ? 'mdi-plus-circle' : 'mdi-pencil-circle';
});

const btnText = computed(() => {
  return id.value != -1 ? 'تعديل' : 'حفظ';
});

const formTitle = computed(() => {
  return id.value === -1 ? 'اضافة بيانات' : 'تعديل بيانات';
});

watch(() => id.value, (val) => {
  if (val < 0) {
    method.value = 'post';
  } else {
    method.value = 'put';
  }
});

watch(() => dialog.value, (val) => {
  if (!val) {
    close();
  }
});

onBeforeMount(() => {
  method.value = 'post';
  tableLoading.value = true;
  getAlbumPhotos();
});

onMounted(() => {
  tableLoading.value = false;
});
</script>
