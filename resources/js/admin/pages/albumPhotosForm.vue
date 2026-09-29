<template>
  <div>
    <v-data-table :headers="headers" :items="photos" :items-per-page-options="itemsPerPagelist" items-per-page="5"
      :loading="tableLoading" transition="dialog-bottom-transition" hide-no-data hover>
      <template v-slot:top>
        <v-toolbar>
          <v-toolbar-title>بيانات الصور</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" persistent max-width="1100px">
            <template v-slot:activator="{ props }">
              <v-btn class="mb-2 bg-red" prepend-icon="mdi-plus" v-bind="props" ripple rounded="xl"
                @click="(e) => e.currentTarget.blur()">
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
                <v-container fluid class="px-md-8 px-4">
                  <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
                    <v-row>
                      <v-col cols="12" md="4">
                        <v-text-field autofocus id="title" name="title" label="الوصف باللغة العربية" v-model="title"
                          variant="outlined" :error-messages="errors.title" density="comfortable" maxlength="255" counter="255"></v-text-field>
                      </v-col>
                      <v-col cols="12" md="4">
                        <v-text-field id="title_en" name="title_en" label="الوصف باللغة الانجليزية" v-model="title_en"
                          variant="outlined" :error-messages="errors.title_en" density="comfortable" maxlength="255" counter="255"></v-text-field>
                      </v-col>
                      <v-col cols="12" :md="imagePath != '' ? 3 : 4">
                        <v-file-input type="file" id="img" name="img" v-model="img" show-size chips accept='image/*'
                          label="اختر ملف الصورة" variant="outlined" :error-messages="errors.img" clearable
                          density="comfortable"></v-file-input>
                      </v-col>
                      <v-col v-if="imagePath != ''" cols="12" md="1">
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
                  <div class="d-flex justify-start mt-6">
                    <v-btn color="green-darken-1" variant="elevated" type="submit" :loading="SubmitLoading"
                      @click="save" rounded="pill" class=" px-8">
                      {{ btnText }}
                    </v-btn>
                    <v-btn color="grey-darken-1" variant="elevated" @click="close" rounded="pill" class="ms-4 px-8">
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

      <template v-slot:[`item.is_active`]="{ item }">
        <v-checkbox
          :model-value="Boolean(item.is_active)"
          @update:model-value="toggleActive(item, $event)"
          color="success"
          hide-details
          density="compact"
        >
          <template v-slot:label>
            <span style="font-size: 13px;">{{ item.is_active ? 'مختارة بالكلية' : 'غير مختارة' }}</span>
          </template>
        </v-checkbox>
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
  </div>
</template>

<script lang="ts" setup>
import { Form, useField, useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import axios from 'axios';
import Swal from 'sweetalert2';

const BASE_URL = window.location.origin;

interface Props {
  album_id?: number;
}

const props = defineProps<Props>();

const url = route('album_photos.index');
const headers = [
  { title: 'الصورة', key: 'thumb_img', sortable: false, minWidth: '100px' },
  { title: 'الوصف باللغة العربية', key: 'title', minWidth: '250px' },
  { title: 'الوصف باللغة الانجليزية', key: 'title_en', minWidth: '250px' },
  { title: 'عرض في الكلية (حد أقصى 5)', key: 'is_active', sortable: false, minWidth: '150px' },
  { title: '', key: 'actions', sortable: false, minWidth: '120px' },
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
  title: zod.string({ required_error: "اختر الوصف باللغة العربية" }).min(1, { message: "اختر الوصف باللغة العربية" }).max(255, { message: "يجب أن لا يتجاوز الوصف 255 حرفاً" }),
  title_en: zod.string({ required_error: "اختر الوصف باللغة الانجليزية" }).min(1, { message: "اختر الوصف باللغة الانجليزية" }).max(255, { message: "يجب أن لا يتجاوز الوصف 255 حرفاً" })
});

const validationSchema = toTypedSchema(zod.union([
  form_items_types.merge(zod.object({
    img: zod.any().refine((files) => (!files || files.length === 0) ? id.value > -1 : true, "اختر الصورة")
      .refine((files) => {
        if (!files || files.length === 0) return true;
        const f = Array.isArray(files) ? files[0] : files;
        return f && (ACCEPTED_IMAGE_TYPES.includes(f.type) || f.name?.toLowerCase().endsWith('.jpg') || f.name?.toLowerCase().endsWith('.jpeg') || f.name?.toLowerCase().endsWith('.png'));
      }, "يتم دعم  فقط .jpg, .jpeg and .png")
      .refine((files) => {
        if (!files || files.length === 0) return true;
        const f = Array.isArray(files) ? files[0] : files;
        return f && (f.size || 0) <= 5 * 1024 * 1024;
      }, `الحد الأقصى لحجم الملف هو 5 ميجابايت`),
    id: zod.number().negative()
  })),
  form_items_types.merge(zod.object({
    img: zod.any().refine((files) => (!files || files.length === 0) ? id.value > -1 : true, "اختر الصورة")
      .refine((files) => {
        if (!files || files.length === 0) return true;
        const f = Array.isArray(files) ? files[0] : files;
        return f && (ACCEPTED_IMAGE_TYPES.includes(f.type) || f.name?.toLowerCase().endsWith('.jpg') || f.name?.toLowerCase().endsWith('.jpeg') || f.name?.toLowerCase().endsWith('.png'));
      }, "يتم دعم  فقط .jpg, .jpeg and .png")
      .refine((files) => {
        if (!files || files.length === 0) return true;
        const f = Array.isArray(files) ? files[0] : files;
        return f && (f.size || 0) <= 5 * 1024 * 1024;
      }, `الحد الأقصى لحجم الملف هو 5 ميجابايت`).nullish(),
    id: zod.number().negative().nullish()
  })),
]));

