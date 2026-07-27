<template>
  <v-container>
    <v-card>
      <Table :id="id" ref="table" :url="url" :headers="headers" toolbar_title="مستخدمو النظام و أعضاء هيئة التدريس"
        @setFieldError="setFieldError" @edit-item="editItem" @close="close" :fullscreen="fullscreen"
        :showButtons="false">
        <v-stepper v-model="currentStep" editable>
          <v-stepper-header>
            <v-stepper-item :complete="currentStep > 1" :value="1" title="البيانات الشخصية"></v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :complete="currentStep > 2" :value="2" title="البيانات الوظيفية"
              :editable="isStep1Valid"></v-stepper-item>
          </v-stepper-header>
          <v-stepper-window>
            <v-stepper-window-item :value="1">
              <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
                <v-row>
                  <v-col cols="12" md="3">
                    <v-text-field id="name" name="name" label="اسم المستخدم باللغة العربية" v-model="name"
                      variant="underlined" :error-messages="errors.name"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="3">
                    <v-text-field id="name_en" name="name_en" label="اسم المستخدم باللغة الانجليزية" v-model="name_en"
                      variant="underlined" :error-messages="errors.name_en"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="3">
                    <v-text-field id="phone" name="phone" label="رقم الهاتف" v-model="phone" variant="underlined"
                      :error-messages="errors.phone"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="3">
                    <v-text-field id="email" name="email" label="البريد الالكتروني" v-model="email" variant="underlined"
                      :error-messages="errors.email"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field id="university_no" name="university_no" label="الرقم الجامعي" v-model="university_no"
                      variant="underlined" :error-messages="errors.university_no"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-select id="role" name="role" :items="user_roles" item-title="name" item-value="id"
                      prepend-icon="mdi-account-box-outline" v-model="role" label="دور المستخدم"
                      :error-messages="errors.role" variant="underlined"></v-select>
                  </v-col>
                  <v-col cols="12" :md="imagePath != undefined ? 3 : 4">
                    <v-file-input type="file" id="img" name="img" ref="imgInput" show-size chips
                      accept='.jpg,.jpeg,.png' @change="onSelectImage" label="اختر ملف الصورة" variant="underlined"
                      :error-messages="errors.img"></v-file-input>
                  </v-col>
                  <v-col v-if="imagePath && imagePath !== 'null' && imagePath !== 'undefined'" cols="12" md="1">
                    <v-img :src="BASE_URL + '/' + imagePath" :lazy-src="BASE_URL + '/' + imagePath" :height="48"
                      style="cursor: pointer" @click="showModal()">
                      <template v-slot:placeholder>
                        <v-row class="fill-height ma-0" align="center" justify="center">
                          <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                        </v-row>
                      </template>
                    </v-img>
                    <v-dialog v-model="showImage" width="800">
                      <v-card>
                        <v-card-text class="pa-3">
                          <v-img v-if="photo" :src="photo" :lazy-src="photo"></v-img>
                        </v-card-text>
                      </v-card>
                    </v-dialog>
                  </v-col>
                  <v-col cols="12">
                    <v-switch :label="`تنشيط المستخدم`" id="active" name="active" v-model="active" color="#198754"
                      density="compact" hide-details class="pr-3"></v-switch>
                  </v-col>
                </v-row>
              </Form>
              <div class="d-flex justify-end mt-6">
                <v-btn color="primary" @click="save" :loading="step1Loading" append-icon="mdi-arrow-left"
                  :disabled="!isStep1Valid">
                  التالي
                </v-btn>
              </div>
            </v-stepper-window-item>
            <v-stepper-window-item :value="2">
              <user-personnel-form :user_id="id"></user-personnel-form>
              <v-btn color="primary" @click="currentStep = 1">السابق</v-btn>
              <v-divider class="my-3"></v-divider>
              <Dialog ref="modal" />
              <v-card class="mx-auto" max-width="100%">
              </v-card>
            </v-stepper-window-item>
          </v-stepper-window>
        </v-stepper>
      </Table>
    </v-card>
  </v-container>
</template>

