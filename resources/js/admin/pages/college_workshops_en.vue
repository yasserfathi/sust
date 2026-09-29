<template>
  <div>
    <v-container fluid class="px-md-8 px-4">
      <v-card>
        <Table ref="table" :id="id" :url="url" :headers="headers"
          toolbar_title="الورش والمؤتمرات والسمنارات باللغة الانجليزية" @setFieldError="setFieldError" @save="save"
          @edit-item="editItem" @close="close" :fullscreen="fullscreen" transition="dialog-bottom-transition">
          <template v-slot:[`item.type`]="{ item }">
            <v-chip size="small" :color="item.type == 2 ? 'primary' : item.type == 3 ? 'purple' : 'teal'"
              variant="tonal">
              {{ item.type == 2 ? 'Conference' : item.type == 3 ? 'Seminar' : 'Workshop' }}
            </v-chip>
          </template>
          <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
            <v-row>
              <v-col cols="12" md="8" class="pt-0">
                <v-text-field autofocus id="title" name="title" label="Title" v-model="title"
                  variant="outlined" class="text-caption" prepend-icon="mdi-bank" :error-messages="errors.title"
                  density="comfortable" dir="ltr" maxlength="255" counter="255"></v-text-field>
              </v-col>
              <v-col cols="12" md="4" class="pt-0">
                <v-select id="type" name="type" :items="workshopTypes" item-title="name" density="comfortable"
                  item-value="id" prepend-icon="mdi-format-list-bulleted-type" v-model="type" label="Type"
                  :error-messages="errors.type" variant="outlined"></v-select>
              </v-col>
              <v-col cols="12" :md="colleges && colleges.length > 1 ? 4 : 6" class="pt-0"
                v-show="colleges && colleges.length > 1">
                <v-select id="college_id" name="college_id" :items="colleges" item-title="name_en" density="comfortable"
                  item-value="id" prepend-icon="mdi-bank" v-model="college_id" @update:modelValue="getAlbums($event)"
                  label="Colleges" :error-messages="errors.college_id" variant="outlined"></v-select>
              </v-col>
              <v-col cols="12" :md="colleges && colleges.length > 1 ? 4 : 6" class="pt-0">
                <date-picker label="Date" id="workshop_date" name="workshop_date" v-model="workshop_date"
                  :error-messages="errors.workshop_date"></date-picker>
              </v-col>
              <v-col cols="12" :md="colleges && colleges.length > 1 ? (filePath != '' ? 3 : 4) : (filePath != '' ? 5 : 6)" class="pt-0">
                <v-file-input type="file" id="file" name="file" v-model="file" show-size chips label="Attach File"
                  accept='.dox,.docx,.pdf' variant="outlined" :error-messages="errors.file"
                  density="comfortable"></v-file-input>
              </v-col>
              <v-col v-if="filePath != ''" cols="12" md="1" class="pt-7">
                <a :href="BASE_URL + filePath" class="text-subtitle-2">Current File<v-icon
                    icon="mdi-file-document" class="ms-1"></v-icon></a>
              </v-col>
              <v-col cols="12">
                <v-select id="album_id" name="album_id" :disabled="disabled" class="mb-5" :items="albums"
                  density="comfortable" item-title="title" item-value="id" prepend-icon="mdi-bank" v-model="album_id"
                  @update:modelValue="getAlbumPhotos($event)" label="Photo Albums" :error-messages="errors.album_id"
                  variant="outlined"></v-select>
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
                    <v-row density="comfortable">
                      <v-col cols="12" class="mb-4">
                        <div class="text-red-darken-3">Content Photos</div>
                      </v-col>
                      <v-col cols="1" v-for="(element, index) in photos" :key="index" style="position: relative">
                        <v-btn style="position: absolute;z-index: 5;left:-2px;top: -5px;min-width:1px" fab
                          size="x-small" color="red" rounded="xl"
                          @click="item = element.thumb_photo, itemType = 'photo', dialog = true">
                          <v-icon icon="mdi-close"></v-icon>
                          <v-tooltip activator="parent" location="top">Delete</v-tooltip>
                        </v-btn>
                        <v-img :src="BASE_URL + element.thumb_photo" :lazy-src="BASE_URL + element.thumb_photo"
                          class="align-end" aspect-ratio="4/3" cover style="cursor: pointer"
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
              <v-col cols="12">
                <v-text-field type="text" label="Keywords" v-model="keyWord" variant="outlined"
                  @keyup.enter="addKeyWords" prepend-icon="mdi-bank" v-click-outside="addKeyWords"
                  :error-messages="errors.keyWord" density="comfortable" dir="ltr">
                  <template v-slot:append>
                    <v-btn size="small" @click="addKeyWords" icon="mdi-plus"></v-btn>
                  </template>
                </v-text-field>
                <span v-for="(chipText, index) in chipData" :key="index" class="ma-1 w-100"><v-chip class="ma-2"
                    color="success">{{
                      chipText }} <v-icon class="mr-2"
                      @click="item = chipText, itemType = 'KeyWord', dialog = true">mdi-trash-can-outline</v-icon></v-chip></span>
              </v-col>
              <v-divider></v-divider>
              <v-col cols="12" class="mt-1">
                <v-text-field name="detail_portion" variant="outlined" label="Summary / Brief" v-model="detail_portion"
                  :error-messages="errors.detail_portion" prepend-icon="mdi-bank" density="comfortable"
                  dir="ltr"></v-text-field>
              </v-col>
              <v-col cols="12" class="pt-0 mt-0">
                <p class="font-weight-medium mb-2"><v-icon>mdi-plus</v-icon> Details</p>
                <Editor v-model="detail" />
                <span style="color:#ba0061;font-size: 12px;">{{ errors.detail }}</span>
              </v-col>
              <v-col cols="12" class="pt-0 mt-0">
                <v-switch :label="`Publish`" id="active" name="active" v-model="active" hide-details ripple
                  color="#198754"></v-switch>
              </v-col>
            </v-row>
          </Form>
        </Table>
      </v-card>
    </v-container>
    <v-dialog v-model="dialog" width="350">
      <v-card style="height:160px;">
        <v-card-text>
          <v-row>
            <v-col cols="12" class="text-center">
              Confirm Delete?
            </v-col>
          </v-row>
          <div class="d-flex justify-center mt-4">
            <v-btn color="green-darken-1" variant="elevated" @click="removeItem()" rounded="pill"
              class=" px-8">Confirm</v-btn>
            <v-btn color="grey-darken-1" variant="elevated" @click="dialog = false" rounded="pill"
              class="ms-4 px-8">Cancel</v-btn>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="showAlbum" persistent>
      <v-card class="mx-auto bg-grey-lighten-4" v-if="album_photos.length > 0">
        <v-container fluid>
          <v-row density="comfortable">
            <v-col cols="12" class="mb-4">
              <div class="text-red-darken-3">Album Photos - {{ album_title }}</div>
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
                <v-tooltip activator="parent" location="top">Add photo to content</v-tooltip>
              </v-checkbox>
            </v-col>
            <v-col cols="12">
              <v-btn block color="green-darken-1" variant="elevated" type="submit"
                @click="showAlbum = false, album_id = 0" ripple rounded="xl">
                Done
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
  </div>
