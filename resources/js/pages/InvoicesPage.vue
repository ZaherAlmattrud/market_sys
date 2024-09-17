<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="12">
        <v-text-field
           variant="outlined"
          v-model="search"
          label="صاحب الفاتورة"
          @input="filterItems"
        ></v-text-field>
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
          <v-toolbar-title>فواتير المشتريات</v-toolbar-title>
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
              >
                فاتورة جديدة</v-btn
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
                      <!-- <v-select v-model="editedItem.account_id" :items="users"
                                                item-title="user_name" item-value="id" label="صاحب الفاتورة"
                                                persistent-hint single-line></v-select> -->

                      <v-autocomplete
                        v-model="editedItem.account_id"
                        :items="users"
                        item-title="user_name"
                        item-value="id"
                        label="صاحب الفاتورة"
                        placeholder="ابدأ البحث"
                        crearable
                          variant="outlined"
                      >
                      </v-autocomplete>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.total"
                        label="الرصيد الإجمالي"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-file-input
                        v-model="editedItem.file"
                        label="الملف"
                        outlined
                        dense
                          variant="outlined"
                      ></v-file-input>
                    </v-col>

                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.num"
                        label="رقم الفاتورة المطبوع"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn   variant="outlined" color="blue darken-1" text @click="close">إلغاء</v-btn>
                <v-btn   variant="outlined"  color="blue darken-1" text @click="save">حفظ</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon v-if="loggedIn" larg @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon v-if="loggedIn" larg @click="deleteItem(item)">mdi-delete</v-icon>
        <v-icon larg @click="moveToInvoiceImg(item)">mdi-invoice-text-outline</v-icon>
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
      areas: [],
      search: '',
      dialog: false,
      dialogDelete: false,

      headers: [
        { title: "التسلسل", key: "id", sortable: false },
        { title: "صاحب الفاتورة", key: "account_id", sortable: false },
        { title: "الرصيد الإجمالي", key: "total", sortable: false },
        { title: "رقم الفاتورة المطبوع", key: "num", sortable: false },
        { title: "التاريخ", key: "date", sortable: false },
        // { title: 'الملف', key: 'file', sortable: false },
        { title: "العمليات", key: "actions", sortable: false },
      ],
      items: [],

      id: 0,
      editedIndex: -1,
      editedItem: {
        id: 0,
        invoice_type: "",
        date: "",
        num: "",
        account_id: "",
        total: "",
        file: "",
        file_url: null,
      },
      defaultItem: {
        id: 0,
        invoice_type: "",
        date: "",
        num: "",
        account_id: "",
        file_url: "",
        total: "",
        file: "",
      },
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "فاتورة جديدة" : "تحديث معلومات فاتورة";
    },
    filteredItems() {
      return this.items.filter((item) => {
        if (this.search != '') {
          return (
            //   item.account_id == this.search

            item.account_id.includes(this.search.toLowerCase())
            //||    item.id == this.search
          );
        } else {
          return true;
        }

        // return (

        //     // item.account_id.includes(this.search.toLowerCase())

        //     true

        // );
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
  
    const response = await axios.get("/api/getAllInvoices");
    console.log("Data Reponse");

    this.items = response.data;

    const response2 = await axios.get("/api/getAllUsers");

    console.log("Rrrrrrrrr");
    console.log(response2);
    this.users = response2.data;
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
      // this.$router.push({ name: 'accountDetails', params: { accountId: 1 } });
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
      // const index = this.items.indexOf(item); 5613
      // confirm('Are you sure you want to delete this item?') && this.items.splice(index, 1);

      console.log("delete api");
      console.log(item);
      const index = this.items.indexOf(item);
      this.items.splice(index, 1);
      await axios.delete(`/api/deleteInvoice/${item.id}`);
    },

    moveToInvoiceImg(item) {
      if (item.id > 82) {
        window.open(
           window.location.origin+`/api/getInvoiceImgLink/${item.id}`,
          "_blank",
          "noopener,noreferrer"
        );
      } else {
        this.$router.push({ name: "invoiceImg", params: { id: item.id } });
      }
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

        const response = axios.post("/api/createInvoice", this.editedItem, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }); // add to data base
        this.items.push(this.editedItem);
      } else {
        // update current area

        console.log("update");
        Object.assign(this.items[this.editedIndex], this.editedItem); // update local data
        const response = axios.put("/api/updateInvoice/" + this.id, this.editedItem,{
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }); // update in data base
      }

      this.close();
    },
  },
};
</script>
