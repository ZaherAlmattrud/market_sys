<template>
  <v-card>
    <!-- <v-layout> -->
    <v-navigation-drawer  color="orange-lighten-5"  location="right" permanent :width="200" v-model="drawerVisible":value="drawerVisible">
      <template v-slot:prepend>
        <v-list-item
          lines="two"
          prepend-avatar="storage/uploads/profile.jpg"
          subtitle=""
          title="المهندس زاهر "
        ></v-list-item>
      </template>

      <v-divider></v-divider>

      <v-list density="compact" nav>
        <RouterLink   to="/">
          <v-list-item
            prepend-icon="mdi-list-box-outline"
            title="المنتجات"
            value="home"
             color="blue"
           
          ></v-list-item>
        </RouterLink>

        <!-- <RouterLink to="/healthy"> <v-list-item prepend-icon="mdi-pipe-valve" title="الصحية"
              value="account"></v-list-item>
          </RouterLink> -->
        <!-- <RouterLink to="/customers"> <v-list-item prepend-icon="mdi-account-box-outline" title="الزبائن"
              value="users"></v-list-item></RouterLink> -->
        <RouterLink to="/users">
          <v-list-item
            prepend-icon="mdi-briefcase-account-outline"
            title="الحسابات"
            value="users"
             color="purple"
          ></v-list-item
        >

      </RouterLink>

        <RouterLink to="/SellsPage">
          <v-list-item
            prepend-icon="mdi-invoice-text-multiple-outline"
            title="المبيعات"
             color="indigo"
            value="SellsPage"
          ></v-list-item
        >

        
        
        
        </RouterLink>
        <RouterLink to="/arresteds">
          <v-list-item
            prepend-icon="mdi-import"
            title="المقبوضات"
            value="arresteds"
              color="green"
          ></v-list-item>
        </RouterLink>
        <!-- <RouterLink to="/accounts"> <v-list-item prepend-icon="mdi-briefcase-account-outline" title="الحسابات"
              value="accounts"></v-list-item></RouterLink> -->

        <RouterLink to="/invoices">
          <v-list-item
            prepend-icon="mdi-invoice-text-multiple-outline"
            title="المشتريات"
            value="invoices"
             color="orange"
          ></v-list-item
        ></RouterLink>

        <RouterLink to="/paids">
          <v-list-item
            prepend-icon="mdi-export"
            title="المدفوعات"
            value="paids"
            
             color="red"
          ></v-list-item>
        </RouterLink>
        <RouterLink to="/areas">
          <v-list-item
            prepend-icon="mdi-map-marker-multiple-outline"
            title="المناطق"
            value="areas"
             color="brown"
          ></v-list-item
        ></RouterLink>

        <RouterLink to="/days">
          <v-list-item
            prepend-icon="mdi-alarm-panel-outline"
            title="اليومية"
            value="days"
                      color="lime"
          ></v-list-item>
        </RouterLink>

        <RouterLink to="/reports">
          <v-list-item
            prepend-icon="mdi-chart-bar"
            title="التقارير"
            value="reports"
            color="orange"
          ></v-list-item>
        </RouterLink>

        <RouterLink to="/category">
          <v-list-item
            prepend-icon="mdi-shape-plus-outline"
            title="الأصناف"
            value="category"
             color="purple"
          ></v-list-item>
        </RouterLink>

        <RouterLink to="/exchange">
          <v-list-item
            prepend-icon="mdi-currency-usd"
            title="الصرف"
            value="exchange"
             color="blue"
          ></v-list-item>
        </RouterLink>

        <RouterLink to="/accountsCash">
          <v-list-item
            prepend-icon="mdi-briefcase-account-outline"
            title="الأرصدة"
            value="accountsCash"
             color="BROWN"
          ></v-list-item>
        </RouterLink>
        <v-list-item
          @click="logout"
          prepend-icon="mdi-account-lock-outline"
          title="الخروج"
          value="logOut"
           color="red"
        ></v-list-item>
      </v-list>
    </v-navigation-drawer>
    <!-- <v-main>
        <RouterView />

      </v-main>
    </v-layout> -->
  </v-card>
</template>
<script>
 

export default {
  props: {
    drawer: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      loggedIn: false,
    };
  },

  computed: {
    drawerVisible() {
      return this.$store.getters.drawerVisible; // الحصول على حالة الشريط الجانبي من Vuex
    }
  },

  beforeCreate() {
    const loggedIn = localStorage.getItem("user");
    if (loggedIn) this.loggedIn = true;
  },

 

  methods: {
    logout() {
      localStorage.removeItem("user");
      this.$router.push({ name: "login" });
    },
  },
};
</script>
