<template>
    <v-data-table :headers="headers" :items="desserts" :sort-by="[{ key: 'calories', order: 'asc' }]">
        <template v-slot:top>
            <v-toolbar flat>
                <v-toolbar-title>المنتجات</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ props }">
                        <v-btn class="mb-2" color="primary" dark v-bind="props">
                            إضافة منتج
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="text-h5">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>

                                <v-row>
                                    <v-col cols="12" md="4" sm="12">
                                        <v-text-field v-model="editedItem.name" label="الاسم"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4" sm="12">
                                        <v-text-field v-model="editedItem.price" label="الشراء"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4" sm="12">
                                        <v-file-input v-model="editedItem.img" label="الصورة" outlined dense
                                            capture="user" accept="image/*"></v-file-input>
                                    </v-col>

                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="4" sm="12">
                                        <v-select v-model="editedItem.invoice" :items="invoices" label="الفاتورة"></v-select>
                                    </v-col>
                                   
                                    <v-col cols="12" md="4" sm="12">
                                        <v-select v-model="editedItem.category" :items="categories" label="الصنف"></v-select>
                                    </v-col>
                                    <v-col cols="12" md="4" sm="12">
                                        <v-text-field v-model="editedItem.sell" label="البيع"></v-text-field>
                                    </v-col>

                                </v-row>

                            </v-container>
                        </v-card-text>

                        <v-card-actions>

                            <v-spacer></v-spacer>
                            <v-btn color="blue-darken-1" variant="text" @click="close">
                                إلغاء
                            </v-btn>
                            <v-btn color="blue-darken-1" variant="text" @click="save">
                                حفظ
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5">هل أنت متأكد من عملية الحذف ؟</v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue-darken-1" variant="text" @click="closeDelete">لا</v-btn>
                            <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">نعم</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-icon class="me-2" size="small" @click="editItem(item)">
                mdi-pencil
            </v-icon>
            <v-icon size="small" @click="deleteItem(item)">
                mdi-delete
            </v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">
                إعادة البيانات الافتراضية
            </v-btn>
        </template>
    </v-data-table>
</template>
<script>
export default {
    data: () => ({
        dialog: false,
        dialogDelete: false,
        categories : [

        'عام' ,'سعد بلاست - بواري' ,'سعد بلاست أكسسوارات' 

        ],

        invoices : ['1','2','3','4','5'],
        headers: [
            {
                title: 'الاسم',
                align: 'start',
                sortable: false,
                key: 'name',
            },
            { title: 'الصورة', key: 'img', sortable: false },
            { title: 'التاريخ', key: 'date', sortable: false },
            { title: 'الشراء', key: 'price', sortable: false },

            { title: 'المبيع', key: 'sell', sortable: false },
            { title: 'العمليات', key: 'actions', sortable: false },
        ],
        desserts: [],
        editedIndex: -1,
        editedItem: {
            name: '',
            price: '',
            img: '',
            sell: '',
            invoice: '',
            category: '',
        },
        defaultItem: {
            name: '',
            price: '',
            img: '',
            sell: '',
            invoice: '',
            category: '',
        },
    }),

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'إضافة منتج' : 'تعديل منتج'
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

    created() {
        this.initialize()
    },

    methods: {
        initialize() {
            this.desserts = [


                {
                    name: 'Frozen Yogurt',
                    date: '2024-1-1',
                    img: 159,
                    price: 6.0,
                    sell: 4.0,
                }


            ]
        },

        editItem(item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        deleteItem(item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        deleteItemConfirm() {
            this.desserts.splice(this.editedIndex, 1)
            this.closeDelete()
        },

        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
                this.desserts.push(this.editedItem)
            }
            this.close()
        },
    },
}
</script>