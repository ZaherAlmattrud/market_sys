<template>
    <v-app>
        <v-main>
            <v-container>
                <!-- مربع البحث وزر الإضافة -->
                <v-row>
                    <v-col cols="2" class="d-flex align-center">
                        <v-btn color="primary" class="text-white" block style="height: 55px;" @click="openAddDialog">
                            جديد
                        </v-btn>
                    </v-col>



                    <v-col cols="8">
                        <v-text-field variant="outlined" v-model="search" label="بحث بالمستخدم" @input="onSearchInput"
                            clearable dense style="height: 40px;" />
                    </v-col>

                    <v-col cols="2" class="d-flex align-center">
                        <RouterLink to="/userTypes" style="width: 100%">
                            <v-btn color="secondary" block style="height: 55px;">
                                أنواع المستخدمين
                            </v-btn>
                        </RouterLink>
                    </v-col>
                </v-row>



                <!-- عرض المستخدمين -->
                <v-row>
                    <v-col cols="12" md="4" v-for="user in users" :key="user.id">
  <v-card class="elevation-3 rounded-xl pa-4" style="min-height: 180px;">
    <v-card-title class="text-h6 font-weight-bold mb-2">
      {{ user.user_name }}
    </v-card-title>
    <v-card-text class="text-body-2">
      <div class="mb-1">
        <strong> الدور:</strong> {{ user.role ??  'غير متوفر' }}
      </div>
      <div class="mb-1">
        <strong>المنطقة:</strong> {{ user.area }}
      </div>
      <div>
        <strong>الهاتف:</strong> {{ user.mobile ?? 'غير متوفر' }}
      </div>
    </v-card-text>
    <v-card-actions class="justify-end">
      <v-btn icon color="primary" @click="editUser(user)">
        <v-icon>mdi-pencil</v-icon>
      </v-btn>
      <v-btn icon color="error" @click="deleteUser(user)">
        <v-icon>mdi-delete</v-icon>
      </v-btn>
    </v-card-actions>
  </v-card>
</v-col>

                </v-row>

                <!-- روابط الصفحات (إذا عندك) -->
                <v-row>
                    <v-col cols="12" class="text-center">
                        <v-btn v-for="link in links" v-if="link" :key="link.label" :disabled="!link.url"
                            @click="goToPage(link)" text v-html="link.label" />
                    </v-col>
                </v-row>

                <!-- نموذج الإضافة/التعديل -->
                <v-dialog v-model="dialog" max-width="600">
                    <v-card class="pa-4 rounded-lg" elevation="2">
                        <v-card-title class="text-h5 pb-4">
                            {{ editedUser.id ? "تعديل مستخدم" : "إضافة مستخدم جديد" }}
                        </v-card-title>
                        <BaseForm v-model="editedUser" :fields="userFields" :grid-cols="1" submit-label="حفظ"
                            cancel-label="إلغاء" :show-cancel="true" @submit="saveUser" @cancel="closeDialog" />
                    </v-card>
                </v-dialog>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import BaseForm from "@/components/FormComponent.vue";

