<template>
  <v-container>
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
          <v-toolbar-title>كشف حساب</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on, attrs }">

              <label>    الســــــــــــــــــــيــد :   {{ user_name }}  </label>
              
            </template>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon v-if="loggedIn" larg @click="editItem(item)">mdi-pencil</v-icon>

                <v-icon v-if="loggedIn" larg @click="deleteItem(item)">mdi-delete</v-icon>
      </template>
    </v-data-table>

    <v-row>
     

      

     

     <v-col cols="6" md="6">
       <v-text-field>   الرصيد الصافي :  {{  total }}</v-text-field>
     </v-col>

     <v-col cols="6" md="6">
       <v-text-field>   المحاسب : أبو معاذ 0932826948</v-text-field>
     </v-col>

    

   </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {

      total : 0 ,
        user_name : 'صاحب الحساب',
        loggedIn : false ,
      users: [],
      id: 0,
      user_types: [],
      areas: [],
      itemsArray: [],
      search: "",
      dialog: false,
      dialogDelete: false,

      headers: [

        { title: "التسلسل", key: "id", sortable: false },
        { title: "البيان", key: "description", sortable: false },
        { title: "المبلغ", key: "total", sortable: false },
        { title: "التاريخ", key: "date", sortable: false },
        { title: "الملاحظات", key: "notes", sortable: false },
     
      ],
      items: [],
      editedIndex: -1,
      editedItem: {
        id: 0,
        account_id: "",
        total: 0,
        notes: "",
        date: "",
      },
      defaultItem: {
        id: 0,
        account_id: "",
        total: 0,
        notes: "",
        date: "",
      },
    };
  },
  computed: {
    formattedDate() {
      return this.date ? new Date(this.date).toLocaleDateString() : "";
    },
    formTitle() {
      return this.editedIndex === -1 ? "دفعة جديدة" : "تحديث معلومات دفعة";
    },
    filteredItems() {
      return this.items.filter((item) => {
        if (this.search != "") {
          return (
            // item.account_id == this.search
            item.account_id.includes(this.search.toLowerCase())
          );
        } else {
          return true;
        }
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
    const response = await axios.get("/api/getAccountSummary/" + accountId);
    this.items = response.data['data'];
    this.user_name = response.data['user_name'];
    this.total =  response.data['total'];

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
      this.id = item.id;
      this.editedIndex = this.items.indexOf(item);
      this.editedItem = Object.assign({}, item);

      this.dialog = true;

      // this.editedIndex = this.items.indexOf(item);
      // this.editedItem = Object.assign({}, item);
      // this.dialog = true;
    },
    async deleteItem(item) {
      // const index = this.items.indexOf(item);
      // confirm('Are you sure you want to delete this item?') && this.items.splice(index, 1);
      console.log("delete api");
      console.log(item);
      const index = this.items.indexOf(item);
      this.items.splice(index, 1);
      await axios.delete(`/api/deletePaid/${item.id}`);
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
        const response = axios.post("/api/createPaid", this.editedItem); // add to data base
        this.items.push(this.editedItem);
      } else {
        // update current area

        console.log("update");
        Object.assign(this.items[this.editedIndex], this.editedItem); // update local data
        const response = axios.put("/api/updatePaid/" + this.id, this.editedItem); // update in data base
      }

      this.close();
    },
  },
};
</script>
