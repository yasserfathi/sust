<template>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
      <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="معرض الصور"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close" :fullscreen="fullscreen"
        transition="dialog-bottom-transition">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" :md="colleges && colleges.length > 1 ? 4 : 6" class="pt-0" v-show="colleges && colleges.length > 1">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="college_id" @update:modelValue="getDepartments($event)" label="اسماء الكليات" :error-messages="errors.college_id" variant="outlined"></v-select>
            </v-col>
            <v-col cols="12" :md="colleges && colleges.length > 1 ? 4 : 6" class="pt-0" v-if="authStore?.user?.role === 1">
              <v-select id="department_id" name="department_id" :disabled="deartment_disabled" :items="departments"
                item-title="name" density="comfortable" item-value="id" prepend-icon="mdi-bank" v-model="department_id"
                @update:modelValue="getUsers($event)" label="اسماء الاقسام" :error-messages="errors.department_id"
                variant="outlined"></v-select>
            </v-col>
            <v-col cols="12" :md="colleges && colleges.length > 1 ? 4 : 6" class="pt-0" v-if="authStore?.user?.role === 1">
              <v-select id="user_id" name="user_id" :disabled="user_disabled" :items="users" item-title="name"
                density="comfortable" item-value="id" prepend-icon="mdi-bank" v-model="user_id"
                label="اعضاء هيئة التدريس" :error-messages="errors.user_id" variant="outlined"></v-select>
            </v-col>
          

            <v-col cols="12" md="6" class="pt-0">
              <v-text-field id="title" name="title" label="وصف الصورة  باللغة العربية" v-model="title"
                prepend-icon="mdi-book-open-page-variant-outline" variant="outlined" :error-messages="errors.title"
                density="comfortable" maxlength="255" counter="255"></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-text-field id="title_en" name="title_en" label="وصف الصورة  باللغة الانجليزية" v-model="title_en"
                prepend-icon="mdi-book-open-page-variant-outline" variant="outlined" :error-messages="errors.title_en"
                density="comfortable" maxlength="255" counter="255"></v-text-field>
            </v-col>
            <v-col cols="12" :md="imagePath != '' ? 3 : 4" class="pt-0">
              <v-file-input type="file" id="img" name="img" v-model="img" show-size chips accept='.jpg,.jpeg,.png'
                label="اختر ملف الصورة" variant="outlined" :error-messages="errors.img"
                density="comfortable"></v-file-input>
            </v-col>

            <v-divider></v-divider>

            <v-card class="mx-auto bg-grey-lighten-4" v-if="album_photos.length > 0">
              <v-container fluid>
                <v-row density="comfortable">
                  <v-col cols="12" class="mb-4">
                    <div class="text-red-darken-3">صور البوم</div>
                  </v-col>
                  <v-col cols="2" v-for="(element, index) in album_photos" :key="index" class="pa-0">
                    <v-img :src="BASE_URL + element.thumb_photo" :lazy-src="BASE_URL + element.thumb_photo"
                      class="align-end" aspect-ratio="4/3" cover style="cursor: pointer">
                      <!-- @click="showModal(element.photo, element.title)"> -->
                      <template v-slot:placeholder>
                        <v-row class="fill-height ma-0" align="center" justify="center">
                          <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                        </v-row>
                      </template>
                    </v-img>
                    <v-checkbox id="active" name="active" hide-details :value="element.thumb_photo" color="#198754"
                      class="mr-8">
                      <v-tooltip activator="parent" location="top">اضافة الصورة للخبر</v-tooltip>
                    </v-checkbox>
                  </v-col>
                  <v-col cols="12">
                    <v-btn block color="green-darken-1" variant="elevated" type="submit" ripple rounded="xl">
                      موافق
                    </v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>


          </v-row>
        </Form>
      </Table>
    </v-card>
  </v-container>
</template>
<script lang="ts" setup>
import { useAuthStore } from '../store/index';
const authStore = useAuthStore();
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import { z as zod } from 'zod';
import axios from 'axios';

