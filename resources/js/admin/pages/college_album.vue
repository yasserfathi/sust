<template>
  <v-container>
    <v-card>
  <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="صور معرض الصفحة الرئيسية"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close">
                <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
                  <v-row>
                          <v-col cols="12">
                    <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                      item-value="id" prepend-icon="mdi-bank" v-model="college_id" @update:modelValue="getAlbums($event)"
                      label="اسماء الكليات" :error-messages="errors.college_id" variant="underlined"></v-select>
                  </v-col>
                  <v-col cols="12">
              <v-select id="album_id" name="album_id" :disabled="disabled" class="mb-5" :items="albums"
                density="comfortable" item-title="title" item-value="id" prepend-icon="mdi-bank" v-model="album_id"
                @update:modelValue="getAlbumPhotos($event)" label="البومات الصور" :error-messages="errors.album_id"
                variant="underlined"></v-select>
              <v-dialog v-model="showImage" width="800">
                <v-card>
                  <v-card-text class="pa-3">
                    <v-img :src="photo" :lazy-src="photo"></v-img>
                    <div class="text-body-1 mt-2">{{ photoDesc }}</div>
                  </v-card-text>
                </v-card>
              </v-dialog>
              <v-card class="mx-auto mt-4 bg-grey-lighten-4" v-if="photos.length > 0">
                <v-container fluid>
                  <v-row dense>
                    <v-col cols="12" class="mb-4">
                      <div class="text-red-darken-3">الصور المختارة لمعرض الصور</div>
                    </v-col>
                    <v-col cols="1" v-for="(element, index) in photos" :key="index" style="position: relative">
                      <v-btn style="position: absolute;z-index: 5;left:-2px;top: -5px;min-width:1px" fab size="x-small"
                        color="red" rounded="xl" @click="item = element.thumb_photo, dialog = true">
                        <v-icon icon="mdi-close"></v-icon>
                        <v-tooltip activator="parent" location="top">حذف</v-tooltip>
                      </v-btn>
                      <v-img :src="BASE_URL + element.thumb_photo" :lazy-src="BASE_URL + element.thumb_photo"
                        class="align-end" :aspect-ratio="4/3" cover style="cursor: pointer"
                        @click="showModal(element.photo, element.title)">
                        <template v-slot:placeholder>
                          <v-row class="fill-height ma-0" align="center" justify="center">
                            <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                          </v-row>
                        </template>
                      </v-img>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card>
            </v-col>
                  </v-row>
                </Form>
        <v-dialog v-model="showAlbum" persistent>
    <v-card class="mx-auto bg-grey-lighten-4" v-if="album_photos.length > 0">
      <v-container fluid>
        <v-row dense>
          <v-col cols="12" class="mb-4">
            <div class="text-red-darken-3">صور البوم - {{ album_title }}</div>
          </v-col>
          <v-col cols="2" v-for="(element, index) in album_photos" :key="index" class="pa-0">
            <v-img :src="BASE_URL + element.thumb_photo" :lazy-src="BASE_URL + element.thumb_photo" class="align-end"
              aspect-ratio="4/3" cover style="cursor: pointer" @click="showModal(element.photo, element.title)">
              <template v-slot:placeholder>
                <v-row class="fill-height ma-0" align="center" justify="center">
                  <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                </v-row>
              </template>
            </v-img>
            <v-checkbox id="active" name="active" hide-details v-model="thumb_photos" :value="element.thumb_photo"
              color="#198754" class="mr-8">
              <v-tooltip activator="parent" location="top">اضافة الصورة للخبر</v-tooltip>
            </v-checkbox>
          </v-col>
          <v-col cols="12">
            <v-btn block color="green-darken-1" variant="elevated" type="submit"
              @click="showAlbum = false" ripple rounded="xl">
              موافق
            </v-btn>
          </v-col>
        </v-row>
      </v-container>
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
        <v-row>
          <v-col cols="12" class="mx-1">
            <v-btn block color="green-darken-1" @click="removeItem()" ripple rounded="xl">موافق</v-btn>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" class="pt-0">
            <v-btn block color="grey-darken-1" @click="dialog = false" ripple rounded="xl">الغاء</v-btn>
          </v-col>
        </v-row>
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
import { defineAsyncComponent, nextTick, onBeforeMount, ref, toRaw, watch } from 'vue';
import { Form, useField, useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import axios from 'axios';
import Swal from 'sweetalert2';

const Table = defineAsyncComponent(() => import('../components/Table.vue') )

const BASE_URL = import.meta.env.VITE_BASE_URL + '/';

const url = route('college_galleries.index');
const headers = [
  { title: 'اسم الكلية', key: 'college.name', sortable: false},
  { title: 'عدد الصور الختارة', key: 'photos_count', sortable: false },
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

const photoDesc = ref();
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
    id.value = -1
    thumb_photos.value = []
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
      albums.value.unshift({ 'id': 0, 'title': 'اختر الالبوم' });
      album_id.value = response.data.albums[0].id
    }
  });
  college_album(college_id);
}

async function getAlbumPhotos(album_id: any) {
  if (album_id != 0) {
    album_title.value = albums.value[1].title;
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

function showModal(photoPath: any, desc: any) {
  photoDesc.value = desc
  photo.value = BASE_URL + photoPath
  showImage.value = true
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



onBeforeMount(() => {
  getColleges();
});

</script>
