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
          <v-toolbar-title>المستخدمين</v-toolbar-title>
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
                >مستخدم جديد</v-btn
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
                        v-model="editedItem.user_name"
                        label="الأسم"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6" md="6">
                      <v-select
                        v-model="editedItem.area"
                        :items="areas"
                        item-title="name"
                        item-value="id"
                        label="المنطقة"
                        persistent-hint
                        single-line
                      ></v-select>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-select
                        v-model="editedItem.user_type"
                        :items="user_types"
                        item-title="name"
                        item-value="id"
                        label="نوع المستخدم"
                        persistent-hint
                        single-line
                      ></v-select>
                    </v-col>

                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.number_in_book"
                        label="رقمه بالدفتر"
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
        { title: "رقمه بالدفتر", key: "number_in_book", sortable: false },
        { title: "نوع المستخدم", key: "user_type", sortable: false },
        { title: "البلدة", key: "area", sortable: false },
        { title: "الحساب", key: "account", sortable: false },
        { title: "العمليات", key: "actions", sortable: false },
      ],
      items: [],
      editedIndex: -1,
      editedItem: {
        id: 0,
        user_name: "",
        user_type: "",
        number_in_book: "",
        area: "",
        account: 0,
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
        return item.user_name.toLowerCase().includes(this.search.toLowerCase());
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