const { handleSubmit, resetForm, errors, setFieldError, setValues } = useForm<FormFields>({ validationSchema });

const id = ref(-1);
const photos = ref<any[]>([]);

const dialog = ref(false);
const disableEditButton = ref(false);
const SubmitLoading = ref(false);
const tableLoading = ref(false);

const { value: title } = useField('title');
const { value: title_en } = useField('title_en');
const { value: img } = useField<string>('img');
const { value: method } = useField('method');

const save = handleSubmit(async (values) => {
  let errorDetected = false;

  const formData = new FormData();
  formData.append("album_id", props.album_id?.toString() || '');
  formData.append("title", values.title.toString());
  formData.append("title_en", values.title_en.toString());

  if (values.img) {
    const fileToAppend = Array.isArray(values.img) ? values.img[0] : values.img;
    if (fileToAppend instanceof File || fileToAppend instanceof Blob) {
      formData.append("img", fileToAppend);
    } else {
      console.warn("values.img is present but is not a valid File object:", values.img);
    }
  }

  SubmitLoading.value = true;
  try {
    if (id.value > -1) {
      formData.append('_method', 'put');
      const result = await axios.post(url + "/" + id.value, formData, { headers: { 'content-type': 'multipart/form-data' } });
      if (result.data.status === 409 || (result.data.message && typeof result.data.message === 'object')) {
        errorDetected = true;
        Object.entries(result.data.message).forEach(([key, value]) => {
          setFieldError(key, value);
        });
      }
    } else {
      const result = await axios.post(url, formData, { headers: { 'content-type': 'multipart/form-data' } });
      if (result.data.status === 409 || (result.data.message && typeof result.data.message === 'object')) {
        errorDetected = true;
        Object.entries(result.data.message).forEach(([key, value]) => {
          setFieldError(key, value);
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
    await getAlbumPhotos();
    Swal.fire({ title: 'تمت العملية بنجاح', icon: 'success', confirmButtonColor: '#198754', confirmButtonText: "موافق", timer: 1500 });
    close();
  }
});

function editItem(item: any) {
  item = toRaw(item);
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
      axios.post(url + '/' + id, { _method: 'DELETE' }).then(async () => {
        await getAlbumPhotos();
        Swal.fire({ title: 'تمت العملية بنجاح', icon: 'success', confirmButtonColor: '#198754', confirmButtonText: "موافق", timer: 1500 });
      });
    }
  });
}

function close() {
  nextTick(() => {
    resetForm();
    id.value = -1;
    method.value = 'post';
    imagePath.value = '';
    imageThumbPath.value = '';
    photo.value = '';
  });
  dialog.value = false;
}

function showImageDialog(imgVal: string, titleVal: string) {
  photo.value = BASE_URL + '/' + imgVal;
  img_title.value = titleVal;
  showImage.value = true;
}


async function toggleActive(item: any, isChecked: any) {
  const willBeActive = Boolean(isChecked);
  
  // Check local limit first
  const currentActiveCount = photos.value.filter((p: any) => p.is_active && p.id !== item.id).length;
  if (willBeActive && currentActiveCount >= 5) {
    Swal.fire({
      title: 'تنبيه',
      text: 'يمكنك اختيار 5 صور كحد أقصى لعرضها في معرض الكلية',
      icon: 'warning',
      confirmButtonColor: '#198754',
      confirmButtonText: 'موافق'
    });
    return;
  }

  try {
    const res = await axios.put(route('album_photos.toggle_active', item.id), {
      is_active: willBeActive
    });
    item.is_active = willBeActive;
    Swal.fire({
      title: 'تم التحديث',
      text: willBeActive ? 'تمت إضافة الصورة لصور الكلية المختارة' : 'تمت إزالة الصورة من صور الكلية المختارة',
      icon: 'success',
      confirmButtonColor: '#198754',
      confirmButtonText: 'موافق',
      timer: 1200
    });
  } catch (err: any) {
    const msg = err.response?.data?.message || 'حدث خطأ أثناء تحديث حالة الصورة';
    Swal.fire({
      title: 'خطأ',
      text: msg,
      icon: 'error',
      confirmButtonColor: '#198754',
      confirmButtonText: 'موافق'
    });
  }
}

async function getAlbumPhotos() {
  if (!props.album_id || props.album_id === -1) return;
  try {
    const response = await axios.get(route('albums.show', props.album_id));
    photos.value = [...response.data.result.photos];
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

watch(() => props.album_id, (val) => {
  if (val && val > 0) {
    getAlbumPhotos();
  }
});

onBeforeMount(() => {
  method.value = 'post';
  tableLoading.value = true;
  if (props.album_id && props.album_id > 0) {
    getAlbumPhotos();
  }
});

onMounted(() => {
  tableLoading.value = false;
});
</script>