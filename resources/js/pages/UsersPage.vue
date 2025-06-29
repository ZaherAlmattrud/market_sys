<template>
  <v-container>
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

    <!-- جدول يدوي -->
    <v-table class="mt-4">
      <thead>
        <tr>
          <th>التسلسل</th>
          <th>الاسم</th>
          <th>رقمه بالدفتر</th>
          <th>نوع المستخدم</th>
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
          <td>{{ item.user_type }}</td>
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

    editItem(item) {
      // افتح حوار التعديل هنا أو انتقل لصفحة أخرى
      console.log("تعديل المستخدم:", item);
    },
  },
  mounted() {
    this.checkLogedIn();
    this.loadUsers();
  },
};
</script>
