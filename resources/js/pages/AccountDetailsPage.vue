<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="12">
        <img src="/public/logo.png" height="150" />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="8" md="8">
        <v-text-field variant="outlined"> الســـــــــــيد : {{ userName }}</v-text-field>
      </v-col>

      <v-col cols="4" md="4">
        <v-text-field variant="outlined">
          رقم الفاتورة : {{ this.$route.params.accountId }}</v-text-field>
      </v-col>
    </v-row>

    <v-row v-if="!isPrintMode">
      <v-col cols="12" md="12">
        <v-text-field variant="outlined" v-model="search" label="البحث" @input="filterItems"></v-text-field>
      </v-col>
    </v-row>

    <v-data-table
      :headers="headers"
      :items="filteredItems"
      :items-per-page="5000"
      item-key="id"
      class="elevation-1"
      hide-default-footer
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title class="dataTableTitle"> تفاصيل الفاتورة</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="600px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                dark
                class="newItemButton mb-2 hidenAtPrint"
                v-bind="attrs"
                v-on="on"
                @click="dialog = true"
                variant="outlined"
              >
                بيان جديد
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="12" md="12">
                      <v-combobox
                        v-model="editedItem.name"
                        :items="products.map((p) => p.name)"
                        item-title="name"
                        item-value="id"
                        label="المنتج"
                        placeholder="اختر المنتج"
                        clearable
                        @update:modelValue="onProductSelected"
                        variant="outlined"
                      />
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model.number="editedItem.quantity"
                        label="الكمية"
                        type="number"
                        min="1"
                        @input="onQuantityOrSellChange"
                        variant="outlined"
                      />
                    </v-col>

                    <v-col cols="6" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.price_after_descount"
                        label="الشراء"
                        disabled
                        variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model.number="editedItem.sell"
                        label="الإفرادي"
                        @input="onQuantityOrSellChange"
                        variant="outlined"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model.number="editedItem.total"
                        label="الإجمالي"
                        variant="outlined"
                        readonly
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                  @keyup.enter="enterClickEvent"
                  variant="outlined"
                  color="blue darken-1"
                  text
                  @click="save"
                >
                  حفظ
                </v-btn>
                <v-btn variant="outlined" color="blue darken-1" text @click="close">
                  إلغاء
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-if="!isPrintMode" v-slot:item.actions="{ item }">
        <v-icon v-if="loggedIn" size="small" @click="deleteItem(item)">mdi-delete</v-icon>
        <v-icon v-if="loggedIn" larg @click="editItem(item)">mdi-pencil</v-icon>
      </template>
    </v-data-table>

    <v-row>
      <v-col cols="6" md="6">
        <v-text-field variant="outlined">
          الاجمالي : {{ invoiceTotal }} {{ currency }}
        </v-text-field>
      </v-col>

      <v-col cols="6" md="6">
        <v-text-field variant="outlined">{{ dateNow }}</v-text-field>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      dateNow:
        new Date().getFullYear() +
        "-" +
        (new Date().getMonth() + 1) +
        "-" +
        new Date().getDate() +
        " | " +
        new Date().getHours() +
        " : " +
        new Date().getMinutes(),

      sellId: 0,
      isPrintMode: false,
      userName: "صاحب الفاتورة",
      currency: "ليرة سورية",
      invoiceTotal: 0,
      loggedIn: false,

      search: "",
      dialog: false,
      headers: [
        { title: "التسلسل", key: "identity", sortable: false },
        { title: " البيــــــــــــان ", key: "name", sortable: false },
        { title: "السعر الإفرادي", key: "sell", sortable: false },
        { title: "الكمية", key: "quantity", sortable: false },
        { title: "القيمة الإجمالية", key: "total", sortable: false },
        { title: "العمليات", key: "actions", sortable: false },
      ],
      products: [],
      items: [],
      editedIndex: -1,

      editedItem: {
        id: 0,
        total: 0,
        name: "",
        quantity: 1,
        price: 0,
        sell: 0,
        price_after_descount: 0,
      },

      defaultItem: {
        id: 0,
        total: 0,
        name: "",
        quantity: 1,
        price: 0,
        sell: 0,
        price_after_descount: 0,
      },
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "بيان جديد" : "تحديث معلومات بيان";
    },
    filteredItems() {
      return this.items
        .filter((item) => item.name.includes(this.search.toLowerCase()))
        .map((item, index) => ({
          ...item,
          identity: index + 1, // الرقم التسلسلي هنا
        }));
    },
  },

  watch: {
    dialog(val) {
      if (!val) this.close();
    },
  },

  async beforeCreate() {
    const sellId = this.$route.params.accountId;
    this.sellId = sellId;

    try {
      const productsResponse = await axios.get("/api/getAllProductsForList");
      this.products = productsResponse.data;

      const sellResponse = await axios.get("/api/getAllSellDetails/" + sellId);
      this.items = sellResponse.data["data"];
      this.invoiceTotal = sellResponse.data["total"];
      this.userName = sellResponse.data["userName"];
      this.currency = sellResponse.data["currency"];
    } catch (error) {
      console.error("Error loading data", error);
    }
  },

  mounted() {
    this.mediaQueryList = window.matchMedia("print");
    this.mediaQueryList.addEventListener("change", this.updatePrintMode);
    this.checkLogedIn();
  },

  beforeUnmount() {
    this.mediaQueryList.removeEventListener("change", this.updatePrintMode);
  },

  methods: {
    enterClickEvent() {
      this.save();
    },

    updatePrintMode(event) {
      this.isPrintMode = event.matches;
    },

    // استدعي هذي الدالة عند تغير الكمية أو السعر اليدوي لتحديث الإجمالي
    onQuantityOrSellChange() {
      if (this.editedItem.quantity < 1) this.editedItem.quantity = 1;
      this.editedItem.total = this.editedItem.sell * this.editedItem.quantity;
    },

    async onProductSelected(productName) {
      const product = this.products.find((p) => p.name === productName);

      if (!product) {
        this.editedItem.price = 0;
        this.editedItem.price_after_descount = 0;
        this.editedItem.sell = 0;
        this.editedItem.total = 0;
        return;
      }

      this.editedItem.id = product.id;

      try {
        const response = await axios.get(`/api/products/${product.id}/price`);

        // جلب السعرين
        this.editedItem.sell = response.data.dynamic_sell_in_sp;
        this.editedItem.price_after_descount = response.data.price_in_sp_after_descount;

        // إذا كانت الكمية موجودة حدث الإجمالي
        if (this.editedItem.quantity) {
          this.editedItem.total = this.editedItem.sell * this.editedItem.quantity;
        }
      } catch (error) {
        console.error("Failed to fetch price:", error);
        this.editedItem.sell = 0;
        this.editedItem.price_after_descount = 0;
        this.editedItem.total = 0;
      }
    },

    editItem(item) {
      this.editedIndex = this.items.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    async deleteItem(item) {
      const index = this.items.indexOf(item);
      if (index > -1) {
        this.items.splice(index, 1);
      }

      // إعادة حساب المجموع بعد الحذف
      this.invoiceTotal = this.items.reduce((sum, i) => sum + i.total, 0);

      try {
        await axios.delete(`/api/deleteSellDetail/${item.id}`);
      } catch (error) {
        console.error("Failed to delete item:", error);
      }
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    checkLogedIn() {
      const loggedIn = localStorage.getItem("user");
      this.loggedIn = !!loggedIn;
    },

    filterItems() {
      // الفلترة تتم تلقائيًا في computed property filteredItems
    },

    async save() {
      if (this.editedItem.quantity < 1) {
        alert("الكمية يجب أن تكون 1 أو أكثر");
        return;
      }

      if (this.editedIndex === -1) {
        // إضافة بيان جديد
        try {
          await axios.post("/api/createSellDetail/" + this.sellId, this.editedItem);
          this.items.push(Object.assign({}, this.editedItem));
        } catch (error) {
          console.error("Failed to create item:", error);
          alert("حدث خطأ أثناء حفظ البيان الجديد.");
        }
      } else {
        // تحديث بيان موجود
        try {
          await axios.put("/api/updateSellDetail/" + this.editedItem.id, this.editedItem);
          Object.assign(this.items[this.editedIndex], this.editedItem);
        } catch (error) {
          console.error("Failed to update item:", error);
          alert("حدث خطأ أثناء تحديث البيان.");
        }
      }

      // إعادة حساب الإجمالي بعد الحفظ
      this.invoiceTotal = this.items.reduce((sum, i) => sum + i.total, 0);

      this.close();
    },
  },
};
</script>

<style scoped>
.userName {
  font-size: 3rem !important;
}

.dataTableTitle {
  font-size: 1rem !important;
}

@media print {
  .newItemButton {
    display: none;
  }

  .hidenAtPrint {
    display: none;
  }

  .operation {}
}
</style>
