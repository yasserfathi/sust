<template>
  <v-container>
    <v-card>
      <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="البرامج الأكاديمية"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close" :fullscreen="fullscreen"
        transition="dialog-bottom-transition">
        <template v-slot:item.program_type="{ item }">
          {{ programTypes.find((t: any) => t.value == item.program_type)?.text || item.program_type }}
        </template>
        <template v-slot:item.college="{ item }">
          {{ item.department?.college?.name || '' }}
        </template>
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" md="6" class="pt-0">
              <v-text-field id="program_name" name="program_name" label="اسم البرنامج (عربي)" v-model="program_name"
                variant="underlined" class="text-caption" prepend-icon="mdi-school"
                :error-messages="errors.program_name"></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-text-field id="program_name_en" name="program_name_en" label="اسم البرنامج (إنجليزي)"
                v-model="program_name_en" variant="underlined" class="text-caption" prepend-icon="mdi-school"
                :error-messages="errors.program_name_en"></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="college_id" @update:modelValue="getDepartments($event)"
                label="الكلية" :error-messages="errors.college_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-select id="department_id" name="department_id" :items="departments" item-title="name"
                density="comfortable" item-value="id" prepend-icon="mdi-office-building" v-model="department_id"
                label="القسم" :error-messages="errors.department_id" variant="underlined" clearable></v-select>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-select id="program_type" name="program_type" :items="programTypes" item-title="text"
                density="comfortable" item-value="value" prepend-icon="mdi-shape" v-model="program_type"
                label="نوع البرنامج" :error-messages="errors.program_type" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-text-field id="credit_hours" name="credit_hours" label="الساعات المعتمدة" v-model="credit_hours"
                variant="underlined" class="text-caption" prepend-icon="mdi-clock"
                :error-messages="errors.credit_hours"></v-text-field>
            </v-col>

            <v-col cols="12" :md="filePath != undefined ? 5 : 6" class="pt-0">
              <v-file-input type="file" id="file" name="file" ref="fileInput" show-size @change="onSelectFile" chips
                label="ملف الخطة الدراسية (PDF)" accept='.pdf' variant="underlined"
                :error-messages="errors.file"></v-file-input>
            </v-col>
            <v-col v-if="filePath != undefined" cols="12" md="1" class="pt-7">
              <a :href="BASE_URL + 'storage/' + filePath" target="_blank" class="text-subtitle-2">الملف<v-icon
                  icon="mdi-file-document"></v-icon></a>
            </v-col>

            <v-col cols="12" class="pt-0 mt-0">
              <v-switch :label="active ? 'مفعل' : 'غير مفعل'" id="active" name="active" v-model="active" hide-details
                ripple color="#198754"></v-switch>
            </v-col>
          </v-row>
        </Form>
      </Table>
    </v-card>
  </v-container>
</template>
<script lang="ts" setup>
import { defineAsyncComponent, toRaw, watch } from 'vue'
const Table = defineAsyncComponent(() => import('../components/Table.vue'))
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import { z as zod } from 'zod';
import { nextTick, onBeforeMount, ref } from 'vue';
import axios from 'axios';

const url = route('academic_programs.index')// Assuming default route name/path
const headers = [
  { title: 'اسم البرنامج', key: 'program_name' },
  { title: 'الكلية', key: 'college' },
  { title: 'نوع البرنامج', key: 'program_type' },
  { title: 'مفعل', key: 'active', sortable: false },
  { title: '', key: 'actions', value: 'id', sortable: false },
];

const programTypes = [
  { text: 'بكالوريوس', value: 1 },
  { text: 'ماجستير', value: 2 },
  { text: 'دكتوراه', value: 3 },
  { text: 'دبلوم', value: 4 },
];

const table = ref();
interface FormFields {
  id: number,
  college_id: number,
  department_id: number | null,
  program_name: string;
  program_name_en: string;
  program_type: number;
  credit_hours?: string | null;
  file: any;
  active: boolean;
}

const BASE_URL = import.meta.env.VITE_BASE_URL + '/';
const MAX_FILE_SIZE = 5000000;
const ACCEPTED_FILE_TYPES = ["application/pdf"];

