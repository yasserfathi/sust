<template>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
  <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="صور معرض الصفحة الرئيسية"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close">
                <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
                  <v-row>
                          <v-col cols="12" v-show="colleges && colleges.length > 1">
                    <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                      item-value="id" prepend-icon="mdi-bank" v-model="college_id" @update:modelValue="getAlbums($event)" label="اسماء الكليات" :error-messages="errors.college_id" variant="outlined"></v-select>
                  </v-col>
                  <v-col cols="12">
              <v-select id="album_id" name="album_id" :disabled="disabled" class="mb-5" :items="albums"
                density="comfortable" item-title="title" item-value="id" prepend-icon="mdi-bank" v-model="album_id"
                @update:modelValue="getAlbumPhotos($event)" label="البومات الصور" :error-messages="errors.album_id"
                variant="outlined"></v-select>
              <!-- Modal for single photo preview with description and checkbox -->
              <v-dialog v-model="showImage" width="700" max-width="95vw">
                <v-card class="rounded-xl overflow-hidden" elevation="6">
                  <v-toolbar color="primary" density="compact" class="px-3">
                    <v-toolbar-title class="text-subtitle-1 font-weight-bold text-white">تفاصيل الصورة</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon size="small" variant="text" @click="showImage = false" class="text-white">
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                  </v-toolbar>
                  <v-card-text class="pa-4 bg-grey-lighten-4">
                    <v-img :src="photo" :lazy-src="photo" max-height="60vh" class="rounded-lg shadow-sm" contain></v-img>
                    <div v-if="photoDesc" class="text-subtitle-1 font-weight-bold mt-3 text-grey-darken-3 text-center">
                      {{ photoDesc }}
                    </div>
                  </v-card-text>
                  <v-card-actions class="pa-4 bg-white border-t d-flex justify-space-between align-center">
                    <v-checkbox
                      v-if="currentSelectedThumb"
                      :model-value="thumb_photos.includes(currentSelectedThumb)"
                      @update:model-value="togglePhotoSelection(currentSelectedThumb)"
                      color="success"
                      hide-details
                      density="comfortable"
                    >
                      <template v-slot:label>
                        <span class="font-weight-bold text-body-2">
                          {{ thumb_photos.includes(currentSelectedThumb) ? 'مختارة لمعرض الصفحة الرئيسية' : 'إضافة لمعرض الصفحة الرئيسية' }}
                        </span>
                      </template>
                    </v-checkbox>
                    <v-btn color="primary" variant="flat" rounded="pill" class="px-6 font-weight-bold" @click="showImage = false">
                      إغلاق
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
              <v-card class="mx-auto mt-4 bg-grey-lighten-4" v-if="photos.length > 0">
                <v-container fluid>
                  <v-row density="comfortable">
                    <v-col cols="12" class="mb-4">
                      <div class="text-red-darken-3 font-weight-bold">الصور المختارة لمعرض الصفحة الرئيسية (الحد الأقصى: 5 صور)</div>
                    </v-col>
                    <v-col cols="12" sm="6" md="4" lg="2" v-for="(element, index) in photos" :key="index" style="position: relative">
                      <v-card class="elevation-2 rounded-lg overflow-hidden">
                        <v-btn style="position: absolute;z-index: 5;left: 4px;top: 4px;min-width:1px" fab size="x-small"
                          color="red" rounded="circle" @click="item = element.thumb_photo, dialog = true">
                          <v-icon icon="mdi-close" size="small"></v-icon>
                          <v-tooltip activator="parent" location="top">حذف الصورة</v-tooltip>
                        </v-btn>
                        <v-img :src="BASE_URL + element.thumb_photo" :lazy-src="BASE_URL + element.thumb_photo"
                          class="align-end" :aspect-ratio="4/3" cover style="cursor: pointer"
                          @click="showModal(element.photo || element.thumb_photo, element.title, element.thumb_photo)">
                          <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                              <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                            </v-row>
                          </template>
                        </v-img>
                      </v-card>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card>
            </v-col>
                  </v-row>
                </Form>
        <v-dialog v-model="showAlbum" persistent max-width="960px">
          <v-card class="mx-auto rounded-xl overflow-hidden" v-if="album_photos.length > 0">
            <v-toolbar color="primary" class="px-4" density="comfortable">
              <v-icon icon="mdi-image-multiple" class="me-2 text-white"></v-icon>
              <v-toolbar-title class="text-subtitle-1 font-weight-bold text-white">
                صور ألبوم: {{ album_title }} <span class="text-caption opacity-80">(اختر حتى 5 صور)</span>
              </v-toolbar-title>
              <v-spacer></v-spacer>
              <v-chip color="white" variant="flat" size="small" class="text-primary font-weight-bold me-2">
                {{ thumb_photos.length }} / 5 مختارة
              </v-chip>
              <v-btn icon variant="text" @click="showAlbum = false" class="text-white">
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-toolbar>

            <v-card-text class="pa-5 bg-grey-lighten-4" style="max-height: 65vh; overflow-y: auto;">
              <v-row dense>
                <v-col cols="12" sm="6" md="4" lg="3" v-for="(element, index) in album_photos" :key="index" class="pa-2">
                  <v-card 
                    :class="['gallery-select-card rounded-lg overflow-hidden elevation-2 transition-all', thumb_photos.includes(element.thumb_photo) ? 'selected-card-border' : '']"
                    style="position: relative;"
                  >
                    <!-- Checkbox Badge Overlay -->
                    <div style="position: absolute; top: 8px; right: 8px; z-index: 4;">
                      <v-checkbox 
                        :model-value="thumb_photos.includes(element.thumb_photo)"
                        @click.stop
                        @update:model-value="togglePhotoSelection(element.thumb_photo)"
                        hide-details 
                        color="success" 
                        density="compact"
                        class="ma-0 pa-0"
                      ></v-checkbox>
                    </div>

                    <!-- Image with click to preview details modal only -->
                    <v-img 
                      :src="BASE_URL + element.thumb_photo" 
                      :lazy-src="BASE_URL + element.thumb_photo" 
                      aspect-ratio="16/10" 
                      cover
                      style="cursor: pointer;"
                      @click.stop="showModal(element.photo || element.thumb_photo, element.title, element.thumb_photo)"
                    >
                      <template v-slot:placeholder>
                        <v-row class="fill-height ma-0" align="center" justify="center">
                          <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                        </v-row>
                      </template>
                    </v-img>
                  </v-card>
                </v-col>
              </v-row>
            </v-card-text>

            <v-card-actions class="pa-4 bg-white border-t d-flex justify-space-between align-center">
              <span class="text-caption text-grey-darken-1">
                تم اختيار <strong class="text-primary">{{ thumb_photos.length }}</strong> من أصل 5 صور مسموحة
              </span>
              <div class="d-flex ga-2">
                <v-btn variant="text" color="grey-darken-1" @click="showAlbum = false" rounded="pill" class="px-5">
                  إلغاء
                </v-btn>
                <v-btn color="primary" variant="flat" @click="showAlbum = false" rounded="pill" class="px-8 font-weight-bold">
                  تأكيد الاختيار
                </v-btn>
              </div>
            </v-card-actions>
          </v-card>
        </v-dialog>
  <v-dialog v-model="dialog" width="350">
    <v-card style="height:160px;">
      <v-card-text>
        <v-row>
          <v-col cols="12" class="text-center">
            تأكيد عملية الحذف ؟
          </v-col>
        </v-row>
        <div class="d-flex justify-center mt-4">
                  <v-btn color="green-darken-1" variant="elevated" @click="removeItem()" rounded="pill" class=" px-8">موافق</v-btn>
            <v-btn color="grey-darken-1" variant="elevated" @click="dialog = false" rounded="pill" class="ms-4 px-8">الغاء</v-btn>
                </div>
      </v-card-text>
    </v-card>
  </v-dialog>

    <template v-slot:[`item.thumb_img`]="{ item }">
      <v-avatar>
        <v-img :src="BASE_URL + item['thumb_img']" :lazy-src="BASE_URL + item['thumb_img']"
          @click="showImageDialog(item['img'], item['title'])">
        </v-img>
      </v-avatar>
    </template>
  </Table>
