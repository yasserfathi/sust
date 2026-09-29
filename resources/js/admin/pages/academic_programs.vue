<template>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
      <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="البرامج الأكاديمية"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close" :fullscreen="fullscreen" :maxWidth="850"
        transition="dialog-bottom-transition">
        <template v-slot:item.program_type="{ item }">
          {{programTypes.find((t: any) => t.value == item.program_type)?.text || item.program_type}}
        </template>
        <template v-slot:item.college="{ item }">
          {{ item.department?.college?.name || '' }}
        </template>
        <template v-slot:item.NOOFYEARSNO="{ item }">
          {{ item.NOOFYEARSNO ? item.NOOFYEARSNO : '-' }}
        </template>
        <template v-slot:item.NOOFSEM="{ item }">
          {{ item.NOOFSEM ? item.NOOFSEM : '-' }}
        </template>
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row density="comfortable">
            <!-- Row 1: Program Names -->
            <v-col cols="12" md="6">
              <v-text-field id="program_name" name="program_name" label="اسم البرنامج (باللغة العربية)" v-model="program_name"
                variant="outlined" class="text-caption" prepend-inner-icon="mdi-school"
                :error-messages="errors.program_name" density="comfortable"></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field id="program_name_en" name="program_name_en" label="اسم البرنامج (باللغة الإنجليزية)"
                v-model="program_name_en" variant="outlined" class="text-caption" prepend-inner-icon="mdi-school-outline"
                :error-messages="errors.program_name_en" density="comfortable" dir="ltr"></v-text-field>
            </v-col>

            <!-- Row 2: College -> School (Optional) -> Department -->
            <v-col cols="12" :md="schools && schools.length > 0 ? 4 : 6" v-if="colleges && colleges.length > 1">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                item-value="id" prepend-inner-icon="mdi-bank" v-model="college_id" @update:modelValue="onCollegeChange($event)"
                label="الكلية" :error-messages="errors.college_id" variant="outlined"></v-select>
            </v-col>
            <v-col cols="12" md="4" v-if="schools && schools.length > 0">
              <v-select id="school_id" name="school_id" :items="schools" item-title="name" density="comfortable"
                item-value="id" prepend-inner-icon="mdi-domain" v-model="school_id" @update:modelValue="onSchoolChange($event)"
                label="المدرسة (اختياري)" :error-messages="errors.school_id" variant="outlined" clearable></v-select>
            </v-col>
            <v-col cols="12" :md="schools && schools.length > 0 ? 4 : 6">
              <v-select id="department_id" name="department_id" :items="departments" item-title="name"
                density="comfortable" item-value="id" prepend-inner-icon="mdi-office-building" v-model="department_id"
                label="القسم" :error-messages="errors.department_id" variant="outlined" clearable></v-select>
            </v-col>

            <!-- Row 3: Program Type + Years + Semesters -->
            <v-col cols="12" md="4">
              <v-select id="program_type" name="program_type" :items="programTypes" item-title="text"
                density="comfortable" item-value="value" prepend-inner-icon="mdi-shape" v-model="program_type"
                label="نوع البرنامج" :error-messages="errors.program_type" variant="outlined"></v-select>
            </v-col>
            <v-col cols="12" md="4">
              <v-text-field id="NOOFYEARSNO" name="NOOFYEARSNO" label="عدد السنوات" v-model="NOOFYEARSNO" type="number"
                variant="outlined" class="text-caption" prepend-inner-icon="mdi-calendar-clock"
                :error-messages="errors.NOOFYEARSNO" density="comfortable"></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
              <v-text-field id="NOOFSEM" name="NOOFSEM" label="عدد الفصول الدراسية" v-model="NOOFSEM" type="number"
                variant="outlined" class="text-caption" prepend-inner-icon="mdi-counter"
                :error-messages="errors.NOOFSEM" density="comfortable"></v-text-field>
            </v-col>

            <!-- Row 4: File & Status -->
            <v-col cols="12" :md="filePath != '' ? 8 : 12">
              <v-file-input type="file" id="file" name="file" v-model="file" show-size chips label="ملف الخطة الدراسية (PDF)" accept='.pdf' variant="outlined" prepend-inner-icon="mdi-paperclip" prepend-icon="" :error-messages="errors.file" density="comfortable"></v-file-input>
            </v-col>
            <v-col v-if="filePath != ''" cols="12" md="4" class="d-flex align-center pb-6">
              <a :href="BASE_URL + 'storage/' + filePath" target="_blank" class="text-subtitle-2 text-primary font-weight-bold text-decoration-none">
                <v-icon icon="mdi-file-document" class="me-1"></v-icon>عرض الملف المرفق
              </a>
            </v-col>

            <v-col cols="12" class="pt-0">
              <v-switch :label="active ? 'مفعل' : 'غير مفعل'" id="active" name="active" v-model="active" hide-details
                ripple color="success"></v-switch>
            </v-col>
          </v-row>
        </Form>
      </Table>
    </v-card>
  </v-container>
</template>
<script lang="ts" setup>
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import { z as zod } from 'zod';
import axios from 'axios';

