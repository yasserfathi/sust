<template>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
      <Table ref="table" :id="id" :url="api_url" :headers="headers" toolbar_title="اضافة رؤساء الاقسام"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close"
        @resetFormFields="resetFormFields"
        transition="dialog-bottom-transition">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" :md="colleges && colleges.length > 1 ? 6 : 12" class="pt-0" v-if="authStore?.user?.role === 1" v-show="colleges && colleges.length > 1">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="college_id" @update:modelValue="getDepartments($event)" label="اسماء الكليات" :error-messages="errors.college_id" variant="outlined"></v-select>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-select id="department_id" name="department_id" :disabled="department_disabled" :items="departments"
                item-title="name" density="comfortable" item-value="id" prepend-icon="mdi-office-building"
                v-model="department_id" @update:modelValue="getUsers($event)" label="اسماء الاقسام"
                :error-messages="errors.department_id" variant="outlined"></v-select>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-select id="user_id" name="user_id" :disabled="user_disabled" :items="users" item-title="name"
                density="comfortable" item-value="id" prepend-icon="mdi-account-tie" v-model="user_id"
                label="اعضاء هيئة التدريس" :error-messages="errors.user_id" variant="outlined"></v-select>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <date-picker label="تاريخ التعيين" id="start_date" name="start_date" v-model="start_date"
                :error-messages="errors.start_date"></date-picker>
            </v-col>
          </v-row>
        </Form>
      </Table>
    </v-card>
  </v-container>
</template>

