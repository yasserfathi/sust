<template>
  <Table :id="id" ref="table" :url="url" :headers="headers" toolbar_title="عناوين الفئات والصفحات التي تم تنسيبها للمستخدمين"
    @setFieldError="setFieldError" @save="save" @edit-item="editItem" @close="close" :showSearchInput="false">
    <Form fast-fail name="form" :validation-schema="validationSchema" lazy-validation>
      <v-row>
        <v-col cols="12" class="pt-0">
          <v-select id="category_id" name="category_id" :items="categories" item-title="title" item-value="id"
            v-model="category_id" @update:modelValue="getPages($event)" label="عناوين الفئات"
            :error-messages="errors.category_id"></v-select>
        </v-col>
      </v-row>
      <v-row v-if="data.length > 0">
        <v-col cols="12">
          <v-switch label="اختيار كل الصفحات" id="all_pages" name="all_pages" v-model="all_pages" color="#198754"
            @change="selectAll()" density="compact" hide-details></v-switch>
        </v-col>
        <v-divider></v-divider>
        <v-col cols="6" md="4" v-for="(page, index) in data" :key="index">
          <v-switch :label="page.title" id="active" name="active" :value="page.id" v-model="page_ids" color="#198754"
            density="compact" hide-details></v-switch>
        </v-col>
      </v-row>
    </Form>
  </Table>
</template>

<script lang="ts" setup>
import { defineAsyncComponent, onMounted, toRaw, watch } from 'vue'
const Table = defineAsyncComponent(() => import('../components/Table.vue'))
import { Form, useField, useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import * as zod from 'zod';
import { nextTick, ref } from 'vue';
import axios from 'axios';
import { isEqual } from 'lodash';

const props = defineProps({
  user_id: Number
});

const url = route('category_page_user.index',props.user_id)
const headers = [
  { title: 'عنوان الفئة', key: 'title', sortable: false, width: 200 },
  { title: 'عناوين الصفحات', key: 'category_page', sortable: false },
  { title: '', key: 'actions', sortable: false, width: 100 },
];

const table = ref();
const all_pages = ref(false);
let data = ref([]);
let data_ids = ref([]);
let checked_ids = ref([]);
let initial_page_ids = ref([]);
let page_ids = ref([]);
const is_all_pages_checked = ref<any>();

interface FormFields {
  id: number,
  category_id: number
}

const validationSchema = toTypedSchema(
  zod.object({
    category_id: zod.number({ required_error: "اختر عنوان الفئة" }),
  })
);

const { handleSubmit, resetForm, errors, setFieldError } = useForm<FormFields>({
  validationSchema,
});


const id = ref(-1);
const categories = ref([{}]);
const { value: category_id } = useField('category_id');

const save = handleSubmit(async () => {

  if (isEqual(toRaw(initial_page_ids.value), toRaw(page_ids.value))) {
    close();
    return;
  }

  table.value.SubmitLoading = true;
  if (id.value < 0) {
    await axios.post(route('category_page_user.store',{ user_id: props.user_id, pages: page_ids.value.toString() })).then(result => {
      table.value.PopulateTable(result.data.status, []);
    })
  } else {
    let array_diff = checked_ids.value.filter(item => !page_ids.value.includes(item));
    var arr = array_diff.map(item => item * -1); // reverse positives ids to negatives and versa
    page_ids.value = page_ids.value.concat(arr);
    await axios.put(route('category_page_user.update',{ user_id: props.user_id, pages: page_ids.value.toString() })).then(result => {
      table.value.PopulateTable(result.data.status, []);
    })
  }
  table.value.SubmitLoading = false;
});

function editItem(item: any) {
  item = toRaw(item);
  table.value.disableEditButton = !table.value.disableEditButton;
  if (table.value.disableEditButton == true) {
    id.value = item.id;
    getPages(item.id);
    category_id.value = item.id;
    table.value.disableEditButton = false
    table.value.dialog = true;
  }
}


function close() {
  nextTick(() => {
    id.value = -1;
    data.value = [];
    checked_ids.value = [];
    page_ids.value = [];
    initial_page_ids.value = [];
    data_ids.value = [];
    resetForm();
  });
  table.value.dialog = false
}

async function getPages(category_id: any) {

  all_pages.value = false;
  is_all_pages_checked.value = null;

  data.value = []; // response data
  data_ids.value = []; // response ids
  checked_ids.value = []; //reposense data positive and negative ids
  page_ids.value = []; // reposense data checked positive ids
  initial_page_ids.value = []; // reposense data checked positive ids
  await axios.get(url + '/' + category_id).then(response => {
    data.value = toRaw(response.data.pages);
    data_ids.value = data.value.map(({ id }) => id);
    data.value.forEach((item: any) => {
      if (item.checked == true) {
        if (checked_ids.value.includes(item.id) == false) {
          checked_ids.value.push(item.id);
        }
      }
      else {
        if (checked_ids.value.includes(item.id) == false) {
          checked_ids.value.push(item.id * -1);
        }
        is_all_pages_checked.value = false;
      }
    });
  });

  initial_page_ids.value = page_ids.value = checked_ids.value.filter(item => item > 0).sort();

  if (is_all_pages_checked.value == null) {
    all_pages.value = true
  }

}

function selectAll() {
  if (all_pages.value == true) {
    page_ids.value = data_ids.value;
  }
  else
    page_ids.value = []
}

async function getCategories() {
  await axios.get(route("category.list")).then(response => {
    categories.value = response.data.categories;
  });
}

watch(() => initial_page_ids.value, val => {
  if (val.length == 0)
    id.value = -1
  else
    id.value = 1
});

watch(() => page_ids.value, val => {
  if (val.length == checked_ids.value.length)
    all_pages.value = true
  else
    all_pages.value = false
});


onMounted(() => {
  getCategories();
});
</script>