</v-card>
</v-container>
</template>
<script lang="ts" setup>
import { Form, useField, useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import axios from 'axios';
import Swal from 'sweetalert2';


const BASE_URL = window.location.origin + '/';

const url = route('college_galleries.index');
const headers = [
  { title: 'اسم الكلية', key: 'college.name', sortable: false},
  { title: 'عدد الصور المختارة', key: 'photos_count', sortable: false },
  { title: '', key: 'view', sortable: false },
];

let colleges = ref<Array<any>>([]);
let albums = ref<Array<any>>([]);
const disabled = ref(true);
const showAlbum = ref(false);
const img_title = ref('');
const photo = ref('');
const showImage = ref(false);
const dialog = ref(false);

const photoDesc = ref('');
const currentSelectedThumb = ref('');
let photos = ref<Array<any>>([]);
let thumb_photos = ref<Array<any>>([]);
let album_photos = ref<Array<any>>([]);
const album_title = ref();
const item = ref();

interface FormFields {
  id: number,
  college_id: number,
  album_id: number
}

const validationSchema = toTypedSchema(
  zod.object({
    college_id: zod.number({ required_error: "اختر اسم الكلية" })
  })
);

const { handleSubmit, resetForm, errors, setFieldError } = useForm<FormFields>({ validationSchema });

const id = ref(-1);

const table = ref<any>(null);

const { value: college_id } = useField('college_id');
const { value: album_id } = useField('album_id');

const save = handleSubmit(async (values) => {
  let errorDetected = false;

  if (photos.value.length == 0) {
    setFieldError('album_id', 'اختر صورة')
    return;
  }

  let photo_ids = photos.value.map((a: any) => a.id);
  const formData = new FormData();
  formData.append("college_id", values.college_id.toString())
  formData.append("photos", photo_ids.sort().toString())
  if (id.value > -1) {
      formData.append('_method', 'put');
      axios.post(url + "/" + id.value, formData, { headers: { 'content-type': 'multipart/form-data' } }).then(result => {
        table.value.PopulateTable(result.data.status, []);
      })
    } else {
      await axios.post(url, formData, { headers: { 'content-type': 'multipart/form-data' } }).then(result => {
        table.value.PopulateTable(result.data.status, []);
      })
    }
});

function editItem(item: any) {
  item = toRaw(item);
  id.value = item.id;
  college_id.value = item.college_id;
  college_album(college_id.value);
  table.value.dialog = true;
}

function college_album(college_id: number){
  axios.get(url + '/' + college_id).then(response => {
    if(response.data.album_photos.length > 0){
      id.value = response.data.album_photos[0].id;
      photos.value = response.data.album_photos;
      thumb_photos.value = photos.value.map((a: any) => a.thumb_photo)
    }
  });
}

function close() {
  nextTick(() => {
    resetForm();
    if (colleges.value && colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
    id.value = -1
    thumb_photos.value = []
    photo.value = '';
  });
  albums.value = []
  table.value.dialog = false
}

function showImageDialog(imgVal: string, titleVal: string) {
  photo.value = BASE_URL + '/' + imgVal;
  img_title.value = titleVal;
  showImage.value = true;
}

async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
  if (colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
    });
}

