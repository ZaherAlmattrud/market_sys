<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="10">
        <v-text-field    variant="outlined" v-model="search" label="البحث" @input="filterItems"></v-text-field>
      </v-col>
      <v-col cols="12" md="2">
        <v-text-field    variant="outlined">{{ filteredItems.length }}</v-text-field>
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
          <v-toolbar-title>الحسابات</v-toolbar-title>
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
                   variant="outlined"
                >حساب جديد</v-btn
              >
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="12">
                      <v-text-field
                        v-model="editedItem.user_name"
                        label="الأسم"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-autocomplete
                        v-model="editedItem.area"
                        :items="areas"
                        item-title="name"
                        item-value="id"
                        label="المنطقة"
                        placeholder="المنطقة"
                          variant="outlined"
                        crearable
                      >
                      </v-autocomplete>
                    </v-col>

                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.number_in_book"
                        label="رقمه بالدفتر"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col cols="12" sm="6" md="6">

                     <v-autocomplete
                        v-model="editedItem.user_type"
                        :items="user_types"
                        item-title="type_name"
                        item-value="id"
                        label="نوع الحساب"
                        placeholder="نوع الحساب"
                          variant="outlined"
                        crearable
                      >
                      </v-autocomplete>

                  

                      <!-- <v-select v-model="editedItem.area" :items="areas" item-title="name"
    item-value="id" label="المنطقة" persistent-hint single-line></v-select> -->
                    </v-col>

                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.mobile"
                        label="موبايل"
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
        <v-icon v-if="loggedIn" larg @click="deleteItem(item)">mdi-delete</v-icon>
        <v-icon  v-if="loggedIn" larg @click="clearAccount(item)">mdi-notebook-remove-outline</v-icon>
        <v-icon  v-if="loggedIn" larg @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon larg @click="moveToAccountDetails(item)">mdi-book-open-page-variant-outline</v-icon>
        <v-icon larg @click="moveToAccountSummary(item)">mdi-account-eye-outline</v-icon>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      loggedIn: false,
      id: 0,
      user_types: [],
      areas: [],
      itemsArray: [],
      search: "",
      dialog: false,
      dialogDelete: false,
      headers: [
        { title: "التسلسل", key: "id", sortable: false },
        { title: "الأسم", key: "user_name", sortable: false },
        { title: "رصيد الحساب", key: "balance", sortable: true },
        { title: "رقمه بالدفتر", key: "number_in_book", sortable: false },
        { title: "نوع المستخدم", key: "user_type", sortable: false },
        { title: "البلدة", key: "area", sortable: false },
        { title: "الحساب", key: "account", sortable: false },
        { title: "موبايل", key: "mobile", sortable: false },
        { title: "العمليات", key: "actions", sortable: false },
      ],
      items: [],
      allUsers: 0,
      editedIndex: -1,
      editedItem: {
        id: 0,
        user_name: "",
        user_type: "",
        number_in_book: "",
        area: "",
        account: 0,
        mobile: "",
      },
      defaultItem: {
        id: 0,
        user_name: "",
        user_type: "",
        area: "",
        account: 0,
      },
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "مستخدم جديد" : "تحديث معلومات مستخدم";
    },
    filteredItems() {
      return this.items.filter((item) => {
        const it =
          item.user_name.includes(this.search.toLowerCase()) ||
          item.area.includes(this.search.toLowerCase()) ||    item.user_type.includes(this.search.toLowerCase()) ;

        return it;
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
    const response = await axios.get("/api/getAllUsers");
    this.items = response.data; // users

    this.allUsers = this.items.length;

    const response_1 = await axios.get("/api/getAllAreas");
    this.areas = response_1.data;

    const response_2 = await axios.get("/api/getAllUserTypes");
    this.user_types = response_2.data;
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
    moveToAccountDetails(item) {
      console.log("=======================");

      console.log(item.id);

      this.$router.push({ name: "accountDetails", params: { accountId: item.account } });
    },

    moveToAccountSummary(item){

      console.log("=======================");

      console.log(item.id);

      this.$router.push({ name: "accountSummary", params: { accountId: item.account } });


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
      await axios.delete(`/api/deleteUser/${item.id}`);
    },
    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async clearAccount(account){


console.log("account clear");

console.log(account);

await axios.delete(`/api/clearAccount/${account.id}`);

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
        const response = axios.post("/api/createUser", this.editedItem); // add to data base
        this.items.push(this.editedItem);
      } else {
        // update current area

        console.log("update");
        Object.assign(this.items[this.editedIndex], this.editedItem); // update local data
        const response = axios.put("/api/updateUser/" + this.id, this.editedItem); // update in data base
      }

      this.close();
    },
  },

  
};
</script>
