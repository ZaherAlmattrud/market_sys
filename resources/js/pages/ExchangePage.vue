<template>
    <v-container>
        <v-data-table :headers="headers" :items="filteredItems" item-key="id" class="elevation-1">
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>العملات </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn variant="outlined" color="primary" dark class="mb-2" v-bind="attrs" v-on="on"
                                @click="dialog = true">
                                عملة جديدة
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field v-model="editedItem.name" label="اسم العملة" variant="outlined" />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field v-model="editedItem.code" label="رمز العملة (مثل USD, EUR)" variant="outlined" />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field v-model="editedItem.value" label="القيمة مقابل الليرة السورية"
                                                variant="outlined" type="number" />
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                     <v-btn variant="outlined" color="blue darken-1" text @click="save">حفظ</v-btn>
                                <v-btn variant="outlined" color="blue darken-1" text @click="close">إلغاء</v-btn>
                           
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon v-if="loggedIn" large @click="editItem(item)">mdi-pencil</v-icon>
                <v-icon v-if="loggedIn" large @click="deleteItem(item)">mdi-delete</v-icon>
                <v-icon v-if="loggedIn" large @click="moveToInvoiceImg(item)">mdi-invoice-text-outline</v-icon>
            </template>
        </v-data-table>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            loggedIn: false,
            dialog: false,
            dialogDelete: false,
            id: 0,
            editedIndex: -1,
            editedItem: {
                id: 0,
                name: '',
                code: '',
                value: '',
                date: ''
            },
            defaultItem: {
                id: 0,
                name: '',
                code: '',
                value: '',
                date: ''
            },
            headers: [
                { title: 'التسلسل', key: 'id', sortable: false },
                { title: 'العملة', key: 'name', sortable: false },
                { title: 'الرمز', key: 'code', sortable: false },
                { title: 'القيمة بالليرة السورية', key: 'value', sortable: false },
                { title: 'التاريخ', key: 'date', sortable: false },
                { title: 'العمليات', key: 'actions', sortable: false }
            ],
            items: [],
            search: ''
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? ' جديد' : 'تحديث';
        },
        filteredItems() {
            return this.items.filter((item) => {
                return this.search === '' || item.name.includes(this.search) || item.code.includes(this.search);
            });
        }
    },
    watch: {
        dialog(val) {
            if (!val) this.close();
        },
        dialogDelete(val) {
            if (!val) this.closeDelete();
        }
    },
    async created() {
        const response = await axios.get('/api/getAll');
        this.items = response.data;
        this.checkLogedIn();
    },
    methods: {
        checkLogedIn() {
            const loggedIn = localStorage.getItem('user');
            this.loggedIn = !!loggedIn;
        },
        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
                this.id = 0;
            });
        },
        editItem(item) {
            this.editedIndex = this.items.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.id = item.id;
            this.dialog = true;
        },
        async deleteItem(item) {
            const index = this.items.indexOf(item);
            this.items.splice(index, 1);
            try {
                await axios.delete(`/api/deleteExchange/${item.id}`);
            } catch (error) {
                console.error('خطأ في الحذف:', error);
            }
        },
        moveToInvoiceImg(item) {
            this.$router.push({ name: 'invoiceImg', params: { id: item.id } });
        },
        async save() {
            if (!this.editedItem.name || !this.editedItem.value || !this.editedItem.code) {
                alert('يرجى تعبئة جميع الحقول');
                return;
            }

            this.editedItem.value = parseFloat(this.editedItem.value);
            const now = new Date();
            this.editedItem.date = now.toISOString().split('T')[0];

            if (this.id === 0) {
                try {
                    const response = await axios.post('/api/createExchange', this.editedItem);
                    // إعادة تحميل القائمة كاملة بعد الإضافة
                    const refreshed = await axios.get('/api/getAll');
                    this.items = refreshed.data;
                } catch (error) {
                    console.error('خطأ في الإضافة:', error);
                }
            } else {
                try {
                    await axios.put(`/api/updateExchange/${this.id}`, this.editedItem);
                    // تحديث العنصر في القائمة مباشرة
                    Object.assign(this.items[this.editedIndex], this.editedItem);
                } catch (error) {
                    console.error('خطأ في التحديث:', error);
                }
            }

            this.close();
        }
    }
};
</script>
