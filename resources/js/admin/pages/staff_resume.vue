<template>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
      <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="السيرة الذاتية"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close"
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
          

            <v-col cols="12" :md="filePath != '' ? 5 : 6" class="pt-0">
              <v-file-input type="file" id="file" name="file" v-model="file" show-size chips accept='.dox,.docx,.pdf'
                label="اختر ملف السيرة باللغة العربية" variant="outlined" :error-messages="errors.file"
                density="comfortable"></v-file-input>
            </v-col>
            <v-col v-if="filePath != ''" cols="12" md="1" class="pt-2">
              <a :href="BASE_URL + filePath" class="text-subtitle-2">الملف الحالي<v-icon icon="mdi-image"></v-icon></a>
            </v-col>
            <v-col cols="12" :md="filePath != '' ? 5 : 6" class="pt-0">
              <v-file-input type="file" id="file_en" name="file_en" v-model="file_en" show-size chips
                accept='.dox,.docx,.pdf' label="اختر ملف السيرة باللغة الانجليزية" variant="outlined"
                :error-messages="errors.file_en" density="comfortable"></v-file-input>
            </v-col>
            <v-col v-if="fileEnPath != ''" cols="12" md="1" class="pt-2">
              <a :href="BASE_URL + fileEnPath" class="text-subtitle-2">الملف الحالي<v-icon icon="mdi-image"></v-icon></a>
            </v-col>
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

const url = route('staff_academic.index', 'staff_resume')
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
const BASE_URL = window.location.origin + '/';
const ACCEPTED_FILE_TYPES = ["application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/pdf"];

const form_items_types = zod.object({
  college_id: authStore?.user?.role !== 1 ? zod.any().nullish() : zod.number({ required_error: "اختر اسم الكلية" }).positive({ message: "اختر اسم الكلية" }),
  department_id: authStore?.user?.role !== 1 ? zod.any().nullish() : zod.number({ required_error: "اختر اسم القسم" }).positive({ message: 'اختر اسم القسم' }).nullable(),
  user_id: authStore?.user?.role !== 1 ? zod.any().nullish() : zod.number({ required_error: "اختر اسم عضو هيئة التدريس" }).positive({ message: "اختر اسم عضو هيئة التدريس" }),
  file_en: zod.any().refine((files) => (!files || files.length === 0) ? id.value > -1 : true, "اختر الملف المرفق")
    .refine((files) => (!files || files.length === 0) ? true : ACCEPTED_FILE_TYPES.includes((Array.isArray(files) ? files[0] : files)?.type), "يتم دعم  فقط .doc, .docx, .pdf")
    .refine((files) => {
            if (!files || files.length === 0) return true;
            const f = Array.isArray(files) ? files[0] : files;
            return f && (f.size || 0) <= 5 * 1024 * 1024;
          }, `الحد الأقصى لحجم الملف هو 5 ميجابايت`).nullish(),
});

const validationSchema = toTypedSchema(zod.union([
  form_items_types.merge(zod.object({
    file: zod.any().refine((files) => (!files || files.length === 0) ? id.value > -1 : true, "اختر الملف المرفق")
      .refine((files) => (!files || files.length === 0) ? true : ACCEPTED_FILE_TYPES.includes((Array.isArray(files) ? files[0] : files)?.type), "يتم دعم  فقط .doc, .docx, .pdf")
      .refine((files) => {
            if (!files || files.length === 0) return true;
            const f = Array.isArray(files) ? files[0] : files;
            return f && (f.size || 0) <= 5 * 1024 * 1024;
          }, `الحد الأقصى لحجم الملف هو 5 ميجابايت`),
    id: zod.number().negative()
  })),
  form_items_types.merge(zod.object({
    file: zod.any().refine((files) => (!files || files.length === 0) ? id.value > -1 : true, "اختر الملف المرفق")
      .refine((files) => (!files || files.length === 0) ? true : ACCEPTED_FILE_TYPES.includes((Array.isArray(files) ? files[0] : files)?.type), "يتم دعم  فقط .doc, .docx, .pdf")
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
const colleges = shallowRef();
const departments = shallowRef([]);
const users = shallowRef([]);

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

const save = handleSubmit(async (values) => {

  let ErorrMsg = [
    { 'field': 'file', 'message': 'يتم دعم  فقط .doc, .docx, .pdf' },
    { 'field': 'file_en', 'message': 'يتم دعم  فقط .doc, .docx, .pdf' },
    { 'field': 'user_id', 'message': 'بيانات السيرة الذاتية موجودة مسبقاُ' },
  ]

  const formData = new FormData();
  formData.append("user_id", values.user_id ? values.user_id.toString() : '')

  if (file.value) {
    const f = Array.isArray(file.value) ? file.value[0] : file.value;
    if (f) formData.append("file", f);
  }
  if (file_en.value) {
    const f_file_en = Array.isArray(file_en.value) ? file_en.value[0] : file_en.value;
    if (f_file_en) formData.append("file_en", f_file_en);
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
    id.value = item.id;
    axios.get(url + '/' + item.id).then(async response => {
      const res = response.data.result;
      if (!res) {
        table.value.disableEditButton = false;
        return;
      }
      item_val.value = res.item_val || '';
      filePath.value = res.file || '';
      const localCollegeId = res.user?.staff_latest?.department?.college?.id || '';
      const deptId = res.user?.staff_latest?.department_id || '';
      const userId = res.user?.id || '';
      
      if (localCollegeId) await getDepartments(localCollegeId);
      if (deptId) await getUsers(deptId);

      deartment_disabled.value = false;
      user_disabled.value = false;
setValues({
        college_id: res.user?.staff_latest?.department?.college?.id || '',
        department_id: deptId,
        user_id: userId,
        lang: res.lang,
      });
      table.value.disableEditButton = false;
      table.value.dialog = true;
    }).catch(err => {
      table.value.disableEditButton = false;
      console.error(err);
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
    filePath.value = ''
    fileEnPath.value = ''
    resetForm();
    if (colleges.value && colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
  });
  table.value.disableEditButton = false;
  table.value.dialog = false;
}





async function getColleges() {
  await axios.get(route("colleges.list")).then(async response => {
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

onBeforeMount(() => {
  getColleges();
});
</script>
