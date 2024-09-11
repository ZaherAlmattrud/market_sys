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
          <v-toolbar-title>المقبوضات</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn    variant="outlined" v-if="loggedIn" color="primary" dark class="mb-2" v-bind="attrs" v-on="on" @click="dialog = true">قبض
                                جديد</v-btn>
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
                        v-model="editedItem.total"
                        label="المبلغ"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6" md="6">
                      <!-- <v-text-field v-model="editedItem.account_id" label="الحساب"></v-text-field> -->

                      <!-- <v-select v-model="editedItem.account_id" :items="users"
                                                item-title="user_name" item-value="id" label="صاحب الحساب"
                                                persistent-hint single-line></v-select> -->

                      <v-autocomplete
                        v-model="editedItem.account_id"
                        :items="users"
                        item-title="user_name"
                        item-value="id"
                        label="صاحب الحساب"
                        placeholder="ابدأ البحث"
                        crearable
                          variant="outlined"
                      >
                      </v-autocomplete>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col cols="12" sm="6" md="12">
                      <v-text-field
                        v-model="editedItem.notes"
                        label="الملاحظات"
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
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      loggedIn: false,
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
        { title: "رقم الحساب", key: "account_id", sortable: false },
        { title: "المبلغ", key: "total", sortable: false },
        { title: "التاريخ", key: "date", sortable: false },
        { title: "الملاحظات", key: "notes", sortable: false },
        { title: "العمليات", key: "actions", sortable: false },
      ],
      items: [],
      editedIndex: -1,
      editedItem: {
        id: 0,
        account_id: "",
        total: "",
        notes: "",
        date: "",
      },
      defaultItem: {
        id: 0,
        account_id: "",
        total: "",
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
      return this.editedIndex === -1 ? "قبض جديد" : "تحديث معلومات قبض";
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
    const response = await axios.get("/api/getAllArresteds");
    this.items = response.data;

    const response2 = await axios.get("/api/getAllUsers");
    this.users = response2.data; // users
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
      await axios.delete(`/api/deleteArrested/${item.id}`);
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
        const response = axios.post("/api/createArrested", this.editedItem); // add to data base
        this.items.push(this.editedItem);
      } else {
        // update current area

        console.log("update");
        Object.assign(this.items[this.editedIndex], this.editedItem); // update local data
        const response = axios.put("/api/updateArrested/" + this.id, this.editedItem); // update in data base
      }

      this.close();
    },
  },
};
</script>
