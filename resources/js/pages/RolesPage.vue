<template>
  <v-container class="pa-6" fluid>
    <v-card elevation="3" class="rounded-xl">
      <v-card-title class="d-flex justify-space-between align-center">
        <span class="text-h6 font-weight-bold">إدارة الأدوار</span>
        <v-btn color="primary" @click="dialogCreate = true" prepend-icon="mdi-plus">
          إضافة دور
        </v-btn>
      </v-card-title>

      <v-divider></v-divider>

      <v-card-text>
        <v-row>
          <v-col
            v-for="role in roles"
            :key="role.id"
            cols="12"
            sm="6"
            md="4"
            lg="3"
          >
            <v-card class="pa-3 d-flex justify-space-between align-center">
              <!-- <span class="text-subtitle-1 font-weight-medium">
                {{ role.name }}
              </span> -->

              <router-link :to="`/rolesPermissionsPage/${role.id}`" class="text-subtitle-1 font-weight-medium">
  {{ role.name }}
</router-link>


              <div>
                <v-btn icon color="blue" @click="openEditDialog(role)">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn icon color="red" @click="deleteRole(role.id)">
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <!-- Dialog: إنشاء دور -->
    <v-dialog v-model="dialogCreate" max-width="500">
      <v-card class="rounded-xl">
        <v-card-title class="font-weight-bold">إضافة دور جديد</v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-form @submit.prevent="createRole">
            <v-text-field
              v-model="newRole"
              label="اسم الدور"
              prepend-inner-icon="mdi-account-badge-outline"
              required
            />
            <div class="d-flex justify-end mt-4">
              <v-btn text @click="dialogCreate = false" class="me-2">إلغاء</v-btn>
              <v-btn type="submit" color="primary">حفظ</v-btn>
            </div>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- Dialog: تعديل دور -->
    <v-dialog v-model="dialogEdit" max-width="500">
      <v-card class="rounded-xl">
        <v-card-title class="font-weight-bold">تعديل الدور</v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-form @submit.prevent="updateRole">
            <v-text-field
              v-model="editRoleData.name"
              label="اسم الدور"
              prepend-inner-icon="mdi-pencil"
              required
            />
            <div class="d-flex justify-end mt-4">
              <v-btn text @click="dialogEdit = false" class="me-2">إلغاء</v-btn>
              <v-btn type="submit" color="primary">تحديث</v-btn>
            </div>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const roles = ref([])
const newRole = ref('')
const dialogCreate = ref(false)
const dialogEdit = ref(false)
const editRoleData = ref({ id: null, name: '' })

const fetchRoles = async () => {
  const res = await axios.get('/api/authorization/roles')
  roles.value = res.data
}

const createRole = async () => {
  if (!newRole.value.trim()) return
  await axios.post('/api/authorization/roles/create', { name: newRole.value })
  newRole.value = ''
  dialogCreate.value = false
  fetchRoles()
}

const deleteRole = async (id) => {
  if (confirm('هل أنت متأكد من حذف هذا الدور؟')) {
    await axios.delete(`/api/authorization/roles/delete/${id}`)
    fetchRoles()
  }
}

const openEditDialog = (role) => {
  editRoleData.value = { ...role }
  dialogEdit.value = true
}

const updateRole = async () => {
  if (!editRoleData.value.name.trim()) return
  await axios.put(`/api/authorization/roles/${editRoleData.value.id}`, {
    name: editRoleData.value.name,
  })
  dialogEdit.value = false
  fetchRoles()
}

onMounted(fetchRoles)
</script>
