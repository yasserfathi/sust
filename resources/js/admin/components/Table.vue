<template>
  <v-card flat rounded="lg" class="overflow-hidden border-sm">

    <div class="table-header-bar px-3 px-sm-6 py-3 bg-primary">
      <div class="d-flex flex-column flex-sm-row align-stretch align-sm-center justify-space-between ga-3">
        
        <!-- Row 1 on Mobile: Title & Icon -->
        <div class="d-flex align-center">
          <v-icon icon="mdi-menu" color="white" class="me-2" size="22"></v-icon>
          <span class="font-weight-bold text-white text-subtitle-1 text-truncate">
            {{ displayTitle }}
          </span>
        </div>

        <!-- Row 2 on Mobile: Actions (Search & Insert Button) -->
        <div class="d-flex align-center flex-wrap flex-sm-nowrap ga-2">
          <v-text-field v-if="showSearchInput" v-model="search" placeholder="البحث..." clearable persistent-clear hide-details
            density="compact" prepend-inner-icon="mdi-magnify" variant="solo" flat rounded="pill"
            class="custom-search-input flex-grow-1"
            style="min-width: 140px;"></v-text-field>

          <v-dialog v-model="dialog" persistent :max-width="maxWidth" :fullscreen="fullscreen"
            transition="dialog-bottom-transition" scrollable>
            <template v-slot:activator="{ props: activatorProps }">
              <v-btn v-if="showInsertButton" color="white" variant="flat" prepend-icon="mdi-plus"
                v-bind="activatorProps" @click="openDialog" rounded="pill" class="text-primary font-weight-bold px-3 px-sm-4 text-no-wrap flex-shrink-0">
                <span class="d-none d-sm-inline">إضافة بيانات</span>
                <span class="d-inline d-sm-none">إضافة</span>
              </v-btn>
              <v-btn v-if="exportUrl" color="white" variant="outlined" prepend-icon="mdi-file-pdf-box"
                :href="exportUrl" target="_blank" rounded="pill" class="px-3 px-sm-4 text-white font-weight-bold opacity-100 text-no-wrap flex-shrink-0">
                <span class="d-none d-sm-inline">تصدير PDF</span>
                <span class="d-inline d-sm-none">PDF</span>
              </v-btn>
            </template>

          <v-card dir="rtl" :rounded="fullscreen ? '0' : 'xl'" class="overflow-hidden d-flex flex-column" :style="{ maxHeight: fullscreen ? '100vh' : '88vh' }">
            <v-toolbar class="border-b px-4 flex-grow-0" density="comfortable" flat color="surface">
              <v-icon start :icon="formIcon" class="ms-2 text-primary" size="24"></v-icon>

              <div class="font-weight-bold text-subtitle-1 text-primary">
                {{ formTitle }}
              </div>
              <v-spacer></v-spacer>
              <v-btn icon variant="text" size="small" @click="dialog = false" class="text-grey-darken-1 hover-lift">
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-toolbar>

            <v-card-text class="pa-0 custom-dialog-scroll" style="overflow-y: auto;">
              <v-container fluid class="pa-6 w-100">
                <slot></slot>
              </v-container>
            </v-card-text>

            <v-card-actions v-if="showButtons" class="px-6 py-2 border-t flex-grow-0" dir="rtl" style="gap: 12px; justify-content: flex-end; background-color: rgb(var(--v-theme-surface));">
              <v-spacer></v-spacer>
              <v-btn color="grey-darken-2" variant="text" @click="close" rounded="pill" class="px-6 font-weight-bold hover-lift">
                إلغاء
              </v-btn>
              <v-btn color="primary" variant="flat" type="submit" :loading="SubmitLoading" @click="save" ripple rounded="pill" class="px-8 font-weight-bold hover-lift">
                {{ btnText }}
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        </div>

      </div>
    </div>

    <v-data-table-server v-if="showTable" hide-no-data hover :headers="filteredHeaders" :items-length="totalItems" :items="data"
      :loading="tableLoading" :search="search" item-value="id" :items-per-page-options="itemsPerPagelist"
      items-per-page-text="الصفوف في الصفحة" @update:options="LoadItems" density="comfortable"
      class="custom-data-table"
      :show-current-page="true">

      <template v-slot:[`item.actions`]="{ item, index }">
        <div class="d-flex align-center ga-1 justify-center">
          <v-btn icon variant="tonal" size="x-small" color="success" @click="editItem(item)"
            :disabled="disableEditButton" :key="index" title="تعديل">
            <v-icon size="15">mdi-pencil-outline</v-icon>
          </v-btn>
          <v-btn icon variant="tonal" size="x-small" color="error" @click="deleteItem(item)" title="حذف">
            <v-icon size="15">mdi-trash-can-outline</v-icon>
          </v-btn>
        </div>
      </template>

      <template v-slot:[`item.view`]="{ item, index }">
        <v-btn icon variant="tonal" size="x-small" color="info" @click="editItem(item)"
          :disabled="disableEditButton" :key="index" title="عرض">
          <v-icon size="15">mdi-eye-outline</v-icon>
        </v-btn>
      </template>

      <template v-slot:[`item.title`]="{ item }">
        <div class="table-title-cell" :title="item.title">
          {{ item.title }}
        </div>
      </template>

      <template v-slot:[`item.name`]="{ item }">
        <div class="table-title-cell" :title="item.name">
          {{ item.name }}
        </div>
      </template>

      <template v-slot:[`item.title_en`]="{ item }">
        <div class="table-title-cell" :title="item.title_en">
          {{ item.title_en }}
        </div>
      </template>

      <template v-slot:[`item.name_en`]="{ item }">
        <div class="table-title-cell" :title="item.name_en">
          {{ item.name_en }}
        </div>
      </template>

      <template v-slot:[`item.active`]="{ item }">
        <v-chip :color="getColor(item['active'])" variant="tonal" size="small">
          <span v-if="item['active'] == 0">غير منشط</span>
          <span v-else>منشط</span>
        </v-chip>
      </template>

      <template v-slot:[`item.priority`]="{ item }">
        <div class="d-flex justify-center align-center">
          <v-switch :model-value="item.priority" @update:model-value="() => changePriority(item.id)"
            :color="item.priority == 1 ? 'success' : 'grey'" hide-details density="compact" class="compact-switch"></v-switch>
        </div>
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
            <v-img :src="BASE_URL + '/' + item['thumb_img']" :lazy-src="BASE_URL + '/' + item['thumb_img']"
              @click="showModal(item['img'] ? item['img'] : item['thumb_img'])" style="cursor: pointer;">
              <template v-slot:placeholder>
                <v-row class="fill-height ma-0" align="center" justify="center">
                  <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                </v-row>
              </template>
            </v-img>
          </v-avatar>
        </div>
        <div class="p-2" v-else>
          <v-avatar color="grey-lighten-3">
            <v-icon icon="mdi-account" color="grey-darken-2" size="x-large"></v-icon>
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
        <div class="p-2" v-else>
          <v-avatar color="grey-lighten-3">
            <v-icon icon="mdi-account" color="grey-darken-2" size="x-large"></v-icon>
          </v-avatar>
        </div>

      </template>

      <template v-for="name in Object.keys($slots).filter(n => n !== 'default')" :key="name" v-slot:[name]="slotData">
        <slot :name="name" v-bind="slotData || {}" />
      </template>
    </v-data-table-server>
    <v-dialog v-model="showImage" width="800">
      <v-card dir="rtl">
        <v-card-text class="pa-3">
          <v-img v-if="photo" :src="photo" :lazy-src="photo"></v-img>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script lang="ts" setup>
