<template>
  <v-data-table :headers="headers" :items="userData" transition="dialog-bottom-transition" hide-default-footer
    disable-pagination hide-no-data hover>
    <template v-slot:top>
      <v-toolbar>
        <v-toolbar-title>البيانات الوظيفية</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" persistent max-width="1100px">
          <template v-slot:activator="{ props }">
            <v-btn class="mb-2 bg-red" prepend-icon="mdi-plus" v-bind="props" @click="openDialog" ripple rounded="xl">
              اضافة بيانات
            </v-btn>
          </template>
          <v-card>
            <v-toolbar class="bg-red-darken-1" density="compact">
              <v-toolbar-title><v-icon end :icon="formIcon"></v-icon> {{ formTitle }}</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn icon @click="close(), dialog = false">
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-toolbar>
            <v-card-text>
              <v-container>
                <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
                  <v-row>
                    <v-col cols="12" md="3">
                      <v-select id="college_id" name="college_id" :items="colleges" item-title="name" item-value="id"
                        density="comfortable" prepend-icon="mdi-bank" v-model="college_id" label="اسماء الكليات"
                        :error-messages="errors.college_id" variant="underlined"></v-select>
                    </v-col>
                    <v-col cols="12" md="3">
                      <v-select id="department_id" name="department_id" :disabled="deartment_disabled"
                        :items="departments" item-title="name" item-value="id" density="comfortable"
                        prepend-icon="mdi-bank" v-model="department_id" label="اسماء الاقسام"
                        :error-messages="errors.department_id" variant="underlined"></v-select>
                    </v-col>
                    <v-col cols="12" md="3">
                      <v-select id="rank" name="rank" :items="ranks" item-title="name" density="comfortable"
                        item-value="id" prepend-icon="mdi-bank" v-model="rank" label="الدرجات العلمية باللغة العربية"
                        :error-messages="errors.rank" variant="underlined"></v-select>
                    </v-col>
                    <v-col cols="12" md="3">
                      <v-select id="rank_en" name="rank_en" :items="ranks_en" item-title="name" density="comfortable"
                        item-value="id" prepend-icon="mdi-bank" v-model="rank_en" label="الدرجات العلمية بالانجليزية"
                        :error-messages="errors.rank_en" variant="underlined"></v-select>
                    </v-col>
                    <v-col cols="12" md="3">
                      <v-select id="job_title" name="job_title" :items="job_titles" item-title="name"
                        density="comfortable" item-value="id" prepend-icon="mdi-bank" v-model="job_title"
                        label="المسمى الوظيفي باللغة العربية" :error-messages="errors.job_title"
                        variant="underlined"></v-select>
                    </v-col>
                    <v-col cols="12" md="3">
                      <v-select id="job_title_en" name="job_title_en" :items="job_titles_en" item-title="name"
                        density="comfortable" item-value="id" prepend-icon="mdi-bank" v-model="job_title_en"
                        label="المسمى الوظيفي بالانجليزية" :error-messages="errors.job_title_en"
                        variant="underlined"></v-select>
                    </v-col>
                    <v-col cols="12" md="4">
                      <date-picker label="تاريخ التعيين" id="hire_date" name="hire_date" v-model="hire_date"
                        :error-messages="errors.hire_date"></date-picker>
                    </v-col>
                    <v-col cols="12" md="3">
                      <v-text-field id="specialty" name="specialty" label="التخصص العام باللغة العربية"
                        v-model="specialty" variant="underlined" :error-messages="errors.specialty"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="3">
                      <v-text-field id="specialty_en" name="specialty_en" label="التخصص العام بالانجليزية"
                        v-model="specialty_en" variant="underlined"
                        :error-messages="errors.specialty_en"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="3">
                      <v-text-field id="subspecialty" name="subspecialty" label="التخصص الدقيق باللغة العربية"
                        v-model="subspecialty" variant="underlined"
                        :error-messages="errors.subspecialty"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="3">
                      <v-text-field id="subspecialty_en" name="subspecialty_en" label="التخصص الدقيق باللغة الانجليزية"
                        v-model="subspecialty_en" variant="underlined"
                        :error-messages="errors.subspecialty_en"></v-text-field>
                    </v-col>
                  </v-row>
                </Form>
                <div class="mt-5" cols="12">
                  <v-btn color="green-darken-1" variant="elevated" type="submit" :loading="SubmitLoading" @click="save"
                    ripple rounded="xl">
                    {{ btnText }}
                  </v-btn>
                  <v-btn color="grey-darken-1" class="mr-1" variant="elevated" @click="close" ripple rounded="xl">
                    الغاء
                  </v-btn>
                </div>
              </v-container>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:[`item.actions`]="{ item, index }">
      <v-icon size="small" color="success" class="me-2" @click="editItem(item)" :disabled="disableEditButton"
        :key="index" icon="mdi-pencil-outline" />
      <v-icon size="small" color="red" @click="deleteItem(item['id'])" icon="mdi-trash-can-outline" />
    </template>
  </v-data-table>
