<template>
  <v-data-table :headers="headers" :items="userData" transition="dialog-bottom-transition" hide-default-footer
    disable-pagination hide-no-data hover>
    <template v-slot:top>
      <v-toolbar>
        <v-toolbar-title>البيانات الوظيفية {{ props.user_name ? 'لـ ' + props.user_name : '' }}</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" persistent max-width="1100px">
          <template v-slot:activator="{ props }">
            <v-btn class="mb-2 bg-red" prepend-icon="mdi-plus" v-bind="props" ripple rounded="xl" @click="(e) => e.currentTarget.blur()">
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
              <v-container fluid class="px-md-8 px-4">
                <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
                  <v-row>
                    <v-col cols="12" md="6" v-show="colleges && colleges.length > 1">
                      <v-select id="college_id" name="college_id" :items="colleges" item-title="name" item-value="id"
                        prepend-icon="mdi-bank" v-model="college_id" label="اسماء الكليات"
                        :error-messages="errors.college_id" variant="outlined" density="comfortable"></v-select>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-select id="department_id" name="department_id" :disabled="deartment_disabled"
                        :items="departments" item-title="name" item-value="id" prepend-icon="mdi-bank"
                        v-model="department_id" label="اسماء الاقسام" :error-messages="errors.department_id"
                        variant="outlined" density="comfortable"></v-select>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-select id="grade" name="grade" :items="grades" item-title="name"
                        item-value="id" prepend-icon="mdi-bank" v-model="grade"
                        label="المسمى الوظيفي باللغة العربية" :error-messages="errors.grade"
                        variant="outlined" density="comfortable"></v-select>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-select id="grade_en" name="grade_en" :items="grades_en" item-title="name"
                        item-value="id" prepend-icon="mdi-bank" v-model="grade_en"
                        label="المسمى الوظيفي بالانجليزية" :error-messages="errors.grade_en"
                        variant="outlined" density="comfortable"></v-select>
                    </v-col>
                    <v-col cols="12">
                      <datePicker prepend-icon="mdi-calendar" id="hire_date" name="hire_date"
                        v-model:modelValue="hire_date" label="تاريخ التعيين" :error-messages="errors.hire_date"
                        variant="outlined"></datePicker>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field autofocus id="specialty" name="specialty" label="التخصص العام باللغة العربية"
                        v-model="specialty" variant="outlined" :error-messages="errors.specialty" density="comfortable"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field id="specialty_en" name="specialty_en" label="التخصص العام بالانجليزية"
                        v-model="specialty_en" variant="outlined"
                        :error-messages="errors.specialty_en" density="comfortable"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field id="subspecialty" name="subspecialty" label="التخصص الدقيق باللغة العربية"
                        v-model="subspecialty" variant="outlined"
                        :error-messages="errors.subspecialty" density="comfortable"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field id="subspecialty_en" name="subspecialty_en" label="التخصص الدقيق باللغة الانجليزية"
                        v-model="subspecialty_en" variant="outlined"
                        :error-messages="errors.subspecialty_en" density="comfortable"></v-text-field>
                    </v-col>
                  </v-row>
                  <div class="d-flex justify-start mt-6">
                    <v-btn color="green-darken-1" variant="elevated" @click="save" :loading="SubmitLoading" rounded="pill" class=" px-8"
 :disabled="!isFormValid">
 {{ btnText }}
 </v-btn>
            <v-btn color="grey-darken-1" variant="elevated" @click="close(), dialog = false" rounded="pill" class="ms-4 px-8">
 الغاء
 </v-btn>
                  </div>
                </Form>
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
import axios from 'axios';
import { isMatch } from 'lodash';
import Swal from 'sweetalert2';

interface Props {
  user_id?: number;
  user_name?: string;
}

const props = defineProps<Props>();
const url = route('staff_employ.index')
const headers = [
  { title: 'الكلية', key: 'department.college.name' },
  { title: 'القسم', key: 'department.name' },
  { title: 'المسمى الوظيفي', key: 'grade' },
  { title: '', key: 'actions', sortable: false },
];

const table = ref();

interface FormFields {
  college_id: number;
  department_id: number;
  grade: string;
  grade_en: string;
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
    grade: zod.string({ required_error: "اختر المسمى الوظيفي" }).min(1, { message: "اختر المسمى الوظيفي" }),
    grade_en: zod.string({ required_error: "اختر المسمى الوظيفي بالانجليزية" }).min(1, { message: "اختر المسمى الوظيفي بالانجليزية" }),
    hire_date: zod.any({ required_error: "اختر تاريخ التعيين", invalid_type_error: "تأكد من تاريخ التعيين" }),
    specialty: zod.string().nullish(),
    subspecialty: zod.string().nullish(),
    specialty_en: zod.string().nullish(),
    subspecialty_en: zod.string().nullish(),
  })
);

