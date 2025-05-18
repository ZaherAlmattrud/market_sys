<template>
  <div class="container">
    <!-- البحث + زر الإضافة -->
    <div class="search-row">
      <input
        v-model="search"
        @input="fetchProducts()"
        placeholder="🔎 ابحث..."
        class="search-input"
      />
      <button @click="openForm" class="add-button">
        <v-icon small class="mr-1">mdi-plus</v-icon>
        إضافة منتج جديد
      </button>
    </div>

    <!-- مودال الفورم -->
    <v-dialog v-model="dialog" max-width="500px" persistent>
      <v-card>
        <v-card-title>
          {{ form.id ? "تعديل المنتج" : "إضافة منتج جديد" }}
          <v-spacer></v-spacer>
          <v-btn icon @click="closeForm">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>

        <v-card-text>
          <BaseForm
            v-model="form"
            :fields="fields"
            :submit-label="form.id ? 'تحديث' : 'إضافة'"
            :show-cancel="true"
            @submit="submitProduct"
            @cancel="closeForm"
          />
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- جدول المنتجات -->
    <table>
      <thead>
        <tr>
          <th>التسلسل</th>
          <th>الاسم</th>
          <th>السعر</th>
          <th>العمليات</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products.data" :key="product.id">
          <td>{{ product.id }}</td>
          <td>{{ product.name }}</td>
          <td>{{ product.price }}</td>
          <td>
            <v-icon small class="mr-2" color="primary" @click="edit(product)">mdi-pencil</v-icon>
            <v-icon small class="mr-2" color="red" @click="destroy(product.id)">mdi-delete</v-icon>
            <v-icon small color="info" @click="viewDetails(product.id)">mdi-information</v-icon>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- أزرار التصفح -->
    <div v-if="products.links && products.links.length" class="pagination">
      <button
        v-for="(link, index) in products.links"
        :key="index"
        v-html="link.label"
        :disabled="!link.url"
        :class="{ active: link.active }"
        @click="goToPage(link.url)"
      ></button>
    </div>
  </div>
</template>

<script>
import BaseForm from "@/components/FormComponent.vue";

export default {
  name: "App",
  components: { BaseForm },
  data() {
    return {
      products: { data: [], links: [], current_page: 1, last_page: 1 },
      form: { id: null, name: "", price: "" },
      search: "",
      dialog: false,
      fields: [
        {
          name: "name",
          label: "اسم المنتج",
          component: "VTextField",
          type: "text",
          required: true,
          rules: [(v) => !!v || "هذا الحقل مطلوب"],
        },
        {
          name: "price",
          label: "السعر",
          component: "VTextField",
          type: "number",
          required: true,
          rules: [
            (v) => !!v || "هذا الحقل مطلوب",
            (v) => v > 0 || "السعر يجب أن يكون أكبر من صفر",
          ],
        },
      ],
    };
  },
  mounted() {
    this.fetchProducts();
  },
  methods: {
    fetchProducts(page = 1) {
      axios
        .get(`/api/getAllProducts?page=${page}&search=${this.search}`)
        .then((res) => {
          this.products = res.data;
        });
    },
    openForm() {
      this.resetForm();
      this.dialog = true;
    },
    closeForm() {
      this.dialog = false;
      this.resetForm();
    },
    submitProduct() {
      const method = this.form.id ? "put" : "post";
      const url = this.form.id
        ? `/api/products/${this.form.id}`
        : `/api/products`;

      axios[method](url, this.form).then(() => {
        this.dialog = false;
        this.resetForm();
        this.fetchProducts(this.products.current_page);
      });
    },
    edit(product) {
      this.form = { ...product };
      this.dialog = true;
    },
    viewDetails(id) {
      this.$router.push({ name: "productDetails", params: { id } });
    },
    destroy(id) {
      if (confirm("هل تريد حذف هذا المنتج؟")) {
        axios.delete(`/api/products/${id}`).then(() => {
          this.fetchProducts(this.products.current_page);
        });
      }
    },
    resetForm() {
      this.form = { id: null, name: "", price: "" };
    },
    goToPage(url) {
      if (!url) return;
      const page = new URL(url).searchParams.get("page");
      this.fetchProducts(page);
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 900px;
  margin: 50px auto;
  font-family: 'Roboto', sans-serif;
  padding: 0 15px;
  direction: rtl;
}

.search-row {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
}

.search-input {
  flex: 3;
  padding: 10px 12px;
  font-size: 14px;
  border: 1px solid #c0c0c0;
  border-radius: 4px;
  outline: none;
  transition: border-color 0.3s ease;
}

.search-input:focus {
  border-color: #1976d2;
  box-shadow: 0 0 5px rgba(25, 118, 210, 0.5);
}

.add-button {
  flex: 1;
  padding: 10px 14px;
  font-size: 14px;
  white-space: nowrap;
  cursor: pointer;
  border: none;
  border-radius: 4px;
  background-color: #1976d2;
  color: white;
  transition: background-color 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.add-button:hover {
  background-color: #115293;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  overflow: hidden;
}

thead {
  background-color: #f5f5f5;
  color: #424242;
  font-weight: 600;
}

table th,
table td {
  padding: 12px 15px;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
}

tbody tr:hover {
  background-color: #e3f2fd;
  cursor: pointer;
}

.pagination {
  margin-top: 20px;
  text-align: center;
}

.pagination button {
  min-width: 36px;
  margin: 0 3px;
  padding: 6px 10px;
  font-size: 14px;
  border-radius: 4px;
  background-color: #eeeeee;
  color: #424242;
  border: 1px solid transparent;
  transition: all 0.3s ease;
}

.pagination button:hover:not(:disabled) {
  background-color: #1976d2;
  color: white;
  border-color: #1976d2;
}

.pagination button.active {
  background-color: #1976d2;
  color: white;
  font-weight: 600;
  border-color: #115293;
}

.pagination button:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}
</style>
