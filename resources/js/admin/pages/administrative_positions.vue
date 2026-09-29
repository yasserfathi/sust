<template>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
      <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="المناصب الإدارية"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close"
        transition="dialog-bottom-transition">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" md="6" class="pt-0">
              <v-text-field id="title" name="title" label="المسمى الوظيفي (عربي)" v-model="title"
                prepend-icon="mdi-account-tie" variant="outlined" :error-messages="errors.title"
                density="comfortable" maxlength="255" counter="255"></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-text-field id="title_en" name="title_en" label="المسمى الوظيفي (إنجليزي)" v-model="title_en"
                prepend-icon="mdi-account-tie" variant="outlined" :error-messages="errors.title_en"
                density="comfortable" dir="ltr" maxlength="255" counter="255"></v-text-field>
            </v-col>
            <v-col cols="12" md="12" class="pt-0">
              <v-switch id="active" name="active" v-model="active" color="success"
                label="منشط" hide-details inset density="compact"></v-switch>
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

const url = 'administrative_positions';

const headers = [
  { title: 'المسمى الوظيفي (عربي)', key: 'title', align: 'center' },
  { title: 'المسمى الوظيفي (إنجليزي)', key: 'title_en', align: 'center' },
  { title: 'منشط', key: 'active', align: 'center' },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
interface FormFields {
  title: string,
  title_en: string | null,
  active: boolean,
}

const validationSchema = toTypedSchema(zod.object({
  title: zod.string({ required_error: "ادخل المسمى الوظيفي بالعربي" }).trim().min(1, { message: 'ادخل المسمى الوظيفي بالعربي' }).max(255, { message: "يجب أن لا يتجاوز العنوان 255 حرفاً" }),
  title_en: zod.string().trim().max(255, { message: "يجب أن لا يتجاوز العنوان 255 حرفاً" }).nullable().optional(),
  active: zod.boolean().default(true),
}));

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
  initialValues: {
    active: true
  }
});

const id = ref(-1);

const { value: title } = useField('title');
const { value: title_en } = useField('title_en');
const { value: active } = useField('active');

let ErorrMsg = [
  { 'field': 'title', 'message': 'خطأ في الإدخال' }
]

const save = handleSubmit(async (values) => {
  const formData = new FormData();
  formData.append("title", values.title.toString())
  if (values.title_en) {
    formData.append("title_en", values.title_en.toString())
  }
  formData.append("active", values.active ? '1' : '0')

  let temp: Array<any> = [];
  table.value.SubmitLoading = true;
  
  try {
    if (id.value < 0) {
      await axios.post(url, formData).then(result => {
        table.value.PopulateTable(201, temp);
      });
    } else {
      formData.append('_method', 'put');
      await axios.post(url + "/" + id.value, formData).then(result => {
        table.value.PopulateTable(200, temp);
      });
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      Object.entries(error.response.data.errors).forEach(([field, messages]: any) => {
        setFieldError(field as keyof FormFields, messages[0]);
      });
    }
    table.value.SubmitLoading = false;
  }
});

function editItem(item: any) {
  item = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.id;
    axios.get(url + '/' + item.id).then(response => {
      let data = response.data;
      setValues({
        title: data.title,
        title_en: data.title_en,
        active: Boolean(data.active),
      })
      table.value.disableEditButton = false
      table.value.dialog = true;
    });
  }
}

function close() {
  nextTick(() => {
    id.value = -1;
    resetForm();
    setValues({ active: true });
  });
  table.value.disableEditButton = false;
  table.value.dialog = false;
}
</script>
