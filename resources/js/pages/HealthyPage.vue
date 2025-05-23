<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="4">
        <v-text-field v-model="search" label="البحث" @input="filterItems"></v-text-field>
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
          <v-toolbar-title>الأدوات الصحية</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                dark
                class="mb-2"
                v-bind="attrs"
                v-on="on"
                @click="dialog = true"
                >منتج جديد</v-btn
              >
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" md="4" sm="12">
                      <v-text-field
                        v-model="editedItem.name"
                        label="الاسم"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4" sm="12">
                      <v-text-field
                        v-model="editedItem.price"
                        label="الشراء"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4" sm="12">
                      <v-file-input
                        v-model="editedItem.img"
                        label="الصورة"
                        outlined
                        dense
                        capture="user"
                        accept="image/*"
                      ></v-file-input>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="4" sm="12">
                      <v-select
                        v-model="editedItem.invoice"
                        :items="invoices"
                        label="الفاتورة"
                      ></v-select>
                    </v-col>

                    <v-col cols="12" md="4" sm="12">
                      <v-select
                        v-model="editedItem.category"
                        :items="categories"
                        label="الصنف"
                      ></v-select>
                    </v-col>
                    <v-col cols="12" md="4" sm="12">
                      <v-text-field
                        v-model="editedItem.sell"
                        label="البيع"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">إلغاء</v-btn>
                <v-btn color="blue darken-1" text @click="save">حفظ</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon larg @click="editItem(item)">mdi-pencil</v-icon>

        <v-icon larg @click="deleteItem(item)">mdi-delete</v-icon>

        <v-icon larg @click="editItem(item)">mdi-account-eye-outline</v-icon>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      loggedIn: false,
      areas: ["حزرما", "النشابية", "نولة"],
      search: "",
      dialog: false,
      dialogDelete: false,
      headers: [
        { title: "الاسم", key: "name", sortable: false },
        { title: "الشراء", key: "price", sortable: false },
        { title: "الشراء بعد الحسم", key: "price_after_descount", sortable: false },
        { title: "التاريخ", key: "date", sortable: false },
        { title: "المبيع", key: "sell", sortable: false },
        { title: "العمليات", key: "actions", sortable: false },
      ],
      items: [],
      id: 0,
      editedIndex: -1,
      editedItem: {
        id: 0,
        name: "",
        pricr: "",
        invoice_id: "",
        category_id: "",
        img: "",
        sell: "",
      },
      defaultItem: {
        id: 0,
        name: "",
        pricr: "",
        invoice_id: "",
        category_id: "",
        img: "",
        sell: "",
      },
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "منتج جديد" : "تحديث معلومات منتج";
    },
    filteredItems() {
      return this.items.filter((item) => {
        return item.name.toLowerCase().includes(this.search.toLowerCase());
      });
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  async beforeCreate() {
    
    const response = await axios.get("/api/getAllProductsHealthy");
    this.items = response.data;
  },

  mounted() {
    this.checkLogedIn();
  },
  methods: {
    checkLogedIn() {
      const loggedIn = localStorage.getItem("user");
      if (loggedIn) {
        this.loggedIn = true;
      } else {
        this.loggedIn = false;
      }
    },
    filterItems() {
      // This will automatically filter items as search input changes
    },
    editItem(item) {
      this.editedIndex = this.items.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem(item) {
      const index = this.items.indexOf(item);
      confirm("Are you sure you want to delete this item?") &&
        this.items.splice(index, 1);
    },
    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
    save() {
      this.dialog = true;
      if (this.editedIndex > -1) {
        Object.assign(this.items[this.editedIndex], this.editedItem);
      } else {
        this.items.push(this.editedItem);
      }
      this.close();
    },
  },
};
</script>
