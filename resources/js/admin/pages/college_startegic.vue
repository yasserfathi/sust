<template>
  <v-container>
    <v-card>
      <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="كلمة عميد الكلية"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close" :fullscreen="fullscreen">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" md="6" class="pt-0">
              <v-select id="lang" name="lang" :items="languages" item-title="name" density="comfortable" item-value="id"
                prepend-icon="mdi-abjad-arabic" v-model="lang" label="لغة المحتوى" :error-messages="errors.lang"
                variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="college_id" label="اسماء الكليات"
                :error-messages="errors.college_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12">
              <v-text-field type="text" label="الكلمات المفتاحية" v-model="textBox" variant="underlined"
                @keyup.enter="addKeyWords" prepend-icon="mdi-tag-text-outline" v-click-outside="addKeyWords"
                :error-messages="errors.textBox">
                <template v-slot:append>
                  <v-btn size="small" @click="addKeyWords" icon="mdi-plus"></v-btn>
                </template>
              </v-text-field>
              <span v-for="(chipText, index) in chipData" :key="index" class="ma-1 w-100"><v-chip class="ma-2"
                  color="success" :model-value="true">{{ chipText }} <v-icon class="mr-2"
                    @click="item = chipText, dialog = true">mdi-trash-can-outline</v-icon></v-chip></span>
            </v-col>
            <v-col cols="12" class="pt-0 mt-0">
              <p class="font-weight-medium mb-2"><v-icon>mdi-text-box-outline</v-icon> الرؤية</p>
              <Editor v-if="isMounted" v-model="vision" />
              <span style="color:#ba0061;font-size: 12px;">{{ errors.vision }}</span>
            </v-col>
            <v-col cols="12" class="pt-0 mt-0">
              <p class="font-weight-medium mb-2"><v-icon>mdi-text-box-outline</v-icon> الرسالة</p>
              <Editor v-if="isMounted" v-model="mission" />
              <span style="color:#ba0061;font-size: 12px;">{{ errors.mission }}</span>
            </v-col>
            <v-col cols="12" class="pt-0 mt-0">
              <p class="font-weight-medium mb-2"><v-icon>mdi-text-box-outline</v-icon> الاهداف</p>
              <Editor v-if="isMounted" v-model="goals" />
              <span style="color:#ba0061;font-size: 12px;">{{ errors.goals }}</span>
            </v-col>
          </v-row>
        </Form>
      </Table>
    </v-card>
    <v-dialog v-model="dialog" width="350">
      <v-card style="height:160px;">
        <v-card-text>
          <v-row>
            <v-col cols="12" class="text-center">
              تأكيد عملية الحذف ؟
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" class="mx-1">
              <v-btn block color="green-darken-1" @click="removeItem()" ripple rounded="xl">موافق</v-btn>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" class="pt-0">
              <v-btn block color="grey-darken-1" @click="dialog = false" ripple rounded="xl">الغاء</v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>
<script lang="ts" setup>
import { defineAsyncComponent, nextTick, onBeforeMount, onMounted ,ref, toRaw } from 'vue';
const Table = defineAsyncComponent(() => import('../components/Table.vue'))
const Editor = defineAsyncComponent(() => import('../components/Editor.vue'))
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import {  z as zod } from 'zod';
import axios from 'axios';

