<template>
    <v-container>
        <v-card>
            <Table
                :id="id"
                ref="table"
                :url="url"
                :headers="headers"
                title="تنسيب الصفحات للمستخدمين"
                @edit-item="editItem"
                :fullscreen="fullscreen"
                :showInsertButton="false"
                :showButtons="false"
            >
                <user-pages-form :user_id="id" :image="imagePath" ref="imgInput"></user-pages-form>
            </Table>
        </v-card>
    </v-container>
</template>

<script lang="ts" setup>
import { defineAsyncComponent, onBeforeMount, ref, toRaw, watch } from "vue";
import { useField, useForm } from "vee-validate";
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from "zod";
import axios from "axios";
import { route } from "ziggy-js";

const Table = defineAsyncComponent(() => import("../components/Table.vue"));
const userPagesForm = defineAsyncComponent(() => import("../pages/userPagesForm.vue"));

const url = route('users.index')
const headers = [
  { title: 'اسم المستخدم', key: 'name' },
  { title: 'الكلية', key: 'staff_latest.department.college.name' },
  { title: 'القسم', key: 'staff_latest.department.name' },
  { title: '', key: 'thumb_img', sortable: false },
  { title: '', key: 'view', sortable: false },
];

const table = ref();
const imgInput = ref();
const imagePath = ref('');

interface FormFields {
    id: number;
    name: string;
    name_en: string;
    phone: string;
    email: string;
    university_no: string;
    role: number;
    img: any;
    active: boolean;
    method: any;
}

const ACCEPTED_IMAGE_TYPES = ["image/jpeg", "image/jpg", "image/png"];

const form_items_types = zod.object({
  name: zod.string({ required_error: "ادخل اسم المستخدم باللغة العربية" }).min(1, { message: "ادخل اسم المستخدم باللغة العربية" }),
  name_en: zod.string({ required_error: "ادخل اسم المستخدم باللغة الانجليزية" }).trim().min(1, { message: 'ادخل اسم المستخدم باللغة الانجليزية' }),
  phone: zod.string({ required_error: "ادخل رقم الهاتف" }).trim().min(1, { message: 'ادخل رقم الهاتف' }),
  email: zod.string({ required_error: "ادخل البريد الالكتروني" }).trim().min(1, { message: 'ادخل البريد الالكتروني' }),
  role: zod.number({ required_error: "اختر دور المستخدم" }),
  university_no: zod.string({ required_error: "ادخل الرقم الجامعي" }).trim().min(1, { message: 'ادخل الرقم الجامعي' })
});

const img_obj = zod.any().refine((files) => files?.length == 1, "اختر الصورة")
  .refine((files) => ACCEPTED_IMAGE_TYPES.includes(files?.[0]?.type), "يتم دعم  فقط .jpg, .jpeg and .png")
  .refine((files) => files?.[0]?.size <= 5 * 1024 * 1024, `الحد الأقصى لحجم الملف هو 5 ميجابايت`);

const validationSchema = toTypedSchema(zod.discriminatedUnion('method', [
  form_items_types.merge(zod.object({
    img: img_obj,
    method: zod.literal('post')
  })),
  form_items_types.merge(zod.object({
    img: img_obj.nullish(),
    method: zod.literal('put')
  })),
]));

const { handleSubmit, setValues } = useForm<FormFields>({ validationSchema });

const id = ref(-1);
const fullscreen = ref(true);
const { value: active } = useField<Boolean>('active');
const { value: method } = useField('method');
const SubmitLoading = ref(false);

let ErrorMsg = [
    { field: "img", message: "يتم دعم  فقط .jpg, .jpeg and .png" },
    { field: "email", message: "البريد الالكتروني موجود مسبقاً" },
];

const save = handleSubmit(async (values) => {
    const formData = new FormData();
    formData.append("album_id", "1");
    formData.append("name", values.name.toString());
    formData.append("name_en", values.name_en.toString());
    formData.append("email", values.email.toString());
    formData.append("univ_no", values.university_no.toString());
    formData.append("phone", values.phone.toString());
    formData.append("role", values.role.toString());
    formData.append("active", Number(active.value).toString());

    if (imgInput.value && imgInput.value.files && imgInput.value.files[0]) {
        formData.append("img", imgInput.value.files[0]);
    }

    let temp: Array<any> = [];
    SubmitLoading.value = true;
    if (id.value > -1) {
        formData.append("_method", "put");
        await axios
            .post(url + "/" + id.value, formData, {
                headers: { "content-type": "multipart/form-data" },
            })
            .then((result) => {
                Object.entries(result.data.message).forEach((item) => {
                    temp.push(toRaw(ErrorMsg.find((a: any) => a.field == item[0])));
                });
                table.value.PopulateTable(result.data.status, temp);
            });
    } else {
        await axios
            .post(url, formData, {
                headers: { "content-type": "multipart/form-data" },
            })
            .then((result) => {
                Object.entries(result.data.message).forEach((item) => {
                    temp.push(toRaw(ErrorMsg.find((a: any) => a.field == item[0])));
                });
                table.value.PopulateTable(result.data.status, temp);
            });
    }
    SubmitLoading.value = false;
    // close();
});

function editItem(item: any) {
    item = toRaw(item);
    table.value.disableEditButton = !table.value.disableEditButton;
    if (table.value.disableEditButton == true) {
        id.value = item.id;
        axios.get(url + "/" + item.id).then((response) => {
            if (response.data.result) {
                item = response.data.result;
                imagePath.value = item.thumb_img || '';
                setValues({
                    name: item.name,
                    name_en: item.name_en,
                    active: item.active,
                    university_no: item.univ_no,
                    role: item.role,
                    phone: item.phone,
                    email: item.email,
                });
            }
            table.value.disableEditButton = false;
            table.value.dialog = true;
        });
    }
}

watch(
    () => id.value,
    (val) => {
        if (val < 0) method.value = "post";
        else method.value = "put";
    }
);

onBeforeMount(() => {
    method.value = "post";
});
</script>
