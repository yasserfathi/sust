<template>
  <div>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
      <Table :id="id" ref="table" :url="url" :headers="headers" toolbar_title="معارض الصور" @edit-item="editItem"
        @setFieldError="setFieldError" @close="close" :fullscreen="fullscreen" :showButtons="false">
        <v-stepper v-model="currentStep" editable>
          <v-stepper-header>
            <v-stepper-item :complete="currentStep > 1" :value="1" title="اضافة معرض صور"></v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :complete="currentStep > 2" :value="2" title="اضافة صور"
              :editable="isStep1Valid"></v-stepper-item>
          </v-stepper-header>
          <v-stepper-window>
            <v-stepper-window-item :value="1">
              <Form fast-fail name="form" class="pt-3" :validation-schema="validationSchema" lazy-validation>
                <v-row>
                  <v-col cols="12" md v-show="colleges && colleges.length > 1">
                    <v-select id="college_id" name="college_id" :items="colleges" item-title="name" item-value="id"
                      v-model="college_id" @update:modelValue="onCollegeChange($event)" label="اسماء الكليات" variant="outlined" :error-messages="errors.college_id"
                      density="comfortable"></v-select>
                  </v-col>
                  <v-col cols="12" md>
                    <v-text-field autofocus id="title" name="title" label="اسم المعرض باللغة العربية" v-model="title"
                      variant="outlined" :error-messages="errors.title" density="comfortable"></v-text-field>
                  </v-col>
                  <v-col cols="12" md>
                    <v-text-field id="title_en" name="title_en" label="اسم المعرض باللغة الانجليزية" v-model="title_en"
                      variant="outlined" :error-messages="errors.title_en" density="comfortable"></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-textarea name="description" variant="outlined" label="وصف المعرض" v-model="description"
                      :error-messages="errors.description" auto-grow rows="1" row-height="20"
                      density="comfortable"></v-textarea>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field id="keyWord" name="keyWord" label="الكلمات المفتاحية" v-model="keyWord"
                      variant="outlined" @keyup.enter="addKeyWords" prepend-icon="mdi-bank"
                      v-click-outside="addKeyWords" :error-messages="errors.keyWord" density="comfortable">
                      <template v-slot:append>
                        <v-btn size="small" @click="addKeyWords" icon="mdi-plus"></v-btn>
                      </template>
                    </v-text-field>
                    <span v-for="(chipText, index) in chipData" :key="index" class="ma-1 w-100"><v-chip class="ma-2"
                        color="success" :model-value="true">{{ chipText }} <v-icon class="mr-2"
                          @click="item = chipText, dialog = true">mdi-trash-can-outline</v-icon></v-chip></span>
                  </v-col>
                  <v-col cols="12">
                    <v-switch :label="`تنشيط المعرض`" id="active" name="active" v-model="active" color="#198754"
                      density="compact" hide-details class="pr-3"></v-switch>
                  </v-col>
                </v-row>
              </Form>
              <div class="d-flex justify-start mt-6">
                <v-btn color="primary" @click="save" :loading="step1Loading" append-icon="mdi-arrow-left">
                  التالي
                </v-btn>
              </div>
            </v-stepper-window-item>
            <v-stepper-window-item :value="2">
              <album-photos-form :album_id="id"></album-photos-form>
              <v-btn color="primary" @click="currentStep = 1">السابق</v-btn>
              <v-divider class="my-3"></v-divider>
              <Dialog ref="modal" />
              <v-card class="mx-auto" max-width="100%">
              </v-card>
            </v-stepper-window-item>
          </v-stepper-window>
        </v-stepper>
      </Table>
      <v-dialog v-model="dialog" width="350">
        <v-card style="height:160px;">
          <v-card-text>
            <v-row>
              <v-col cols="12" class="text-center">
                تأكيد عملية الحذف ؟
              </v-col>
            </v-row>
            <div class="d-flex justify-center mt-4">
              <v-btn color="green-darken-1" variant="elevated" @click="removeItem()" rounded="pill"
                class=" px-8">موافق</v-btn>
              <v-btn color="grey-darken-1" variant="elevated" @click="dialog = false" rounded="pill"
                class="ms-4 px-8">الغاء</v-btn>
            </div>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-card>
  </v-container>
  </div>
</template>
<script lang="ts" setup>
import albumPhotosForm from '../pages/albumPhotosForm.vue'
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import axios from 'axios';
import { isMatch } from 'lodash';

const url = route('albums.index')
const headers = [
  { title: 'عنوان المعرض بالعربية', key: 'title' },
  { title: 'اسم الكلية', key: 'college.name', align: 'center' },
  { title: 'وصف المعرض', key: 'description', sortable: false },
  { title: 'تنشيط المعرض', key: 'active', sortable: false },
  { title: '', key: 'actions', sortable: false },
];

let chipData = ref(['جامعة السودان للعلوم والتكنولوجيا']);
const table = ref();
const modal = ref();
const currentStep = ref(1);
const step1Loading = ref(false);