async function getAlbums(college_id: any) {
  await axios.post(route("albums.list"), { 'college_id': college_id }).then(response => {
    if (disabled.value == true) {
      disabled.value = !disabled.value
    }
    if (response.data.albums.length < 1) {
      albums.value = []
      album_id.value = undefined
      disabled.value = true
    }
    else {
      albums.value = response.data.albums;
      // Auto-select the first album for this college
      const targetAlbum = albums.value[0];
      albums.value.unshift({ 'id': 0, 'title': 'اختر الالبوم' });
      album_id.value = targetAlbum.id;
      getAlbumPhotos(targetAlbum.id);
    }
  });
  college_album(college_id);
}

async function getAlbumPhotos(album_id: any) {
  if (album_id != 0) {
    const selected = albums.value.find((a: any) => a.id === album_id);
    album_title.value = selected ? selected.title : '';
    await axios.post(route("album_photos.list"), { 'album_id': album_id }).then(response => {
      album_photos.value = response.data.album_photos;
      if (album_photos.value.length != 0) {
        showAlbum.value = true
      }
      else
        Swal.fire({ title: 'لا توجد صور بهذا الالبوم', icon: 'error', confirmButtonColor: '#198754', confirmButtonText: "موافق" });
    });
    thumb_photos.value = photos.value.map((a: any) => a.thumb_photo)
  }
}

function showModal(photoPath: any, desc: any = '', thumbPath: any = '') {
  photo.value = BASE_URL + '/' + photoPath;
  photoDesc.value = desc || '';
  currentSelectedThumb.value = thumbPath || '';
  showImage.value = true;
}

function removeItem() {
    photos.value = photos.value.filter(function (key: any) { return key.thumb_photo != item.value; });
    thumb_photos.value = photos.value.map((a: any) => a.thumb_photo)
    if (thumb_photos.value.length == 0)
      setFieldError('album_id', 'اختر صورة / صور محتوى الخبر')
  dialog.value = false
}

watch(
  () => thumb_photos.value,
  (newVal) => {
      if (newVal.length > 5) {
        const trimmed = newVal.slice(0, 5);
        thumb_photos.value = trimmed;
        Swal.fire({
          title: "تنبيه",
          text: "يمكنك اختيار 5 صور فقط",
          icon: "warning",
          confirmButtonColor: "#198754",
          confirmButtonText: "موافق",
        });
        return;
      }
      photos.value = newVal
        .map((item) => {
          return (
            toRaw(album_photos.value.find((a: any) => a.thumb_photo === item)) ||
            toRaw(photos.value.find((a: any) => a.thumb_photo === item))
          );
        })
        .filter(Boolean);
  },
  { deep: true }
);



function togglePhotoSelection(thumbPath: string) {
  const index = thumb_photos.value.indexOf(thumbPath);
  if (index > -1) {
    thumb_photos.value.splice(index, 1);
  } else {
    if (thumb_photos.value.length >= 5) {
      Swal.fire({
        title: "تنبيه",
        text: "يمكنك اختيار 5 صور فقط",
        icon: "warning",
        confirmButtonColor: "#198754",
        confirmButtonText: "موافق",
      });
      return;
    }
    thumb_photos.value.push(thumbPath);
  }
}

onBeforeMount(() => {
  getColleges();
});

</script>

<style scoped>
.gallery-select-card {
  border: 2px solid transparent;
  transition: all 0.2s ease-in-out;
}

.gallery-select-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.selected-card-border {
  border: 2px solid #198754 !important;
  box-shadow: 0 0 10px rgba(25, 135, 84, 0.35) !important;
}
</style>
