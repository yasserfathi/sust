<template>
  <div>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
      <Table :id="id" ref="table" :url="url" :exportUrl="'/admin/schools/print'" :headers="headers"
        toolbar_title="اسماء المدارس" @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close"
        @resetFormFields="resetFormFields" transition="dialog-bottom-transition">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" v-show="colleges && colleges.length > 1">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" item-value="id"
                v-model="college_id" label="اسماء الكليات" :error-messages="errors.college_id" density="comfortable"></v-select>

              <v-text-field autofocus id="name" name="name" label="اسم المدرسة باللغة العربية" v-model="name" variant="outlined"
                :error-messages="errors.name" density="comfortable"></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-text-field id="name_en" name="name_en" label="اسم المدرسة باللغة الانجليزية" v-model="name_en"
                variant="outlined" :error-messages="errors.name_en" density="comfortable" dir="ltr"></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-text-field type="text" label="الكلمات المفتاحية باللغة العربية" v-model="keyWord_ar"
                variant="outlined" @keyup.enter="addKeyWordsAr" prepend-icon="mdi-bank"
                v-click-outside="addKeyWordsAr" :error-messages="errors.keyWord_ar" density="comfortable">
                <template v-slot:append>
                  <v-btn size="small" @click="addKeyWordsAr" icon="mdi-plus"></v-btn>
                </template>
              </v-text-field>
              <span v-for="(chipText, index) in chipData_ar" :key="index" class="ma-1 w-100"><v-chip class="ma-2"
                  color="success">{{
                    chipText }} <v-icon class="mr-2"
                    @click="item = chipText, dialog = true">mdi-trash-can-outline</v-icon></v-chip></span>
            </v-col>
            <v-col cols="12">
              <v-text-field type="text" label="الكلمات المفتاحية باللغة الانجليزية" v-model="keyWord"
                variant="outlined" @keyup.enter="addKeyWords" prepend-icon="mdi-bank" v-click-outside="addKeyWords"
                :error-messages="errors.keyWord" density="comfortable" dir="ltr">
                <template v-slot:append>
                  <v-btn size="small" @click="addKeyWords" icon="mdi-plus"></v-btn>
                </template>
              </v-text-field>
              <span v-for="(chipText, index) in chipData" :key="index" class="ma-1 w-100"><v-chip class="ma-2"
                  color="success">{{
                    chipText }} <v-icon class="mr-2"
                    @click="item = chipText, dialog = true">mdi-trash-can-outline</v-icon></v-chip></span>
            </v-col>
            <v-col cols="12" class="pt-0 mt-0">
              <p class="font-weight-medium mb-2"><v-icon>mdi-plus</v-icon> عن المدرسة باللغة العربية</p>
              <Editor v-if="renderEditor" v-model="description_ar" />
              <span style="color:#ba0061;font-size: 12px;">{{ errors.description_ar }}</span>
            </v-col>
            <v-col cols="12" class="pt-0 mt-0">
              <p class="font-weight-medium mb-2"><v-icon>mdi-plus</v-icon> عن المدرسة باللغة الانجليزية</p>
              <Editor v-if="renderEditor" v-model="description" dir="ltr" />
              <span style="color:#ba0061;font-size: 12px;">{{ errors.description }}</span>
            </v-col>
            <v-col cols="12">
              <v-switch :label="`تنشيط المدرسة`" id="active" name="active" v-model="active" color="#198754"
                density="compact" hide-descriptions></v-switch>
            </v-col>
          </v-row>
          <div id="buttons"></div>
        </Form>
      </Table>
    </v-card>
  </v-container>
  <v-dialog v-model="dialog" width="350">
    <v-card style="height:160px;">
      <v-card-text>
        <v-row>
          <v-col cols="12" class="text-center">
            تأكيد عملية الحذف ؟
          </v-col>
        </v-row>
        <div class="d-flex justify-center mt-4">
                  <v-btn color="green-darken-1" variant="elevated" @click="removeItem()" rounded="pill" class=" px-8">موافق</v-btn>
            <v-btn color="grey-darken-1" variant="elevated" @click="dialog = false" rounded="pill" class="ms-4 px-8">الغاء</v-btn>
                </div>
      </v-card-text>
    </v-card>
  </v-dialog>
  </div>
</template>

<script lang="ts" setup>
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import axios from 'axios';
import { isMatch } from 'lodash';

const url = route('schools.index')
const headers = [
  { title: 'الرقم', key: 'id' },
  { title: 'اسم الكلية', key: 'college.name' },
  { title: 'اسم المدرسة بالعربية', key: 'name' },
  { title: 'اسم المدرسة بالانجليزية', key: 'name_en' },
  { title: 'تنشيط', key: 'active', sortable: false },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
const renderEditor = ref(false);

interface FormFields {
  id: number,
  college_id: number;
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
    name: zod.string({ required_error: "ادخل اسم المدرسة باللغة العربية" }).min(1, { message: "ادخل اسم المدرسة باللغة العربية" }),
    name_en: zod.string({ required_error: "ادخل اسم المدرسة باللغة الانجليزية" }).trim().min(1, { message: 'ادخل اسم المدرسة باللغة الانجليزية' }),
    description: zod.string({ required_error: "ادخل عن المدرسة باللغة الانجليزية" }).trim().min(1, { message: 'ادخل عن المدرسة باللغة الانجليزية' }),
    description_ar: zod.string({ required_error: "ادخل عن المدرسة باللغة العربية" }).trim().min(1, { message: 'ادخل عن المدرسة باللغة العربية' })
  })
);

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
let colleges = ref([{}]);
let chipData = ref(['Sudan University of Science and Technology']);
let chipData_ar = ref(['جامعة السودان للعلوم والتكنولوجيا']);
const dialog = ref(false);
const item = ref();
let currentItem = {};
const { value: college_id, } = useField('college_id');
const { value: name, } = useField('name');
const { value: name_en, } = useField('name_en');
const { value: keyWord, } = useField('keyWord');
const { value: keyWord_ar, } = useField('keyWord_ar');
const { value: description, } = useField('description');
const { value: description_ar, } = useField('description_ar');
const { value: active, } = useField('active');

let temp: Array<any> = [];

let ErorrMsg = [{ 'field': 'name', 'message': 'اسم المدرسة مع الكلية موجود مسبقا' }]

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

function addKeyWordsAr() {
  if (keyWord_ar.value != undefined && keyWord_ar.value.trim() != '') {
    const parts = keyWord_ar.value.split(',').map(s => s.trim()).filter(s => s !== '');
    parts.forEach(part => {
      if (!chipData_ar.value.includes(part)) {
        chipData_ar.value.push(part);
      }
    });
  }
  keyWord_ar.value = "";
}

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
  });
  table.value.dialog = false
}

function removeItem() {
  if (chipData.value.includes(item.value)) {
    chipData.value.splice(chipData.value.indexOf(item.value), 1);
    if (chipData.value.length === 0) setFieldError('keyWord', 'ادخل الكلمة / الكلمات المفتاحية');
  }
  if (chipData_ar.value.includes(item.value)) {
    chipData_ar.value.splice(chipData_ar.value.indexOf(item.value), 1);
    if (chipData_ar.value.length === 0) setFieldError('keyWord_ar', 'ادخل الكلمة / الكلمات المفتاحية');
  }
  dialog.value = false
}

async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
  if (colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
    });
}

onMounted(() => {
  getColleges();
});
</script>
