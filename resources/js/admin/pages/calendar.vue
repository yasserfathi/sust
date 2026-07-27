<template>
  <v-container>
    <v-card>
      <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="التقويم الدراسي باللغة العربية"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close" transition="dialog-bottom-transition">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" md="4" class="pt-0">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="college_id"
                label="اسماء الكليات" :error-messages="errors.college_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="4" class="pt-0">
              <v-text-field id="year" name="year" label="تاريخ السنة" v-model="year"
                prepend-icon="mdi-calendar-account-outline" variant="underlined"
                :error-messages="errors.year"></v-text-field>
            </v-col>
            <v-col cols="12" :md="filePath != undefined ? 3 : 4" class="pt-0">
              <v-file-input type="file" id="file" name="file" ref="fileInput" show-size @input="onSelectFile" chips accept='.dox,.docx,.pdf'
                label="اختر الملف  المرفق" variant="underlined" :error-messages="errors.file"></v-file-input>
            </v-col>
            <v-col v-if="filePath != undefined" cols="12" md="1" class="pt-2">
              <a :href="BASE_URL+ filePath" class="text-subtitle-2">الملف الحالي<v-icon icon="mdi-image"></v-icon></a>
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

const url = route('calendar.index')
const headers = [
  { title: 'اسم الكلية', key: 'college.name', align: 'center' },
  { title: 'تاريخ السنة', key: 'year', align: 'center' },
  { title: 'الملف', key: 'file',width:200 },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
interface FormFields {
  id: number,
  college_id: number,
  year: number,
  file: any;
}
const BASE_URL = import.meta.env.VITE_BASE_URL + '/';
const ACCEPTED_FILE_TYPES = ["application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/pdf"];

const form_items_types =zod.object({
    college_id: zod.number({ required_error: "اختر اسم الكلية" }).positive({ message: "اختر اسم الكلية" }),
    year: zod.string({ required_error: "ادخل تاريخ السنة" }).trim().min(1, { message: 'ادخل تاريخ السنة' }),
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

const fileInput = ref();
const filePath = ref();

const { value: college_id } = useField('college_id');
const { value: year } = useField('year');
const { value: file } = useField('file');

let ErorrMsg = [
    {'field':'file','message':'يتم دعم  فقط .doc, .docx, .pdf'},
    {'field':'year','message':'التقويم الدراسي موجود مسبقاً'},
  ]

const save = handleSubmit(async(values) => {
  const formData = new FormData();
  formData.append("college_id", values.college_id.toString())
  formData.append("year", values.year.toString())

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
      filePath.value = undefined;
      if(item.file != null && item.file.length > 0){
        filePath.value = item.file;
      }
      setValues({
        college_id: item.college.id,
        year: item.year,
      })
      table.value.disableEditButton = false
      table.value.dialog = true;
    });
  }
}

function close() {
  nextTick(() => {
    id.value = -1
    filePath.value = undefined
    resetForm();
  });
  table.value.disableEditButton = false;
  table.value.dialog = false;
}

function onSelectFile() {
  file.value = fileInput.value.files
}

async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
  });
}

onBeforeMount(() => {
  getColleges();
});
</script>
