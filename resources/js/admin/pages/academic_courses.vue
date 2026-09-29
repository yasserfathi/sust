<template>
    <v-container fluid class="px-md-8 px-4">
        <v-card>
            <Table ref="table" :id="id" :url="url" :headers="headers" toolbar_title="المقررات الدراسية"
                @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close"
                :fullscreen="fullscreen" transition="dialog-bottom-transition">
                <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
                    <v-row>
                        <v-col cols="12" class="pt-0">
                            <v-autocomplete id="program_id" name="program_id" :items="programs"
                                item-title="program_name" density="comfortable" item-value="id"
                                prepend-icon="mdi-school" v-model="program_id" label="البرنامج الأكاديمي"
                                :error-messages="errors.program_id" variant="outlined"></v-autocomplete>
                        </v-col>
                        <v-col cols="12" md="6" class="pt-0">
                            <v-text-field id="course_title" name="course_title" label="اسم المقرر"
                                v-model="course_title" variant="outlined" class="text-caption"
                                prepend-icon="mdi-book-open-page-variant"
                                :error-messages="errors.course_title" density="comfortable"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6" class="pt-0">
                            <v-text-field id="course_code" name="course_code" label="رمز المقرر" v-model="course_code"
                                variant="outlined" class="text-caption" prepend-icon="mdi-barcode"
                                :error-messages="errors.course_code" density="comfortable"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6" class="pt-0">
                            <v-text-field id="course_hours" name="course_hours" label="الساعات" v-model="course_hours"
                                variant="outlined" class="text-caption" prepend-icon="mdi-clock"
                                :error-messages="errors.course_hours" density="comfortable"></v-text-field>
                        </v-col>
                        <v-col cols="6" md="3" class="pt-0">
                            <v-text-field id="year" name="year" label="السنة" v-model="year" variant="outlined"
                                type="number" class="text-caption" prepend-icon="mdi-calendar"
                                :error-messages="errors.year" density="comfortable"></v-text-field>
                        </v-col>
                        <v-col cols="6" md="3" class="pt-0">
                            <v-select id="semester" name="semester" :items="[1, 2, 3]" label="الفصل" v-model="semester"
                                variant="outlined" class="text-caption" prepend-icon="mdi-calendar-range"
                                :error-messages="errors.semester" density="comfortable"></v-select>
                        </v-col>
                        <v-col cols="12" md="6" class="pt-0">
                            <v-select id="lang" name="lang" :items="languages" item-title="name" density="comfortable"
                                item-value="id" prepend-icon="mdi-abjad-arabic" v-model="lang"
                                label="لغة المحتوى" :error-messages="errors.lang" variant="outlined"></v-select>
                        </v-col>
                        <v-col cols="12" :md="filePath != '' ? 5 : 6" class="pt-0">
                            <v-file-input type="file" id="course_file" name="course_file" v-model="course_file" show-size chips label="ملف المقرر (PDF, Docs, Slides)" accept='.pdf,.doc,.docx,.ppt,.pptx' variant="outlined" :error-messages="errors.course_file" density="comfortable"></v-file-input>
                        </v-col>
                        <v-col v-if="filePath != ''" cols="12" md="1" class="pt-7">
                            <a :href="BASE_URL + 'storage/' + filePath" target="_blank"
                                class="text-subtitle-2">الملف<v-icon icon="mdi-file-document"></v-icon></a>
                        </v-col>

                        <v-col cols="12" class="pt-0 mt-0">
                            <v-textarea label="وصف المقرر (اختياري)" v-model="course_desc" variant="outlined"
                                rows="2" density="comfortable"></v-textarea>
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

const url = route('academic_courses.index')
const headers = [
    { title: 'رمز المقرر', key: 'course_code' },
    { title: 'اسم المقرر', key: 'course_title' },
    { title: 'البرنامج', key: 'program.program_name' },
    { title: 'السنة', key: 'year' },
    { title: 'الفصل', key: 'semester' },
    { title: '', key: 'actions', value: 'id', sortable: false },
];

