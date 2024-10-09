<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="10">
        <v-text-field    variant="outlined" v-model="search" label="البحث" @input="filterItems"></v-text-field>
      </v-col>
      <v-col cols="12" md="2">
        <v-text-field      variant="outlined">{{ filteredItems.length }}</v-text-field>
      </v-col>
    </v-row>
    <v-data-table
    color="blue"
      :headers="headers"
      :items="filteredItems"
      item-key="id"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>المنتجات</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="550px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                dark
                class="mb-2"
                v-bind="attrs"
                v-on="on"
                @click="dialog = true"
                   variant="outlined"
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
                    <v-col cols="12" md="8" sm="12">
                      <v-text-field
                        v-model="editedItem.name"
                        label="الاسم"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4" sm="12">
                      <v-text-field
                        v-model="editedItem.price"
                        label="الشراء"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="12" sm="12">
                      <v-text-field
                        v-model="editedItem.code"
                        label="الكود"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="4" sm="12">

                      <label>الفاتورة</label>
                      <v-select
                        label="I"
                        v-model="editedItem.invoice_id"
                        :items="invoices"
                        item-title="id"
                        item-value="id"
                        persistent-hint
                        single-line
                      
                        variant="outlined"
                        
                      >    </v-select>
                    
                    </v-col>

                    <v-col cols="12" md="4" sm="12">
                     <label>الصنف</label>
                      <v-select
                        v-model="editedItem.category_id"
                        :items="categories"
                        item-title="name"
                        item-value="id"
                        persistent-hint
                        single-line
                       
                         variant="outlined"
                         outlined
                         dense
                      ></v-select>
                    </v-col>
                    <v-col cols="12" md="4" sm="12">
                      <label>الصورة</label>
                      <v-file-input
                        v-model="editedItem.file"
                        label="الملف"
                          variant="outlined"
                        outlined
                        dense
                      ></v-file-input>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col cols="12" md="8" sm="12">
                      <v-text-field
                        v-model="editedItem.pricr_in_doller"
                        label="السعر بالعملة الاجنبية"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4" sm="12">
                      <v-text-field
                        v-model="editedItem.notes"
                        label="ملاحظات"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col cols="12" md="12" sm="12">
                      <v-text-field
                        v-model="editedItem.sell"
                        label="البيع"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn   variant="outlined" color="blue darken-1" text @click="close">إلغاء</v-btn>
                <v-btn   variant="outlined" color="blue darken-1" text @click="save">حفظ</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon v-if="loggedIn" larg @click="editItem(item)">mdi-pencil</v-icon>

        <v-icon v-if="loggedIn" larg @click="deleteItem(item)">mdi-delete</v-icon>

        <v-icon larg @click="moveToProductImg(item)">mdi-account-eye-outline</v-icon>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      loggedIn : false ,
      invoices: [],
      categories: [],
      search: "",
      dialog: false,
      dialogDelete: false,
      headers: [
      //  { title: "التسلسل", key: "id", sortable: false },
        { title: "الاسم", key: "name", sortable: false , class: "blue" },
        { title: "الكود", key: "code", sortable: false },
        { title: "الشراء", key: "price", sortable: false },
        { title: "الشراء بالعملة الثانوية", key: "price_in_dollar", sortable: false },
        { title: "الشراء بعد الحسم", key: "price_after_descount", sortable: false },
        { title: "الفاتورة", key: "invoice_id", sortable: false },
        { title: "صاحب الفاتورة", key: "suppler", sortable: false },

        { title: "الصنف", key: "category_id", sortable: false },
        { title: "التاريخ", key: "date", sortable: false },
        { title: "الملاحظات", key: "notes", sortable: false },

        // { title: 'المحدث', key: 'updatingPrice', sortable: false },
        { title: "المبيع", key: "sell", sortable: false },
        { title: "العمليات", key: "actions", sortable: false },
      ],
      items: [],
      id: 0,
      editedIndex: -1,
      editedItem: {
        id: 0,
        name: "",
        price: "",
        pricr_in_doller: 0,
        invoice_id: "",
        category_id: "",
        file: "",
        code :0,
        sell: "",
        code: "",
        notes: "",
      },
      defaultItem: {
        id: 0,
        name: "",
        price: "",
        invoice_id: "",
        category_id: "",
        code :0,
        sell: "",
        file: "",
        pricr_in_doller: 0,
      },
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "منتج جديد" : "تحديث معلومات منتج";
    },
    filteredItems() {
      return this.items.filter((item) => {
        return (
          item.name.includes(this.search.toLowerCase()) ||
          item.code.includes(this.search.toUpperCase())
        );
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

  mounted(){

    this.checkLogedIn();
  },

   
  async beforeCreate() {
    
    const response = await axios.get("/api/getAllProducts");
    this.items = response.data;

    const response2 = await axios.get("/api/getAllInvoices");

    this.invoices = response2.data;

    const response3 = await axios.get("/api/getAllCategories");

    this.categories = response3.data;
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
    moveToProductImg(item) {
      if (item.id > 560) {
        window.open(
          window.location.origin+`/api/getProductImgLink/${item.id}`,
          "_blank",
          "noopener,noreferrer"
        );
      } else {
        this.$router.push({ name: "ProductImage", params: { url: item.id } });
      }
    },
    filterItems() {
      // This will automatically filter items as search input changes
    },
    editItem(item) {
      // this.editedIndex = this.items.indexOf(item);
      // this.editedItem = Object.assign({}, item);
      // this.dialog = true;

      this.id = item.id;
      this.editedIndex = this.items.indexOf(item);
      this.editedItem = Object.assign({}, item);

      this.dialog = true;
    },
    async deleteItem(item) {
      console.log("delete api");
      console.log(item);
      const index = this.items.indexOf(item);
      this.items.splice(index, 1);
      await axios.delete(`/api/deleteProduct/${item.id}`);
    },
    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
    save() {
      // this.dialog = true;
      // if (this.editedIndex > -1) {
      //     Object.assign(this.items[this.editedIndex], this.editedItem);
      // } else {
      //     this.items.push(this.editedItem);
      // }
      // this.close();

      this.dialog = true;

      if (this.id == 0) {
        // create new area

        console.log("create");
        console.log( this.editedItem );

        // add to local data array

        const response = axios.post("/api/createProduct", this.editedItem, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });

       
        this.items.push(this.editedItem);

      } else {
        // update current area

        console.log("update");
        console.log("update");
        Object.assign(this.items[this.editedIndex], this.editedItem); // update local data
        const response = axios.put("/api/updateProduct/" + this.id, 
        
        this.editedItem


        // , {
        //   headers: {
        //     "Content-Type": "multipart/form-data",
        //   },
        // }
      
      ); // update in data base
      
      }

      this.close();
    },
  },
};
</script>
