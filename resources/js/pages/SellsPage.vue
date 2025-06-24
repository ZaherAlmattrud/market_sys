<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="10">
        <v-text-field
          variant="outlined"
          v-model="search"
          label="البحث"
          @input="onSearchInput"
          clearable
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="2">
        <v-text-field
          variant="outlined"
          :value="totalItems"
          label="عدد النتائج"
          readonly
        ></v-text-field>
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
  @update:options="onOptionsUpdate"
  hide-default-footer
>
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>فواتير المبيعات</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                dark
                class="mb-2"
                v-bind="attrs"
                v-on="on"
                variant="outlined"
              >فاتورة جديدة</v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-autocomplete
                        v-model="editedItem.user_id"
                        :items="users"
                        item-title="user_name"
                        item-value="id"
                        label="صاحب الفاتورة"
                        clearable
                        single-line
                        variant="outlined"
                      ></v-autocomplete>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                        v-model="editedItem.notes"
                        label="ملاحظات"
                        variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn variant="outlined" color="blue darken-1" text @click="close">إلغاء</v-btn>
                <v-btn variant="outlined" color="blue darken-1" text @click="save">حفظ</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>

      <template v-slot:item.date="{ item }">
        {{ formatDate(item.date) }}
      </template>

      <template v-slot:item.user_id="{ item }">
        {{  item.user_id }}
            <!-- {{ getUserNameById(item.user_id) }} -->
      </template>

      <template v-slot:item.actions="{ item }">
        <v-icon v-if="loggedIn" large @click="deleteItem(item)">mdi-delete</v-icon>
        <v-icon v-if="loggedIn" large @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon large @click="moveToAccountDetails(item)">mdi-account-eye-outline</v-icon>
      </template>
    </v-data-table>

    <!-- أزرار الباجنيشن -->
    <div class="my-4 d-flex justify-center">
      <v-btn
        v-for="(link, index) in paginationLinks"
        :key="index"
        :disabled="!link.url"
        :color="link.active ? 'primary' : 'default'"
        class="mx-1"
        v-html="link.label"
        @click="goToPage(link.url)"
      ></v-btn>
    </div>
  </v-container>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      loggedIn: false,
      search: "",
      page: 1,
      itemsPerPage: 6,
      totalItems: 0,
      loading: false,
      dialog: false,

      headers: [
        { title: "التسلسل", key: "id", sortable: false },
        { title: "الأسم", key: "user_id", sortable: false },
        { title: "الاجمالي", key: "total", sortable: false },
        { title: "التاريخ", key: "date", sortable: false },
        { title: "الملاحظات", key: "notes", sortable: false },
        { title: "العمليات", key: "actions", sortable: false },
      ],

      items: [],
      users: [],
      editedIndex: -1,
      editedItem: {
        id: 0,
        user_id: "",
        total: "",
        notes: "",
        is_paid: "غير مدفوعة",
      },

      searchTimeout: null,
      paginationLinks: [],
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "فاتورة جديدة" : "تحديث معلومات فاتورة";
    },
  },

  watch: {
    dialog(val) {
      if (!val) this.close();
    },
  },

  async mounted() {
    this.checkLogedIn();
    await this.fetchUsers();
    await this.fetchItems();
  },

  methods: {
    formatDate(dateString) {
      if (!dateString) return "";
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(dateString).toLocaleDateString("ar-EG", options);
    },

    checkLogedIn() {
      this.loggedIn = !!localStorage.getItem("user");
    },

    async fetchUsers() {
      try {
        const response = await axios.get("/api/getAllUsers");
        this.users = response.data;
      } catch (error) {
        console.error("خطأ أثناء تحميل المستخدمين:", error);
      }
    },

    async fetchItems() {
      this.loading = true;
      try {
        const response = await axios.get("/api/getAllSells", {
          params: {
            page: this.page,
            search: this.search,
          },
        });

        this.items = response.data.data;
        this.totalItems = response.data.total || response.data.meta?.total || 0;
        this.itemsPerPage = response.data.per_page || response.data.meta?.per_page || this.itemsPerPage;
        this.paginationLinks = response.data.links || [];

      } catch (error) {
        console.error("حدث خطأ أثناء تحميل البيانات:", error);
      }
      this.loading = false;
    },

    goToPage(url) {
      if (!url) return;
      try {
        const urlObj = new URL(url);
        const pageParam = urlObj.searchParams.get("page");
        if (pageParam) {
          this.page = Number(pageParam);
          this.fetchItems();
        }
      } catch (e) {
        console.error("رابط غير صالح للصفحة:", url);
      }
    },

    onOptionsUpdate(options) {
      let shouldFetch = false;
      if (options.page !== this.page) {
        this.page = options.page;
        shouldFetch = true;
      }
      if (options.itemsPerPage !== this.itemsPerPage) {
        this.itemsPerPage = options.itemsPerPage;
        shouldFetch = true;
      }
      if (shouldFetch) {
        this.fetchItems();
      }
    },

    onSearchInput() {
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        this.page = 1;
        this.fetchItems();
      }, 500);
    },

    getUserNameById(id) {

      console.log("id");
       console.log(id);
      const user = this.users.find((u) => u.id === id);
      return user ? user.user_name : "غير معروف";
    },

    moveToAccountDetails(item) {
      this.$router.push({ name: "sellDetails", params: { sellId: item.id } });
    },

    editItem(item) {
      this.editedIndex = this.items.indexOf(item);
      this.editedItem = { ...item };
      this.dialog = true;
    },

    async deleteItem(item) {
      try {
        const index = this.items.indexOf(item);
        await axios.delete(`/api/deleteSell/${item.id}`);
        if (index > -1) this.items.splice(index, 1);
        this.fetchItems();
      } catch (error) {
        console.error("حدث خطأ أثناء الحذف:", error);
      }
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = {
          id: 0,
          user_id: "",
          total: "",
          notes: "",
          is_paid: "غير مدفوعة",
        };
        this.editedIndex = -1;
      });
    },

    async save() {
      try {
        if (this.editedItem.id === 0) {
          await axios.post("/api/createSell", this.editedItem);
        } else {
          await axios.put(`/api/updateSell/${this.editedItem.id}`, this.editedItem);
        }
        this.fetchItems();
        this.close();
      } catch (error) {
        console.error("حدث خطأ أثناء الحفظ:", error);
      }
    },
  },
};
</script>
