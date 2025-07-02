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
          <v-toolbar-title>حركة الصندوق</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn   variant="outlined" v-if="loggedIn" color="primary" dark class="mb-2" v-bind="attrs" v-on="on"
                                @click="dialog = true">إضافة حركة اليوم</v-btn>
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
                        v-model="editedItem.before"
                        label="مبلغ سابق"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="6" md="12">
                      <v-text-field
                        v-model="editedItem.arresteds"
                        label="المقبوضات"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="6" md="12">
                      <v-text-field
                        v-model="editedItem.paids"
                        label="المدفوعات"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="6" md="12">
                      <v-text-field
                        v-model="editedItem.box"
                        label="الصندوق"
                          variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>

                    <v-btn   variant="outlined" color="blue darken-1" text @click="save">حفظ</v-btn>
                <v-btn   variant="outlined" color="blue darken-1" text @click="close">إلغاء</v-btn>
            
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon v-if="loggedIn" larg @click="deleteItem(item)">mdi-delete</v-icon>
                <v-icon v-if="loggedIn" larg @click="editItem(item)">mdi-pencil</v-icon>
                <v-icon v-if="loggedIn" larg @click="moveToAccountDetails(item)">mdi-account-eye-outline</v-icon>
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
        { title: "التسلسل", key: "id", sortable: false },
        { title: "التاريخ", key: "day", sortable: false },
         { title: "مبلغ سابق", key: "before", sortable: false },
        { title: "المقبوضات", key: "arresteds", sortable: false },
        { title: "المدفوعات", key: "paids", sortable: false },
        { title: "الصندوق", key: "box", sortable: false },
        { title: "فرق الحساب", key: "difference", sortable: false },
        { title: "العمليات", key: "actions", sortable: false },
      ],
      items: [],
      allUsers: 0,
      editedIndex: -1,
      editedItem: {
        id: 0,
        arresteds: "",
        paids: "",
        box: "",
        before: 0 ,
      },
      defaultItem: {
        id: 0,
        user_name: "",
        user_type: "",
        area: "",
        account: 0,
           before: 0 ,
      },
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "إضافة حركة اليوم" : "تحديث معلومات حركة";
    },
    filteredItems() {
      return this.items.filter((item) => {
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
 

    const response = await axios.get("/api/getAllDays");
    this.items = response.data; //
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

    moveToAccountDetails(item) {},

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
      await axios.delete(`/api/deleteDay/${item.id}`);
    },
    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
   save() {
  if (this.id == 0) {
    // تعيين ID جديد (مثلاً الرقم التسلسلي الأحدث)
    this.editedItem.id = this.items.length ? this.items[this.items.length - 1].id + 1 : 1;

    // تعيين التاريخ إلى اليوم الحالي (مثلاً)
    this.editedItem.day = new Date().toLocaleDateString('ar-EG'); // أو التنسيق اللي بدك إياه

    // حساب فرق الحساب
   
    this.editedItem.difference =
  Number(this.editedItem.box + this.editedItem.before ) -
  (Number(this.editedItem.arresteds) - Number(this.editedItem.paids));
  

    // إضافة العنصر إلى القائمة
    this.items.push(Object.assign({}, this.editedItem));

    // إرسال لل API مع انتظار الرد (لو تحب تستخدم await)
    axios.post("/api/createDay", this.editedItem)
      .then(response => {
        // ممكن تحدث العنصر بناء على الرد إذا فيه بيانات إضافية
      })
      .catch(error => {
        console.error(error);
      });

  } else {
    // تحديث فرق الحساب
  this.editedItem.difference =
  Number(this.editedItem.box + this.editedItem.before) -
  (Number(this.editedItem.arresteds) - Number(this.editedItem.paids));;

    // تحديث العنصر في القائمة
    Object.assign(this.items[this.editedIndex], this.editedItem);

    // إرسال التحديث لل API
    axios.put("/api/updateDay/" + this.id, this.editedItem)
      .then(response => {
        // تحديث إذا احتجت
      })
      .catch(error => {
        console.error(error);
      });
  }

  // إغلاق الحوار وتنظيف الحقول
  this.close();
}

  },
};
</script>