import { useAuthStore } from '../store/index';
import { useFetchFunctions } from '../composables/useFetchFunctions';
import axios from 'axios';
import Swal from 'sweetalert2';

const authStore = useAuthStore();
const BASE_URL = window.location.origin;

// تعريف الخصائص
export interface TableProps {
  id?: number | null;
  toolbar_title?: string;
  title?: string;
  url?: string;
  headers?: any[];
  fullscreen?: boolean;
  maxWidth?: string | number;
  showInsertButton?: boolean;
  showButtons?: boolean;
  showSearchInput?: boolean;
  showTable?: boolean;
  exportUrl?: string;
}

const props = withDefaults(defineProps<TableProps>(), {
  showInsertButton: true,
  showButtons: true,
  showSearchInput: true,
  showTable: true,
  exportUrl: '',
  maxWidth: 800,
  fullscreen: false,
});

const emit = defineEmits<{
  (e: 'id', val: any): void;
  (e: 'close'): void;
  (e: 'save'): void;
  (e: 'editItem', item: any): void;
  (e: 'setFieldError', errors: any): void;
  (e: 'resetFormFields'): void;
  (e: 'changePriority', id: any): void;
}>();

const search = ref('');
const SubmitLoading = ref(false);
const dialog = ref(false);
const disableEditButton = ref(false);
const showImage = ref(false);
const photo = ref('');

