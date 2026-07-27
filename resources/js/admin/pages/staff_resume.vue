<template>
  <v-container>
    <v-card>
      <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="السيرة الذاتية"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close" transition="dialog-bottom-transition">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" md="4" class="pt-0">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="college_id"  @update:modelValue="getDepartments($event)"
                label="اسماء الكليات" :error-messages="errors.college_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="4" class="pt-0">
              <v-select id="department_id" name="department_id" :disabled="deartment_disabled" :items="departments" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="department_id"  @update:modelValue="getUsers($event)"
                label="اسماء الاقسام" :error-messages="errors.department_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="4" class="pt-0">
              <v-select id="user_id" name="user_id" :disabled="user_disabled" :items="users" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="user_id"
                label="اعضاء هيئة التدريس" :error-messages="errors.user_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" :md="filePath != undefined ? 5 : 6" class="pt-0">
              <v-file-input type="file" id="file" name="file" ref="fileInput" show-size @input="onSelectFile" chips accept='.dox,.docx,.pdf'
                label="اختر ملف السيرة باللغة العربية" variant="underlined" :error-messages="errors.file"></v-file-input>
            </v-col>
            <v-col v-if="filePath != undefined" cols="12" md="1" class="pt-2">
              <a :href="BASE_URL+ filePath" class="text-subtitle-2">الملف الحالي<v-icon icon="mdi-image"></v-icon></a>
            </v-col>
            <v-col cols="12" :md="filePath != undefined ? 5 : 6" class="pt-0">
              <v-file-input type="file" id="file_en" name="file_en" ref="fileEnInput" show-size @input="onSelectEnFile" chips accept='.dox,.docx,.pdf'
                label="اختر ملف السيرة باللغة الانجليزية" variant="underlined" :error-messages="errors.file_en"></v-file-input>
            </v-col>
            <v-col v-if="fileEnPath != undefined" cols="12" md="1" class="pt-2">
              <a :href="BASE_URL+ fileEnPath" class="text-subtitle-2">الملف الحالي<v-icon icon="mdi-image"></v-icon></a>
            </v-col>
          </v-row>
        </Form>
      </Table>
    </v-card>
  </v-container>
</template>
<script lang="ts" setup>
import { defineAsyncComponent, toRaw } from 'vue'
const Table = defineAsyncComponent(() => import('../components/Table.vue'))
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import { z as zod} from 'zod';
import { nextTick, onBeforeMount, ref } from 'vue';
import axios from 'axios';

const url = route('staff_academic.index','staff_resume')
const headers = [
  { title: 'اسم عضو هيئة التدريس', key: 'user.name', align: 'center' },
  { title: 'السيرة الذاتية باللغة العربية', key: 'file', align: 'center', sortable: false },
  { title: 'السيرة الذاتية باللغة الانجليزية', key: 'file_en', align: 'center', sortable: false },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
interface FormFields {
  id: number,
  college_id: number,
  department_id: number,
  user_id: number,
  file: any;
  file_en: any;
}
const BASE_URL = import.meta.env.VITE_BASE_URL + '/';
const ACCEPTED_FILE_TYPES = ["application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/pdf"];

const form_items_types =zod.object({
    college_id: zod.number({ required_error: "اختر اسم الكلية" }).positive({ message: "اختر اسم الكلية" }),
    department_id: zod.number({ required_error: "اختر اسم القسم" }).positive({ message: 'اختر اسم القسم' }).nullable(),
    user_id: zod.number({ required_error: "اختر اسم عضو هيئة التدريس" }).positive({ message: "اختر اسم عضو هيئة التدريس" }),
    file_en: zod.any().refine((files) => files?.length == 1, "اختر الملف المرفق")
          .refine((files) => ACCEPTED_FILE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .doc, .docx, .pdf")
          .refine((files) => files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`).nullish(),
  });

const validationSchema = toTypedSchema(zod.union([
  form_items_types.merge(zod.object({
    file: zod.any().refine((files) => files?.length == 1, "اختر الملف المرفق")
          .refine((files) => ACCEPTED_FILE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .doc, .docx, .pdf")
          .refine((files) => files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`),
    id: zod.number().negative()
  })),
  form_items_types.merge(zod.object({
    file: zod.any().refine((files) => files?.length == 1, "اختر الملف المرفق")
          .refine((files) => ACCEPTED_FILE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .doc, .docx, .pdf")
          .refine((files) => files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`).nullish(),
    id: zod.number().negative().nullish()
  })),
]));


const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
const colleges = ref();
const departments = ref();
const users = ref();

const deartment_disabled = ref(true);
const user_disabled = ref(true);
const fileInput = ref();
const fileEnInput = ref();
const filePath = ref();
const fileEnPath = ref();

const { value: college_id } = useField('college_id');
const { value: department_id } = useField('department_id');
const { value: user_id } = useField('user_id');
const { value: file } = useField('file');
const { value: file_en } = useField('file_en');

const save = handleSubmit(async(values) => {

  let ErorrMsg = [
    {'field':'file','message':'يتم دعم  فقط .doc, .docx, .pdf'},
    {'field':'file_en','message':'يتم دعم  فقط .doc, .docx, .pdf'},
    {'field':'user_id','message':'بيانات السيرة الذاتية موجودة مسبقاُ'},
  ]

  const formData = new FormData();
  formData.append("user_id", values.user_id.toString())

  if(fileInput.value.files[0] != undefined){
    formData.append("file", fileInput.value.files[0])
  }

  if(fileEnInput.value.files[0] != undefined){
    formData.append("file_en", fileEnInput.value.files[0])
  }

  let temp: Array<any> = [];
  table.value.SubmitLoading = true;
  if(id.value < 0){
    await axios.post(url,formData,{headers:{'content-type':'multipart/form-data' }}).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable(result.data.status,temp);
    })
  }
  else{
    formData.append('_method', 'put');
    await axios.post(url + "/" + id.value, formData,{headers:{'content-type':'multipart/form-data' }}).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable(result.data.status,temp);
    });
  }
  table.value.SubmitLoading = false;
  // close();
});

function editItem(item: any) {
  item = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.id;
    axios.get(url + '/' + item.id).then(response => {
      item = response.data.result;
      filePath.value = undefined;
      fileEnPath.value = undefined;
      if(item.file != null && item.file.length > 0){
        filePath.value = item.file;
      }
      if(item.file_en != null && item.file_en.length > 0){
        fileEnPath.value = item.file_en;
      }
      deartment_disabled.value = user_disabled.value = false
      departments.value = [{ 'id': item.user.staff_latest.department_id, 'name': item.user.staff_latest.department.name }];
      users.value = [{ 'id': item.user.staff_latest.id, 'name': item.user.name }];
      setValues({
        college_id: item.user.staff_latest.department.college.id,
        department_id: item.user.staff_latest.department.id,
        user_id: item.user.staff_latest.id,
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
    department_id.value = -1;
    users.value.unshift({ 'id': -1, 'name': 'اختر عضو هيئة التدريس' });
    user_id.value = -1
    deartment_disabled.value = user_disabled.value = true
    filePath.value = undefined
    fileEnPath.value = undefined
    resetForm();
  });
  table.value.disableEditButton = false;
  table.value.dialog = false;
}

function onSelectFile() {
  file.value = fileInput.value.files
}

function onSelectEnFile() {
  file_en.value = fileEnInput.value.files
}

async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
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

onBeforeMount(() => {
  getColleges();
});
</script>
