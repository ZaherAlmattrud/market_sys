<template>



  <v-container>

    <v-row>
      <v-col cols="12" md="12">
    <img src="/public/logo.png" height="125">
    </v-col>
    </v-row>
    <v-row>
    </v-row>


    <v-row>
      <v-col class="hidenAtPrint" cols="1" md="1">
        <v-button @click="$router.go(-1)">كافة الحسابات</v-button>
      </v-col>
      <v-col class="hidenAtPrint"cols="1" md="1">
        <v-text-field    variant="outlined"> {{ book_number }} </v-text-field>
      </v-col>

      <v-col class="hidenAtPrint" cols="3" md="3">
        <v-text-field    variant="outlined" v-model="search" label="البحث" @input="filterItems"></v-text-field>
      </v-col>

      <v-col  class="userName"  cols="12" md="7">
        <v-text-field     variant="outlined">الســـــــــــــــيـــد :  {{ account_persion }} </v-text-field>
      </v-col>

      <!-- <v-col   cols="2" md="2">
        <v-text-field    variant="outlined"> {{ total }} : الاجمالي </v-text-field>
      </v-col> -->

      <!-- <v-col cols="12" md="3">
        <v-text-field    variant="outlined"> {{ debts }} : المتبقي </v-text-field>
      </v-col> -->
    </v-row>
    <!-- <v-row>
      <v-col cols="12" md="3">
        <v-text-field    variant="outlined"> {{ paids }} : المدفوعات </v-text-field>
      </v-col>
      <v-col cols="12" md="3">
        <v-text-field    variant="outlined"> {{ arresteds }} : المقبوضات </v-text-field>
      </v-col>

      <v-col cols="12" md="3">
        <v-text-field    variant="outlined"> {{ invoices }} : الفواتير </v-text-field>
      </v-col>

      <v-col cols="12" md="3">
        <v-text-field    variant="outlined"> {{ book }}: الدفتر </v-text-field>
      </v-col>
    </v-row> -->
    <v-data-table
      :headers="headers"
      :items="filteredItems"
      item-key="id"
      class="elevation-1"
         :items-per-page="5000"
         hide-default-footer
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>تفاصيل الحساب</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="600px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                dark
                class="mb-2 hidenAtPrint"
                v-bind="attrs"
                v-on="on"
                @click="dialog = true"
                   variant="outlined"
              >
                بيان جديد</v-btn
              >
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
                        v-model="editedItem.quantity"
                        label="الكمية"
                        @change="updateTotal"
                        variant="outlined"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6" md="6">
                      <!-- <v-autocomplete
                        v-model="editedItem.description"
                        :items="products"
                        item-title="name"
                        item-value="name"
                        label="المنتج"
                        placeholder="المنتج"
                        crearable
                        @update:modelValue="updatePrice"
                   
                      >
                      </v-autocomplete> -->

                      <v-combobox
                        v-model="editedItem.description"
                        :items="products"
                        item-title="name"
                        item-value="name"
                        label="المنتج"
                        placeholder="المنتج"
                        crearable
                        @update:modelValue="updatePrice"
                        variant="outlined"
                   
                      >
                      </v-combobox>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.price"
                        label="الإفرادي"
                        @change="updateTotal"
                        variant="outlined"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.total"
                        label="الإجمالي"
                         @change="updateOnePrice"
                        variant="outlined"
                      ></v-text-field>

                      <!-- <mony   currency="EUR" locale="fr-FR" decimal="," thousand="," /> -->
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
      <template   v-slot:item.actions="{ item }">
        <v-icon class="hidenAtPrint" v-if="loggedIn" size="small" @click="deleteItem(item)">
          mdi-delete
        </v-icon>
        <v-icon class="hidenAtPrint" v-if="loggedIn" larg @click="editItem(item)">mdi-pencil</v-icon>
      </template>
    </v-data-table>


     <v-row> <v-col cols="12" md="12"></v-col></v-row>

<v-row>
  <v-col cols="6" md="6">
        <v-text-field  variant="outlined" >   الاجمالي :  {{  total }}</v-text-field>
      </v-col>

      <v-col cols="6" md="6">
        <v-text-field  variant="outlined"> {{ dateNow }}</v-text-field>
      </v-col>

</v-row>
   
  </v-container>

  
</template>

<script>
 
export default {
  components: {
    
  },

  data() {
    return {

      
      dateNow :  new Date().getFullYear()+"-"+(new Date().getMonth()+1)+"-"+new Date().getDate() + " | "+ new Date().getHours()+" : "+new Date().getMinutes(),
     
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
        { title: "التسلسل", key: "identity", sortable: false },
        { title: " البيــــــــــــان ", key: "description", sortable: false },
        { title: "القيمة الإجمالية", key: "total", sortable: false },
        { title: "السعر الإفرادي", key: "price", sortable: false },

        { title: "الكمية", key: "quantity", sortable: false },
        { title: "التاريخ", key: "date", sortable: false },

        { title: "العمليات", key: "actions", sortable: false },
      ],
      items: [],
      editedIndex: -1,
      editedItem: {
        id: 1,
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
      products:[],
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "بيان جديد" : "تحديث معلومات بيان";
    },
    filteredItems() {
      return this.items.filter((item) => {
        return item.description.toLowerCase().includes(this.search.toLowerCase());
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
    const accountId = this.$route.params.accountId;

    const responses = await axios.get("/api/getAllProducts");
    this.products = responses.data; //

    console.log("account id");
    console.log(accountId);
    const response = await axios.get("/api/getAccountDetails/" + accountId);
    this.items = response.data["data"]; // users

    // this.invoices = response.data["invoices"];
    this.total = response.data["total"];
    // this.book = response.data["book"];
    // this.paids = response.data["paids"];
    // this.arresteds = response.data["arresteds"];
    // this.debts = response.data["debts"];
    this.account_persion = response.data["account_persion"];
    this.book_number = response.data["book_number"];
  },

  mounted() {
    this.checkLogedIn();
  },
  methods: {

    updateOnePrice() {
      this.editedItem.price = this.editedItem.total / this.editedItem.quantity;
    },

    updatePrice() {


var itemName = '';


  if ( this.editedItem.description && typeof this.editedItem.description == 'object' ){
   itemName = this.editedItem.description.name;
         
  } else{

   itemName = this.editedItem.description

  }

const item = this.products.find((i) => i.name === itemName);

if (item) {
  this.editedItem.price = item.sell;
//  this.editedItem.total = item.sell;
}else{

  this.editedItem.price = 0;
  this.editedItem.total = 0;
}
},

updateTotal() {
this.editedItem.total = this.editedItem.price * this.editedItem.quantity;
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

        if ( this.editedItem.description && typeof this.editedItem.description == 'object' ){
          this.editedItem.description = this.editedItem.description.name
               
        }

        console.log("create");
        // add to local data array
        const response = axios.post(
          "/api/createAccountDetail/" + this.$route.params.accountId,
          this.editedItem
        ); // add to data base
        this.items.push(this.editedItem);
      } else {
        // update current area

        console.log("update");
        Object.assign(this.items[this.editedIndex], this.editedItem); // update local data
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
<style scoped>

.userName {

font-size: 3rem !important;
}


@media print {


  .hidenAtPrint{

       display: none;
}

}


</style>
