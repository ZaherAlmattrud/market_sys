<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="4">
                <v-text-field v-model="search" label="صاحب الفاتورة" @input="filterItems"></v-text-field>
            </v-col>
        </v-row>
        <v-data-table :headers="headers" :items="filteredItems" item-key="id" class="elevation-1">
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>الفواتير</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on" @click="dialog = true"> فاتورة جديدة</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="6" md="6">
                                            <v-select v-model="editedItem.area" :items="areas" label="صاحب الفاتورة"></v-select>
                                      
                                             </v-col>
                                        <v-col cols="12" sm="6" md="6">
                                            
                                            <v-text-field v-model="editedItem.name" label="الرصيد الإجمالي"></v-text-field>
                                         </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" sm="6" md="6">
                                            <v-text-field v-model="editedItem.name" label="التاريخ"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6" md="6">
                                            <v-file-input v-model="editedItem.img" label="الملف" outlined dense
                                             ></v-file-input>
                                            

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

            areas : [ 'حزرما' ,'النشابية' ,'نولة'],
            search: '',
            dialog: false,
            dialogDelete: false,
            headers: [

                { title: 'التسلسل', key: 'id', sortable: false },
                { title: 'رقم الفاتورة', key: 'account', sortable: false },
                { title: 'التاريخ', key: 'date', sortable: false },
                { title: 'صاحب الفاتورة', key: 'person_name', sortable: false },
                { title: 'الرصيد الإجمالي', key: 'total', sortable: false },
                { title: 'العمليات', key: 'actions', sortable: false },


            ],
            items: [

                {

                    'id': 1,
                    'account': 564 ,
                    'date' : '2024-5-9',
                    'person_name': 'Zaher',
                    'total' : '2500000' 

                }
            
            ],
            editedIndex: -1,
            editedItem: {
                'id': 1,
                    'account': 2500000 ,
                    'person_name': 'Zaher',
                    'total' : 'حزرما',
                    
            },
            defaultItem: {
                'id': 1,
                    'account': '' ,
                    'person_name': '',
                    'total' : '',
                    'paid' : '' ,
                    'debts' : '' ,
            },
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'فاتورة جديدة' : 'تحديث معلومات فاتورة';
        },
        filteredItems() {
            return this.items.filter((item) => {
                return (
                    item.person_name.toLowerCase().includes(this.search.toLowerCase())
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
    methods: {

        moveToAccountDetails(item){

            this.$router.push({ name: 'accountDetails', params: { accountId: 1 } });
        } ,
        filterItems() {
            // This will automatically filter items as search input changes
        },
        editItem(item) {
            this.editedIndex = this.items.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },
        deleteItem(item) {
            const index = this.items.indexOf(item);
            confirm('Are you sure you want to delete this item?') && this.items.splice(index, 1);
        },
        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },
        save() {

            this.dialog = true;
            if (this.editedIndex > -1) {
                Object.assign(this.items[this.editedIndex], this.editedItem);
            } else {
                this.items.push(this.editedItem);
            }
            this.close();
        },
    },
};
</script>