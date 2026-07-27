<template>
  <v-card border flat rounded="lg">

    <v-toolbar flat color="primary" class="px-2">
      <v-toolbar-title class="font-weight-bold d-flex align-center text-white">
        <v-icon start icon="mdi-menu" class="me-2"></v-icon>
        {{ toolbar_title }} </v-toolbar-title>
      <v-spacer></v-spacer>

      <v-text-field v-if="showSearchInput" v-model="search" label="البحث" clearable persistent-clear hide-details
        density="compact" prepend-inner-icon="mdi-magnify" variant="solo" flat class="ms-4"
        style="max-width: 300px;"></v-text-field>

      <v-dialog v-model="dialog" persistent :max-width="maxWidth" :fullscreen="fullscreen"
        transition="dialog-bottom-transition">
        <template v-slot:activator="{ props: activatorProps }">
          <v-btn v-if="showInsertButton" class="ms-4" color="white" variant="outlined" prepend-icon="mdi-plus"
            v-bind="activatorProps" @click="openDialog" rounded="lg">
            اضافة بيانات
          </v-btn>
          <v-btn v-if="exportUrl" class="ms-4" color="white" variant="outlined" prepend-icon="mdi-file-pdf-box"
            :href="exportUrl" target="_blank" rounded="lg">
            تصدير PDF
          </v-btn>
        </template>

        <v-card>
          <v-toolbar color="primary" variant="tonal" density="compact">
            <v-icon start :icon="formIcon" class="mr-3"></v-icon>

            <div>
              {{ formTitle }}
            </div>
            <v-spacer></v-spacer>
            <v-btn icon @click="dialog = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card-text>
            <slot></slot>
          </v-card-text>
          <v-card-actions v-if="showButtons" class="px-5 pb-4">
            <v-btn color="grey-darken-1" variant="elevated" @click="close" ripple rounded="xl">
              الغاء
            </v-btn>
            <v-btn color="green-darken-1" variant="elevated" type="submit" :loading="SubmitLoading" @click="save" ripple
              rounded="xl">
              {{ btnText }}
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar>

    <v-data-table-server v-if="showTable" hide-no-data hover :headers="headers" :items-length="totalItems" :items="data"
      :loading="tableLoading" :search="search" item-value="id" :items-per-page-options="itemsPerPagelist"
      items-per-page-text="الصفوف في الصفحة" @update:options="LoadItems" density="comfortable"
      :show-current-page="true">

      <template v-slot:[`item.actions`]="{ item, index }">
        <v-btn icon variant="text" size="small" color="grey-darken-1" @click="editItem(item)"
          :disabled="disableEditButton" :key="index">
          <v-icon>mdi-pencil-outline</v-icon>
        </v-btn>
        <v-btn icon variant="text" size="small" color="error" @click="deleteItem(item)">
          <v-icon>mdi-trash-can-outline</v-icon>
        </v-btn>
      </template>

      <template v-slot:[`item.view`]="{ item, index }">
        <v-btn icon variant="text" size="small" color="grey-darken-1" @click="editItem(item)"
          :disabled="disableEditButton" :key="index">
          <v-icon>mdi-eye-outline</v-icon>
        </v-btn>
      </template>

      <template v-slot:[`item.active`]="{ item }">
        <v-chip :color="getColor(item['active'])" variant="tonal">
          <label v-if="item['active'] == 0">غير منشط</label>
          <label v-else>منشط</label>
        </v-chip>
      </template>

      <template v-slot:[`item.priority`]="{ item }">
        <v-switch :model-value="item.priority" @update:model-value="() => changePriority(item.id)"
          :color="item.priority == 1 ? 'success' : 'grey'" hide-details inset density="compact"></v-switch>
      </template>

      <template v-slot:[`item.total_images`]="{ item }">
        <div class="p-2">
          <a v-if="item['total_images'] != ''" target="_blank" href="#">
            <v-chip color="black">{{ item['total_images'] }}</v-chip>
          </a>
        </div>
      </template>

      <template v-slot:[`item.category_page`]="{ item }">
        <v-chip color="primary" class="ma-2" v-for="category in (item['category_page'] as Array<any>)"
          :key="category.id">
          {{ category.title }}
        </v-chip>
      </template>

      <template v-slot:[`item.thumb_img`]="{ item }">
        <div class="p-2" v-if="isValidImage(item['thumb_img'])">
          <v-avatar>
            <v-img :src="BASE_URL + '/' + item['thumb_img']" :lazy-src="BASE_URL + '/' + item['thumb_img']">
              <template v-slot:placeholder>
                <v-row class="fill-height ma-0" align="center" justify="center">
                  <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                </v-row>
              </template>
            </v-img>
          </v-avatar>
        </div>
      </template>

      <template v-slot:[`item.img`]="{ item }">
        <div class="p-2" v-if="item['img'] && isValidImage(item['img'])">
          <v-avatar>
            <v-img :src="BASE_URL + '/' + item['img']" :lazy-src="BASE_URL + '/' + item['img']"
              @click="showModal(item['img'])" style="cursor: pointer;">
              <template v-slot:placeholder>
                <v-row class="fill-height ma-0" align="center" justify="center">
                  <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                </v-row>
              </template>
            </v-img>
          </v-avatar>
        </div>
      </template>

      <!-- Dynamically pass through all slots from parent except default -->
      <template v-for="name in Object.keys($slots).filter(n => n !== 'default')" v-slot:[name]="slotData">
        <slot :name="name" v-bind="slotData || {}" />
      </template>
    </v-data-table-server>
  </v-card>