const table = ref();
interface FormFields {
    id: number,
    program_id: number,
    course_title: string;
    course_code: string;
    course_hours: string;
    year: number;
    semester: number;
    lang: number;
    course_file: any;
    course_desc: string;
}

const BASE_URL = window.location.origin + '/';
const MAX_FILE_SIZE = 10000000; // 10MB

const validationSchema = toTypedSchema(
    zod.object({
        program_id: zod.number({ required_error: "اختر البرنامج" }),
        course_title: zod.string({ required_error: "ادخل اسم المقرر" }).min(1),
        course_code: zod.string({ required_error: "ادخل رمز المقرر" }).min(1),
        course_hours: zod.string({ required_error: "ادخل الساعات" }),
        year: zod.number({ required_error: "ادخل السنة" }),
        semester: zod.number({ required_error: "اختر الفصل" }),
        lang: zod.number().default(1),
        course_desc: zod.string().optional().nullable(),
        course_file: zod.any().optional().nullable(),
    })
);

const { handleSubmit, resetForm, errors, setValues, setFieldError } = useForm<FormFields>({
    validationSchema,
});

const id = ref(-1);
let programs = ref<Array<any>>([]);

const fullscreen = ref(true);
const fileInput = ref();
const filePath = ref();

const { value: program_id } = useField('program_id');
const { value: course_title } = useField('course_title');
const { value: course_code } = useField('course_code');
const { value: course_hours } = useField('course_hours');
const { value: year } = useField('year');
const { value: semester } = useField('semester');
const { value: lang } = useField('lang');
const { value: course_desc } = useField('course_desc');


let ErorrMsg = 'المقرر موجود مسبقا'

const save = handleSubmit((values) => {
    const formData = new FormData();
    formData.append("program_id", values.program_id.toString())
    formData.append("course_title", values.course_title)
    formData.append("course_code", values.course_code)
    formData.append("course_hours", values.course_hours)
    formData.append("year", values.year.toString())
    formData.append("semester", values.semester.toString())
    formData.append("lang", values.lang ? values.lang.toString() : '1')
    if (values.course_desc) formData.append("course_desc", values.course_desc)

    if (file.value) {
    const f = Array.isArray(file.value) ? file.value[0] : file.value;
    if (f) formData.append("course_file", f);
  }

    if (id.value > -1) {
        formData.append('_method', 'put');
        axios.post(url + "/" + id.value, formData, { headers: { 'content-type': 'multipart/form-data' } }).then(result => {
            table.value.PopulateTable(result.data.status, 'course_title', ErorrMsg);
        })
    } else {
        axios.post(url, formData, { headers: { 'content-type': 'multipart/form-data' } }).then(result => {
            table.value.PopulateTable(result.data.status, 'course_title', ErorrMsg);
        });
    }
});

function editItem(item: any) {
    item = toRaw(item);
    table.value.disableEditButton = !table.value.disableEditButton;
    if (table.value.disableEditButton == true) {
        id.value = item.id;
        axios.get(url + '/' + item.id).then(response => {
            let data = response.data.result;
            id.value = data.course_id;
            filePath.value = data.course_file;

            setValues({
                program_id: data.program_id,
                course_title: data.course_title,
                course_code: data.course_code,
                course_hours: data.course_hours,
                year: data.year,
                semester: data.semester,
                lang: data.lang,
                course_desc: data.course_desc
            });

            table.value.disableEditButton = false
            table.value.dialog = true
        });
    }
}

function close() {
    nextTick(() => {
        id.value = -1
        resetForm();
        filePath.value = '';
    });
    table.value.disableEditButton = false;
    table.value.dialog = false;
}



async function getPrograms() {
    try {
        // Need endpoint to get all programs for dropdown. 
        // Using index with items=-1 or similar if supported, or a separate list endpoint
        // Assuming index endpoint supports items=-1 for all
        await axios.get(route('academic_courses.index', { 'items': 1000 })).then(response => { //academic_courses
            programs.value = response.data.result.data || [];
        });
    } catch (e) { console.error(e); }
}

onBeforeMount(() => {
    getPrograms();
});
</script>