const url = route('academic_programs.index')
const headers = [
  { title: 'اسم البرنامج', key: 'program_name' },
  { title: 'الكلية', key: 'college' },
  { title: 'نوع البرنامج', key: 'program_type' },
  { title: 'عدد السنوات', key: 'NOOFYEARSNO' },
  { title: 'عدد الفصول', key: 'NOOFSEM' },
  { title: 'مفعل', key: 'active', sortable: false },
  { title: '', key: 'actions', value: 'id', sortable: false },
];

const programTypes = [
  { text: 'بكالوريوس', value: 1 },
  { text: 'ماجستير', value: 2 },
  { text: 'دكتوراة', value: 3 },
  { text: 'دبلوم', value: 4 },
];

const table = ref();
interface FormFields {
  id: number,
  college_id: number,
  school_id?: number | null,
  department_id: number | null,
  program_name: string;
  program_name_en: string;
  program_type: number;
  NOOFYEARSNO?: number | null;
  NOOFSEM?: number | null;
  file: any;
  active: boolean;
}

const BASE_URL = window.location.origin + '/';

const validationSchema = toTypedSchema(
  zod.object({
    college_id: zod.coerce.number({ required_error: "اختر الكلية" }),
    school_id: zod.coerce.number().nullish(),
    program_name: zod.string({ required_error: "ادخل اسم البرنامج (باللغة العربية)" }).min(1, { message: "ادخل اسم البرنامج (باللغة العربية)" }),
    program_name_en: zod.string({ required_error: "ادخل اسم البرنامج (باللغة الإنجليزية)" }).min(1, { message: "ادخل اسم البرنامج (باللغة الإنجليزية)" }),
    department_id: zod.coerce.number({ required_error: "اختر القسم" }),
    program_type: zod.coerce.number({ required_error: "اختر نوع البرنامج" }),
    NOOFYEARSNO: zod.coerce.number().nullish(),
    NOOFSEM: zod.coerce.number().nullish(),
  })
);

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
let colleges = ref<Array<any>>([]);
let schools = ref<Array<any>>([]);
let departments = ref<Array<any>>([]);

const fullscreen = ref(false);
const filePath = ref();

const { value: program_name } = useField('program_name');
const { value: program_name_en } = useField('program_name_en');
const { value: college_id } = useField('college_id');
const { value: school_id } = useField('school_id');
const { value: department_id } = useField('department_id');
const { value: program_type } = useField('program_type');
const { value: NOOFYEARSNO } = useField('NOOFYEARSNO');
const { value: NOOFSEM } = useField('NOOFSEM');
const { value: file } = useField('file');
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
  if (values.NOOFYEARSNO !== undefined && values.NOOFYEARSNO !== null && String(values.NOOFYEARSNO) !== '') {
    formData.append("NOOFYEARSNO", values.NOOFYEARSNO.toString())
  }
  if (values.NOOFSEM !== undefined && values.NOOFSEM !== null && String(values.NOOFSEM) !== '') {
    formData.append("NOOFSEM", values.NOOFSEM.toString())
  }
  formData.append("active", active.value ? '1' : '0')

  if (file.value) {
    const f = Array.isArray(file.value) ? file.value[0] : file.value;
    if (f) formData.append("file", f);
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
    axios.get(url + '/' + item.id).then(async response => {
      let data = response.data.result;
      id.value = data.id;
      filePath.value = data.file;

      const collegeId = data.department?.college_id || data.department?.college?.id;
      const schoolId = data.department?.school_id || data.department?.school?.id;

      if (collegeId) {
        await getSchools(collegeId);
        await getDepartments(collegeId, schoolId);
      }

      setValues({
        college_id: collegeId,
        school_id: schoolId || null,
        department_id: data.department_id,
        program_name: data.program_name,
        program_name_en: data.program_name_en,
        program_type: Number(data.program_type),
        NOOFYEARSNO: data.NOOFYEARSNO ?? null,
        NOOFSEM: data.NOOFSEM ?? null,
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
    filePath.value = '';
    active.value = false;
    school_id.value = null;
    schools.value = [];
  });
  table.value.disableEditButton = false;
  table.value.dialog = false;
}

async function getColleges() {
  try {
    await axios.get(route('colleges.list')).then(response => {
      colleges.value = response.data.colleges || [];
    });
  } catch (e) { console.error(e); }
}

async function getSchools(collegeId: number) {
  if (!collegeId) {
    schools.value = [];
    return;
  }
  try {
    await axios.post(route('schools.list'), { college_id: collegeId }).then(response => {
      schools.value = response.data.schools || [];
    });
  } catch (e) { console.error(e); }
}

async function getDepartments(collegeId: any, schoolId?: any) {
  if (!collegeId) {
    departments.value = [];
    return;
  }
  try {
    await axios.post(route('departments.list'), { college_id: collegeId, school_id: schoolId }).then(response => {
      departments.value = response.data.departments || [];
    });
  } catch (e) { console.error(e); }
}

async function onCollegeChange(collegeId: any) {
  school_id.value = null;
  department_id.value = null;
  if (collegeId) {
    await getSchools(collegeId);
    await getDepartments(collegeId, null);
  } else {
    schools.value = [];
    departments.value = [];
  }
}

async function onSchoolChange(schoolId: any) {
  department_id.value = null;
  if (college_id.value) {
    await getDepartments(college_id.value, schoolId);
  }
}

onBeforeMount(() => {
  getColleges();
});
</script>