export default {
    components: { BaseForm },
    data() {
        return {
            users: [],
            userTypes: [],
            areas: [],
            search: "",
            links: [],
            currentPage: 1,
            dialog: false,
            editedUser: {
                user_name: "",
                user_type: "",
                area_id: "",
                mobile: "",
                password: "",
                password_confirmation: "",
            },
            userFields: [
                {
                    name: "user_name",
                    label: "اسم المستخدم",
                    type: "text",
                    required: true,
                    cols: 12,
                },
                {
                    name: "user_type",
                    label: "نوع المستخدم",
                    component: "VSelect",
                    items: [],
                    itemTitle: "name",
                    itemValue: "id",
                    required: true,
                    cols: 12,
                },
                {
                    name: "area_id",
                    label: "المنطقة",
                    component: "VSelect",
                    items: [],
                    itemTitle: "name",
                    itemValue: "id",
                    required: true,
                    cols: 12,
                },
                {
                    name: "mobile",
                    label: "رقم الهاتف",
                    type: "text",
                    required: true,
                    cols: 12,
                },
                {
                    name: "password",
                    label: "كلمة المرور",
                    type: "password",
                    required: false, // مطلوب فقط عند الإضافة أو التعديل مع تغيير كلمة المرور
                    cols: 12,
                },

                {
                    name: "password_confirmation",
                    label: "تأكيد كلمة المرور",
                    type: "password",
                    required: false,
                    cols: 12,
                }

            ],
        };
    },
    methods: {
        async fetchUsers(page = 1) {
            try {
                const response = await axios.get("/api/getAllSystemUsers", {
                    params: { page, search: this.search },
                });
                this.users = response.data.data;
                this.links = response.data.links ?? [];
                this.currentPage = page;
            } catch (error) {
                console.error("فشل جلب المستخدمين:", error);
            }
        },
        async fetchUserTypes() {
            try {
                const response = await axios.get("/api/getAllUserTypes");
                this.userTypes = response.data;
                const userTypeField = this.userFields.find((f) => f.name === "user_type");
                if (userTypeField) userTypeField.items = this.userTypes;
            } catch (error) {
                console.error("فشل جلب أنواع المستخدمين:", error);
            }
        },
        async fetchAreas() {
            try {
                const response = await axios.get("/api/getAllAreas");
                this.areas = response.data;
                const areaField = this.userFields.find((f) => f.name === "area_id");
                if (areaField) areaField.items = this.areas;
            } catch (error) {
                console.error("فشل جلب المناطق:", error);
            }
        },
        getUserTypeName(id) {
            const type = this.userTypes.find((t) => t.id === id);
            return type ? type.type_name : "---";
        },
        getAreaName(id) {
            const area = this.areas.find((a) => a.id === id);
            return area ? area.name : "---";
        },
        goToPage(link) {
            if (link.url) {
                const url = new URL(link.url);
                const page = url.searchParams.get("page");
                this.fetchUsers(page);
            }
        },
        onSearchInput() {
            this.fetchUsers(1);
        },
        openAddDialog() {
            this.editedUser = {
                user_name: "",
                user_type: "",
                area_id: "",
                mobile: "",
                password: "",
                password_confirmation: "",
            };
            this.dialog = false;
            this.$nextTick(() => {
                this.dialog = true;
            });
        },
        editUser(user) {
            this.editedUser = { ...user, password: "" };
            this.dialog = true;
        },
        async deleteUser(user) {
            if (!confirm("هل أنت متأكد من حذف المستخدم؟")) return;
            try {
                await axios.delete(`/api/deleteUser/${user.id}`);
                this.fetchUsers(this.currentPage);
            } catch (error) {
                console.error("فشل الحذف:", error);
            }
        },
        closeDialog() {
            this.dialog = false;
        },
        async saveUser(formData) {
            // try {
            //     if (!formData.id) {
            //         await axios.post("/api/auth/register", formData);
            //     } else {
            //         await axios.put(`/api/auth/${formData.id}`, formData);
            //     }
            //     this.fetchUsers(this.currentPage);
            //     this.closeDialog();
            // } catch (error) {
            //     console.error("فشل الحفظ:", error);
            // }

            try {
                // خذ نسخة من البيانات المربوطة بـ BaseForm (editedUser)
                const dataToSend = { ...this.editedUser };

                console.log("🚀 سيتم إرسال:", dataToSend);

                if (!dataToSend.id) {
                    await axios.post("/api/auth/register", dataToSend);
                } else {
                    await axios.put(`/api/auth/${dataToSend.id}`, dataToSend);
                }

                this.fetchUsers(this.currentPage);
                this.closeDialog();
            } catch (error) {
                console.error("فشل الحفظ:", error.response?.data ?? error);
            }
        },
    },
    mounted() {
        this.fetchUserTypes();
        this.fetchAreas();
        this.fetchUsers();
    },
};
</script>
