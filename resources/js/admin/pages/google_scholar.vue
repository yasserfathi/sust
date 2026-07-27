<template>
  <v-container>
    <v-card>
      <Table ref="table" :id="id" :url="api_url" :headers="headers" toolbar_title="رابط الباحث العلمي قوقل"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close" transition="dialog-bottom-transition">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" md="4" class="pt-0">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="college_id"  @update:modelValue="getDepartments($event)"
                label="اسماء الكليات" :error-messages="errors.college_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="4" class="pt-0">
              <v-select id="department_id" name="department_id" :disabled="deartment_disabled" :items="departments" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="department_id"  @update:modelValue="getUsers($event)"
                label="اسماء الاقسام" :error-messages="errors.department_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="4" class="pt-0">
              <v-select id="user_id" name="user_id" :disabled="user_disabled" :items="users" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="user_id"
                label="اعضاء هيئة التدريس" :error-messages="errors.user_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="12" class="pt-0">
              <v-text-field id="url" name="url" label="رابط الباحث العلمي قوقل (URL)" v-model="url" prepend-icon="mdi-book-open-page-variant-outline"
                variant="underlined" :error-messages="errors.url"></v-text-field>
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

const api_url = route('staff_academic.index','google_scholar')

const headers = [
  { title: 'اسم عضو هيئة التدريس', key: 'user.name', align: 'center' },
  { title: 'عنوان رابط الباحث العلمي قوقل', key: 'url', align: 'center' },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
interface FormFields {
  id: number,
  college_id: number,
  department_id: number,
  user_id: number,
  url: string,
}

const validationSchema = toTypedSchema(zod.object({
    college_id: zod.number({ required_error: "اختر اسم الكلية" }).positive({ message: "اختر اسم الكلية" }),
    department_id: zod.number({ required_error: "اختر اسم القسم" }).positive({ message: 'اختر اسم القسم' }).nullable(),
    user_id: zod.number({ required_error: "اختر اسم عضو هيئة التدريس" }).positive({ message: "اختر اسم عضو هيئة التدريس" }),
    url: zod.string({ required_error: "ادخل رابط الباحث العلمي قوقل" }).trim().min(1, { message: 'ادخل رابط الباحث العلمي قوقل' }),
  }));

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
const colleges = ref();
const departments = ref();
const users = ref();

const deartment_disabled = ref(true);
const user_disabled = ref(true);

const { value: college_id } = useField('college_id');
const { value: department_id } = useField('department_id');
const { value: user_id } = useField('user_id');
const { value: item_val } = useField('item_val');
const { value: url } = useField('url');

const save = handleSubmit(async(values) => {

  let ErorrMsg = [
    {'field':'url','message':'الرابط العلمي لهذا العضو موجود مسبقاً'},
  ]

  const formData = new FormData();
  formData.append("lang", '1')
  formData.append("user_id", values.user_id.toString())
  formData.append("item_val", 'google_scholar')
  formData.append("url", values.url.toString())

  let temp: Array<any> = [];
  table.value.SubmitLoading = true;
  if(id.value < 0){
    await axios.post(api_url,formData,{headers:{'content-type':'multipart/form-data' }}).then(result => {
      if(result.data.message['item_val'] != undefined){
        temp.push(ErorrMsg[0]);
      }
      table.value.PopulateTable(result.data.status,temp);
    })
  }
  else{
    formData.append('_method', 'put');
    await axios.post(api_url + "/" + id.value, formData,{headers:{'content-type':'multipart/form-data' }}).then(result => {
      if(result.data.message['item_val'] != undefined){
        temp.push(ErorrMsg[0]);
      }
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
    axios.get(api_url + '/' + item.id).then(response => {
      item = response.data.result;
      item_val.value = item.item_val;
      url.value = item.url;
      deartment_disabled.value = user_disabled.value = false
      departments.value = [{ 'id': item.user.staff_latest.department_id, 'name': item.user.staff_latest.department.name }];
      users.value = [{ 'id': item.user.staff_latest.id, 'name': item.user.name }];
      setValues({
        college_id: item.user.staff_latest.department.college.id,
        department_id: item.user.staff_latest.department.id,
        user_id: item.user.staff_latest.id,
      })
      table.value.disableEditButton = false
      table.value.dialog = true;
    });
  }
}

function close() {
  nextTick(() => {
    id.value = -1
    departments.value.unshift({ 'id': -1, 'name': 'اختر القسم' });
    department_id.value = -1;
    users.value.unshift({ 'id': -1, 'name': 'اختر عضو هيئة التدريس' });
    user_id.value = -1
    deartment_disabled.value = user_disabled.value = true
    resetForm();
  });
  table.value.disableEditButton = false;
  table.value.dialog = false;
}

async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
  });
}

async function getDepartments(college_id: any) {
  await axios.post(route("departments.list"), { 'college_id': college_id }).then(response => {
    if (deartment_disabled.value == true) {
      deartment_disabled.value = !deartment_disabled.value

    }

    if (response.data.departments.length < 1) {
      departments.value.unshift({ 'id': -1, 'name': 'اختر القسم' });
      department_id.value = -1;

      users.value.unshift({ 'id': -1, 'name': 'اختر عضو هيئة التدريس' });
      user_id.value = -1

      deartment_disabled.value = user_disabled.value = true
    }
    else {
      departments.value = response.data.departments;
      departments.value.unshift({ 'id': -1, 'name': 'اختر القسم' });
      department_id.value = response.data.departments[0].id
    }
  });
}

async function getUsers(department_id: any) {
  await axios.post(route("users.list"), { 'department_id': department_id }).then(response => {
    if (user_disabled.value == true) {
      user_disabled.value = !user_disabled.value
    }
    if (response.data.users.length < 1) {
      users.value.unshift({ 'id': -1, 'name': 'اختر عضو هيئة التدريس' });
      user_id.value = -1
    }
    else {
      users.value = response.data.users;
      users.value.unshift({ 'id': -1, 'name': 'اختر عضو هيئة التدريس' });
      user_id.value = response.data.users[0].id
    }
  });
}

onBeforeMount(() => {
  getColleges();
});
</script>