</template>
<script lang="ts" setup>
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import { defineAsyncComponent, nextTick, onMounted, computed, ref, toRaw, watch } from 'vue';
const datePicker = defineAsyncComponent(() => import('../components/DatePicker.vue'))
import axios from 'axios';
import { isMatch } from 'lodash';
import Swal from 'sweetalert2';

interface Props {
  user_id?: number;
}

const props = defineProps<Props>();
const url = route('staff_employ.index')
const headers = [
  { title: 'الكلية', key: 'department.college.name' },
  { title: 'القسم', key: 'department.name' },
  { title: 'الدرجة العلمية', key: 'rank' },
  { title: 'المسمى الوظيفي', key: 'job_title' },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();

interface FormFields {
  college_id: number;
  department_id: number;
  job_title: string;
  job_title_en: string;
  rank: string;
  rank_en: string;
  hire_date: any;
  specialty: string;
  subspecialty: string;
  specialty_en: string;
  subspecialty_en: string;
}

const validationSchema = toTypedSchema(
  zod.object({
    college_id: zod.number({ required_error: "اختر اسم الكلية" }),
    department_id: zod.number({ required_error: "اختر اسم القسم" }),
    rank: zod.string({ required_error: "اختر الدرجة العلمية" }).min(1, { message: "اختر الدرجة العلمية" }),
    rank_en: zod.string({ required_error: "اختر الدرجة العلمية بالانجليزية" }).min(1, { message: "اختر الدرجة العلمية بالانجليزية" }),
    job_title: zod.string({ required_error: "اختر المسمى الوظيفي" }).min(1, { message: "اختر المسمى الوظيفي" }),
    job_title_en: zod.string({ required_error: "اختر المسمى الوظيفي بالانجليزية" }).min(1, { message: "اختر المسمى الوظيفي بالانجليزية" }),
    hire_date: zod.coerce.date({ required_error: "اختر تاريخ التعيين", invalid_type_error: "تأكد من تاريخ التعيين" }),
    specialty: zod.string().nullish(),
    subspecialty: zod.string().nullish(),
    specialty_en: zod.string().nullish(),
    subspecialty_en: zod.string().nullish(),
  })
);

const { handleSubmit, resetForm, errors, setValues } = useForm<FormFields>({ validationSchema });

const id = ref(-1);
let colleges = ref([{}]);
let userData = ref([]);
const departments = ref();
let ranks = ref<Array<any>>([{ 'id': '', 'name': 'اختر الدرجة العلمية' },
{ 'id': 'Bachelor', 'name': 'بكالوريوس' },
{ 'id': 'Master', 'name': 'ماجستير' },
{ 'id': 'Doctor', 'name': 'دكتوراه' },
{ 'id': 'Professor', 'name': 'الاستاذ' }]);

let job_titles = ref<Array<any>>([{ 'id': '', 'name': 'اختر المسمى الوظيفي' },
{ 'id': 'Teaching assistant', 'name': 'مساعد تدريس' },
{ 'id': 'Lecturer', 'name': 'محاضر' },
{ 'id': 'Assistant Professor', 'name': 'استاذ مساعد' },
{ 'id': 'Associate Professor', 'name': 'استاذ مشارك' },
{ 'id': 'Professor', 'name': 'الاستاذ' }]);

let ranks_en = ref<Array<any>>([{ 'id': '', 'name': 'Select a rank' },
{ 'id': 'Bachelor', 'name': 'Bachelor' },
{ 'id': 'Master', 'name': 'Master' },
{ 'id': 'Doctor', 'name': 'Doctor' },
{ 'id': 'Professor', 'name': 'Professor' }]);

let job_titles_en = ref<Array<any>>([{ 'id': '', 'name': 'Select a job title' },
{ 'id': 'Teaching assistant', 'name': 'Teaching assistant' },
{ 'id': 'Lecturer', 'name': 'Lecturer' },
{ 'id': 'Assistant Professor', 'name': 'Assistant Professor' },
{ 'id': 'Associate Professor', 'name': 'Associate Professor' },
{ 'id': 'Professor', 'name': 'Professor' }]);

let currentItem = {};
const dialog = ref(false);
const openDialog = ref(false);
const disableEditButton = ref(false);
const deartment_disabled = ref(true);
const SubmitLoading = ref(false);

const { value: college_id } = useField('college_id');
const { value: department_id } = useField('department_id');
const { value: hire_date } = useField('hire_date');
const { value: rank } = useField('rank');
const { value: rank_en } = useField('rank_en');
const { value: job_title } = useField('job_title');
const { value: job_title_en } = useField('job_title_en');
const { value: specialty } = useField('specialty');
const { value: subspecialty } = useField('subspecialty');
const { value: specialty_en } = useField('specialty_en');
const { value: subspecialty_en } = useField('subspecialty_en');

const save = handleSubmit(async (values) => {
  values.hire_date = new Date(values.hire_date.toString()).toLocaleDateString('en-US')
  values = Object.assign(values, { user_id: props.user_id?.toString() });
  if (id.value > -1 && isMatch(currentItem, values) != true) {
    await axios.put(url + "/" + id.value, values)
  }
  else if (id.value === -1) {
    await axios.post(url, values);
  }
  getUserData();
  Swal.fire({ title: 'تمت العملية بنجاح', icon: 'success', confirmButtonColor: '#198754', confirmButtonText: "موافق", timer: 1500 });
  close();
});

// function editItem(item: any) {
//   currentItem = item = JSON.parse(JSON.stringify(item));
//   id.value = item.id;
//   setValues({
//     college_id: item.college_id,
//     title: item.title,
//     title_en: item.title_en,
//     active: item.active,
//   })
//   table.value.dialog = true
// }

function editItem(item: any) {
  currentItem = item = toRaw(item);
  disableEditButton.value = !disableEditButton.value;
  if (disableEditButton.value == true) {
    id.value = item.id;
    axios.get(url + '/' + id.value).then(response => {
      item = response.data.result;
      deartment_disabled.value = false;
      setValues({
        department_id: item[0].department_id || '',
        college_id: item[0].department?.college?.id || '',
        rank: item[0].rank || '',
        rank_en: item[0].rank_en || '',
        job_title: item[0].job_title || '',
        job_title_en: item[0].job_title_en || '',
        hire_date: new Date(item[0].hire_date),
        specialty: item[0].specialty || '',
        subspecialty: item[0].subspecialty || '',
        specialty_en: item[0].specialty_en || '',
        subspecialty_en: item[0].subspecialty_en || ''
      })
      disableEditButton.value = false
      dialog.value = true
    });
  }
}

function deleteItem(id: number) {
  Swal.fire({
    title: 'تأكيد عملية الحذف ؟',
    icon: 'warning',
    confirmButtonColor: '#198754',
    cancelButtonColor: '#d33',
    confirmButtonText: 'موافق',
    cancelButtonText: 'الغاء',
    showCancelButton: true,
    showCloseButton: true
  }).then((result: { isConfirmed: any; }) => {
    if (result.isConfirmed) {
      axios.post(url + '/' + id, { _method: 'DELETE' }).then(() => {
        getUserData();
        Swal.fire({ title: 'تمت العملية بنجاح', icon: 'success', confirmButtonColor: '#198754', confirmButtonText: "موافق", timer: 1500 });
      });
    }
  });
}

function close() {
  nextTick(() => {
    id.value = -1
    resetForm();
  });
  dialog.value = false
}

async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
  });
}

