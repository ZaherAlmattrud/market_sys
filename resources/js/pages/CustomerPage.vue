<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="4">
                <v-text-field v-model="search" label="البحث" @input="filterItems"></v-text-field>
            </v-col>
        </v-row>
        <v-data-table :headers="headers" :items="filteredItems" item-key="id" class="elevation-1">
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>الزبائن</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on" @click="dialog = true">زبون جديد</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="6" md="6">
                                            <v-text-field v-model="editedItem.name" label="الأسم"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6" md="6">
                                            

                                                <v-select v-model="editedItem.area" :items="areas" label="البلدة"></v-select>
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

                <v-icon larg @click="editItem(item)">mdi-account-eye-outline</v-icon>
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

                { title: 'التسلسل', key: 'num', sortable: false },
                { title: 'الأسم', key: 'name', sortable: false },
                { title: 'البلدة', key: 'area', sortable: false },
                { title: 'الحساب', key: 'account', sortable: false },
                { title: 'العمليات', key: 'actions', sortable: false },


            ],
            items: [

                {

                    'num': 1,
                    'name': 'Zaher',
                    'area' : 'حزرما',
                    'account': 95

                },
                {

                    'num': 1,
                    'name': 'Zaher',
                    'account': 95

                },
                {

                    'num': 1,
                    'name': 'Zaher',
                    'account': 95

                },
                {

                    'num': 1,
                    'name': 'Zaher',
                    'account': 95

                },
                {

                    'num': 1,
                    'name': 'Zaher',
                    'account': 95

                },
            ],
            editedIndex: -1,
            editedItem: {
                num: 0,
                name: '',
                account: 0,
            },
            defaultItem: {
                num: 0,
                name: '',
                account: 0,
            },
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'زبون جديد' : 'تحديث معلومات زبون';
        },
        filteredItems() {
            return this.items.filter((item) => {
                return (
                    item.name.toLowerCase().includes(this.search.toLowerCase())
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