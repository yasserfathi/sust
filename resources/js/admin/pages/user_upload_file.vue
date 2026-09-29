<template>
  <div>
  <v-container fluid class="px-md-8 px-4">
    <v-card>
      <Table :id="id" ref="table" :showSearchInput="false" :showTable="false"
        toolbar_title="اضافة مستخدمين من ملف" @setFieldError="setFieldError" @save="save" @close="close"
        @resetFormFields="resetFormFields" transition="dialog-bottom-transition">
        <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
          <v-row>
            <v-col cols="12" v-show="colleges && colleges.length > 1">
              <v-select id="college_id" name="college_id" :items="colleges" item-title="name" item-value="id"
                v-model="college_id" label="اسماء الكليات" :error-messages="errors.college_id" density="comfortable"></v-select>
            </v-col>
            <v-col cols="12">
              <v-file-input type="file" id="file" name="file" v-model="file" show-size chips label="اختر الملف المرفق" accept='.csv, .xlsx, .xls' variant="outlined" :error-messages="errors.file" density="comfortable"></v-file-input>
            </v-col>
          </v-row>
          <div id="buttons"></div>
        </Form>
      </Table>
    </v-card>

    <v-dialog v-model="showResultModal" max-width="900px" persistent>
      <v-card>
        <v-card-title class="text-h5 bg-primary text-white pb-3 pt-3 px-4">نتائج الرفع</v-card-title>
        <v-card-text class="pt-4 px-4" style="max-height: 70vh; overflow-y: auto;">
          <h3 class="text-success mb-2 font-weight-bold">تمت الإضافة/التحديث بنجاح ({{ successRecords.length }})</h3>
          <v-table density="compact" class="mb-5 elevation-1">
            <thead>
              <tr>
                <th class="text-right">الاسم</th>
                <th class="text-right">الرقم الجامعي</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in successRecords" :key="item.univ_no">
                <td>{{ item.name }}</td>
                <td>{{ item.univ_no }}</td>
              </tr>
              <tr v-if="successRecords.length === 0">
                <td colspan="2" class="text-center py-3 text-grey">لا توجد سجلات ناجحة</td>
              </tr>
            </tbody>
          </v-table>

          <h3 class="text-error mb-2 font-weight-bold text-red">فشل الإدراج ({{ failedRecords.length }})</h3>
          <v-table density="compact" class="elevation-1">
            <thead>
              <tr>
                <th class="text-right">الاسم</th>
                <th class="text-right">الرقم الجامعي</th>
                <th class="text-right">السبب</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, i) in failedRecords" :key="i">
                <td>{{ item.name }}</td>
                <td>{{ item.univ_no }}</td>
                <td class="text-red">{{ item.reason }}</td>
              </tr>
              <tr v-if="failedRecords.length === 0">
                <td colspan="3" class="text-center py-3 text-grey">لا توجد أخطاء</td>
              </tr>
            </tbody>
          </v-table>
        </v-card-text>
        <v-card-actions class="px-4 pb-4">
          <v-spacer></v-spacer>
          <v-btn color="primary" variant="elevated" @click="closeResultModal">إغلاق</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-container>
  </div>
</template>

<script lang="ts" setup>
import { Form, useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import axios from 'axios';

const url = route('user-upload-data.store')

const table = ref();

interface FormFields {
  id: number;
  college_id: number;
  file: any;
}

const ACCEPTED_FILE_TYPES = [
  'text/csv',
  'application/vnd.ms-excel',
  'application/csv',
  'text/x-csv',
  'application/x-csv',
  'text/comma-separated-values',
  'text/x-comma-separated-values',
  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
]

const validationSchema = toTypedSchema(
  zod.object({
    college_id: zod.number({ required_error: "اختر اسم الكلية" }),
    file: zod.any().refine((val) => {
      if (!val) return false;
      if (Array.isArray(val) && val.length > 0) return true;
      if (val instanceof File) return true;
      return false;
    }, "اختر الملف المرفق")
      .refine((val) => {
        if (!val || (Array.isArray(val) && val.length === 0)) return true;
        const f = Array.isArray(val) ? val[0] : val;
        return f && (f.size || 0) <= 5 * 1024 * 1024;
      }, `الحد الأقصى لحجم الملف هو 5 ميجابايت`)
      .refine((val) => {
        if (!val || (Array.isArray(val) && val.length === 0)) return true;
        const f = Array.isArray(val) ? val[0] : val;
        if (!f) return false;
        return ACCEPTED_FILE_TYPES.includes(f.type) || f.name?.endsWith('.xlsx') || f.name?.endsWith('.csv') || f.name?.endsWith('.xls');
      }, "يتم دعم الملفات بصيغة .csv و .xlsx فقط"),
  })
);

const { handleSubmit, resetForm, errors, setFieldError } = useForm<FormFields>({
  validationSchema,
});

const id = ref(-1);
let colleges = ref([{}]);
const fileInput = ref();
const { value: college_id, } = useField('college_id');
const { value: file } = useField('file');

const showResultModal = ref(false);
const successRecords = ref<any[]>([]);
const failedRecords = ref<any[]>([]);

function closeResultModal() {
  showResultModal.value = false;
  successRecords.value = [];
  failedRecords.value = [];
}

let temp: Array<any> = [];

let ErorrMsg = [{ 'field': 'file', 'message': 'يتم دعم الملفات بصيغة .csv و .xlsx فقط' }]

const save = handleSubmit(async (values) => {
  const formData = new FormData();
  formData.append("college_id", values.college_id.toString())

  if (file.value) {
    if (file.value) {
    const f = Array.isArray(file.value) ? file.value[0] : file.value;
    if (f) formData.append("file", f);
  } else if (file.value instanceof File) {
      formData.append("file", file.value);
    }
  }

  let temp: Array<any> = [];
  table.value.SubmitLoading = true;
  try {
    const result = await axios.post(url, formData, { headers: { 'content-type': 'multipart/form-data' } });
    successRecords.value = result.data.success_records || [];
    failedRecords.value = result.data.failed_records || [];
    showResultModal.value = true;
    
    if (typeof result.data.message === 'object') {
      Object.entries(result.data.message).forEach((item) => {
        temp.push(toRaw(ErorrMsg.find((a: any) => a.field == item[0])));
      });
    }
    table.value.PopulateTable(result.data.status, temp);
  } catch (error: any) {
    console.error("Save failed", error);
    if (error.response && error.response.status === 422) {
      let msg = error.response.data.message + '\n\n';
      if (error.response.data.accepted_columns) {
        msg += 'الأعمدة المعتمدة هي:\n' + error.response.data.accepted_columns.join(' | ');
      }
      alert(msg);
    } else {
      alert('حدث خطأ غير متوقع أثناء الرفع');
    }
  } finally {
    table.value.SubmitLoading = false;
  }
});

function resetFormFields() {
  id.value = -1
  resetForm();
  if (colleges.value && colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
}



function close() {
  nextTick(() => {
    resetFormFields();
  });
  table.value.dialog = false
}

async function getColleges() {
  await axios.get(route("colleges.list")).then(response => {
    colleges.value = response.data.colleges;
  if (colleges.value.length === 1) { college_id.value = colleges.value[0].id; }
    });
}

onMounted(() => {
  getColleges();
});
</script>
