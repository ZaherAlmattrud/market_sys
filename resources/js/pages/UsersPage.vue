<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="2">
        <v-btn class="add-button" color="blue" @click="openAddUserDialog">
          <v-icon left>mdi-account-plus</v-icon>
          مستخدم جديد
        </v-btn>
      </v-col>

      <v-col cols="12" md="8">
        <v-text-field v-model="search" label="البحث" variant="outlined" @input="onSearch" />
      </v-col>

      <v-col cols="12" md="2">
        <v-text-field :value="totalItems" label="عدد النتائج" variant="outlined" readonly />
      </v-col>
    </v-row>

    <!-- جدول -->
    <v-table class="mt-4" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
      <thead style="background-color: #fafafa;">
        <tr>
          <th>الاسم</th>
          <th>رقمه بالدفتر</th>
          <th>البلدة</th>
          <th>موبايل</th>
          <th>العمليات</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in items" :key="item.id">
          <td>{{ item.user_name }}</td>
          <td>{{ item.number_in_book }}</td>
          <td>{{ item.area }}</td>
          <td>{{ item.mobile }}</td>
          <td>
            <v-icon
              v-if="loggedIn"
              @click="deleteItem(item)"
              style="color: #e53935; cursor: pointer;"
              title="حذف"
            >mdi-delete</v-icon>
            <v-icon
              v-if="loggedIn"
              @click="clearAccount(item)"
              style="color: #fb8c00; cursor: pointer;"
              title="مسح الحساب"
            >mdi-notebook-remove-outline</v-icon>
            <v-icon
              v-if="loggedIn"
              @click="editItem(item)"
              style="color: #1e88e5; cursor: pointer;"
              title="تعديل"
            >mdi-pencil</v-icon>
            <v-icon
              @click="moveToAccountDetails(item)"
              style="color: #43a047; cursor: pointer;"
              title="تفاصيل الحساب"
            >mdi-book-open-page-variant-outline</v-icon>
            <v-icon
              @click="moveToAccountSummary(item)"
              style="color: #8e24aa; cursor: pointer;"
              title="ملخص الحساب"
            >mdi-account-eye-outline</v-icon>
          </td>
        </tr>
      </tbody>
    </v-table>

    <!-- تنقل بين الصفحات -->
    <v-row class="mt-4" justify="center" align="center">
      <v-btn :disabled="currentPage === 1" @click="prevPage" color="blue" variant="elevated">السابق</v-btn>
      <span class="mx-4">صفحة {{ currentPage }} من {{ totalPages }}</span>
      <v-btn :disabled="currentPage === totalPages" @click="nextPage" color="blue" variant="elevated">التالي</v-btn>
    </v-row>

    <!-- مودال النموذج -->
    <v-dialog v-model="formDialog" max-width="600px">
      <v-card>
        <v-card-title class="text-white" style="background-color: #1e88e5;">
          {{ isEdit ? 'تعديل مستخدم' : 'إضافة مستخدم' }}
        </v-card-title>
        <v-card-text>
          <DynamicForm
            v-model="formData"
            :fields="formFields"
            submit-label="حفظ"
            cancel-label="إلغاء"
            :show-cancel="true"
            @submit="handleSubmit"
            @cancel="formDialog = false"
            :grid-cols="1"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import DynamicForm from '@/components/FormComponent.vue';
import { useRouter } from 'vue-router';

const items = ref([]);
const totalItems = ref(0);
const search = ref('');
const pageSize = 6;
const currentPage = ref(1);
const totalPages = ref(1);
const loggedIn = ref(false);

const areas = ref([]);
const userTypes = ref([]);

const formDialog = ref(false);
const isEdit = ref(false);

const formData = ref({});
const formFields = computed(() => [
  {
    name: 'user_name',
    label: 'الاسم',
    component: 'VTextField',
    rules: [(v) => !!v || 'مطلوب'],
  },
  {
    name: 'number_in_book',
    label: 'رقم الدفتر',
    component: 'VTextField',
  },
  {
    name: 'user_type',
    label: 'نوع المستخدم',
    component: 'VSelect',
    items: userTypes.value,
    itemTitle: 'name',
    itemValue: 'id',
    rules: [(v) => !!v || 'مطلوب'],
  },
  {
    name: "area",
    label: "البلدة",
    component: "VAutocomplete",
    items: areas.value,
    itemTitle: 'name',
    itemValue: 'id',
    rules: [(v) => !!v || 'مطلوب'],
    clearable: true,
  },
  {
    name: 'mobile',
    label: 'الموبايل',
    component: 'VTextField',
  },
]);

const router = useRouter();

async function loadUsers() {
  const res = await axios.get('/api/getAllUserWithPagination', {
    params: {
      page: currentPage.value,
      pageSize,
      search: search.value,
    },
  });
  items.value = res.data.items;
  totalItems.value = res.data.total;
  totalPages.value = Math.ceil(res.data.total / pageSize);
}

function onSearch() {
  currentPage.value = 1;
  loadUsers();
}

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    loadUsers();
  }
}

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
    loadUsers();
  }
}

function checkLoggedIn() {
  loggedIn.value = !!localStorage.getItem('user');
}

function moveToAccountDetails(item) {
  router.push({ name: 'accountDetails', params: { accountId: item.account } });
}

function moveToAccountSummary(item) {
  router.push({ name: 'accountSummary', params: { accountId: item.account } });
}

async function deleteItem(item) {
  await axios.delete(`/api/deleteUser/${item.id}`);
  loadUsers();
}

async function clearAccount(item) {
  await axios.delete(`/api/clearAccount/${item.id}`);
  loadUsers();
}

function openAddUserDialog() {
  formData.value = {
    user_name: '',
    number_in_book: '',
    user_type: 1,
    area: null,
    mobile: '',
  };
  isEdit.value = false;
  formDialog.value = true;
}

function editItem(item) {
  formData.value = { ...item };
  isEdit.value = true;
  formDialog.value = true;
}

async function handleSubmit(data) {
  try {
    if (isEdit.value) {
      await axios.put(`/api/updateUser/${data.id}`, data);
    } else {
      await axios.post('/api/createUser', data);
    }
    formDialog.value = false;
    loadUsers();
  } catch (e) {
    console.error('خطأ في الحفظ:', e);
  }
}

async function fetchAreas() {
  const res = await axios.get('/api/getAllAreas');
  areas.value = res.data;
}

async function fetchUserTypes() {
  const res = await axios.get('/api/getAllUserTypes');
  userTypes.value = res.data;
}

onMounted(() => {
  checkLoggedIn();
  loadUsers();
  fetchAreas();
  fetchUserTypes();
});
</script>

<style scoped>
.add-button {
  padding: 10px 14px;
  font-size: 14px;
  background-color: #1e88e5;
  color: white;
  border-radius: 8px;
  transition: 0.3s ease;
}

.add-button:hover {
  background-color: #1565c0;
}

v-icon {
  font-size: 20px;
}
</style>
