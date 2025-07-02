<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="10">
        <v-text-field
          v-model="search"
          label="البحث"
          variant="outlined"
          clearable
          @input="onSearchInput"
        />
      </v-col>
      <v-col cols="12" md="2">
        <v-text-field
          :value="totalItems"
          label="عدد النتائج"
          readonly
          variant="outlined"
        />
      </v-col>
    </v-row>

    <v-data-table
      :headers="headers"
      :items="items"
      :server-items-length="totalItems"
      :items-per-page="itemsPerPage"
      :page.sync="page"
      :loading="loading"
      item-key="id"
      class="elevation-1"
      hide-default-footer
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>فواتير المبيعات</v-toolbar-title>
          <v-spacer></v-spacer>

          <v-dialog v-model="dialog" max-width="700px">
            <template v-slot:activator="{ props }">
              <v-btn color="primary" variant="flat" class="mb-2" v-bind="props">
                فاتورة جديدة
              </v-btn>
            </template>

           <v-card>
  <v-card-title>
    <span class="text-h6">تفاصيل فاتورة المبيعات</span>
  </v-card-title>

  <v-card-text>
    <BaseForm
      v-model="editedItem"
      :fields="formFields"
     
      :cancel-label="'إلغاء'"
       :submit-label="'حفظ'"
      :show-cancel="true"
      @submit="save"
      @cancel="close"
    />
  </v-card-text>
</v-card>

          </v-dialog>
        </v-toolbar>
      </template>

      <template v-slot:item.date="{ item }">
        {{ formatDate(item.date) }}
      </template>

      <template v-slot:item.actions="{ item }">
        <v-icon v-if="loggedIn" large @click="deleteItem(item)">mdi-delete</v-icon>
        <v-icon v-if="loggedIn" large @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon large @click="moveToAccountDetails(item)">mdi-account-eye-outline</v-icon>
      </template>
    </v-data-table>

    <div class="my-4 d-flex justify-center">
      <v-btn
        v-for="(link, index) in paginationLinks"
        :key="index"
        :disabled="!link.url"
        :color="link.active ? 'primary' : 'default'"
        class="mx-1"
        v-html="link.label"
        @click="goToPage(link.url)"
      />
    </div>
  </v-container>
</template>

<script setup>
import { ref, reactive, computed, watch } from "vue";
import axios from "axios";
import BaseForm from "@/components/FormComponent.vue";
import { currencies } from "@/data/currencies.js";

const dialog = ref(false);
const search = ref("");
const page = ref(1);
const itemsPerPage = ref(6);
const totalItems = ref(0);
const loading = ref(false);
const items = ref([]);
const users = ref([]);
const paginationLinks = ref([]);
const loggedIn = ref(false);

const editedItem = reactive({
  id: 0,
  user_id: null,
  currency: "SYP",
  total: 0,
  notes: "",
  date:  new Date().toISOString().substr(0, 10),
});

const headers = [
  { title: "التسلسل", key: "id", sortable: false },
  { title: "الأسم", key: "user_id", sortable: false },
  { title: "العملة", key: "currency", sortable: false },
  { title: "الاجمالي", key: "total", sortable: false },
  { title: "التاريخ", key: "date", sortable: false },
  { title: "الملاحظات", key: "notes", sortable: false },
  { title: "العمليات", key: "actions", sortable: false }
];

// تكوين الحقول حسب BaseForm
const formFields = computed(() => [
  {
    name: "user_id",
    label: "صاحب الفاتورة",
    component: "VAutocomplete",
    items: users.value,
    itemTitle: "user_name",
    itemValue: "id",
    required: true,
    clearable: true,
    returnObject: false,
    rules: [(v) => !!v || "هذا الحقل مطلوب"]
  },
  {
    name: "currency",
    label: "العملة",
    component: "VSelect",
    items: currencies,
    itemTitle: "text",
    itemValue: "value",
    required: true,
    clearable: true,
    rules: [(v) => !!v || "اختر العملة"]
  },

  {
    name: "date",
    label: "التاريخ",
    type: "date",
    required: true,
    rules: [(v) => !!v || "هذا الحقل مطلوب"]
  },
  
]);

function formatDate(dateString) {
  if (!dateString) return "";
  const options = { year: "numeric", month: "long", day: "numeric" };
  return new Date(dateString).toLocaleDateString("ar-EG", options);
}

async function fetchUsers() {
  try {
    const res = await axios.get("/api/getAllUsers");
    users.value = res.data;
  } catch (error) {
    console.error("خطأ في تحميل المستخدمين:", error);
  }
}

async function fetchItems() {
  loading.value = true;
  try {
    const res = await axios.get("/api/getAllSells", {
      params: { page: page.value, search: search.value }
    });
    items.value = res.data.data;
    totalItems.value = res.data.total || res.data.meta?.total || 0;
    itemsPerPage.value = res.data.per_page || res.data.meta?.per_page || itemsPerPage.value;
    paginationLinks.value = res.data.links || [];
  } catch (error) {
    console.error("خطأ في تحميل الفواتير:", error);
  }
  loading.value = false;
}

function onSearchInput() {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    page.value = 1;
    fetchItems();
  }, 500);
}

function goToPage(url) {
  if (!url) return;
  try {
    const urlObj = new URL(url);
    const pageParam = urlObj.searchParams.get("page");
    if (pageParam) {
      page.value = Number(pageParam);
      fetchItems();
    }
  } catch {
    console.error("رابط صفحة غير صالح:", url);
  }
}

import { useRouter } from 'vue-router';

const router = useRouter();


function moveToAccountDetails(item) {
  // تعديل حسب الراوتر لديك
  router.push({ name: 'sellDetails', params: { sellId: item.id } });
  
}

function editItem(item) {
  Object.assign(editedItem, item);
  dialog.value = true;
}

async function deleteItem(item) {
  try {
    await axios.delete(`/api/deleteSell/${item.id}`);
    fetchItems();
  } catch (error) {
    console.error("خطأ أثناء الحذف:", error);
  }
}

function close() {
  dialog.value = false;
  Object.assign(editedItem, {
    id: 0,
    user_id: null,
    currency: "SYP",
    total: 0,
    notes: "",
    date:  new Date().toISOString().substr(0, 10)
  });
}

async function save(payload) {
  try {
    if (payload.id === 0) {
      await axios.post("/api/createSell", payload);
    } else {
      await axios.put(`/api/updateSell/${payload.id}`, payload);
    }
    fetchItems();
    close();
  } catch (error) {
    console.error("خطأ أثناء الحفظ:", error);
  }
}

let searchTimeout = null;

function checkLoggedIn() {
  loggedIn.value = !!localStorage.getItem("user");
}

// عند تحميل الصفحة
fetchUsers();
fetchItems();
checkLoggedIn();

</script>
