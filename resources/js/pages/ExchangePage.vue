<template>
    <v-container>
      
        <v-data-table :headers="headers" :items="filteredItems" item-key="id" class="elevation-1">
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>سعر الصرف</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on" @click="dialog = true">
                                صرف جديد</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                    <v-row>

                               

                                        <v-col cols="12" sm="6" md="12">
                                            <v-text-field v-model="editedItem.value"
                                                label="سعر الصرف"
                                                  variant="outlined"
                                                
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
                <v-icon v-if="loggedIn" larg @click="editItem(item)">mdi-pencil</v-icon>
                <v-icon v-if="loggedIn" larg @click="deleteItem(item)">mdi-delete</v-icon>
                <v-icon v-if="loggedIn" larg @click="moveToInvoiceImg(item)">mdi-invoice-text-outline</v-icon>
            </template>
        </v-data-table>
    </v-container>
</template>

<script>
export default {
    data() {
        return {

            loggedIn : false ,
            users: [],
            areas: [],
            search: '',
            dialog: false,
            dialogDelete: false,



            headers: [

                { title: 'التسلسل', key: 'id', sortable: false },
                { title: 'القيمة بالليرة السورية', key: 'value', sortable: false },
                { title: 'التاريخ', key: 'date', sortable: false },
                // { title: 'الملف', key: 'file', sortable: false },
                { title: 'العمليات', key: 'actions', sortable: false },


            ],
            items: [



            ],

            id: 0,
            editedIndex: -1,
            editedItem: {

                id: 0,
                value : 0


            },
            defaultItem: {
                id: 0,
                value : ''
            },
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'صرف جديد' : 'تحديث معلومات الصرف';
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

                    return (

                        true

                    );


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
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },

    async beforeCreate() {
        

        const response = await axios.get('/api/getAll');
        this.items = response.data;

 

    },

    mounted() {
    this.checkLogedIn();
  },
    methods: {


        checkLogedIn(){

const loggedIn = localStorage.getItem('user');
if ( loggedIn ){

     this.loggedIn = true ;
}else{
    this.loggedIn = false ;

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
            ;


            this.$router.push({ name: 'invoiceImg', params: { id: item.id } });



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

            if (this.id == 0) { // create new area

                console.log('create');
                // add to local data array


                const response = axios.post('/api/createInvoice', this.editedItem, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                }); // add to data base
                this.items.push(this.editedItem);



            } else { // update current area

                console.log('update');
                Object.assign(this.items[this.editedIndex], this.editedItem); // update local data
                const response = axios.put('/api/updateInvoice/' + this.id, this.editedItem); // update in data base


            }

            this.close();
        },
    },
};
</script>