const url = route('college_strategics.index');
const headers = [
  { title: 'لغة المحتوى', key: 'lang', width: 200 },
  { title: 'اسم الكلية', key: 'college.name', align: 'center' },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
const isMounted = ref(false)
interface FormFields {
  id: number,
  college_id: number,
  lang: number,
  vision: any;
  mission: any;
  goals: any;
}


const validationSchema = toTypedSchema(zod.object({
  lang: zod.number({ required_error: "اختر لغة المحتوى" }).gte(0, { message: 'اختر لغة المحتوى' }),
  college_id: zod.number({ required_error: "اختر اسم الكلية" }),
  vision: zod.string({ required_error: "ادخل الرؤية" }).trim().min(1, { message: 'ادخل الرؤية' }),
  mission: zod.string({ required_error: "ادخل الرسالة" }).trim().min(1, { message: 'ادخل الرسالة' }),
  goals: zod.string({ required_error: "ادخل الأهداف" }).trim().min(1, { message: 'ادخل الأهداف' }),
  textBox: zod.string().nullish().transform((value, ctx): string => {
    if (chipData.value.length == 0) {
      ctx.addIssue({
        code: "custom",
        message: 'ادخل الكلمة / الكلمات المفتاحية',
      });
    }
    return 'chipData.value';
  }),
}));


const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
const { value: textBox } = useField<any>('textBox');
let colleges = ref<Array<any>>();
let languages = ref<Array<any>>([{ 'id': -1, 'name': 'اختر لغة المحتوى' }, { 'id': 1, 'name': 'اللغة العربية' }, { 'id': 2, 'name': 'اللغة الانجليزية' }]);


const fullscreen = ref(true);
const dialog = ref(false);
const item = ref();
let chipData = ref(['جامعة السودان للعلوم و التكنولوجيا']);

const { value: college_id } = useField('college_id');
const { value: lang } = useField('lang');
const { value: vision } = useField('vision'); 
const { value: mission } = useField('mission');
const { value: goals } = useField('goals');

const save = handleSubmit(async (values) => {
  const formData = new FormData();
  formData.append("college_id", values.college_id.toString())
  formData.append("lang", values.lang.toString())
  formData.append("keywords", chipData.value.toString())
  formData.append("vision", values.vision)
  formData.append("mission", values.mission)
  formData.append("goals", values.goals)

  try {
    table.value.SubmitLoading = true;
    let result;
    const requestUrl = id.value > -1 ? `${url}/${id.value}` : url;
    
    if (id.value > -1) {
      formData.append('_method', 'put');
    }

    result = await axios.post(requestUrl, formData);
    checkFormErrorMsg(result);
  } catch (error) {
    console.error("Failed to save data:", error);
    // Optionally, show a generic error message to the user
    table.value.PopulateTable('error', 'An unexpected error occurred.');
  } finally {
    table.value.SubmitLoading = false;
  }
});

function checkFormErrorMsg(result: any) {
  let temp: Array<any> = [];
  if (result.data && result.data.message && typeof result.data.message === 'object') {
    Object.entries(result.data.message).forEach(([field, messages]) => {
      if (Array.isArray(messages) && messages.length > 0) {
        setFieldError(field as keyof FormFields, messages[0]);
      }
    });
  }
  table.value.PopulateTable(result.data.status, result.data.message);
}

function editItem(item: any) {
  item = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.id;
    axios.get(url + '/' + item.id).then(response => {
      item = response.data.result;
      chipData.value = item.keywords ? item.keywords.split(',') : ['جامعة السودان للعلوم و التكنولوجيا'];
      setValues({
        college_id: item.college_id,
        vision: item.vision, 
        mission: item.mission,
        goals: item.goals,
        keywords: item.keywords,
        lang: item.lang,
      })
      table.value.disableEditButton = false
      table.value.dialog = true;
    });
  }
}

function editItem1(item: any) {
  item = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.id;
    chipData.value = item.keywords ? item.keywords.split(',') : ['جامعة السودان للعلوم و التكنولوجيا'];
    setValues({
      college_id: item.college_id,
      lang: item.lang,
      vision: item.vision,
      mission: item.mission,
      goals: item.goals,
    });
    
    table.value.disableEditButton = false;
    table.value.dialog = true;
  }
}

function close() {
  nextTick(() => {
    id.value = -1
    chipData.value = ['جامعة السودان للعلوم و التكنولوجيا'];
    imagePath.value = filePath.value = undefined;
    resetForm();
  });
  table.value.disableEditButton = false;
  table.value.dialog = false;
}

function addKeyWords() {
  if (textBox.value != undefined && textBox.value.trim() != '') {
    chipData.value.push(textBox.value)
    textBox.value = ""
  }
}

function removeItem() {
  chipData.value.splice(chipData.value.indexOf(item.value), 1)
  if (chipData.value.length == 0) {
    textBox.value = undefined;
  }
  dialog.value = false
}

async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
  });
}

onBeforeMount(() => {
  getColleges();
});

onMounted(() => {
  isMounted.value = true
})
</script>
