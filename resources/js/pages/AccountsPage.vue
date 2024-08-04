<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="10">
        <v-text-field
          v-model="search"
          label="صاحب الحساب"
          @input="filterItems"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="2">
        <v-text-field>{{ filteredItems.length }}</v-text-field>
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
                @click="dialog = false"
              >
                حساب جديد</v-btn
              >
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="12" md="12">
                      <v-text-field
                        v-model="editedItem.account"
                        label="رقم الحساب"
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
        <v-icon larg @click="moveToAccountDetails(item)">mdi-account-eye-outline</v-icon>
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
      search: "",
      dialog: false,
      dialogDelete: false,
      headers: [
        //   { title: 'رقم الحساب', key: 'account', sortable: true },
        { title: "صاحب الحساب", key: "person_name", sortable: false },
        { title: "نوع الحساب", key: "account_user_type", sortable: false },
        // { title: 'دفتر الديون', key: 'book', sortable: true },
        // { title: ' الفواتير', key: 'invoices', sortable: true },
        // { title: 'الرصيد الإجمالي', key: 'total', sortable: true },
        // { title: 'المقبوض', key: 'arrested', sortable: false },
        // { title: 'المدفوع', key: 'paid', sortable: false },
        // { title: ' المتبقي ', key: 'debts', sortable: true },
        { title: "العمليات", key: "actions", sortable: false },
      ],
      items: [],
      editedIndex: -1,
      editedItem: {
        id: "",
        account: 0,
        person_name: "",
        account_user_type: "",
        total: "",
        paid: 0,
        debts: "",
      },
      defaultItem: {
        id: 1,
        account: 2500000,
        person_name: "Zaher",
        account_user_type: "",
        total: "حزرما",
        paid: 1000,
        debts: "",
      },
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "حساب جديد" : "تحديث معلومات حساب";
    },
    filteredItems() {
      return this.items.filter((item) => {
        return item.person_name.toLowerCase().includes(this.search.toLowerCase());
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
  
    const response = await axios.get("/api/getAllAccounts");
    this.items = response.data; // users
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

      this.$router.push({ name: "accountDetails", params: { accountId: item.id } });
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
        // // add to local data array
        // const response = axios.post('/api/createUser', this.editedItem); // add to data base
        // this.items.push(this.editedItem);
      } else {
        // update current area

        console.log("update");
        Object.assign(this.items[this.editedIndex], this.editedItem); // update local data
        const response = axios.put("/api/updateAccount/" + this.id, this.editedItem); // update in data base
      }

      this.close();
    },
  },
};
</script>
