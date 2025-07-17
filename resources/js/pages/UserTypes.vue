<template>
  <v-app>
    <v-main>
      <v-container>
        <!-- زر الإضافة -->
        <v-row>
          <v-col cols="2" class="d-flex align-center">
            <v-btn color="primary" class="text-white" block style="height: 55px;" @click="openAddDialog">
              إضافة نوع
            </v-btn>
          </v-col>
        </v-row>

        <!-- عرض الأنواع -->
        <v-row>
          <v-col cols="12" md="4" v-for="type in userTypes" :key="type.id">
            <v-card class="transparent-card" elevation="1">
              <v-card-title class="text-h6">
                {{ type.type_name }}
              </v-card-title>
              <v-card-actions>
                <v-btn icon @click="editType(type)">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn icon @click="deleteType(type)">
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>

        <!-- نافذة الإضافة أو التعديل -->
        <v-dialog v-model="dialog" max-width="500">
          <v-card class="pa-4 rounded-lg" elevation="2">
            <v-card-title class="text-h5 pb-4">
              {{ editedType.id ? "تعديل النوع" : "إضافة نوع مستخدم" }}
            </v-card-title>
            <BaseForm
              v-model="editedType"
              :fields="fields"
              submit-label="حفظ"
              cancel-label="إلغاء"
              :show-cancel="true"
              @submit="saveType"
              @cancel="closeDialog"
            />
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
      dialog: false,
      userTypes: [],
      editedType: {
        type_name: "",
      },
      fields: [
        {
          name: "type_name",
          label: "اسم النوع",
          type: "text",
          required: true,
          cols: 12,
        },
      ],
    };
  },
  methods: {
    async fetchUserTypes() {
      try {
        const res = await axios.get("/api/user-types");
        this.userTypes = res.data;
      } catch (e) {
        console.error("فشل جلب الأنواع:", e);
      }
    },
    openAddDialog() {
      this.editedType = { type_name: "" };
      this.dialog = true;
    },
    editType(type) {
      this.editedType = { ...type };
      this.dialog = true;
    },
    async deleteType(type) {
      if (!confirm("هل أنت متأكد من الحذف؟")) return;
      try {
        await axios.delete(`/api/user-types/${type.id}`);
        this.fetchUserTypes();
      } catch (e) {
        console.error("فشل الحذف:", e);
      }
    },
    async saveType(data) {
      try {
        if (data.id) {
          await axios.put(`/api/user-types/${data.id}`, data);
        } else {
          await axios.post("/api/user-types", data);
        }
        this.fetchUserTypes();
        this.closeDialog();
      } catch (e) {
        console.error("فشل الحفظ:", e);
      }
    },
    closeDialog() {
      this.dialog = false;
    },
  },
  mounted() {
    this.fetchUserTypes();
  },
};
</script>
