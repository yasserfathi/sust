<template>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
      <Table :id="id" ref="table" :url="url" :headers="headers" toolbar_title="فئات لوحة التحكم"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12">
              <v-text-field id="title" name="title" label="عنوان الفئة" v-model="title" variant="outlined"
                :error-messages="errors.title" density="comfortable" maxlength="255" counter="255"></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-switch :label="`تنشيط الفئة`" id="active" name="active" v-model="active" color="#198754"
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

const url = route('category.index')
const headers = [
  { title: 'عنوان الفئة', key: 'title' },
  { title: 'تنشيط', key: 'active', sortable: false },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
let currentItem = {};

interface FormFields {
  id: number,
  title: string;
  active: boolean;
}

const validationSchema = toTypedSchema(
  zod.object({
    title: zod.string({ required_error: "ادخل عنوان الفئة" }).min(1, { message: "ادخل عنوان الفئة" }).max(255, { message: "يجب أن لا يتجاوز العنوان 255 حرفاً" }),
  })
);

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
const { value: title } = useField('title');
const { value: active } = useField('active');
let ErorrMsg = [{'field':'title','message':'عنوان الفئة موجود مسبقا'}]

const save = handleSubmit(async (values) => {
  table.value.loading = true;
  values = Object.assign(values, { active: active.value });
  if (id.value > -1 && isMatch(currentItem, values) != true) {
    await axios.put(url + "/" + id.value, values).then(result => {
      table.value.PopulateTable(result.data.status, ErorrMsg);
    })
  } else if (id.value === -1) {
    await axios.post(url, values).then(result => {
      table.value.PopulateTable(result.data.status, ErorrMsg);
    });
  }
  //  close();
});

function editItem(item: any) {
  item = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.id;
    axios.get(url + '/' + item.id).then(response => {
      item = response.data.result;
      setValues({
        title: item.title,
        active: item.active,
      })
      table.value.disableEditButton = false
      table.value.dialog = true;
    });
  }
}

function close() {
  nextTick(() => {
    id.value = -1
    resetForm();
  });
  table.value.dialog = false
}
</script>