interface FormFields {
  id: number,
  college_id: number;
  title: string;
  title_en: string;
  description: string;
  keyWord: string;
  active: boolean;
}

const validationSchema = toTypedSchema(
  zod.object({
    college_id: zod.number({ required_error: "اختر اسم الكلية" }),
    title: zod.string({ required_error: "ادخل اسم المعرض باللغة العربية" }).min(1, { message: "ادخل اسم المعرض باللغة العربية" }),
    title_en: zod.string({ required_error: "ادخل اسم المعرض باللغة الانجليزية" }).trim().min(1, { message: 'ادخل اسم المعرض باللغة الانجليزية' }),
    description: zod.string({ required_error: "ادخل وصف المعرض" }).trim().min(1, { message: 'ادخل وصف المعرض' }),
  })
);

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
let colleges = ref<Array<any>>([]);
let currentItem = {};
const fullscreen = ref(true);
const dialog = ref(false);
const item = ref();
let temp: Array<any> = [];

const { value: college_id } = useField('college_id');
const { value: title } = useField('title');
const { value: title_en } = useField('title_en');
const { value: description } = useField('description');
const { value: active } = useField('active');
const { value: keyWord } = useField<string>('keyWord');

let ErorrMsg = [{ 'field': 'title', 'message': 'اسم المعرض موجود مسبقا' }]

const save = handleSubmit(async (values) => {
  step1Loading.value = true;
  if (chipData.value.length == 0) {
    setFieldError('keyWord', 'ادخل الكلمة / الكلمات المفتاحية');
  }
  temp = [];
  values = Object.assign(values, { keywords: chipData.value.toString() });
  values = Object.assign(values, { active: active.value });
  if (id.value > -1 && isMatch(currentItem, values) != true) {
    await axios.put(url + "/" + id.value, values).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable_album_news(result.data.status, temp);
      currentStep.value = 2
    })
  }
  else if (id.value < 0) {
    await axios.post(url, values).then(result => {
      id.value = result.data.album_id;
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable_album_news(result.data.status, temp);
      currentStep.value = 2
    });
  }
  // close();
  step1Loading.value = false;
});

function editItem(item: any) {
  currentItem = item = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.id;
    axios.get(url + '/' + item.id).then(response => {
      item = response.data.result;
      setValues({
        college_id: item.college_id,
        title: item.title,
        title_en: item.title_en,
        description: item.description,
        active: item.active
      })
      table.value.disableEditButton = false
      currentStep.value = 1
      table.value.dialog = true
    });
  }
}

function close() {
  nextTick(() => {
    id.value = -1
    if (chipData.value.length < 1) {
      chipData.value.push('جامعة السودان للعلوم والتكنولوجيا')
    }
    resetForm();
    if (colleges.value && colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
  });
  currentStep.value = 1
  table.value.dialog = false
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
  chipData.value.splice(chipData.value.indexOf(item.value), 1);
  if (chipData.value.length == 0) {
    setFieldError('keyWord', 'ادخل الكلمة / الكلمات المفتاحية');
  }
  dialog.value = false
}

async function onCollegeChange(c_id: number) {
  if (!c_id) return;
  try {
    const response = await axios.post(route("albums.list"), { college_id: c_id });
    if (response.data.albums && response.data.albums.length > 0) {
      const firstAlbum = response.data.albums[0];
      id.value = firstAlbum.id;
      // Fetch full album details to populate inputs and keywords
      const fullRes = await axios.get(url + '/' + firstAlbum.id);
      const itemData = fullRes.data.result;
      setValues({
        college_id: itemData.college_id,
        title: itemData.title,
        title_en: itemData.title_en,
        description: itemData.description,
        active: Boolean(itemData.active)
      });
      if (itemData.keywords) {
        chipData.value = itemData.keywords.split(',').map((s: string) => s.trim()).filter((s: string) => s !== '');
      }
    } else {
      id.value = -1;
      const selectedCollege = colleges.value.find((c: any) => c.id === c_id);
      if (selectedCollege) {
        setValues({
          college_id: c_id,
          title: 'معرض صور ' + selectedCollege.name,
          title_en: 'Photo Gallery - ' + (selectedCollege.name_en || selectedCollege.name),
          description: 'الألبوم الافتراضي لصور ' + selectedCollege.name,
          active: true
        });
        chipData.value = ['جامعة السودان للعلوم والتكنولوجيا', selectedCollege.name];
      }
    }
  } catch (error) {
    console.error('Error fetching college album:', error);
  }
}

async function getColleges() {
  await axios.get(route('colleges.list')).then(response => {
    colleges.value = response.data.colleges;
    if (colleges.value && colleges.value.length === 1) {
      college_id.value = colleges.value[0].id;
      onCollegeChange(colleges.value[0].id);
    }
  });
}

const isStep1Valid = computed(() =>
  id.value !== -1
);

onMounted(() => {
  getColleges();
});
</script>
