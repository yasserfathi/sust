<template>
  <v-container>
    <v-card>
      <Table :id="id" ref="table" :url="url" :headers="headers" title="اسماء الكليات" @setFieldError="setFieldError"
        @save="save" @edit-item="editItem" @close="close">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12">
              <v-text-field id="name" name="name" label="اسم الكلية باللغة العربية" v-model="name" variant="underlined"
                :error-messages="errors.name"></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-text-field id="name_en" name="name_en" label="اسم الكلية باللغة الانجليزية" v-model="name_en"
                variant="underlined" :error-messages="errors.name_en"></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-select id="college_type" name="college_type" :items="college_types" item-title="name" item-value="val"
                v-model="college_type" label="النوع" :error-messages="errors.college_type"></v-select>
            </v-col>
            <v-col cols="12" :md="logoPath != undefined ? 4 : 6" class="pt-0">
              <v-file-input type="file" id="logo" name="logo" ref="logoInput" show-size chips accept='images/*'
                @change="onSelectLogoImage" label="اختر ملف الشعار العربي" variant="underlined"
                :error-messages="errors.logo"></v-file-input>
            </v-col>
            <v-col v-if="logoPath != undefined" cols="12" md="2">
              <v-img :src="BASE_URL + '/' + logoPath" :lazy-src="BASE_URL + '/' + logoPath" :height="48"
                style="cursor: pointer" @click="showModal(logoPath)">
                <template v-slot:placeholder>
                  <v-row class="fill-height ma-0" align="center" justify="center">
                    <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                  </v-row>
                </template>
              </v-img>
            </v-col>

            <v-col cols="12" :md="logoEnPath != undefined ? 4 : 6" class="pt-0">
              <v-file-input type="file" id="logo_en" name="logo_en" ref="logoEnInput" show-size chips accept='images/*'
                @change="onSelectLogoENImage" label="اختر ملف الشعار الانجليزي" variant="underlined"
                :error-messages="errors.logo_en"></v-file-input>
            </v-col>
            <v-col v-if="logoEnPath != undefined" cols="12" md="2">
              <v-img :src="BASE_URL + '/' + logoEnPath" :lazy-src="BASE_URL + '/' + logoEnPath" :height="48"
                style="cursor: pointer" @click="showModal(logoEnPath)">
                <template v-slot:placeholder>
                  <v-row class="fill-height ma-0" align="center" justify="center">
                    <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                  </v-row>
                </template>
              </v-img>
            </v-col>

            <v-col cols="12" :md="bannerPath != undefined ? 4 : 6" class="pt-0">
              <v-file-input type="file" id="banner" name="banner" ref="bannerInput" show-size chips accept='images/*'
                @change="onSelectBannerImage" label="اختر ملف البانر" variant="underlined"
                :error-messages="errors.banner"></v-file-input>
            </v-col>
            <v-col v-if="bannerPath != undefined" cols="12" md="2">
              <v-img :src="BASE_URL + '/' + bannerPath" :lazy-src="BASE_URL + '/' + bannerPath" :height="48"
                style="cursor: pointer" @click="showModal(bannerPath)">
                <template v-slot:placeholder>
                  <v-row class="fill-height ma-0" align="center" justify="center">
                    <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                  </v-row>
                </template>
              </v-img>
            </v-col>
            <v-col cols="12">
              <v-switch :label="`تنشيط الكلية`" id="active" name="active" v-model="active" color="#198754"
                density="compact" hide-details></v-switch>
            </v-col>
          </v-row>
        </Form>
      </Table>
    </v-card>
    <Dialog v-model="modal" width="500">
      <v-card>
        <v-card-text class="pa-3">
          <v-img :src="photo" :lazy-src="photo"></v-img>
        </v-card-text>
      </v-card>
    </Dialog>
  </v-container>
</template>
<script lang="ts" setup>
import { defineAsyncComponent, toRaw } from 'vue'
const Table = defineAsyncComponent(() => import('../components/Table.vue'))
const Dialog = defineAsyncComponent(() => import('../components/Dialog.vue'))
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import { nextTick, ref } from 'vue';
import axios from 'axios';