<script lang="ts" setup>
import { useAuthStore } from '../store/index';
const authStore = useAuthStore();
import { Form, useField, useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import { z as zod } from 'zod';
import axios from 'axios';

const api_url = route('head_of_department.index');
const url = api_url;

const headers = [
  { title: 'اسم الكلية', key: 'department.college.name', align: 'center' },
  { title: 'اسم القسم', key: 'department.name', align: 'center' },
  { title: 'اسم عضو هيئة التدريس', key: 'staff.user.name', align: 'center' },
  { title: 'تاريخ التعيين', key: 'start_date', align: 'center' },
  { title: 'تاريخ انتهاء التعيين', key: 'end_date', align: 'center' },
  { title: '', key: 'actions', sortable: false },
];

const table = ref<InstanceType<typeof Table>>();
const currentItem = ref<any>(null);

interface FormFields {
  id: number;
  college_id: number;
  department_id: number;
  user_id: number;
  start_date: any;
}

const validationSchema = computed(() => {
  const isSuperAdmin = authStore?.user?.role === 1;
  return toTypedSchema(zod.object({
    college_id: isSuperAdmin ? zod.number({ required_error: "اختر اسم الكلية" }).positive({ message: "اختر اسم الكلية" }) : zod.any().nullish(),
    department_id: isSuperAdmin ? zod.number({ required_error: "اختر اسم القسم" }).positive({ message: 'اختر اسم القسم' }) : zod.any().nullish(),
    user_id: isSuperAdmin ? zod.number({ required_error: "اختر اسم عضو هيئة التدريس" }).positive({ message: "اختر اسم عضو هيئة التدريس" }) : zod.any().nullish(),
    start_date: zod.any().refine(val => val !== null && val !== undefined && val !== '', { message: "اختر تاريخ التعيين للقسم" }),
  }));
});

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
const colleges = ref<Array<{ id: number, name: string }>>([]);
const departments = ref<Array<{ id: number, name: string }>>([]);
const users = ref<Array<{ id: number, name: string }>>([]);

const department_disabled = ref(true);
const user_disabled = ref(true);

const { value: college_id } = useField<number>('college_id');
const { value: department_id } = useField<number>('department_id');
const { value: user_id } = useField<number>('user_id');
const { value: start_date } = useField<any>('start_date');

start_date.value = new Date();

let ErorrMsg = [
  { 'field': 'department_id', 'message': 'البيانات موجودة مسبقا' },
  { 'field': 'user_id', 'message': 'اختر عضو هيئة التدريس' },
  { 'field': 'start_date', 'message': 'تاريخ التعيين غير صحيح' },
  { 'field': 'end_date', 'message': 'تاريخ التعيين أقل من تاريخ التعيين السابق' }
];

const save = handleSubmit(async (values: any) => {
  let temp: Array<any> = [];
  if (table.value) {
    table.value.SubmitLoading = true;
    if (values.start_date) {
      const d = new Date(values.start_date);
      values.start_date = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
    }
    try {
      let result;
      if (id.value > -1 && !isMatch(currentItem.value, values)) {
        result = await axios.put(`${url}/${id.value}`, values);
      } else if (id.value === -1) {
        result = await axios.post(url, values);
      }

      if (result && result.data && result.data.message == 'The new start date is earlier than the previous one') {
        temp.push({ 'field': 'start_date', 'message': 'تاريخ التعيين الجديد أسبق من تاريخ التعيين السابق' });
      } else if (result && result.data && result.data.message) {
        if (typeof result.data.message === 'object') {
          Object.entries(result.data.message).forEach(([field, msg]) => {
            const found = ErorrMsg.find((a: any) => a.field == field);
            if (found) {
              temp.push(toRaw(found));
            } else {
              temp.push({ field, message: Array.isArray(msg) ? msg[0] : msg });
            }
          });
        }
      }
      if (result && result.data) {
        table.value.PopulateTable(result.data.status, temp);
      }
    } catch (error) {
      console.error('Error saving data:', error);
    } finally {
      if (table.value) {
        table.value.SubmitLoading = false;
      }
    }
  }
});

function isMatch(item1: any, item2: any): boolean {
  return JSON.stringify(item1) === JSON.stringify(item2);
}

function editItem(item: any) {
  if (!table.value) return;

  item = toRaw(item);
  currentItem.value = item;
  table.value.disableEditButton = !table.value.disableEditButton;

  if (table.value.disableEditButton) {
    id.value = item.id;
    axios.get(`${api_url}/${item.id}`).then(async response => {
      item = response.data.result;
      department_disabled.value = user_disabled.value = false;
      
      const collegeId = item.department?.college?.id;
      const deptId = item.department?.id;
      const usrId = item.staff?.user?.id ?? item.user_id;

      if (collegeId) {
        await getDepartments(collegeId, true);
      }
      if (deptId) {
        await getUsers(deptId, true);
      }
      start_date.value = new Date(item.start_date);

      setValues({
        college_id: collegeId,
        department_id: deptId,
        user_id: usrId,
      });

      table.value.disableEditButton = false;
      table.value.dialog = true;
    }).catch(error => {
      console.error('Error fetching item:', error);
      table.value.disableEditButton = false;
    });
  }
}

function resetFormFields() {
  id.value = -1;
  currentItem.value = null;
  resetForm();
  start_date.value = new Date();
  if (colleges.value && colleges.value.length === 1) {
    college_id.value = colleges.value[0].id;
    getDepartments(colleges.value[0].id);
  }
}

function close() {
  nextTick(() => {
    id.value = -1;
    currentItem.value = null;

    if (departments.value) {
      departments.value = [{ id: -1, name: 'اختر القسم' }];
    }
    department_id.value = -1;

    if (users.value) {
      users.value = [{ id: -1, name: 'اختر عضو هيئة التدريس' }];
    }
    user_id.value = -1;

    department_disabled.value = user_disabled.value = true;
    resetForm();
    start_date.value = new Date();
    if (colleges.value && colleges.value.length === 1) { 
      college_id.value = colleges.value[0].id; 
      getDepartments(colleges.value[0].id);
    }
  });

  if (table.value) {
    table.value.disableEditButton = false;
    table.value.dialog = false;
  }
}

async function getColleges() {
  try {
    const response = await axios.get(route("colleges.list"));
    colleges.value = response.data.colleges;
    if (colleges.value && colleges.value.length === 1) {
      college_id.value = colleges.value[0].id;
      getDepartments(colleges.value[0].id);
    }
  } catch (error) {
    console.error('Error fetching colleges:', error);
  }
}

async function getDepartments(college_id: number, skipReset = false) {
  try {
    const response = await axios.post(route("departments.list"), { college_id });

    if (!response.data.departments || response.data.departments.length === 0) {
      departments.value = [{ id: -1, name: 'اختر القسم' }];
      if (!skipReset) department_id.value = -1;
      department_disabled.value = true;

      users.value = [{ id: -1, name: 'اختر عضو هيئة التدريس' }];
      if (!skipReset) user_id.value = -1;

      user_disabled.value = true;
    } else {
      departments.value = [{ id: -1, name: 'اختر القسم' }, ...response.data.departments];
      if (!skipReset) department_id.value = -1;
      department_disabled.value = false;
    }
  } catch (error) {
    console.error('Error fetching departments:', error);
  }
}

async function getUsers(department_id: number, skipReset = false) {
  try {
    const response = await axios.post(route("users.list"), { department_id });
    if (!response.data.users || response.data.users.length === 0) {
      users.value = [{ id: -1, name: 'اختر عضو هيئة التدريس' }];
      if (!skipReset) user_id.value = -1;
      user_disabled.value = true;
    } else {
      users.value = [{ id: -1, name: 'اختر عضو هيئة التدريس' }, ...response.data.users];
      if (!skipReset) user_id.value = -1;
      user_disabled.value = false;
    }
  } catch (error) {
    console.error('Error fetching users:', error);
  }
}

onBeforeMount(() => {
  getColleges();
});
</script>