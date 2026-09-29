<template>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
      <Table :id="id" ref="table" :url="url" :exportUrl="'/admin/departments/print'" :headers="headers"
        toolbar_title="اسماء الاقسام" @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close"
        @resetFormFields="resetFormFields" transition="dialog-bottom-transition">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" md="6" v-show="colleges && colleges.length > 1">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" item-value="id"
                v-model="college_id" label="اسم الكلية" :error-messages="errors.college_id"></v-select>
            </v-col>

            <v-col cols="12" md="6" v-show="colleges && colleges.length > 1">
              <v-select id="school_id" name="school_id" :items="schools" item-title="name" item-value="id"
                v-model="school_id" label="اسم المدرسة (اختياري)" :error-messages="errors.school_id" clearable></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field autofocus id="name" name="name" label="اسم القسم باللغة العربية" v-model="name"
                :error-messages="errors.name"></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field id="name_en" name="name_en" label="اسم القسم باللغة الانجليزية" v-model="name_en"
                :error-messages="errors.name_en" dir="ltr"></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-combobox v-model="chipData_ar" chips multiple closable-chips label="الكلمات المفتاحية باللغة العربية"
                :error-messages="errors.keyWord_ar" prepend-inner-icon="mdi-bank"></v-combobox>
            </v-col>

            <v-col cols="12" md="6">
              <v-combobox v-model="chipData" chips multiple closable-chips label="الكلمات المفتاحية باللغة الانجليزية"
                :error-messages="errors.keyWord" prepend-inner-icon="mdi-bank" dir="ltr"></v-combobox>
            </v-col>

            <v-col cols="12">
              <p class="text-subtitle-2 font-weight-medium mb-3 text-grey-darken-1"><v-icon size="small" class="me-1">mdi-text-box-outline</v-icon> عن القسم باللغة العربية</p>
              <Editor v-if="renderEditor" v-model="description_ar" />
              <span class="text-error text-caption mt-1 d-block">{{ errors.description_ar }}</span>
            </v-col>

            <v-col cols="12">
              <p class="text-subtitle-2 font-weight-medium mb-3 text-grey-darken-1"><v-icon size="small" class="me-1">mdi-text-box-outline</v-icon> عن القسم باللغة الانجليزية</p>
              <Editor v-if="renderEditor" v-model="description" dir="ltr" />
              <span class="text-error text-caption mt-1 d-block">{{ errors.description }}</span>
            </v-col>

            <v-col cols="12">
              <v-switch label="تنشيط القسم" id="active" name="active" v-model="active" color="success"
                density="compact" hide-details></v-switch>
            </v-col>
          </v-row>
          <div id="buttons"></div>
        </Form>
      </Table>
    </v-card>
  </v-container>
</template>

<script lang="ts" setup>
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import axios from 'axios';
import { isMatch } from 'lodash';