const validationSchema = toTypedSchema(
  zod.object({
    college_id: zod.coerce.number({ required_error: "اختر الكلية" }),
    program_name: zod.string({ required_error: "ادخل اسم البرنامج (عربي)" }).min(1, { message: "ادخل اسم البرنامج (عربي)" }),
    program_name_en: zod.string({ required_error: "ادخل اسم البرنامج (إنجليزي)" }).min(1, { message: "ادخل اسم البرنامج (إنجليزي)" }),
    department_id: zod.coerce.number({ required_error: "اختر القسم" }),
    program_type: zod.coerce.number({ required_error: "اختر نوع البرنامج" }),
    credit_hours: zod.string().nullish(),
  })
);

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
let colleges = ref<Array<any>>([]);
let departments = ref<Array<any>>([]);

const fullscreen = ref(true);
const fileInput = ref();
const filePath = ref();

const { value: program_name } = useField('program_name');
const { value: program_name_en } = useField('program_name_en');
const { value: college_id } = useField('college_id');
const { value: department_id } = useField('department_id');
const { value: program_type } = useField('program_type');
const { value: credit_hours } = useField('credit_hours');
const { value: active } = useField('active');


let ErorrMsg = [
  { field: 'program_name', message: 'البرنامج موجود مسبقا' },
  { field: 'program_name_en', message: 'البرنامج موجود مسبقا باللغة الانجليزية' },
  { field: 'file', message: 'صيغة الملف غير مدعومة' }
];

const save = handleSubmit((values) => {
  const formData = new FormData();
  formData.append("program_name", values.program_name)
  formData.append("program_name_en", values.program_name_en)

  if (values.department_id) formData.append("department_id", values.department_id.toString())
  formData.append("program_type", values.program_type.toString())
  if (values.credit_hours) formData.append("credit_hours", values.credit_hours.toString())
  formData.append("active", active.value ? '1' : '0')

  if (fileInput.value && fileInput.value.files[0] != undefined) {
    formData.append("file", fileInput.value.files[0])
  }

  let temp: Array<any> = [];

  if (id.value > -1) {
    formData.append('_method', 'put');
    axios.post(url + "/" + id.value, formData, { headers: { 'content-type': 'multipart/form-data' }, validateStatus: (status) => status < 500 }).then(result => {
      if (result.data.status == 409 && result.data.message) {
        Object.entries(result.data.message).forEach((item: any) => {
          let err = ErorrMsg.find(a => item[1][0] === a.field + '.duplicate' || item[1][0] === a.field);
          if (err) temp.push(toRaw(err));
        });
      }
      table.value.PopulateTable(result.data.status, temp);
    })
  } else {
    axios.post(url, formData, { headers: { 'content-type': 'multipart/form-data' }, validateStatus: (status) => status < 500 }).then(result => {
      if (result.data.status == 409 && result.data.message) {
        Object.entries(result.data.message).forEach((item: any) => {
          let err = ErorrMsg.find(a => item[1][0] === a.field + '.duplicate' || item[1][0] === a.field);
          if (err) temp.push(toRaw(err));
        });
      }
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
      let data = response.data.result;
      id.value = data.id; // Ensure ID is set
      filePath.value = data.file;

      // Load departments
      if (data.department?.college?.id) getDepartments(data.department.college.id);

      setValues({
        college_id: data.department?.college?.id,
        department_id: data.department_id,
        program_name: data.program_name,
        program_name_en: data.program_name_en,
        program_type: Number(data.program_type),
        credit_hours: data.credit_hours === 'undefined' ? '' : data.credit_hours,
        active: data.active == 1 || data.active == true,
      });

      table.value.disableEditButton = false
      table.value.dialog = true
    });
  }
}

function close() {
  nextTick(() => {
    id.value = -1
    resetForm();
    filePath.value = undefined;
    active.value = false;
  });
  table.value.disableEditButton = false;
  table.value.dialog = false;
}

function onSelectFile() {
  // logic handled in save
}

async function getColleges() {
  // Use explicit route or path if route() helper not available in this context setup?
  // Assuming route() is globally available mixin or plugin
  try {
    await axios.get(route('colleges.list')).then(response => {
      colleges.value = response.data.colleges || [];
    });
  } catch (e) { console.error(e); }
}

async function getDepartments(college_id: any) {
  if (!college_id) {
    departments.value = [];
    return;
  }
  try {
    await axios.post(route('departments.list'), { 'college_id': college_id }).then(response => {
      departments.value = response.data.departments || [];
    });
  } catch (e) { console.error(e); }
}

onBeforeMount(() => {
  getColleges();
});
</script>
