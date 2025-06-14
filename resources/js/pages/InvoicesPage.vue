<template>
  <v-app>
    <v-main>
      <v-container>
        <!-- مربع البحث وزر الإضافة -->
        <v-row>

            <v-col cols="2" class="d-flex align-center">
            <v-btn color="primary" class="text-white" block style="height: 55px;" @click="openAddDialog">
              جديد
            </v-btn>
          </v-col>

          <v-col cols="10">
            <v-text-field variant="outlined" v-model="search" label="صاحب الفاتورة" @input="onSearchInput" clearable
              dense style="height: 40px;" 
              />
          </v-col>
        
        </v-row>


        <!-- عرض الفواتير -->
        <v-row>
          <v-col cols="12" md="4" v-for="item in items" :key="item.id">
            <v-card class="transparent-card" elevation="0">
              <v-card-title class="text-h6">
                فاتورة مشتريات رقم #{{ item.id }}
              </v-card-title>
              <v-card-text>
                <div><strong>القيمة الإجمالية:</strong> {{ item.total }}</div>
                <div><strong>مصدر الفاتورة:</strong> {{ getUserName(item.account_id) }}</div>
                <div><strong>التاريخ:</strong> {{ item.date }}</div>
              </v-card-text>
              <v-card-actions>
                <v-btn icon @click="editItem(item)">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn icon @click="deleteItem(item)">
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
                <v-btn icon @click="moveToInvoiceImg(item)">
                  <v-icon>mdi-invoice-text-outline</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>

        <!-- روابط الصفحات -->
        <v-row>
          <v-col cols="12" class="text-center">
            <v-btn v-for="link in links" :key="link.label" :disabled="!link.url" @click="goToPage(link)" text
              v-html="link.label" />
          </v-col>
        </v-row>

        <!-- نموذج الإضافة/التعديل باستخدام BaseForm -->
        <v-dialog v-model="dialog" max-width="600">
          <v-card class="pa-4 rounded-lg" elevation="2">
            <v-card-title class="text-h5 pb-4">
              {{ editedItem.id ? "تعديل فاتورة مشتريات" : "إضافة فاتورة مشتريات" }}
            </v-card-title>
            <BaseForm v-model="editedItem" :fields="invoiceFields" :grid-cols="1" submit-label="حفظ"
              cancel-label="إلغاء" :show-cancel="true" @submit="save" @cancel="close" />
          </v-card>
        </v-dialog>

      </v-container>
    </v-main>
  </v-app>
</template>
<script>
import BaseForm from "@/components/FormComponent.vue";

export default {
  components: { BaseForm },
  data() {
    return {
      items: [],
      users: [],
      search: "",
      meta: {},
      links: [],
      currentPage: 1,
      dialog: false,
      editedIndex: -1,
      editedItem: {
        account_id: "",
        total: "",
        date: new Date().toISOString().slice(0, 10),
        photo: null,
      },
      invoiceFields: [

        {
          name: "account_id",
          label: "مصدر الفاتورة",
          component: "VSelect",
          items: [],
          itemTitle: "user_name",
          itemValue: "id",
          required: true,
        },
        { name: "total", label: "القيمة", type: "number", required: true },
        { name: "date", label: "التاريخ", type: "date", required: true },

        { name: "photo", label: "ملف الفاتورة", component: "cameraOrFile" },  // هنا التعديل
      ],
    };
  },
  methods: {
    async fetchInvoices(page = 1) {
      try {
        const response = await axios.get("/api/getAllInvoices", {
          params: { page, search: this.search },
        });
        this.items = response.data.data;
        this.meta = response.data.meta;
        this.links = response.data.links;
        this.currentPage = page;
      } catch (error) {
        console.error("Failed to fetch invoices:", error);
      }
    },
    async fetchUsers() {
      try {
        const response = await axios.get("/api/getAllUsers");
        this.users = response.data;
        // تحديث الخيارات داخل الحقول
        const userField = this.invoiceFields.find(f => f.name === "account_id");
        if (userField) userField.items = this.users;
      } catch (error) {
        console.error("Failed to fetch users:", error);
      }
    },
    getUserName(id) {
      const user = this.users.find((u) => u.id === id);
      return user ? user.user_name : "---";
    },
    goToPage(link) {
      if (link.url) {
        const url = new URL(link.url);
        const page = url.searchParams.get("page");
        this.fetchInvoices(page);
      }
    },
    onSearchInput() {
      this.fetchInvoices(1);
    },
    openAddDialog() {
      this.editedItem = {
        
        account_id: "",
        total: "",
        date: new Date().toISOString().slice(0, 10),
        photo: null,
      };
      this.dialog = true;
    },
    editItem(item) {
      this.editedItem = { ...item };
      this.dialog = true;
    },
    async deleteItem(item) {
      if (!confirm("هل أنت متأكد من حذف الفاتورة؟")) return;
      try {
        await axios.delete(`/api/deleteInvoice/${item.id}`);
        this.fetchInvoices(this.currentPage);
      } catch (error) {
        console.error("Delete failed:", error);
      }
    },
    moveToInvoiceImg(item) {
 
        this.$router.push({ name: "invoiceImg", params: { id: item.id } });
      
    },
    close() {
      this.dialog = false;
    },
    async save(formData) {
      try {
        const payload = new FormData();
        for (const key in formData) {
          payload.append(key, formData[key]);
        }

        if (!formData.id) {
          await axios.post("/api/createInvoice", payload, {
            headers: { "Content-Type": "multipart/form-data" },
          });
        } else {
          await axios.post(`/api/updateInvoice/${formData.id}`, payload, {
            headers: { "Content-Type": "multipart/form-data" },
          });
        }

        this.fetchInvoices(this.currentPage);
        this.close();
      } catch (error) {
        console.error("Save failed:", error);
      }
    },
  },
  mounted() {
    this.fetchUsers();
    this.fetchInvoices();
  },
};
</script>
