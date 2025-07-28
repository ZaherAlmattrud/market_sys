<!-- هذا الملف يحتوي فقط على إدارة المستخدمين -->
<template>
  <v-container>


    <v-row>
      <v-col cols="6" class="d-flex align-center">
        <RouterLink to="/rolesPage" style="width: 100%">
          <v-btn color="blue" block style="height: 55px;">
            الأدوار
          </v-btn>
        </RouterLink>
      </v-col>

      <v-col cols="6" class="d-flex align-center">
        <RouterLink to="/permissionsPage" style="width: 100%">
          <v-btn color="green" block style="height: 55px;">
            الصلاحيات
          </v-btn>
        </RouterLink>
      </v-col>
    </v-row>
    <v-row>

    </v-row>

    <v-card>
      <v-card-title>إدارة صلاحيات وأدوار مستخدم</v-card-title>
      <v-card-text>
        <v-autocomplete v-model="selectedUser" :items="users" item-value="id" item-title="user_name"
          label="اختر المستخدم" return-object clearable variant="outlined" @change="loadUserRolesAndPermissions" />

        <v-divider class="my-4" />

        <v-row>
          <!-- الأدوار -->
          <v-col cols="12" md="6">
            <v-form @submit.prevent="assignRole">
              <v-select v-model="form.role" :items="roles.map(r => r.name)" label="اختر الدور لإضافته"
                variant="outlined" required />
              <v-btn type="submit" color="success" class="mt-2">تعيين الدور</v-btn>
            </v-form>

            <v-list v-if="userRoles.length" class="mt-4">
              <v-subheader>الأدوار المعينة</v-subheader>
              <v-list-item v-for="role in userRoles" :key="role">
                <v-list-item-content>{{ role }}</v-list-item-content>
                <v-list-item-action>
                  <v-btn icon color="red" @click="removeRole(role)">
                    <v-icon>mdi-close</v-icon>
                  </v-btn>
                </v-list-item-action>
              </v-list-item>
            </v-list>
          </v-col>

          <!-- الصلاحيات -->
          <v-col cols="12" md="6">
            <v-form @submit.prevent="givePermission">
              <v-select v-model="form.permission" :items="permissions.map(p => p.name)" label="اختر الصلاحية لإضافتها"
                variant="outlined" required />
              <v-btn type="submit" color="success" class="mt-2">تعيين الصلاحية</v-btn>
            </v-form>

            <v-list v-if="userPermissions.length" class="mt-4">
              <v-subheader>الصلاحيات المعينة</v-subheader>
              <v-list-item v-for="permission in userPermissions" :key="permission">
                <v-list-item-content>{{ permission }}</v-list-item-content>
                <v-list-item-action>
                  <v-btn icon color="red" @click="removePermission(permission)">
                    <v-icon>mdi-close</v-icon>
                  </v-btn>
                </v-list-item-action>
              </v-list-item>
            </v-list>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const selectedUser = ref(null)
const users = ref([])
const roles = ref([])
const permissions = ref([])
const userRoles = ref([])
const userPermissions = ref([])
const form = ref({ role: '', permission: '' })

onMounted(async () => {
  users.value = (await axios.get('/api/getAllUsers')).data
  roles.value = (await axios.get('/api/authorization/roles')).data
  permissions.value = (await axios.get('/api/authorization/permissions')).data
})

const loadUserRolesAndPermissions = async () => {
  if (!selectedUser.value) return
  const res = await axios.get(`/api/user/${selectedUser.value.id}/permissions-roles`)
  userRoles.value = res.data.roles
  userPermissions.value = res.data.permissions
}

const assignRole = async () => {
  await axios.post('/api/authorization/assign-role', {
    user_id: selectedUser.value.id,
    role: form.value.role
  })
  form.value.role = ''
  loadUserRolesAndPermissions()
}

const givePermission = async () => {
  await axios.post('/api/authorization/give-permission', {
    user_id: selectedUser.value.id,
    permission: form.value.permission
  })
  form.value.permission = ''
  loadUserRolesAndPermissions()
}

const removeRole = async (role) => {
  await axios.post('/api/remove-role', {
    user_id: selectedUser.value.id,
    role
  })
  loadUserRolesAndPermissions()
}

const removePermission = async (permission) => {
  await axios.post('/api/remove-permission', {
    user_id: selectedUser.value.id,
    permission
  })
  loadUserRolesAndPermissions()
}
</script>