<script lang="ts" setup>
import { defineAsyncComponent, nextTick, onBeforeMount, computed, ref, toRaw, watch, onMounted } from 'vue';
const Table = defineAsyncComponent(() => import('../components/Table.vue'))
import userPersonnelForm from '../pages/userPersonnelForm.vue'
const Dialog = defineAsyncComponent(() => import('../components/Dialog.vue'))
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import axios from 'axios';
import Swal from 'sweetalert2';
import { isMatch } from 'lodash';
const BASE_URL = import.meta.env.VITE_BASE_URL;

const url = route('users.index')
const headers = [
  { title: 'اسم المستخدم', key: 'name' },
  { title: 'الكلية', key: 'staff[0].department.college.name' },
  { title: 'القسم', key: 'staff[0].department.name' },
  { title: 'الدرجة العلمية', key: 'staff[0].rank' },
  { title: 'المسمى الوظيفي', key: 'staff[0].job_title' },
  { title: '', key: 'thumb_img', sortable: false },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
const modal = ref();
const imgInput = ref();
const imagePath = ref();
const photo = ref();
const showImage = ref(false);
const currentStep = ref(1);
const step1Loading = ref(false);

interface FormFields {
  id: number,
  name: string;
  name_en: string;
  phone: string;
  email: string;
  university_no: string;
  role: number;
  img: any;
  active: boolean;
  method: any;
}

const ACCEPTED_IMAGE_TYPES = ["image/jpeg", "image/jpg", "image/png"];

const form_items_types = zod.object({
  name: zod.string({ required_error: "ادخل اسم المستخدم باللغة العربية" }).min(1, { message: "ادخل اسم المستخدم باللغة العربية" }),
  name_en: zod.string({ required_error: "ادخل اسم المستخدم باللغة الانجليزية" }).trim().min(1, { message: 'ادخل اسم المستخدم باللغة الانجليزية' }),
  phone: zod.string({ required_error: "ادخل رقم الهاتف" }).trim().min(1, { message: 'ادخل رقم الهاتف' }),
  email: zod.string({ required_error: "ادخل البريد الالكتروني" }).trim().min(1, { message: 'ادخل البريد الالكتروني' }),
  role: zod.number({ required_error: "اختر دور المستخدم" }),
  university_no: zod.string({ required_error: "ادخل الرقم الجامعي" }).trim().min(1, { message: 'ادخل الرقم الجامعي' })
});

const img_obj = zod.any()
  .refine((files) => !files || files.length === 0 || ACCEPTED_IMAGE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .jpg, .jpeg and .png")
  .refine((files) => !files || files.length === 0 || files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`);

const validationSchema = toTypedSchema(zod.discriminatedUnion('method', [
  form_items_types.merge(zod.object({
    img: img_obj,
    method: zod.literal('post')
  })),
  form_items_types.merge(zod.object({
    img: img_obj.nullish(),
    method: zod.literal('put')
  })),
]));

const { handleSubmit, resetForm, errors, setValues, setFieldError, meta } = useForm<FormFields>({ validationSchema });

let user_roles = ref<Array<any>>([{ 'id': -1, 'name': 'اختر دور المستخدم' }, { 'id': 1, 'name': 'مدير النظام' }, { 'id': 2, 'name': 'عضو هيئة تدريس' }, { 'id': 3, 'name': 'موظف' }]);

const id = ref(-1);
let currentItem = {};
const fullscreen = ref(true);
const { value: name } = useField('name');
const { value: name_en } = useField('name_en');
const { value: phone } = useField('phone');
const { value: email } = useField('email');
const { value: university_no } = useField('university_no');
const { value: role } = useField('role');
const { value: active } = useField<Boolean>('active');
const { value: img } = useField<string>('img');
const { value: method } = useField('method');
let ErorrMsg = [
  { 'field': 'img', 'message': 'يتم دعم  فقط .jpg, .jpeg and .png' },
  { 'field': 'img', 'message': 'يتم دعم  فقط .jpg, .jpeg and .png' },
  { 'field': 'email', 'message': 'البريد الالكتروني موجود مسبقاً' },
  { 'field': 'univ_no', 'message': 'الرقم الجامعي موجود مسبقاً' },
  { 'field': 'phone', 'message': 'رقم الهاتف موجود مسبقاً' },
]

const save = handleSubmit(async (values) => {
  step1Loading.value = true;
  const formData = new FormData();
  formData.append("album_id", '1');
  formData.append("name", values.name.toString());
  formData.append("name_en", values.name_en.toString());
  formData.append("email", values.email.toString());
  formData.append("univ_no", values.university_no.toString());
  formData.append("phone", values.phone.toString());
  formData.append("role", values.role.toString());
  formData.append("active", Number(active.value).toString());

  if (imgInput.value.files[0] != undefined) {
    formData.append("img", imgInput.value.files[0])
  }

  let temp: Array<any> = [];
  if (id.value > -1 && isMatch(currentItem, values) != true) {
    formData.append('_method', 'put');
    await axios.post(url + "/" + id.value, formData, {
      headers: { 'content-type': 'multipart/form-data' },
      validateStatus: (status) => status < 500
    }).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable_album_news(result.data.status, temp);
      if (result.status === 200 || result.status === 201) {
        currentStep.value = 2;
      }
    });
  } else if (id.value > -1 && isMatch(currentItem, values) == true) {
    currentStep.value = 2;
  }
  else {
    await axios.post(url, formData, {
      headers: { 'content-type': 'multipart/form-data' },
      validateStatus: (status) => status < 500
    }).then(result => {
      id.value = result.data.user_id;
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable_album_news(result.data.status, temp);

      // Navigate based on status
      if (result.status === 201) {
        setTimeout(() => {
          currentStep.value = 2; // Auto-advance to functional data on success
        }, 1500);
      } else if (result.status === 409) {
        currentStep.value = 1; // Stay on personal data on error
        let errorText = 'حدث خطأ ما';
        if (result.data.message.email && result.data.message.email[0] === 'duplicate') {
          errorText = 'البريد الالكتروني مستخدم من قبل';
        } else if (result.data.message.univ_no && result.data.message.univ_no[0] === 'duplicate') {
          errorText = 'الرقم الجامعي مستخدم من قبل';
        } else if (result.data.message.phone && result.data.message.phone[0] === 'duplicate') {
          errorText = 'رقم الهاتف مستخدم من قبل';
        }

        Swal.fire({
          title: 'خطأ!',
          text: errorText,
          icon: 'error',
          confirmButtonColor: '#d33',
          confirmButtonText: 'موافق'
        }).then(() => {
          nextTick(() => {
            currentStep.value = "1";
          });
        });
      } else {
        currentStep.value = 1; // Stay on personal data on error
      }
    });
  }
  // if (valid) {
  //   currentStep.value = 2;
  // }
  step1Loading.value = false;
  // close();
});

// function editItem(item: any) {
//   currentItem = item = JSON.parse(JSON.stringify(item));
//   id.value = item.id;
//   setValues({
//     college_id: item.college_id,
//     title: item.title,
//     title_en: item.title_en,
//     active: item.active,
//   })
//   table.value.dialog = true
// }

function editItem(item: any) {
  item = toRaw(item);
  currentItem = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.id;
    axios.get(url + '/' + item.id).then(response => {
      item = response.data.result;
      if (item != null) {
        imagePath.value = item.thumb_img;
        setValues({
          name: item.name,
          name_en: item.name_en,
          active: item.active,
          university_no: item.univ_no,
          role: parseInt(item.role),
          phone: item.phone,
          email: item.email
        })
      }
      table.value.disableEditButton = false
      table.value.dialog = true
    });
  }
}

function close() {
  nextTick(() => {
    id.value = -1;
    imagePath.value = undefined;
    resetForm();
  });
  currentStep.value = 1
  table.value.dialog = false
}

function showModal() {
  modal.value.showDialog = true
}

function onSelectImage() {
  img.value = imgInput.value.files
}

const isStep1Valid = computed(() => {

  return name.value && name_en.value && phone.value &&
    email.value && university_no.value && role.value !== -1 && meta.value.valid && Object.keys(errors.value).length === 0;
});

watch(() => id.value, val => {
  if (val < 0)
    method.value = 'post'
  else
    method.value = 'put'
});

onBeforeMount(() => {
  method.value = 'post';
});

</script>
