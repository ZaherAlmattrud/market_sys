<template>
  <v-container>
    <v-card class="pa-6">
      <v-card-title class="d-flex justify-space-between">
        <span class="text-h5">إدارة صلاحيات الدور: {{ role.name }}</span>
        <div>
            <v-btn color="primary" class="me-3" @click="saveChanges">حفظ التعديلات</v-btn>
         
          <v-btn color="success" class="me-2" @click="selectAll">تحديد الكل</v-btn>
          <v-btn color="error" @click="deselectAll">إلغاء تحديد الكل</v-btn>
        </div>
      </v-card-title>

      <v-card-text>
        <v-simple-table>
          <thead>
            <tr>
              <th>الاسم</th>
              <th>الحالة</th>
              <th>تحكم</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="perm in allPermissions" :key="perm.id">
              <td>{{ perm.name }}</td>
              <td>
                <v-icon :color="selectedPermissions.includes(perm.id) ? 'green' : 'grey'">
                  {{ selectedPermissions.includes(perm.id) ? 'mdi-check-circle' : 'mdi-close-circle' }}
                </v-icon>
              </td>
              <td>
                <v-btn icon @click="togglePermission(perm.id)">
                  <v-icon>
                    {{ selectedPermissions.includes(perm.id) ? 'mdi-minus-circle' : 'mdi-plus-circle' }}
                  </v-icon>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </v-simple-table>

      
      </v-card-text>
    </v-card>
  </v-container>
</template>
<script setup>

import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const roleId = route.params.id

const role = ref({})
const allPermissions = ref([])
const originalPermissions = ref([])
const selectedPermissions = ref([])

onMounted(async () => {
  const roleRes = await axios.get(`/api/authorization/roles/${roleId}`)
  role.value = roleRes.data

  const permsRes = await axios.get('/api/authorization/permissions')
  allPermissions.value = permsRes.data

  const rolePermsRes = await axios.get(`/api/authorization/roles/permissions/${roleId}`)
  originalPermissions.value = rolePermsRes.data.map(p => p.id)
  selectedPermissions.value = [...originalPermissions.value]
})

const togglePermission = (id) => {
  if (selectedPermissions.value.includes(id)) {
    selectedPermissions.value = selectedPermissions.value.filter(p => p !== id)
  } else {
    selectedPermissions.value.push(id)
  }
}

const selectAll = () => {
  selectedPermissions.value = allPermissions.value.map(p => p.id)
}

const deselectAll = () => {
  selectedPermissions.value = []
}

const saveChanges = async () => {
  const toAdd = selectedPermissions.value.filter(id => !originalPermissions.value.includes(id))
  const toRemove = originalPermissions.value.filter(id => !selectedPermissions.value.includes(id))

  await axios.post(`/api/authorization/roles/updatePermissions/${roleId}`, {
    permissions_to_add: toAdd,
    permissions_to_remove: toRemove,
  })

  alert('تم الحفظ بنجاح')
}

</script>  