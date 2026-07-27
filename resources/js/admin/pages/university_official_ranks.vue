<template>
  <v-container>
    <v-card>
      <Table ref="table" :id="id" :url="api_url" :headers="headers" toolbar_title="اضافة منصب"
        @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close" transition="dialog-bottom-transition">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" md="12" class="pt-0">
              <v-select id="rank" name="rank" :items="ranksList" item-title="name_ar" density="comfortable"
                item-value="name" prepend-icon="mdi-badge-account" v-model="rank"
                label="المنصب" :error-messages="errors.rank" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-bank" v-model="college_id" @update:modelValue="getDepartments($event)"
                label="اسماء الكليات" :error-messages="errors.college_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-select id="department_id" name="department_id" :disabled="department_disabled" :items="departments" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-office-building" v-model="department_id" @update:modelValue="getUsers($event)"
                label="اسماء الاقسام" :error-messages="errors.department_id" variant="underlined"></v-select>
            </v-col>
            <v-col cols="12" md="6" class="pt-0">
              <v-select id="user_id" name="user_id" :disabled="user_disabled" :items="users" item-title="name" density="comfortable"
                item-value="id" prepend-icon="mdi-account-tie" v-model="user_id"
                label="اعضاء هيئة التدريس" :error-messages="errors.user_id" variant="underlined"></v-select>
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
import { defineAsyncComponent, ref, onBeforeMount, nextTick, toRaw } from 'vue';
import { Form, useField, useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import { z as zod } from 'zod';
import axios from 'axios';

const Table = defineAsyncComponent(() => import('../components/Table.vue'));
const DatePicker = defineAsyncComponent(() => import('../components/DatePicker.vue'));

const api_url = route('university_official_ranks.index'); // Ensure this route exists in web.php
const url = api_url; 
const headers = [
  { title: 'المنصب', key: 'name_ar', align: 'center' },
  { title: 'اسم الكلية', key: 'college.name', align: 'center' },
  { title: 'اسم عضو هيئة التدريس', key: 'user.name', align: 'center' },
  { title: 'تاريخ التعيين', key: 'start_date', align: 'center' },
  { title: 'تاريخ انتهاء التعيين', key: 'end_date', align: 'center' },
  { title: '', key: 'actions', sortable: false },
];

const ranksList = [
  { name: 'Deputy Vice Chancellor', name_ar: 'نائب المدير' },
  { name: 'Secretary of Academic Affairs', name_ar: 'أمين الشؤون العلمية' },
  { name: 'University Principal', name_ar: 'وكيل الجامعة' },
  { name: 'Chairholder, UNESCO Chair for Women, Science & Technology', name_ar: 'رئيسة كرسي اليونسكو للمرأة والعلوم والتكنولوجيا' },
];

const table = ref<InstanceType<typeof Table>>();
const currentItem = ref<any>(null);

interface FormFields {
  id: number;
  rank: string;
  college_id: number;
  user_id: number;
  start_date: Date;
}

const validationSchema = toTypedSchema(zod.object({
  rank: zod.string({ required_error: "اختر المنصب" }).min(1, { message: "اختر المنصب" }),
  college_id: zod.number({ required_error: "اختر اسم الكلية" }).positive({ message: "اختر اسم الكلية" }),
  user_id: zod.number({ required_error: "اختر اسم عضو هيئة التدريس" }).positive({ message: "اختر اسم عضو هيئة التدريس" }),
  start_date: zod.date({ required_error: "اختر تاريخ التعيين" }),
}));

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
const colleges = ref<Array<{id: number, name: string}>>([]);
const departments = ref<Array<{id: number, name: string}>>([]);
const users = ref<Array<{id: number, name: string}>>([]);

const user_disabled = ref(true);
const department_disabled = ref(true);

const { value: rank } = useField<string>('rank');
const { value: college_id } = useField<number>('college_id');
const { value: department_id } = useField<number>('department_id');
const { value: user_id } = useField<number>('user_id');
const { value: start_date } = useField<Date>('start_date');

start_date.value = new Date();

let ErorrMsg = [{'field':'college_id','message':'البيانات موجودة مسبقا'},
                {'field':'end_date','message':'تاريخ  التعيين أقل من تاريخ التعيين السابق'}]

const save = handleSubmit(async (values: any) => {
  let temp: Array<any> = [];
  if (table.value) {
    table.value.SubmitLoading = true;
    values.start_date = new Date(values.start_date.toString()).toLocaleDateString('sv-SE');
    
    // Map the selected rank to name and name_ar (or name_en based on user prompt context)
    const selectedRank = ranksList.find(r => r.name === values.rank);
    if (selectedRank) {
      values.name = selectedRank.name;      // English name
      values.name_ar = selectedRank.name_ar; // Arabic name
    }
    
    try {
      let result;
      if (id.value > -1 && !isMatch(currentItem.value, values)) {
        result = await axios.put(`${url}/${id.value}`, values);
      } else if (id.value === -1) {
        result = await axios.post(url, values);
      }

      if (result && result.data && result.data.message == 'The new start date is earlier than the previous one') {
        temp.push({'field':'start_date','message':'تاريخ التعيين الجديد أسبق من تاريخ التعيين السابق' });
      } else if (result && result.data && result.data.message) {
        Object.entries(result.data.message).forEach((item) => {
          temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
        });
      }
      if (result && result.data) {
        table.value.PopulateTable(result.data.status, temp);
      }
    } catch (error) {
      console.error('Error saving data:', error);
      // Handle error appropriately
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
    axios.get(`${api_url}/${item.id}`).then(response => {
      item = response.data.result;
      department_disabled.value = user_disabled.value = false;
      departments.value = [{ id: item.user.staff_latest.department.id, name: item.user.staff_latest.department.name }];
      users.value = [{ id: item.user.id, name: item.user.name }];
      start_date.value = new Date(item.start_date);
      
      setValues({
        rank: item.name,
        college_id: item.college.id,
        department_id: item.user.staff_latest.department.id,
        user_id: item.user.id,
      });
      
      table.value.disableEditButton = false;
      table.value.dialog = true;
    }).catch(error => {
      console.error('Error fetching item:', error);
      table.value.disableEditButton = false;
    });
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
  } catch (error) {
    console.error('Error fetching colleges:', error);
  }
}

async function getDepartments(college_id: number) {
  try {
    const response = await axios.post(route("departments.list"), { college_id });
    
    if (!response.data.departments || response.data.departments.length === 0) {
      departments.value = [{ id: -1, name: 'اختر القسم' }];
      department_id.value = -1;
      department_disabled.value = true;
      
      users.value = [{ id: -1, name: 'اختر عضو هيئة التدريس' }];
      user_id.value = -1;
      
      user_disabled.value = true;
    } else {
      departments.value = [{ id: -1, name: 'اختر القسم' }, ...response.data.departments];
      department_id.value = -1;
      department_disabled.value = false;
    }
  } catch (error) {
    console.error('Error fetching departments:', error);
  }
}

async function getUsers(department_id: number) {
  try {
    const response = await axios.post(route("users.list"), { department_id });
    if (!response.data.users || response.data.users.length === 0) {
      users.value = [{ id: -1, name: 'اختر عضو هيئة التدريس' }];
      user_id.value = -1;
      user_disabled.value = true;
    } else {
      users.value = [{ id: -1, name: 'اختر عضو هيئة التدريس' }, ...response.data.users];
      user_id.value = -1;
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