</template>

<script lang="ts" setup>
import { useFetchFunctions } from '../composables/useFetchFunctions';
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, computed, watch } from 'vue';

const BASE_URL = import.meta.env.VITE_BASE_URL;

// تعريف الخصائص
const props = defineProps({
  id: Number,
  toolbar_title: String,
  url: String,
  headers: Array as () => any[],
  fullscreen: Boolean,
  showInsertButton: {
    type: Boolean,
    default: () => true
  },
  showButtons: {
    type: Boolean,
    default: () => true
  },
  showSearchInput: {
    type: Boolean,
    default: () => true
  },
  showTable: {
    type: Boolean,
    default: () => true
  },
  exportUrl: {
    type: String,
    default: () => ''
  }
});

const emit = defineEmits(['id', 'close', 'save', 'editItem', 'setFieldError', 'resetFormFields', 'changePriority']);

const search = ref('');
const SubmitLoading = ref(false);
const dialog = ref(false);
const disableEditButton = ref(false);

const itemsPerPagelist = ref([
  { value: 5, title: '5' },
  { value: 10, title: '10' },
  { value: 25, title: '25' },
  { value: 50, title: '50' },
  { value: 100, title: '100' },
  { value: -1, title: '$vuetify.dataFooter.itemsPerPageAll' }
]);

const { LoadItems, data, tableLoading, totalItems } = useFetchFunctions(props.url || '');

const formTitle = computed(() => {
  if (props.id === -1 || props.id === null || props.id === undefined) {
    return 'اضافة بيانات';
  }
  return 'تعديل بيانات';
});

const formIcon = computed(() => {
  return (props.id ?? -1) > 0 ? 'mdi-pencil-circle' : 'mdi-plus-circle';
});

const btnText = computed(() => {
  return (props.id ?? -1) > 0 ? 'تعديل' : 'حفظ';
});

const maxWidth = computed(() => {
  return props.fullscreen === false ? '1200' : '';
});

// Functions
function getColor(active: any) {
  return active == true ? "#198754" : "red-darken-1";
}

function isValidImage(path: any) {
  if (!path) return false;
  if (typeof path !== 'string') return false;
  if (path === 'null' || path === 'undefined') return false;
  if (path.toLowerCase().includes('null')) return false;
  return true;
}

function showModal(imgUrl: string) {
}

function deleteItem(item: any) {
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
    if (result.isConfirmed && props.url) {
      var params = item["id"];
      if (props.url.substring(props.url.lastIndexOf("/") + 1, props.url.length) === 'permission_roles') {
        params = item['permission'].id + '/' + item['role'].id;
      } else if (props.url.substring(props.url.lastIndexOf("/") + 1, props.url.length) === 'role_users') {
        params = item['role'].id + '/' + item['user'].id;
      }
      axios.post(props.url + '/' + params, { _method: 'DELETE' }).then(() => {
        LoadItems(null);
        Swal.fire({ title: 'تمت العملية بنجاح', icon: 'success', confirmButtonColor: '#198754', confirmButtonText: "Ok", timer: 1500 });
      });
    }
  });
}

function PopulateTable(status: number, errors: Array<{ field: string, message: string }>) {
  if (status == 409 && errors.length > 0) {
    Array.from(errors).forEach(element =>
      emit('setFieldError', element['field'], element['message'], true)
    );
  } else {
    close();
    LoadItems(null);
    if (status != 304) {
      Swal.fire({ title: 'تمت العملية بنجاح', icon: 'success', confirmButtonColor: '#198754', confirmButtonText: "Ok", timer: 1500 });
    }
  }
  SubmitLoading.value = false;
}

function PopulateTable_album_news(status: number, errors: Array<{ field: string, message: string }>) {
  if (status == 409 && errors.length > 0) {
    Array.from(errors).forEach(element =>
      emit('setFieldError', element['field'], element['message'], true)
    );
  } else {
    LoadItems(null);
    if (status != 304) {
      Swal.fire({ title: 'تمت العملية بنجاح', icon: 'success', confirmButtonColor: '#198754', confirmButtonText: "Ok", timer: 1500 });
    }
  }
}

function changePriority(id: any) {
  if (data.value) {
    let priorityTrueCount = data.value.filter((item: any) => item.priority === true).length;
    if (priorityTrueCount < 5) {
      emit('changePriority', id);
    } else {
      Swal.fire({
        title: 'الأخبار المميزة بأولوية النشر عددها 5',
        icon: 'warning',
        confirmButtonColor: '#198754',
        cancelButtonColor: '#d33',
        confirmButtonText: 'موافق',
        cancelButtonText: 'الغاء',
        showCancelButton: true,
        showCloseButton: true
      });
    }
  }
}

function save() {
  emit('save');
}

function editItem(item: any) {
  emit('editItem', item);
}

function openDialog() {
  emit('resetFormFields');
}

function close() {
  emit('close');
}

watch(dialog, val => {
  val || close();
});

defineExpose({
  dialog,
  disableEditButton,
  PopulateTable,
  PopulateTable_album_news,
  SubmitLoading,
  tableLoading
});
</script>