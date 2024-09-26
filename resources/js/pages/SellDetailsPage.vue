<template>



  <v-container>
     
    <v-row>
      <v-col cols="12" md="12">
    <img src="/public/logo.png" height="150"  >
    </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="12">
        <!-- <v-divider></v-divider> -->
    </v-col>
    </v-row>

  
   
    <v-row>
     

      

     

     <v-col cols="8" md="8">
       <v-text-field   variant="outlined">   الســـــــــــيد  :  {{  userName  }}</v-text-field>
     </v-col>

     <v-col cols="4" md="4">
       <v-text-field   variant="outlined">   رقم الفاتورة  :  {{  this.$route.params.sellId  }}</v-text-field>
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
      <template v-if="!isPrintMode" v-slot:item.actions="{ item }">
        <v-icon v-if="loggedIn" size="small" @click="deleteItem(item)">
          mdi-delete
        </v-icon>
        <v-icon v-if="loggedIn" larg @click="editItem(item)">mdi-pencil</v-icon>
      </template>
    </v-data-table>
    <v-row>
      <v-col cols="12" md="12"></v-col>
    </v-row>
   
    <v-row>
     

      

     

      <v-col cols="6" md="6">
        <v-text-field   variant="outlined">   الاجمالي :  {{  invoiceTotal }}</v-text-field>
      </v-col>

      <v-col cols="6" md="6">
        <v-text-field     variant="outlined">{{dateNow}}</v-text-field>
      </v-col>

     

    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {

      
      dateNow :  new Date().getFullYear()+"-"+(new Date().getMonth()+1)+"-"+new Date().getDate() + " | "+ new Date().getHours()+" : "+new Date().getMinutes(),
     

      sellId : 0 ,
      isPrintMode : false ,
      userName : 'صاحب الفاتورة',
      invoiceTotal : 0 ,
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
        { title: "التسلسل", key: "identity", sortable: false  },
        { title: " البيــــــــــــان ", key: "description", sortable: false },
        { title: "القيمة الإجمالية", key: "total", sortable: false },
        { title: "السعر الإفرادي", key: "price", sortable: false },

        { title: "الكمية", key: "quantity", sortable: false },
        // { title: "التاريخ", key: "date", sortable: false },

        { title: "العمليات", key: "actions", sortable: false },
      ],
      products: [],
      items: [],
      editedIndex: -1,


      editedItem: {
        id: 0,
        total: 0,
        description: "",
        quantity: "",
        price: 0,
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
      return this.items.filter((item) => {
        // return item.description.toLowerCase().includes(this.search.toLowerCase());

        return true;
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
    const sellId = this.$route.params.sellId;
    this.sellId= sellId ;
    const responses = await axios.get("/api/getAllProducts");
    this.products = responses.data; //

    const response = await axios.get("/api/getAllSellDetails/" + sellId);
    this.items = response.data["data"]; // 


    this.invoiceTotal =  response.data["total"];
    this.userName =  response.data["userName"];

    // this.invoices = response.data["invoices"];
    this.total = response.data["total"];
    // this.book = response.data["book"];
    // this.paids = response.data["paids"];
    // this.arresteds = response.data["arresteds"];
    // this.debts = response.data["debts"];
    // this.account_persion = response.data["account_persion"];
    // this.book_number = response.data["book_number"];
  },

  mounted() {

    this.mediaQueryList = window.matchMedia('print');

    this.mediaQueryList.addEventListener('change',this.updatePrintMode);
    this.checkLogedIn();

   
  },

  beforeUnmount() {
    this.mediaQueryList.removeEventListener('change',this.updatePrintMode);
  },
  methods: {

    updatePrintMode (event){

                   this.isPrintMode = event.matches ;
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
    //    this.editedItem.total = item.sell;
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
      await axios.delete(`/api/deleteSellDetail/${item.id}`);
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


        console.log(this.editedItem.description);

        console.log("create");
        // add to local data array
        const response = axios.post(
          "/api/createSellDetail/" + this.$route.params.sellId,
          this.editedItem
        ); // add to data base
        this.items.push(this.editedItem);
      } else {
        // update current area

        console.log("update");
        Object.assign(this.items[this.editedIndex], this.editedItem); // update local data
        const response = axios.put(
          "/api/updateSellDetail/" + this.id,
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


.dataTableTitle {

  font-size: 1rem !important;
}

@media print{


.newItemButton {
  
  display: none;
}

.hidenAtPrint{

display: none;
}


.operation {

 
}

}




 
</style>
