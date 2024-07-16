<template>
    <v-container>

        <v-row>
            <v-col cols="12" md="10">
                <v-text-field v-model="search" label="البحث" @input="filterItems"></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
                <v-text-field>{{ filteredItems.length }}</v-text-field>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12" md="6">
                <v-text-field> {{ debtstotalSupplers }} : مستحقات التجار المتبقية علينا </v-text-field>
            </v-col>

            <v-col cols="12" md="6">
                <v-text-field> {{ debtstotalSupplers }} : مستحقات الزبائن المتبقية لنا </v-text-field>
            </v-col>
        </v-row>

        <v-data-table :headers="headers" :items="filteredItems" item-key="id" class="elevation-1">
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>التقارير</v-toolbar-title>
                    <v-spacer></v-spacer>

                </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon larg @click="editItem(item)">mdi-pencil</v-icon>

                <v-icon larg @click="deleteItem(item)">mdi-delete</v-icon>

                <v-icon larg @click="moveToProductImg(item)">mdi-account-eye-outline</v-icon>
            </template>
        </v-data-table>
    </v-container>
</template>

<script>
export default {
    data() {
        return {

            debtstotalSupplers: 0,
            invoices: [],
            categories: [],
            search: '',
            dialog: false,
            dialogDelete: false,
            headers: [

                { title: 'التسلسل', key: 'id', sortable: false },
                { title: 'البلدة', key: 'name', sortable: false },
                { title: 'المستحقات', key: 'total', sortable: false },
                { title: 'المقبوضات', key: 'arresteds', sortable: false },
                { title: 'المدفوعات', key: 'paids', sortable: false },
                { title: 'المتبقي', key: 'debts', sortable: false },



            ],
            items: [


            ],
            id: 0,
            editedIndex: -1,
            editedItem: {
                id: 0,
                name: '',
                pricr: '',
                invoice_id: '',
                category_id: '',
                'file': '',
                img: '',
                sell: '',

            },
            defaultItem: {

                id: 0,
                name: '',
                pricr: '',
                invoice_id: '',
                category_id: '',
                img: '',
                sell: '',
                'file': '',


            },
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'منتج جديد' : 'تحديث معلومات منتج';
        },
        filteredItems() {
            return this.items.filter((item) => {
                return (
                    item.name.includes(this.search.toLowerCase())
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

        const response = await axios.get('/api/report');
        this.items = response.data['data'];
        this.debtstotalSupplers = response.data['debtstotalSupplers'];




    },
    methods: {

        moveToProductImg(item) {
            ;


            this.$router.push({ name: 'ProductImage', params: { url: item.id } });



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
            console.log("delete api");
            console.log(item);
            const index = this.items.indexOf(item);
            this.items.splice(index, 1);
            await axios.delete(`/api/deleteProduct/${item.id}`);
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
                const response = axios.post('/api/createProduct', this.editedItem, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                }); // add to data base
                this.items.push(this.editedItem);



            } else { // update current area

                console.log('update');
                Object.assign(this.items[this.editedIndex], this.editedItem); // update local data
                const response = axios.put('/api/updateProduct/' + this.id, this.editedItem); // update in data base


            }

            this.close();
        },
    },
};
</script>