const url = route('departments.index')
const headers = [
  { title: 'الرقم', key: 'id' },
  { title: 'اسم الكلية', key: 'college.name' },
  { title: 'اسم القسم بالعربية', key: 'name' },
  { title: 'اسم القسم بالانجليزية', key: 'name_en' },
  { title: 'تنشيط', key: 'active', sortable: false },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
const renderEditor = ref(false);

interface FormFields {
  id: number,
  college_id: number;
  school_id?: number | null;
  name: string;
  name_en: string;
  keyWord: string;
  keyWord_ar: string;
  description: string;
  description_ar: string;
  active: boolean;
}

const validationSchema = toTypedSchema(
  zod.object({
    college_id: zod.number({ required_error: "اختر اسم الكلية" }),
    school_id: zod.number().nullable().optional(),
    name: zod.string({ required_error: "ادخل اسم القسم باللغة العربية" }).min(1, { message: "ادخل اسم القسم باللغة العربية" }),
    name_en: zod.string({ required_error: "ادخل اسم القسم باللغة الانجليزية" }).trim().min(1, { message: 'ادخل اسم القسم باللغة الانجليزية' }),
    description: zod.string({ required_error: "ادخل عن القسم باللغة الانجليزية" }).trim().min(1, { message: 'ادخل عن القسم باللغة الانجليزية' }),
    description_ar: zod.string({ required_error: "ادخل عن القسم باللغة العربية" }).trim().min(1, { message: 'ادخل عن القسم باللغة العربية' })
  })
);

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
let colleges = ref([{}]);
let schools = ref([{}]);
let chipData = ref(['Sudan University of Science and Technology']);
let chipData_ar = ref(['جامعة السودان للعلوم والتكنولوجيا']);
let currentItem = {};
const { value: college_id, } = useField('college_id');
const { value: school_id, } = useField('school_id');
const { value: name, } = useField('name');
const { value: name_en, } = useField('name_en');
const { value: keyWord, } = useField('keyWord');
const { value: keyWord_ar, } = useField('keyWord_ar');
const { value: description, } = useField('description');
const { value: description_ar, } = useField('description_ar');
const { value: active, } = useField('active');

let temp: Array<any> = [];

let ErorrMsg = [{ 'field': 'name', 'message': 'اسم القسم مع الكلية موجود مسبقا' }]

const save = handleSubmit(async (values) => {
  temp = [];
  table.value.SubmitLoading = true;
  values = Object.assign(values, { keywords: chipData.value.toString() });
  values = Object.assign(values, { keywords_ar: chipData_ar.value.toString() });
  values = Object.assign(values, { description: description.value });
  values = Object.assign(values, { description_ar: description_ar.value });
  values = Object.assign(values, { active: active.value });
  if (id.value > -1 && isMatch(currentItem, values) != true) {
    await axios.put(url + "/" + id.value, values).then(result => {
      if (result.data.status === 409) {
        Object.entries(result.data.message).forEach((item) => {
          temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
        });
      }
      table.value.PopulateTable(result.data.status, temp);
    }).catch(err => { if (table?.value) table.value.SubmitLoading = false; console.error(err); })
  }
  else if (id.value === -1) {
    await axios.post(url, values).then(result => {
      if (result.data.status === 409) {
        Object.entries(result.data.message).forEach((item) => {
          temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
        });
      }
      table.value.PopulateTable(result.data.status, temp);
    }).catch(err => { if (table?.value) table.value.SubmitLoading = false; console.error(err); });
  }
  
});

// Helper functions for chips removed as v-combobox handles this natively

function editItem(item: any) {
  renderEditor.value = false;
  currentItem = item = toRaw(item);
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
      if (item.keywords_ar != null && item.keywords_ar.length > 0) {
        chipData_ar.value = item.keywords_ar.split(',');
      } else {
        chipData_ar.value = ['جامعة السودان للعلوم والتكنولوجيا'];
      }
      setValues({
        college_id: item.college_id,
        school_id: item.school_id,
        name: item.name,
        name_en: item.name_en,
        keyWord: '',
        keyWord_ar: '',
        description: item.description,
        description_ar: item.description_ar,
        active: item.active,
      })
      table.value.disableEditButton = false
      table.value.dialog = true;
      nextTick(() => {
        setTimeout(() => {
          renderEditor.value = true;
        }, 100);
      });
    });
  }
}

function resetFormFields() {
  renderEditor.value = false;
  id.value = -1
  resetForm();
  if (colleges.value && colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
  school_id.value = null;
  chipData.value = ['Sudan University of Science and Technology'];
  chipData_ar.value = ['جامعة السودان للعلوم والتكنولوجيا'];
  nextTick(() => {
    setTimeout(() => {
      renderEditor.value = true;
    }, 100);
  });
}

function close() {
  nextTick(() => {
    id.value = -1
    if (chipData.value.length < 1) {
      chipData.value.push('Sudan University of Science and Technology')
    }
    if (chipData_ar.value.length < 1) {
      chipData_ar.value.push('جامعة السودان للعلوم والتكنولوجيا')
    }
    resetForm();
    if (colleges.value && colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
    school_id.value = null;
  });
  table.value.dialog = false
}

async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
  if (colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
    });
}

async function getSchools(collegeId: number) {
  await axios.post(route("schools.list"), { college_id: collegeId }).then(response => {
    schools.value = response.data.schools;
  });
}

watch(college_id, (newVal) => {
  if (newVal) {
    getSchools(newVal as number);
  } else {
    schools.value = [{}];
  }
});

onMounted(() => {
  getColleges();
});
</script>
