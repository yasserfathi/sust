<template>
  <v-container>
    <v-card>
      <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="كلمة عميد الكلية"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close"
        :fullscreen="fullscreen" transition="dialog-bottom-transition">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" md="3" class="pt-0">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="college_id"
                label="اسماء الكليات" :error-messages="errors.college_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="3" class="pt-0">
              <v-select id="lang" name="lang" :items="languages" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-abjad-arabic" v-model="lang"
                label="لغة المحتوى" :error-messages="errors.lang" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" :md="imagePath != undefined ? 2 : 3" class="pt-0">
              <v-file-input type="file" id="img" name="img" ref="imgInput" show-size chips accept='.jpg,.jpeg,.png' @change="onSelectImage"
                label="اختر ملف الصورة" variant="underlined" :error-messages="errors.img"></v-file-input>
            </v-col>
            <v-col v-if="imagePath != undefined" cols="12" md="1">
                <v-img :src="BASE_URL+ imageThumbPath" :lazy-src="BASE_URL+ imageThumbPath" :height="48" style="cursor: pointer" @click="showModal(BASE_URL+ imagePath)">
                  <template v-slot:placeholder>
                    <v-row class="fill-height ma-0" align="center" justify="center">
                      <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                    </v-row>
                  </template>
                </v-img>
                <v-dialog v-model="showImage" width="800">
                <v-card>
                  <v-card-text class="pa-3">
                    <v-img :src="photo" :lazy-src="photo"></v-img>
                  </v-card-text>
                </v-card>
              </v-dialog>
            </v-col>
            <v-col cols="12" :md="filePath != undefined ? 2 : 3" class="pt-0">
              <v-file-input type="file" id="file" name="file" ref="fileInput" show-size @input="onSelectFile" chips accept='.dox,.docx,.pdf'
                label="اختر الملف  المرفق" variant="underlined" :error-messages="errors.file"></v-file-input>
            </v-col>
            <v-col v-if="filePath != undefined" cols="12" md="1" class="pt-7">
              <a :href="BASE_URL+ filePath" class="text-subtitle-2">الملف الحالي<v-icon icon="mdi-image"></v-icon></a>
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
            <v-col cols="12" class="mt-1">
              <v-text-field name="detail_portion" variant="underlined" label="جزء من المحتوى" v-model="detail_portion"
                :error-messages="errors.detail_portion" prepend-icon="mdi-text-box-outline"></v-text-field>
            </v-col>
            <v-col cols="12" class="pt-0 mt-0">
              <p class="font-weight-medium mb-2"><v-icon>mdi-text-box-outline</v-icon> المحتوى</p>
              <Editor v-model="detail"/>
              <span style="color:#ba0061;font-size: 12px;">{{ errors.detail }}</span>
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
import { defineAsyncComponent, toRaw } from 'vue'
const Table = defineAsyncComponent(() => import('../components/Table.vue'))
const Editor = defineAsyncComponent(() => import('../components/Editor.vue'))
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import { z as zod} from 'zod';
import { nextTick, onBeforeMount, ref } from 'vue';
import axios from 'axios';

