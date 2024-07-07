<template>
  <v-card>
    <v-layout>
      <v-navigation-drawer location="right" permanent :width="175">
        <template v-slot:prepend>
          <v-list-item lines="two" prepend-avatar="https://randomuser.me/api/portraits/women/81.jpg"
            subtitle="Logged in" title="Jane Smith"></v-list-item>
        </template>

        <v-divider></v-divider>

        <v-list density="compact" nav>
          <RouterLink to="/"> <v-list-item prepend-icon="mdi-list-box-outline" title="المنتجات"
              value="home"></v-list-item>
          </RouterLink>

          <!-- <RouterLink to="/healthy"> <v-list-item prepend-icon="mdi-pipe-valve" title="الصحية"
              value="account"></v-list-item>
          </RouterLink> -->
          <!-- <RouterLink to="/customers"> <v-list-item prepend-icon="mdi-account-box-outline" title="الزبائن"
              value="users"></v-list-item></RouterLink> -->
          <RouterLink to="/users"> <v-list-item prepend-icon="mdi-account-group-outline" title="المستخدمين"
              value="users"></v-list-item></RouterLink>
          <RouterLink to="/arresteds"> <v-list-item prepend-icon="mdi-import" title="المقبوضات"
              value="arresteds"></v-list-item>
          </RouterLink>
          <RouterLink to="/accounts"> <v-list-item prepend-icon="mdi-briefcase-account-outline" title="الحسابات"
              value="accounts"></v-list-item></RouterLink>





          <RouterLink to="/invoices"> <v-list-item prepend-icon="mdi-invoice-text-multiple-outline" title="الفواتير"
              value="invoices"></v-list-item></RouterLink>

          <RouterLink to="/paids"> <v-list-item prepend-icon="mdi-export" title="المدفوعات" value="paids"></v-list-item>
          </RouterLink>
          <RouterLink to="/areas"> <v-list-item prepend-icon="mdi-map-marker-multiple-outline" title="المناطق"
              value="areas"></v-list-item></RouterLink>
          <RouterLink to="/category"> <v-list-item prepend-icon="mdi-shape-plus-outline" title="الأصناف"
              value="category"></v-list-item>
          </RouterLink>
          <v-list-item @click="logout" prepend-icon="mdi-account-lock-outline" title="الخروج"
            value="logOut"></v-list-item>


        </v-list>

      </v-navigation-drawer>
      <v-main>
        <RouterView />

      </v-main>
    </v-layout>
  </v-card>
</template>
<script>
export default {
  data() {
    return {



    };
  },
  methods: {

    logout() {

      localStorage.removeItem('user');
      this.$router.push({ name: 'login' });
    },




  }
}
</script>