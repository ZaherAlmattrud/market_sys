<template>
  <v-container class="pa-6" fluid>
    <v-card elevation="3" class="rounded-xl">
      <v-card-title class="d-flex justify-space-between align-center">
        <span class="text-h6 font-weight-bold">إدارة الصلاحيات</span>
        <v-btn color="primary" @click="dialogCreate = true" prepend-icon="mdi-plus">
          إضافة صلاحية
        </v-btn>
      </v-card-title>

      <v-divider></v-divider>

      <v-card-text>
        <v-row>
          <v-col
            v-for="permission in permissions"
            :key="permission.id"
            cols="12"
            sm="6"
            md="4"
            lg="3"
          >
            <v-card class="pa-3 d-flex justify-space-between align-center">
              <span class="text-subtitle-1 font-weight-medium">
                {{ permission.name }}
              </span>
              <div>
                <v-btn icon color="blue" @click="openEditDialog(permission)">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn icon color="red" @click="deletePermission(permission.id)">
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <!-- Dialog: إنشاء صلاحية -->
    <v-dialog v-model="dialogCreate" max-width="500">
      <v-card class="rounded-xl">
        <v-card-title class="font-weight-bold">إضافة صلاحية</v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-form @submit.prevent="createPermission">
            <v-text-field
              v-model="newPermission"
              label="اسم الصلاحية"
              prepend-inner-icon="mdi-shield-key-outline"
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

    <!-- Dialog: تعديل صلاحية -->
    <v-dialog v-model="dialogEdit" max-width="500">
      <v-card class="rounded-xl">
        <v-card-title class="font-weight-bold">تعديل اسم الصلاحية</v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-form @submit.prevent="updatePermission">
            <v-text-field
              v-model="editPermissionData.name"
              label="اسم الصلاحية"
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

const permissions = ref([])
const newPermission = ref('')
const dialogCreate = ref(false)
const dialogEdit = ref(false)
const editPermissionData = ref({ id: null, name: '' })

const fetchPermissions = async () => {
  const res = await axios.get('/api/authorization/permissions')
  permissions.value = res.data
}

const createPermission = async () => {
  if (!newPermission.value.trim()) return
  await axios.post('/api/authorization/permissions/create', { name: newPermission.value })
  newPermission.value = ''
  dialogCreate.value = false
  fetchPermissions()
}

const deletePermission = async (id) => {
  if (confirm('هل أنت متأكد من حذف هذه الصلاحية؟')) {
    await axios.delete(`/api/permissions/delete/${id}`)
    fetchPermissions()
  }
}

const openEditDialog = (permission) => {
  editPermissionData.value = { ...permission }
  dialogEdit.value = true
}

const updatePermission = async () => {
  if (!editPermissionData.value.name.trim()) return
  await axios.put(`/api/permissions/update/${editPermissionData.value.id}`, {
    name: editPermissionData.value.name,
  })
  dialogEdit.value = false
  fetchPermissions()
}

onMounted(fetchPermissions)
</script>