const url = route('page.index','dean_word')
const headers = [
  { title: 'لغة المحتوى', key: 'lang',width:200 },
  { title: 'اسم الكلية', key: 'college.name', align: 'center' },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
interface FormFields {
  id: number,
  college_id: number,
  lang: number,
  textBox: any;
  img: any;
  file: any;
  detail_portion: string;
  detail: string;
}

const BASE_URL = import.meta.env.VITE_BASE_URL + '/';
const ACCEPTED_IMAGE_TYPES = ["image/jpeg", "image/jpg", "image/png"];
const ACCEPTED_FILE_TYPES = ["application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/pdf"];

const validationSchema = toTypedSchema(
  zod.object({
    lang: zod.number({ required_error: "اختر لغة المحتوى" }).gte(0,{ message: 'اختر لغة المحتوى' }),
    college_id: zod.number({ required_error: "اختر اسم الكلية" }),
    file: zod.any().refine((files) => files?.length == 1, "اختر الملف المرفق")
          .refine((files) => ACCEPTED_FILE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .doc, .docx, .pdf")
          .refine((files) => files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`).nullish(),
          img: zod.any().refine((files) => files?.length == 1, "اختر الصورة")
          .refine((files) => ACCEPTED_IMAGE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .jpg, .jpeg and .png")
         .refine((files) => files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`).nullish(),
    detail_portion: zod.string({ required_error: "ادخل جزء من المحتوى" }).trim().min(1, { message: 'ادخل جزء من المحتوى' }),
    detail: zod.string({ required_error: "ادخل المحتوى" }).trim().min(1, { message: 'ادخل المحتوى' }),
    textBox: zod.string().nullish().transform((value, ctx): string => {
      if (chipData.value.length == 0) {
        ctx.addIssue({
          code: "custom",
          message: 'ادخل الكلمة / الكلمات المفتاحية',
        });
      }
      return 'chipData.value';
    }),
  })
);

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
const { value: textBox } = useField<any>('textBox');
let colleges = ref<Array<any>>();
let languages = ref<Array<any>>([{'id':-1,'name':'اختر لغة المحتوى'},{'id':1,'name':'اللغة العربية'},{'id':2,'name':'اللغة الانجليزية'}]);


const fullscreen = ref(true);
const imgInput = ref();
const fileInput = ref();
const filePath = ref();
const imageThumbPath = ref();
const imagePath = ref();
const dialog = ref(false);
const photo = ref();
const showImage = ref(false);
const item = ref();
let chipData = ref(['جامعة السودان للعلوم و التكنولوجيا']);

const { value: college_id } = useField('college_id');
const { value: lang } = useField('lang');
const { value: img } = useField('img');
const { value: file } = useField('file');
const { value: detail_portion } = useField('detail_portion');
const { value: detail } = useField<string>('detail');

const save = handleSubmit(async(values) => {

  let language_name = toRaw(languages.value)?.find(a => a.id == values.lang).name;
  let college_name = toRaw(colleges.value)?.find(a => a.id == values.college_id).name;
  let ErorrMsg = [
    {'field':'img','message':'يتم دعم  فقط .jpg, .jpeg and .png'},
    {'field':'file','message':'يتم دعم  فقط .doc, .docx, .pdf'},
    {'field':'lang','message':'محتوى  '+ college_name + ' ب' + language_name  +' موجود مسبقا'},
  ]

  const formData = new FormData();
  formData.append("college_id", values.college_id.toString())
  formData.append("lang", values.lang.toString())
  formData.append("keywords", chipData.value.toString())
  formData.append("detail_portion", values.detail_portion)
  formData.append("detail", values.detail)
  if(imgInput.value.files[0] != undefined){
    formData.append("img", imgInput.value.files[0])
  }

  if(fileInput.value.files[0] != undefined){
    formData.append("file", fileInput.value.files[0])
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
      filePath.value = item.file;
      imageThumbPath.value = item.thumb_img;
      imagePath.value = item.img;
      setValues({
        college_id: item.college_id,
        lang: item.lang,
        detail_portion: item.detail_portion,
        detail: item.detail,
      })
      table.value.disableEditButton = false
      table.value.dialog = true;
    });
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

function onSelectImage() {
  img.value = imgInput.value.files
}

function onSelectFile() {
  file.value = fileInput.value.files
}

function addKeyWords() {
  if (textBox.value != undefined && textBox.value.trim() != '') {
    chipData.value.push(textBox.value)
    textBox.value = ""
  }
}

function removeItem() {
  chipData.value.splice(chipData.value.indexOf(item.value), 1)
    if (chipData.value.length == 0){
      textBox.value = undefined;
    }
  dialog.value = false
}

async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
  });
}

function showModal(photoPath: any) {
  photo.value = photoPath
  showImage.value = true
}

onBeforeMount(() => {
  getColleges();
});
</script>