const url = route('staff_academic.index', 'staff_album_photos')
const headers = [
  { title: 'اسم عضو هيئة التدريس', key: 'user.name', align: 'center' },
  { title: 'عدد الصور', key: 'total_images', align: 'center', sortable: false },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
interface FormFields {
  id: number,
  college_id: number,
  department_id: number,
  user_id: number,
  title: string,
  title_en: string,
  img: any;
}
const BASE_URL = window.location.origin + '/';
const ACCEPTED_IMAGE_TYPES = ["image/jpeg", "image/jpg", "image/png"];

const form_items_types = zod.object({
  college_id: authStore?.user?.role !== 1 ? zod.any().nullish() : zod.number({ required_error: "اختر اسم الكلية" }).positive({ message: "اختر اسم الكلية" }),
  department_id: authStore?.user?.role !== 1 ? zod.any().nullish() : zod.number({ required_error: "اختر اسم القسم" }).positive({ message: 'اختر اسم القسم' }).nullable(),
  title: zod.string({ required_error: "ادخل وصف الصورة  باللغة العربية" }).trim().min(1, { message: 'ادخل وصف الصورة  باللغة العربية' }).max(255, { message: "يجب أن لا يتجاوز الوصف 255 حرفاً" }),
  title_en: zod.string({ required_error: "ادخل وصف الصورة  باللغة الانجليزية" }).trim().min(1, { message: 'ادخل وصف الصورة  باللغة الانجليزية' }).max(255, { message: "يجب أن لا يتجاوز الوصف 255 حرفاً" }),
  user_id: authStore?.user?.role !== 1 ? zod.any().nullish() : zod.number({ required_error: "اختر اسم عضو هيئة التدريس" }).positive({ message: "اختر اسم عضو هيئة التدريس" }),
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


const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
const fullscreen = ref(true);
const colleges = shallowRef();
const departments = shallowRef([]);
const users = shallowRef([]);

const deartment_disabled = ref(true);
const user_disabled = ref(true);
const imgInput = ref();
const imageThumbPath = ref();
const imagePath = ref();
const photo = ref();
const showImage = ref(false);
let album_photos = ref<Array<any>>([]);

const { value: college_id } = useField('college_id');
const { value: department_id } = useField('department_id');
const { value: user_id } = useField('user_id');
const { value: title } = useField('title');
const { value: title_en } = useField('title_en');
const { value: img } = useField('img');

const save = handleSubmit(async (values) => {

  let ErorrMsg = [
    { 'field': 'img', 'message': 'يتم دعم  فقط .jpg, .jpeg and .png' },
    { 'field': 'item_val', 'message': 'عنوان الكتاب موجود مسبقاً' },
  ]

  const formData = new FormData();
  formData.append("user_id", values.user_id ? values.user_id.toString() : '')
  formData.append("title", values.title.toString())
  formData.append("title_en", values.title_en.toString())
  if (img.value) {
    const f_img = Array.isArray(img.value) ? img.value[0] : img.value;
    if (f_img) formData.append("img", f_img);
  }

  let temp: Array<any> = [];
  table.value.SubmitLoading = true;
  if (id.value < 0) {
    await axios.post(url, formData, { headers: { 'content-type': 'multipart/form-data' } }).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable(result.data.status, temp);
    }).catch(err => { table.value.SubmitLoading = false; console.error(err); })
  }
  else {
    formData.append('_method', 'put');
    await axios.post(url + "/" + id.value, formData, { headers: { 'content-type': 'multipart/form-data' } }).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable(result.data.status, temp);
    }).catch(err => { table.value.SubmitLoading = false; console.error(err); });
  }

  // close();
});

function editItem(item: any) {
  item = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.user.id;
    axios.get(url + '/' + item.user.id).then(response => {
      item = response.data.result;
      album_photos.value = response.data.result;
      title.value = item.title;
      title_en.value = item.title_en;
      imageThumbPath.value = item.thumb_img;
      imagePath.value = item.img;
      deartment_disabled.value = user_disabled.value = false
      departments.value = [{ 'id': item[0].department_id, 'name': item[0].department_name }];
      //users.value = [{ 'id': item[0].user_id, 'name': item.user.name }];
      setValues({
        college_id: item[0].college_id,
        department_id: item[0].department_id,
        user_id: item[0].user_id,
      })
      table.value.disableEditButton = false
      table.value.dialog = true;
    });
  }
}

function close() {
  nextTick(() => {
    id.value = -1
    departments.value.unshift({ 'id': -1, 'name': 'اختر القسم' });
    imageThumbPath.value = '';
    photo.value = '';
    department_id.value = -1;
    users.value.unshift({ 'id': -1, 'name': 'اختر عضو هيئة التدريس' });
    user_id.value = -1
    deartment_disabled.value = user_disabled.value = true
    imagePath.value = ''
    resetForm();
    if (colleges.value && colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
  });
  table.value.disableEditButton = false;
  table.value.dialog = false;
}



async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
  if (colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
    });
}

async function getDepartments(college_id: any) {
  await axios.post(route("departments.list"), { 'college_id': college_id }).then(response => {
    if (deartment_disabled.value == true) {
      deartment_disabled.value = !deartment_disabled.value

    }

    if (response.data.departments.length < 1) {
      departments.value.unshift({ 'id': -1, 'name': 'اختر القسم' });
      department_id.value = -1;

      users.value.unshift({ 'id': -1, 'name': 'اختر عضو هيئة التدريس' });
      user_id.value = -1

      deartment_disabled.value = user_disabled.value = true
    }
    else {
      departments.value = response.data.departments;
      departments.value.unshift({ 'id': -1, 'name': 'اختر القسم' });
      department_id.value = response.data.departments[0].id
    }
  });
}

async function getUsers(department_id: any) {
  await axios.post(route("users.list"), { 'department_id': department_id }).then(response => {
    if (user_disabled.value == true) {
      user_disabled.value = !user_disabled.value
    }
    if (response.data.users.length < 1) {
      users.value.unshift({ 'id': -1, 'name': 'اختر عضو هيئة التدريس' });
      user_id.value = -1
    }
    else {
      users.value = response.data.users;
      users.value.unshift({ 'id': -1, 'name': 'اختر عضو هيئة التدريس' });
      user_id.value = response.data.users[0].id
    }
  });
}

function showModal(photoPath: any) {
  photo.value = photoPath
  showImage.value = true
}

onBeforeMount(() => {
  getColleges();
});
</script>