async function getUserData() {
  if (!props.user_id || props.user_id <= 0) return;
  try {
    const response = await axios.get(route('staff_employ.show', props.user_id));
    userData.value = response.data.result;
  } catch (error) {
    console.error('Error fetching staff data:', error);
  }
}

watch(() => props.user_id, () => {
  getUserData();
});

watch(() => college_id.value, (newVal) => {
  if (newVal) {
    axios.post(route("departments.list"), { college_id: newVal }).then(response => {
      departments.value = response.data.departments;
      deartment_disabled.value = false;
    });
  } else {
    departments.value = [];
    deartment_disabled.value = true;
  }
});

watch(() => rank.value, (newVal) => {
  if (newVal && newVal !== rank_en.value) {
    rank_en.value = newVal;
  }
});

watch(() => rank_en.value, (newVal) => {
  if (newVal && newVal !== rank.value) {
    rank.value = newVal;
  }
});

watch(() => job_title.value, (newVal) => {
  if (newVal && newVal !== job_title_en.value) {
    job_title_en.value = newVal;
  }
});

watch(() => job_title_en.value, (newVal) => {
  if (newVal && newVal !== job_title.value) {
    job_title.value = newVal;
  }
});

const formIcon = computed(() => {
  return id.value === -1 ? 'mdi-plus-circle' : 'mdi-pencil-circle'
});

const btnText = computed(() => {
  return id.value != -1 ? 'تعديل' : 'حفظ'
});

const formTitle = computed(() => {
  return id.value === -1 ? 'اضافة بيانات' : 'تعديل بيانات'
});

onMounted(() => {
  getColleges();
  getUserData();
});
</script>
