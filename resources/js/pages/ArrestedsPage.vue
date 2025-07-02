<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="10">
        <v-text-field
          variant="outlined"
          v-model="search"
          label="البحث"
          @input="filterItems"
        />
      </v-col>
      <v-col cols="12" md="2">
        <v-text-field
          variant="outlined"
          :model-value="filteredItems.length"
          readonly
        />
      </v-col>
    </v-row>

    <v-data-table
      :headers="headers"
      :items="filteredItems"
      item-key="id"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>المقبوضات</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="600px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                variant="outlined"
                color="primary"
                dark
                class="mb-2"
                v-bind="attrs"
                v-on="on"
                @click="newItem"
              >
                قبض جديد
              </v-btn>
            </template>

            <v-card>
              <v-card-title class="pb-0">
                <v-row class="align-center">
                  <v-col cols="12">
                    <h3 class="text-h6 font-weight-bold mb-2">{{ formTitle }}</h3>
                  </v-col>
                </v-row>
              </v-card-title>

              <v-card-text class="pt-0">
                <v-container>
                  <BaseForm
                    v-model="editedItem"
                    :fields="formFields"
                    :submit-label="'حفظ'"
                    :cancel-label="'إلغاء'"
                    :show-cancel="true"
                    :grid-cols="2"
                    @submit="save"
                    @cancel="close"
                  />
                </v-container>
              </v-card-text>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>

      <template v-slot:item.currency="{ item }">
        {{ getCurrencyName(item.currency) }}
      </template>

      <template v-slot:item.actions="{ item }">
        <v-icon large @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon large @click="deleteItem(item)">mdi-delete</v-icon>
      </template>
    </v-data-table>
  </v-container>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import BaseForm from "@/components/FormComponent.vue";
import { currencies } from "@/data/currencies"; // تأكد إنه ملف العملات موجود عندك

const users = ref([]);
const items = ref([]);
const search = ref("");
const dialog = ref(false);
const id = ref(0);
const editedIndex = ref(-1);

const editedItem = ref({
  id: 0,
  account_id: "",
  total: "",
  currency: "",
  notes: "",
  date: new Date().toISOString().substring(0, 10),
});

const defaultItem = () => ({
  id: 0,
  account_id: "",
  total: "",
  currency: "",
  notes: "",
  date: new Date().toISOString().substring(0, 10),
});

const headers = [
  { title: "التسلسل", key: "id", sortable: false },
  { title: "رقم الحساب", key: "account_id", sortable: false },
  { title: "المبلغ", key: "total", sortable: false },
  { title: "العملة", key: "currency", sortable: false },
  { title: "التاريخ", key: "date", sortable: false },
  { title: "الملاحظات", key: "notes", sortable: false },
  { title: "العمليات", key: "actions", sortable: false },
];

const formTitle = computed(() =>
  editedIndex.value === -1 ? "قبض جديد" : "تحديث معلومات قبض"
);

const filteredItems = computed(() => {
  return items.value.filter((item) => {
    if (search.value) {
      return item.account_id
        .toString()
        .toLowerCase()
        .includes(search.value.toLowerCase());
    }
    return true;
  });
});

function getCurrencyName(code) {
  const match = currencies.find((c) => c.value === code);
  return match ? match.text : code;
}

function newItem() {
  id.value = 0;
  editedIndex.value = -1;
  editedItem.value = defaultItem();
  dialog.value = true;
}

function editItem(item) {
  id.value = item.id;
  editedIndex.value = items.value.indexOf(item);
  editedItem.value = { ...item };
  dialog.value = true;
}

async function deleteItem(item) {
  const index = items.value.indexOf(item);
  items.value.splice(index, 1);
  await axios.delete(`/api/deleteArrested/${item.id}`);
}

async function save() {
  if (id.value === 0) {
    await axios.post("/api/createArrested", editedItem.value);
    items.value.unshift({ ...editedItem.value });
  } else {
    Object.assign(items.value[editedIndex.value], editedItem.value);
    await axios.put(`/api/updateArrested/${id.value}`, editedItem.value);
  }
  close();
}

function close() {
  dialog.value = false;
  editedItem.value = defaultItem();
  editedIndex.value = -1;
  id.value = 0;
}

function filterItems() {
  // الفلترة تتم عبر computed
}

const formFields = computed(() => [
  {
    name: "total",
    label: "المبلغ",
    type: "number",
    component: "VTextField",
    required: true,
  },
  {
    name: "currency",
    label: "العملة",
    component: "VSelect",
    items: currencies,
    itemTitle: "text",
    itemValue: "value",
    required: true,
  },
  {
    name: "account_id",
    label: "صاحب الحساب",
    component: "VAutocomplete",
    items: users.value,
    itemTitle: "user_name",
    itemValue: "id",
    placeholder: "ابدأ البحث",
    required: true,
  },
  {
    name: "date",
    label: "التاريخ",
    type: "date",
    component: "VTextField",
    required: true,
  },
  {
    name: "notes",
    label: "الملاحظات",
    component: "VTextField",
    cols: 12,
  },
]);

onMounted(async () => {
  const [usersRes, itemsRes] = await Promise.all([
    axios.get("/api/getAllUsers"),
    axios.get("/api/getAllArresteds"),
  ]);
  users.value = usersRes.data;
  items.value = itemsRes.data;
});
</script>
