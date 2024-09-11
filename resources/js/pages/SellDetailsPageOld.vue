<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="1">
        <v-button @click="$router.go(-1)">كافة المبيعات</v-button>
      </v-col>

      <v-col cols="12" md="5">
        <v-text-field v-model="search" label="البحث" @input="filterItems"></v-text-field>
      </v-col>

      <v-col cols="12" md="3">
        <v-text-field>  {{ sellId }} </v-text-field>
      </v-col>

      <v-col cols="12" md="3">
        <v-text-field> {{ total }} : الاجمالي </v-text-field>
      </v-col>
    </v-row>

    <v-data-table
      :headers="headers"
      :items="filteredItems"
      :items-per-page="5000"
      item-key="id"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>تفاصيل الفاتورة</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="600px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                dark
                class="mb-2"
                v-bind="attrs"
                v-on="on"
                @click="dialog = true"
              >
                بيان جديد</v-btn
              >

              <!-- <h6>مركز المطرود التجاري</h6> -->
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="selectedItem.salePriceOne"
                        label="الإفرادي"
                        @change="updatePriceAllatUpdate"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6" md="6">
                      <v-autocomplete
                        v-model="selectedItem.description"
                        :items="products"
                        item-title="name"
                        item-value="id"
                        label="المنتج"
                        placeholder="المنتج"
                        crearable
                        @update:modelValue="updatePriceOne"
                      >
                      </v-autocomplete>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="selectedItem.salePriceAll"
                        label="الإجمالي"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="selectedItem.quantity"
                        label="الكمية"
                        @change="updatePriceAll"
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
        <v-icon v-if="loggedIn" size="small" @click="deleteItem(item)">
          mdi-delete
        </v-icon>
        <v-icon v-if="loggedIn" larg @click="editItem(item)">mdi-pencil</v-icon>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
import MoneyFormat from "vue-money-format";
export default {
  components: {
    "money-format": MoneyFormat,
  },

  data() {
    return {

      sellId : 0 ,
      user_id : 0 ,
      loggedIn: false,
      book_number: 0,
      account_persion: "",
      total: 0,
      invoices: 0,
      book: 0,
      paids: 0,
      arresteds: 0,
      debts: 0,

      id: 0,
      search: "",
      dialog: false,
      dialogDelete: false,
      headers: [
        { title: "التسلسل", key: "id", sortable: false },
        { title: " البيــــــــــــان ", key: "description", sortable: false },
        { title: "القيمة الإجمالية", key: "total", sortable: false },
        { title: "السعر الإفرادي", key: "price", sortable: false },

        { title: "الكمية", key: "quantity", sortable: false },
        { title: "التاريخ", key: "date", sortable: false },

        { title: "العمليات", key: "actions", sortable: false },
      ],

      products:[],
      items: [],
      selectedItem: {
        id : 0 ,
        sellId : 0 ,
        description: 0,
        price: 0,
        total: 0,
        quantity :  1,
      },
      salePriceOne: 0,
      salePriceAll: 0,
      editedIndex: -1,
      editedItem: {
        id: 0,
        total: "",
        description: "",
        quantity: "",

        price: "",
      },
      defaultItem: {
        id: 1,
        total: "",
        description: "",
        quantity: "",
        price: "",
      },
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "بيان جديد" : "تحديث معلومات بيان";
    },
    filteredItems() {
      // return this.items.filter((item) => {
      //   return true ;
      // });
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
    
    this.sellId =   this.$route.params.sellId;
    

    // console.log("account id");
    // console.log(accountId);
    const response = await axios.get("/api/getAllProducts");
    this.products = response.data; // 

    // this.invoices = response.data["invoices"];
    // this.total = response.data["total"];
    // this.book = response.data["book"];
    // this.paids = response.data["paids"];
    // this.arresteds = response.data["arresteds"];
    // this.debts = response.data["debts"];
    // this.account_persion = response.data["account_persion"];
    // this.book_number = response.data["book_number"];
  },

  mounted() {
    this.checkLogedIn();
  },
  methods: {
    updatePriceOne() {
      const item = this.products.find((i) => i.id === this.selectedItem.id);

      if (item) {
        this.selectedItem.salePriceOne = item.sell;
        this.selectedItem.salePriceAll = item.sell;
      }

      console.log("ittttttttttttttttttttem : ".item);
    },
    updatePriceAll() {

      this.selectedItem.salePriceAll =  this.selectedItem.salePriceOne *  this.selectedItem.quantity ;

    },

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
      // this.editedIndex = this.items.indexOf(item);
      // this.editedItem = Object.assign({}, item);
      // this.dialog = true;

      this.id = item.id;
      this.editedIndex = this.items.indexOf(item);
      this.editedItem = Object.assign({}, item);

      this.dialog = true;
    },
    async deleteItem(item) {
      // const index = this.items.indexOf(item);
      // confirm('Are you sure you want to delete this item?') && this.items.splice(index, 1);

      console.log("delete api");
      console.log(item);
      const index = this.items.indexOf(item);
      this.items.splice(index, 1);
      await axios.delete(`/api/deleteAccountDetail/${item.id}`);
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
        // add to local data array
        const response = axios.post(
          "/api/createAccountDetail/" + this.$route.params.accountId,
          this.editedItem
        ); // add to data base
        this.items.push(this.selectedItem);
      } else {
        // update current area

        console.log("update");
        Object.assign(this.items[this.editedIndex], this.selectedItem); // update local data
        const response = axios.put(
          "/api/updateAccountDetail/" + this.id,
          this.editedItem
        ); // update in data base
      }

      this.close();
    },
  },
};
</script>