</template>
<script lang="ts" setup>
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import { z as zod } from 'zod';
import axios from 'axios';
import Swal from 'sweetalert2';

const url = route('workshops_en.index')
const headers = [
  { title: 'Title', key: 'title', width: 280 },
  { title: 'Type', key: 'type', align: 'center', width: 120 },
  { title: 'College', key: 'college.name', align: 'center' },
  { title: 'Date', key: 'workshop_date', align: 'center' },
  { title: 'Active', key: 'active', sortable: false, align: 'center' },
  { title: 'Actions', key: 'actions', value: 'id', sortable: false, align: 'center' },
];

const workshopTypes = [
  { id: 1, name: 'Workshop' },
  { id: 2, name: 'Conference' },
  { id: 3, name: 'Seminar' },
];

const table = ref();
interface FormFields {
  id: number,
  college_id: number,
  album_id: number,
  title: string;
  type: number;
  workshop_date: Date;
  file: any;
  keyWord: string;
  detail_portion: string;
  detail: string;
  active: boolean;
}

const BASE_URL = window.location.origin + '/';
const ACCEPTED_FILE_TYPES = ["application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/pdf"];

const validationSchema = toTypedSchema(
  zod.object({
    college_id: zod.number({ required_error: "اختر اسم الكلية" }),
    title: zod.string({ required_error: "ادخل عنوان الورشة / المؤتمر" }).min(1, { message: "ادخل عنوان الورشة / المؤتمر" }).max(255, { message: "يجب أن لا يتجاوز العنوان 255 حرفاً" }),
    type: zod.number({ required_error: "Select Type" }),
    workshop_date: zod.any({ required_error: "اختر تاريخ الفعالية", invalid_type_error: "تأكد من التاريخ" }),
    file: zod.any().optional()
      .refine((files) => !files || files.length === 0 || files.length === 1, "اختر الملف المرفق")
      .refine((files) => !files || files.length === 0 || ((Array.isArray(files) ? files[0] : files)?.size || 0) <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`)
      .refine((files) => !files || files.length === 0 || ACCEPTED_FILE_TYPES.includes((Array.isArray(files) ? files[0] : files)?.type), "يتم دعم  فقط .doc, .docx, .pdf").nullish(),
    detail_portion: zod.string({ required_error: "ادخل جزء من المحتوى" }).trim().min(1, { message: 'ادخل جزء من المحتوى' }),
    detail: zod.string({ required_error: "ادخل المحتوى" }).trim().min(1, { message: 'ادخل المحتوى' }),
  })
);

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
let colleges = ref<Array<any>>([]);
let albums = ref<Array<any>>([]);
let chipData = ref(['Sudan University of Science and Technology']);

const disabled = ref(true);
const fullscreen = ref(true);
const showAlbum = ref(false);
const showImage = ref(false);
const photo = ref();
const photoDesc = ref();
let photos = ref<Array<any>>([]);
let thumb_photos = ref<Array<any>>([]);
let album_photos = ref<Array<any>>([]);
const album_title = ref();
const dialog = ref(false);
const item = ref();
const itemType = ref();
const filePath = ref();

const { value: title } = useField('title');
const { value: type } = useField('type');
const { value: college_id } = useField('college_id');
const { value: album_id } = useField('album_id');
const { value: workshop_date } = useField<Date>('workshop_date');
const { value: file } = useField('file');
const { value: detail_portion } = useField('detail_portion');
const { value: detail } = useField<string>('detail');
const { value: active } = useField('active');
const { value: keyWord } = useField<string>('keyWord');

let ErorrMsg = [
  { 'field': 'file', 'message': 'يتم دعم  فقط .doc, .docx, .pdf' },
  { 'field': 'title', 'message': 'عنوان الورشة موجود مسبقا' },
]

type.value = 1;
workshop_date.value = new Date();

const save = handleSubmit((values) => {
  if (photos.value.length == 0) {
    setFieldError('album_id', 'اختر صورة / صور محتوى الورشة')
    return;
  }

  let photo_ids = photos.value.map((a: any) => a.id);
  const formData = new FormData();
  formData.append("college_id", values.college_id.toString())
  formData.append("title", values.title)
  formData.append("type", values.type.toString())
  formData.append("lang", '2')
  formData.append("photos", photo_ids.sort().toString())
  formData.append("keywords", chipData.value.toString())
    let formattedDate = '';
  if (typeof values.workshop_date === 'string' && values.workshop_date.includes('-')) {
    formattedDate = values.workshop_date;
  } else {
    const d = new Date(values.workshop_date);
    formattedDate = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
  }
  formData.append("workshop_date", formattedDate)
  formData.append("detail_portion", values.detail_portion)
  formData.append("detail", values.detail)
  formData.append("active", Number(active.value).toString())

  if (file.value) {
    const f = Array.isArray(file.value) ? file.value[0] : file.value;
    if (f) formData.append("file", f);
  }

  let temp: Array<any> = [];
  table.value.SubmitLoading = true;
  if (id.value < 0) {
    axios.post(url, formData, { headers: { 'content-type': 'multipart/form-data' } }).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable(result.data.status, temp);
    }).catch(err => { if (table?.value) table.value.SubmitLoading = false; console.error(err); });
  } else {
    formData.append('_method', 'put');
    axios.post(url + "/" + id.value, formData, { headers: { 'content-type': 'multipart/form-data' } }).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable(result.data.status, temp);
    }).catch(err => { if (table?.value) table.value.SubmitLoading = false; console.error(err); })
  }
});

function editItem(item: any) {
  item = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.id;
    axios.get(url + '/' + item.id).then(response => {
      item = response.data.result;
      if (item.keywords != null && item.keywords.length > 0) {
        chipData.value = item.keywords.split(',');
      } else {
        chipData.value = ['Sudan University of Science and Technology'];
      }
      filePath.value = item.file;
      photos.value = toRaw(item.photos);
      album_photos.value = thumb_photos.value = []
      setValues({
        college_id: item.college_id,
        title: item.title,
        type: item.type || 1,
        workshop_date: item.workshop_date,
        active: item.active,
        detail_portion: item.detail_portion,
        detail: item.detail,
      })
      getAlbums(item.college_id)
      table.value.disableEditButton = false
      table.value.dialog = true
    });
  }
}

function close() {
  nextTick(() => {
    id.value = -1
    type.value = 1
    if (chipData.value.length < 1) {
      chipData.value.push('Sudan University of Science and Technology')
    }
    resetForm();
    if (colleges.value && colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
    filePath.value = '';
    photo.value = '';
  });
  photos.value = [];
  albums.value = [];
  table.value.disableEditButton = false;
  table.value.dialog = false;
  table.value.tableloading = false;
}

function addKeyWords() {
  if (keyWord.value != undefined && keyWord.value.trim() != '') {
    const parts = keyWord.value.split(',').map(s => s.trim()).filter(s => s !== '');
    parts.forEach(part => {
      if (!chipData.value.includes(part)) {
        chipData.value.push(part);
      }
    });
  }
  keyWord.value = "";
}

function removeItem() {
  if (itemType.value == 'KeyWord') {
    chipData.value.splice(chipData.value.indexOf(item.value), 1)
    if (chipData.value.length == 0)
      setFieldError('keyWord', 'ادخل الكلمة / الكلمات المفتاحية')
  }
  else if (itemType.value == 'photo') {
    photos.value = photos.value.filter(function (key: any) { return key.thumb_photo != item.value; });
    thumb_photos.value = photos.value.map((a: any) => a.thumb_photo)
    if (thumb_photos.value.length == 0)
      setFieldError('album_id', 'اختر صورة / صور محتوى الورشة')
  }
  dialog.value = false
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
      albums.value.unshift({ 'id': 0, 'title': 'اختر الالبوم' });
      album_id.value = response.data.albums[0].id
    }
  });
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
  photo.value = BASE_URL + '/' + photoPath
  showImage.value = true
}

watch(
  () => thumb_photos.value,
  (newVal) => {
    if (album_photos.value.length > 0) {
      const temp = newVal
        .map((item) => {
          return (
            toRaw(album_photos.value.find((a: any) => a.thumb_photo === item)) ||
            toRaw(photos.value.find((a: any) => a.thumb_photo === item))
          );
        })
        .filter(Boolean);

      if (temp.length > 0) {
        photos.value = temp;
      }
    }
  },
  { deep: true }
);

onBeforeMount(() => {
  getColleges();
});
</script>
