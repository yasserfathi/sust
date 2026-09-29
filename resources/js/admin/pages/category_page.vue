<template>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
      <Table :id="id" ref="table" :url="api_url" :headers="headers" toolbar_title="صفحات فئات لوحة التحكم"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close"
        @resetFormFields="resetFormFields">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12">
              <v-select id="category_id" name="category_id" :items="categories" item-title="title" item-value="id"
                v-model="category_id" label="عناوين الفئات" :error-messages="errors.category_id" density="comfortable"></v-select>
            </v-col>
            <v-col cols="6">
              <v-text-field id="title" name="title" label="اسم الصفحة" v-model="title" variant="outlined"
                :error-messages="errors.title" density="comfortable" maxlength="255" counter="255"></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-locale-provider ltr>
                <v-text-field id="url" name="url" label="عنوان الصفحة (URL)" v-model="url" variant="outlined"
                  :error-messages="errors.url" density="comfortable"></v-text-field>
              </v-locale-provider>
            </v-col>
            <v-col cols="12">
              <v-switch :label="`تنشيط عنوان الصفحة `" id="active" name="active" v-model="active" color="#198754"
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


const api_url = route('category_page.index')
const headers = [
  { title: 'الفئة', key: 'category.title' },
  { title: 'اسم الصفحة', key: 'title' },
  { title: 'تنشيط', key: 'active', sortable: false },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();

interface FormFields {
  id: number,
  category_id: number;
  title: string;
  url: string;
  active: boolean;
}

const validationSchema = toTypedSchema(
  zod.object({
    category_id: zod.number({ required_error: "اختر عنوان الفئة" }),
    title: zod.string({ required_error: "ادخل اسم الصفحة" }).min(1, { message: "ادخل اسم الصفحة" }).max(255, { message: "يجب أن لا يتجاوز الاسم 255 حرفاً" }),
    url: zod.string({ required_error: "ادخل اسم الصفحة" }).trim().min(1, { message: 'ادخل اسم الصفحة' })
  })
);

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
let categories = ref([{}]);
let currentItem = {};
const { value: category_id, } = useField('category_id');
const { value: title } = useField('title');
const { value: url } = useField('url');
const { value: active } = useField('active');

let ErorrMsg = [{ 'field': 'title', 'message': 'اسم الصفجة مع عنوان الفئة موجود مسبقا' }]

const save = handleSubmit(async (values) => {
  let temp: Array<any> = [];
  table.value.SubmitLoading = true;
  values = Object.assign(values, { active: active.value });
  if (id.value > -1 && isMatch(currentItem, values) != true) {
    await axios.put(api_url + "/" + id.value, values).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable(result.data.status, temp);
    }).catch(err => { if (table?.value) table.value.SubmitLoading = false; console.error(err); })
  }
  else if (id.value === -1) {
    await axios.post(api_url, values).then(result => {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
      table.value.PopulateTable(result.data.status, temp);
    }).catch(err => { if (table?.value) table.value.SubmitLoading = false; console.error(err); });
  }
  
});

function editItem(item: any) {
  currentItem = item = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.id;
    axios.get(api_url + '/' + item.id).then(response => {
      item = response.data.result;
      setValues({
        category_id: item.category_id,
        title: item.title,
        url: item.url,
        active: item.active,
      })
      table.value.disableEditButton = false
      table.value.dialog = true;
    });
  }
}

function resetFormFields() {
  id.value = -1
  resetForm();
}

function close() {
  nextTick(() => {
    resetFormFields();
  });
  table.value.dialog = false
  window.location.reload();
}

async function getCategories() {
  await axios.get(route("category.list")).then(response => {
    categories.value = response.data.categories;
  });
}

onMounted(() => {
  getCategories();
});
</script>

<style>
#url {
  direction: ltr
}
</style>