const { handleSubmit, resetForm, errors, setValues, meta } = useForm<FormFields>({ validationSchema });

const isFormValid = computed(() => {
  return meta.value.valid && Object.keys(errors.value).length === 0;
});

const id = ref(-1);
let colleges = shallowRef([{}]);
let userData = ref([]);
const departments = shallowRef([]);
let grades = ref<Array<any>>([{ 'id': '', 'name': 'اختر المسمى الوظيفي' },
{ 'id': 'مساعد تدريس', 'name': 'مساعد تدريس' },
{ 'id': 'محاضر', 'name': 'محاضر' },
{ 'id': 'استاذ مساعد', 'name': 'استاذ مساعد' },
{ 'id': 'استاذ مشارك', 'name': 'استاذ مشارك' },
{ 'id': 'استاذ', 'name': 'الاستاذ' }]);

let grades_en = ref<Array<any>>([{ 'id': '', 'name': 'Select a Grade' },
{ 'id': 'Teaching assistant', 'name': 'Teaching assistant' },
{ 'id': 'Lecturer', 'name': 'Lecturer' },
{ 'id': 'Assistant Professor', 'name': 'Assistant Professor' },
{ 'id': 'Associate Professor', 'name': 'Associate Professor' },
{ 'id': 'Professor', 'name': 'Professor' }]);

let currentItem = {};
const dialog = ref(false);
const disableEditButton = ref(false);
const deartment_disabled = ref(true);
const SubmitLoading = ref(false);

const { value: college_id } = useField('college_id');
const { value: department_id } = useField('department_id');
const { value: hire_date } = useField('hire_date');
const { value: grade } = useField('grade');
const { value: grade_en } = useField('grade_en');
const { value: specialty } = useField('specialty');
const { value: subspecialty } = useField('subspecialty');
const { value: specialty_en } = useField('specialty_en');
const { value: subspecialty_en } = useField('subspecialty_en');



const save = handleSubmit(async (values) => {
  SubmitLoading.value = true;
  try {
      if (typeof values.hire_date === 'string' && values.hire_date.includes('-')) {
    values.hire_date = values.hire_date;
  } else {
    const d = new Date(values.hire_date);
    values.hire_date = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
  }
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
  } catch (error) {
    console.error("Save failed", error);
    Swal.fire({
      title: 'خطأ!',
      text: 'حدث خطأ أثناء حفظ البيانات',
      icon: 'error',
      confirmButtonColor: '#d33',
      confirmButtonText: 'موافق'
    });
  } finally {
    SubmitLoading.value = false;
  }
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

async function editItem(item: any) {
  currentItem = item = toRaw(item);
  id.value = item.id;
  const collegeId = item.department?.college?.id || item.department?.college_id || '';
  if (collegeId) {
    try {
      const response = await axios.post(route("departments.list"), { college_id: collegeId });
      departments.value = response.data.departments;
      deartment_disabled.value = false;
    } catch (e) {
      console.error('Error fetching departments on edit:', e);
    }
  }
  setValues({
    department_id: item.department_id || '',
    college_id: collegeId,
    grade: item.grade || '',
    grade_en: item.grade_en || '',
    hire_date: item.hire_date ? new Date(item.hire_date) : '',
    specialty: item.specialty || '',
    subspecialty: item.subspecialty || '',
    specialty_en: item.specialty_en || '',
    subspecialty_en: item.subspecialty_en || ''
  })
  dialog.value = true;
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
    if (colleges.value && colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
  });
  dialog.value = false
}

async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
  if (colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
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

watch(() => grade.value, (newVal) => {
  if (!newVal) {
    grade_en.value = '';
    return;
  }
  const idx = grades.value.findIndex(item => item.id === newVal);
  if (idx !== -1 && grades_en.value[idx]) {
    if (grade_en.value !== grades_en.value[idx].id) {
      grade_en.value = grades_en.value[idx].id;
    }
  }
});

watch(() => grade_en.value, (newVal) => {
  if (!newVal) {
    grade.value = '';
    return;
  }
  const idx = grades_en.value.findIndex(item => item.id === newVal);
  if (idx !== -1 && grades.value[idx]) {
    if (grade.value !== grades.value[idx].id) {
      grade.value = grades.value[idx].id;
    }
  }
});

const formIcon = computed(() => {
  return id.value === -1 ? 'mdi-plus-circle' : 'mdi-pencil-circle'
});

const btnText = computed(() => {
  return id.value != -1 ? 'تعديل' : 'حفظ'
});

const formTitle = computed(() => {
  let title = id.value === -1 ? 'اضافة بيانات وظيفية' : 'تعديل البيانات الوظيفية';
  if (props.user_name) {
    title += ' لـ ' + props.user_name;
  }
  return title;
});

onMounted(() => {
  getColleges();
  getUserData();
});
</script>
