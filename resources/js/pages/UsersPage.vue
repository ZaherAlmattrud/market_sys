<template>
  <v-container>
    <!-- زر إضافة مستخدم جديد -->
    <v-row class="mb-4" justify="end">
      <v-btn color="primary" @click="openAddUserDialog">
        <v-icon left>mdi-account-plus</v-icon>
        مستخدم جديد
      </v-btn>
    </v-row>

    <!-- البحث وعدد العناصر -->
    <v-row>
      <v-col cols="12" md="10">
        <v-text-field
          v-model="search"
          label="البحث"
          variant="outlined"
          @input="onSearch"
        />
      </v-col>
      <v-col cols="12" md="2">
        <v-text-field
          :value="totalItems"
          label="عدد النتائج"
          variant="outlined"
          readonly
        />
      </v-col>
    </v-row>

    <!-- جدول المستخدمين -->
    <v-table class="mt-4">
      <thead>
        <tr>
          <th>التسلسل</th>
          <th>الاسم</th>
          <th>رقمه بالدفتر</th>
          <!-- <th>نوع المستخدم</th> -->
          <th>البلدة</th>
          <th>موبايل</th>
          <th>العمليات</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in items" :key="item.id">
          <td>{{ item.id }}</td>
          <td>{{ item.user_name }}</td>
          <td>{{ item.number_in_book }}</td>
          <!-- <td>{{ item.user_type }}</td> -->
          <td>{{ item.area }}</td>
          <td>{{ item.mobile }}</td>
          <td>
            <v-icon v-if="loggedIn" @click="deleteItem(item)">mdi-delete</v-icon>
            <v-icon v-if="loggedIn" @click="clearAccount(item)">mdi-notebook-remove-outline</v-icon>
            <v-icon v-if="loggedIn" @click="editItem(item)">mdi-pencil</v-icon>
            <v-icon @click="moveToAccountDetails(item)">mdi-book-open-page-variant-outline</v-icon>
            <v-icon @click="moveToAccountSummary(item)">mdi-account-eye-outline</v-icon>
          </td>
        </tr>
      </tbody>
    </v-table>

    <!-- الباجنشن -->
    <v-row class="mt-4" justify="center" align="center">
      <v-btn :disabled="currentPage === 1" @click="prevPage" variant="outlined">السابق</v-btn>
      <span class="mx-4">صفحة {{ currentPage }} من {{ totalPages }}</span>
      <v-btn :disabled="currentPage === totalPages" @click="nextPage" variant="outlined">التالي</v-btn>
    </v-row>

    <!-- مودال إضافة مستخدم -->
    <v-dialog v-model="addUserDialog" max-width="500px">
      <v-card>
        <v-card-title>إضافة مستخدم جديد</v-card-title>
        <v-card-text>
          <v-text-field label="الاسم" v-model="newUser.user_name" />
          <v-text-field label="رقم الدفتر" v-model="newUser.number_in_book" />
          <v-text-field label="نوع المستخدم" v-model="newUser.user_type" />
          <v-text-field label="البلدة" v-model="newUser.area" />
          <v-text-field label="الموبايل" v-model="newUser.mobile" />
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="green" @click="saveNewUser">حفظ</v-btn>
          <v-btn color="red" @click="addUserDialog = false">إغلاق</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- مودال تعديل مستخدم -->
    <v-dialog v-model="editDialog" max-width="500px">
      <v-card>
        <v-card-title>تعديل المستخدم</v-card-title>
        <v-card-text>
          <v-text-field label="الاسم" v-model="editedItem.user_name" />
          <v-text-field label="رقم الدفتر" v-model="editedItem.number_in_book" />
          <v-text-field label="نوع المستخدم" v-model="editedItem.user_type" />
          <v-text-field label="البلدة" v-model="editedItem.area" />
          <v-text-field label="الموبايل" v-model="editedItem.mobile" />
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="green" @click="updateUser">تحديث</v-btn>
          <v-btn color="red" @click="editDialog = false">إغلاق</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      items: [],
      totalItems: 0,
      search: "",
      pageSize: 6,
      currentPage: 1,
      totalPages: 1,
      loggedIn: false,

      // للمودالات
      addUserDialog: false,
      editDialog: false,

      // بيانات المستخدم الجديد
      newUser: {
        user_name: "",
        number_in_book: "",
        user_type: "",
        area: "",
        mobile: "",
      },

      // بيانات التعديل
      editedItem: {},
    };
  },
  methods: {
    async loadUsers() {
      try {
        const response = await axios.get("/api/getAllUserWithPagination", {
          params: {
            page: this.currentPage,
            pageSize: this.pageSize,
            search: this.search,
          },
        });

        this.items = response.data.items;
        this.totalItems = response.data.total;
        this.totalPages = Math.ceil(this.totalItems / this.pageSize);
      } catch (error) {
        console.error("حدث خطأ أثناء تحميل البيانات:", error);
      }
    },

    onSearch() {
      this.currentPage = 1;
      this.loadUsers();
    },

    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.loadUsers();
      }
    },

    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.loadUsers();
      }
    },

    checkLogedIn() {
      const user = localStorage.getItem("user");
      this.loggedIn = !!user;
    },

    moveToAccountDetails(item) {
      this.$router.push({ name: "accountDetails", params: { accountId: item.account } });
    },

    moveToAccountSummary(item) {
      this.$router.push({ name: "accountSummary", params: { accountId: item.account } });
    },

    async deleteItem(item) {
      try {
        await axios.delete(`/api/deleteUser/${item.id}`);
        this.loadUsers();
      } catch (error) {
        console.error("فشل حذف المستخدم:", error);
      }
    },

    async clearAccount(item) {
      try {
        await axios.delete(`/api/clearAccount/${item.id}`);
        this.loadUsers();
      } catch (error) {
        console.error("فشل تفريغ الحساب:", error);
      }
    },

    openAddUserDialog() {
      this.newUser = {
        user_name: "",
        number_in_book: "",
        user_type: "",
        area: "",
        mobile: "",
      };
      this.addUserDialog = true;
    },

    async saveNewUser() {
      try {
        await axios.post("/api/createUser", this.newUser);
        this.addUserDialog = false;
        this.loadUsers();
      } catch (error) {
        console.error("فشل إضافة المستخدم:", error);
      }
    },

    editItem(item) {
      this.editedItem = { ...item };
      this.editDialog = true;
    },

    async updateUser() {
      try {
        await axios.put(`/api/updateUser/${this.editedItem.id}`, this.editedItem);
        this.editDialog = false;
        this.loadUsers();
      } catch (error) {
        console.error("فشل تحديث المستخدم:", error);
      }
    },
  },
  mounted() {
    this.checkLogedIn();
    this.loadUsers();
  },
};
</script>