const BASE_URL = import.meta.env.VITE_BASE_URL + '/';
const url = route('colleges.index')
const headers = [
  { title: 'اسم الكلية باللغة االعربية', key: 'name' },
  { title: 'اسم الكلية باللغة الانجليزية', key: 'name_en' },
  { title: 'النوع', key: 'college_type' },
  { title: 'تنشيط', key: 'active', sortable: false },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();

interface FormFields {
  id: number,
  name: string;
  name_en: string;
  college_type: string;
  active: boolean;
  logo: any;
  logo_en: any;
  banner: any;
}

const ACCEPTED_IMAGE_TYPES = ["image/jpeg", "image/jpg", "image/png"];

const validationSchema = toTypedSchema(
  zod.object({
    name: zod.string({ required_error: "ادخل اسم الكلية باللغة العربية" }).min(1, { message: "ادخل اسم الكلية باللغة العربية" }),
    name_en: zod.string({ required_error: "ادخل اسم الكلية باللغة الانجليزية" }).trim().min(1, { message: 'ادخل اسم الكلية باللغة الانجليزية' }),
    college_type: zod.string({ required_error: "اختر النوع" }).trim().min(1, { message: 'اختر النوع' }),
    logo: zod.any().refine((files) => files?.length == 1, "اختر الصورة")
      .refine((files) => ACCEPTED_IMAGE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .jpg, .jpeg and .png")
      .refine((files) => files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`).nullish(),
    logo_en: zod.any().refine((files) => files?.length == 1, "اختر الصورة")
      .refine((files) => ACCEPTED_IMAGE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .jpg, .jpeg and .png")
      .refine((files) => files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`).nullish(),
    banner: zod.any().refine((files) => files?.length == 1, "اختر الصورة")
      .refine((files) => ACCEPTED_IMAGE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .jpg, .jpeg and .png")
      .refine((files) => files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`).nullish(),
  })
);

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
const modal = ref(false);
const photo = ref();
const logoInput = ref();
const logoEnInput = ref();
const bannerInput = ref();
const logoPath = ref();
const logoEnPath = ref();
const bannerPath = ref();
const { value: name, } = useField('name');
const { value: name_en } = useField('name_en');
const { value: college_type } = useField('college_type');
const { value: active } = useField('active');
const { value: logo } = useField('logo');
const { value: logo_en } = useField('logo_en');
const { value: banner } = useField('banner');

const college_types = [
  { name: 'كلية', val: 'college' },
  { name: 'عمادة', val: 'deanship' },
  { name: 'مركز', val: 'center' },
  { name: 'أمانة', val: 'secretariat' }
];

let ErorrMsg = [{ 'field': 'name', 'message': 'اسم الكلية موجود مسبقا' }]

const save = handleSubmit(async (values) => {
  let temp: Array<any> = [];
  table.value.SubmitLoading = true;

  const formData = new FormData();
  formData.append("name", values.name)
  formData.append("name_en", values.name_en)
  formData.append("college_type", values.college_type.toString())
  formData.append("active", Number(active.value))

  if (logoInput.value.files[0] != undefined) {
    formData.append("logo", logoInput.value.files[0])
  }

  if (logoEnInput.value.files[0] != undefined) {
    formData.append("logo_en", logoEnInput.value.files[0])
  }

  if (bannerInput.value.files[0] != undefined) {
    formData.append("banner", bannerInput.value.files[0])
  }

  // values = Object.assign(values, { active: active.value });
  if (id.value > -1) {
    formData.append('_method', 'put');
    await axios.post(url + "/" + id.value, formData, { headers: { 'content-type': 'multipart/form-data' } }).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable(result.data.status, temp);
    })
  } else if (id.value === -1) {
    await axios.post(url, formData, { headers: { 'content-type': 'multipart/form-data' } }).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable(result.data.status, temp);
    });
  }
});

function editItem(item: any) {
  item = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.id;
    axios.get(url + '/' + item.id).then(response => {
      item = response.data.result;
      if (item != null) {
        logoPath.value = item.logo;
        logoEnPath.value = item.logo_en;
        bannerPath.value = item.banner;
        setValues({
          name: item.name,
          name_en: item.name_en,
          college_type: college_types.find(i => i.name === item.college_type)?.val,
          active: item.active,
        })
      }
      table.value.disableEditButton = false
      table.value.dialog = true;
    });
  }
}

function showModal(photoPath: any) {
  photo.value = BASE_URL + photoPath
  modal.value = true
}

function onSelectLogoImage() {
  logo.value = logoInput.value.files
}

function onSelectLogoENImage() {
  logo_en.value = logoEnInput.value.files
}

function onSelectBannerImage() {
  banner.value = bannerInput.value.files
}

function close() {
  nextTick(() => {
    id.value = -1;
    logoPath.value = undefined;
    logoEnPath.value = undefined;
    bannerPath.value = undefined;
    resetForm();
  });
  table.value.dialog = false
}
</script>
