<template>
  <v-container>
    <v-card>
      <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="الدورات التدريبية"
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
            <v-col cols="12" md="4" class="pt-0">
              <v-select id="lang" name="lang" :items="languages" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-abjad-arabic" v-model="lang"
                label="لغة المحتوى" :error-messages="errors.lang" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="8" class="pt-0">
              <v-text-field id="item_val" name="item_val" label="اسم اللجنة / الجمعية" v-model="item_val" prepend-icon="mdi-book-open-page-variant-outline"
                variant="underlined" :error-messages="errors.item_val"></v-text-field>
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

const url = route('staff_academic.index','training_courses')

const headers = [
  { title: 'اسم عضو هيئة التدريس', key: 'user.name', align: 'center' },
  { title: 'اسم اللجنة / الجمعية', key: 'item_val', align: 'center' },
  { title: 'لغة المحتوى', key: 'lang',width:200 },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();
interface FormFields {
  id: number,
  lang: number,
  college_id: number,
  department_id: number,
  user_id: number,
  item_val: string,
}

const validationSchema = toTypedSchema(zod.object({
    lang: zod.number({ required_error: "اختر لغة المحتوى" }).gte(0,{ message: 'اختر لغة المحتوى' }),
    college_id: zod.number({ required_error: "اختر اسم الكلية" }).positive({ message: "اختر اسم الكلية" }),
    department_id: zod.number({ required_error: "اختر اسم القسم" }).positive({ message: 'اختر اسم القسم' }).nullable(),
    item_val: zod.string({ required_error: "ادخل اسم اللجنة / الجمعية" }).trim().min(1, { message: 'ادخل اسم اللجنة / الجمعية' }),
    user_id: zod.number({ required_error: "اختر اسم عضو هيئة التدريس" }).positive({ message: "اختر اسم عضو هيئة التدريس" }),
  }));

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
const colleges = ref();
const departments = ref();
const users = ref();
  let languages = ref<Array<any>>([{'id':-1,'name':'اختر لغة المحتوى'},{'id':1,'name':'اللغة الانجليزية'},{'id':2,'name':'اللغة العربية'}]);
const deartment_disabled = ref(true);
const user_disabled = ref(true);

const { value: college_id } = useField('college_id');
const { value: department_id } = useField('department_id');
const { value: user_id } = useField('user_id');
const { value: lang } = useField('lang');
const { value: item_val } = useField('item_val');

const save = handleSubmit(async(values) => {

  let ErorrMsg = [
    {'field':'item_val','message':'اسم اللجنة / الجمعية موجود مسبقاً'},
  ]

  const formData = new FormData();
  formData.append("user_id", values.user_id.toString())
  formData.append("lang", values.lang.toString())
  formData.append("item_val", values.item_val.toString())

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
      item_val.value = item.item_val;
      deartment_disabled.value = user_disabled.value = false
      departments.value = [{ 'id': item.user.staff_latest.department_id, 'name': item.user.staff_latest.department.name }];
      users.value = [{ 'id': item.user.staff_latest.id, 'name': item.user.name }];
      setValues({
        college_id: item.user.staff_latest.department.college.id,
        department_id: item.user.staff_latest.department.id,
        user_id: item.user.staff_latest.id,
        lang: item.lang,
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
