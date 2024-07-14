<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="8">
                <v-text-field v-model="search" label="البحث" @input="filterItems"></v-text-field>
            </v-col>
            <v-col cols="12" md="4">



            </v-col>
        </v-row>
        <v-data-table :headers="headers" :items="filteredItems" item-key="id" class="elevation-1">
            <template v-slot:top>


                <v-toolbar flat>
                    <v-toolbar-title>تفاصيل الحساب</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="600px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on" @click="dialog = true">
                                بيان جديد</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="6" md="6">
                                            <v-text-field v-model="editedItem.description"
                                                label="البيان"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6" md="6">
                                            <v-text-field v-model="editedItem.quantity" label="الكمية"></v-text-field>


                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" sm="6" md="6">
                                            <v-text-field v-model="editedItem.price" label="الأفرادي"></v-text-field>


                                        </v-col>
                                        <v-col cols="12" sm="6" md="6">
                                            <v-text-field v-model="editedItem.total" label="الإجمالي"></v-text-field>
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
                <v-icon size="small" @click="deleteItem(item)">
                    mdi-delete
                </v-icon>
                <v-icon larg @click="editItem(item)">mdi-pencil</v-icon>
            </template>
        </v-data-table>
    </v-container>
</template>

<script>
export default {


    data() {
        return {

            id: 0,
            search: '',
            dialog: false,
            dialogDelete: false,
            headers: [

                { title: 'التسلسل', key: 'id', sortable: false },
                { title: ' البيــــــــــــان ', key: 'description', sortable: false },
                { title: 'القيمة الإجمالية', key: 'total', sortable: false },
                { title: 'السعر الإفرادي', key: 'price', sortable: false },

                { title: 'الكمية', key: 'quantity', sortable: false },
                { title: 'التاريخ', key: 'date', sortable: false },

                { title: 'العمليات', key: 'actions', sortable: false },


            ],
            items: [



            ],
            editedIndex: -1,
            editedItem: {
                'id': 1,
                'total': '',
                'description': '',
                'quantity': '',
                
                'price': '',
            },
            defaultItem: {
                'id': 1,
                'total': '',
                'description': '',
                'quantity': '',
                'price': '',
            },
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'بيان جديد' : 'تحديث معلومات بيان';
        },
        filteredItems() {
            return this.items.filter((item) => {
                return (
                    item.description.toLowerCase().includes(this.search.toLowerCase())
                );
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


        const accountId = this.$route.params.accountId;

        console.log('account id');
        console.log(accountId);
        const response = await axios.get('/api/getAccountDetails/' + accountId);
        this.items = response.data; // users



    },
    methods: {


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

            if (this.id == 0) { // create new area

                console.log('create');
                // add to local data array
                const response = axios.post('/api/createAccountDetail/' + this.$route.params.accountId, this.editedItem); // add to data base
                this.items.push(this.editedItem);



            } else { // update current area

                console.log('update');
                Object.assign(this.items[this.editedIndex], this.editedItem); // update local data
                const response = axios.put('/api/updateAccountDetail/' + this.id, this.editedItem); // update in data base


            }

            this.close();
        },
    },
};
</script>