const itemsPerPagelist = ref([
  { value: 5, title: '5' },
  { value: 10, title: '10' },
  { value: 25, title: '25' },
  { value: 50, title: '50' },
  { value: 100, title: '100' },
  { value: -1, title: '$vuetify.dataFooter.itemsPerPageAll' }
]);

const { LoadItems, data, tableLoading, totalItems } = useFetchFunctions(props.url || '');
const displayTitle = computed(() => props.toolbar_title || props.title || '');

const filteredHeaders = computed(() => {
  let list = props.headers || [];
  const isSuperAdmin = authStore?.user?.role === 1;
  const isCollegeRep = authStore?.user?.is_college_rep === 1 || authStore?.user?.is_college_rep === true;
  const isStaffMember = authStore?.user?.role === 3 || (!isSuperAdmin && !isCollegeRep);

  if (!isSuperAdmin || isCollegeRep) {
    list = list.filter(h => h.title !== 'الكلية' && h.title !== 'اسم الكلية');
  }

  // If logged in as staff member, hide the staff member column (since all records belong to them)
  if (isStaffMember) {
    list = list.filter(h => 
      h.key !== 'user.name' && 
      h.key !== 'staff.user.name' && 
      h.key !== 'user_id' && 
      h.title !== 'اسم عضو هيئة التدريس' &&
      h.title !== 'عضو هيئة التدريس'
    );
  }

  return list.map(h => {
    const item = { ...h };
    if (item.key === 'actions' && (!item.title || item.title.trim() === '')) {
      item.title = 'الإجراءات';
    }
    if (['actions', 'active', 'priority', 'view', 'total_images', 'thumb_img', 'news_date', 'date', 'created_at'].includes(item.key) && !item.align) {
      item.align = 'center';
    }
    return item;
  });
});

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
  if (imgUrl) {
    photo.value = BASE_URL + '/' + imgUrl;
    showImage.value = true;
  }
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
      let params = item["id"];
      if (props.url.endsWith('permission_roles')) {
        params = item['permission'].id + '/' + item['role'].id;
      } else if (props.url.endsWith('role_users')) {
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
    Array.from(errors).forEach(element => {
      if (element && element['field'] && element['message']) {
        emit('setFieldError', element['field'], element['message'], true)
      }
    });
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
    let itemToChange = data.value.find((item: any) => item.id === id);
    let priorityTrueCount = data.value.filter((item: any) => item.priority === true || item.priority == 1).length;
    
    if (itemToChange && (itemToChange.priority === true || itemToChange.priority == 1)) {
      emit('changePriority', id);
    } else if (priorityTrueCount < 3) {
      emit('changePriority', id);
    } else {
      Swal.fire({
        title: 'الأخبار المميزة بأولوية النشر عددها 3',
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
  SubmitLoading.value = false;
  emit('resetFormFields');
}

function close() {
  SubmitLoading.value = false;
  emit('close');
}

watch(SubmitLoading, (val) => {
  if (val) {
    // Safety auto-reset after 10s to prevent infinite loading spinner
    setTimeout(() => {
      if (SubmitLoading.value) {
        SubmitLoading.value = false;
      }
    }, 10000);
  }
});

watch(dialog, val => {
  if (!val) {
    SubmitLoading.value = false;
    close();
  }
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

<style scoped>
.custom-data-table :deep(.v-table__wrapper) {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.custom-data-table :deep(table) {
  min-width: 600px;
}

@media (max-width: 600px) {
  .custom-search-input {
    width: 100% !important;
  }
}
</style>