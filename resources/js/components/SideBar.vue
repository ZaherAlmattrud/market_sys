<template>
  <v-card>
    <v-navigation-drawer color="orange-lighten-5" location="right" permanent :width="200" v-model="drawerVisible">
      <template v-slot:prepend>
        <v-list-item @click="goToProfile" class="user-info" style="cursor:pointer;">
          <template v-slot:prepend>
            <v-avatar size="40">
              <img :src="avatar" alt="User avatar" />
            </v-avatar>
          </template>
          <v-list-item-content>
            <v-list-item-title class="user-name">{{ userName }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

      </template>

      <v-divider />

      <v-list density="compact" nav>

        <!-- المنتجات -->
        <RouterLink v-if="hasRole(['SuperAdmin', 'Admin', 'Employee', 'Customer'])" to="/products">
          <v-list-item class="hover-blue">
            <template v-slot:prepend>
              <v-icon color="blue">mdi-list-box-outline</v-icon>
            </template>
            المنتجات
          </v-list-item>
        </RouterLink>

        <!-- الحسابات -->
        <RouterLink v-if="hasRole(['SuperAdmin', 'Admin', 'Employee'])" to="/users">
          <v-list-item class="hover-purple">
            <template v-slot:prepend>
              <v-icon color="purple">mdi-briefcase-account-outline</v-icon>
            </template>
            الحسابات
          </v-list-item>
        </RouterLink>

        <!-- المبيعات -->
        <RouterLink v-if="hasRole(['SuperAdmin', 'Admin', 'Employee'])" to="/SellsPage">
          <v-list-item class="hover-indigo">
            <template v-slot:prepend>
              <v-icon color="indigo">mdi-invoice-text-multiple-outline</v-icon>
            </template>
            المبيعات
          </v-list-item>
        </RouterLink>

        <!-- المقبوضات -->
        <RouterLink v-if="hasRole(['SuperAdmin', 'Admin', 'Employee'])" to="/arresteds">
          <v-list-item class="hover-green">
            <template v-slot:prepend>
              <v-icon color="green">mdi-import</v-icon>
            </template>
            المقبوضات
          </v-list-item>
        </RouterLink>

        <!-- المشتريات -->
        <RouterLink v-if="hasRole(['SuperAdmin', 'Admin', 'Employee'])" to="/invoices">
          <v-list-item class="hover-orange">
            <template v-slot:prepend>
              <v-icon color="orange">mdi-invoice-text-multiple-outline</v-icon>
            </template>
            المشتريات
          </v-list-item>
        </RouterLink>

        <!-- المدفوعات -->
        <RouterLink v-if="hasRole(['SuperAdmin', 'Admin'])" to="/paids">
          <v-list-item class="hover-red">
            <template v-slot:prepend>
              <v-icon color="red">mdi-export</v-icon>
            </template>
            المدفوعات
          </v-list-item>
        </RouterLink>

        <!-- المناطق -->
        <RouterLink v-if="hasRole(['SuperAdmin', 'Admin', 'Employee'])" to="/areas">
          <v-list-item class="hover-brown">
            <template v-slot:prepend>
              <v-icon color="brown">mdi-map-marker-multiple-outline</v-icon>
            </template>
            المناطق
          </v-list-item>
        </RouterLink>

        <!-- اليومية -->
        <RouterLink v-if="hasRole(['SuperAdmin', 'Admin'])" to="/days">
          <v-list-item class="hover-lime">
            <template v-slot:prepend>
              <v-icon color="lime">mdi-alarm-panel-outline</v-icon>
            </template>
            اليومية
          </v-list-item>
        </RouterLink>


        <!-- الأصناف -->
        <RouterLink v-if="hasRole(['SuperAdmin', 'Admin'])" to="/category">
          <v-list-item class="hover-deep-purple">
            <template v-slot:prepend>
              <v-icon color="deep-purple">mdi-shape-plus-outline</v-icon>
            </template>
            الأصناف
          </v-list-item>
        </RouterLink>

        <!-- العملات -->
        <RouterLink v-if="hasRole(['SuperAdmin', 'Admin'])" to="/exchange">
          <v-list-item class="hover-cyan">
            <template v-slot:prepend>
              <v-icon color="cyan">mdi-currency-usd</v-icon>
            </template>
            العملات
          </v-list-item>
        </RouterLink>

        <!-- الأرصدة -->
        <RouterLink v-if="hasRole(['SuperAdmin', 'Admin'])" to="/accountsCash">
          <v-list-item class="hover-teal">
            <template v-slot:prepend>
              <v-icon color="teal">mdi-briefcase-account-outline</v-icon>
            </template>
            الأرصدة
          </v-list-item>
        </RouterLink>



        <!-- إدارة الوصول -->
        <RouterLink v-if="hasRole(['SuperAdmin'])" to="/authorizationPag">
          <v-list-item class="hover-cyan">
            <template v-slot:prepend>
              <v-icon color="cyan">mdi-account-cog</v-icon>
            </template>
            التحكم بالوصول
          </v-list-item>
        </RouterLink>


        <!-- إدارة المستخدمين -->
        <RouterLink v-if="hasRole(['SuperAdmin'])" to="/usersSetting">
          <v-list-item class="hover-purple">
            <template v-slot:prepend>
              <v-icon color="purple">mdi-account-cog</v-icon>
            </template>
            المستخدمين
          </v-list-item>
        </RouterLink>


        <!-- تسجيل الخروج -->
        <!-- <v-list-item @click="logout" class="hover-red">
          <template v-slot:prepend>
            <v-icon color="red">mdi-account-lock-outline</v-icon>
          </template>
          الخروج
        </v-list-item> -->

      </v-list>
    </v-navigation-drawer>
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
  computed: {


    drawerVisible() {
      return this.$store.getters.drawerVisible;
    },
    userInfo() {
      return this.$store.state.user;
    },
    userName() {
      return this.userInfo?.user_name || 'مستخدم';
    },
    avatar() {
      return this.$store.state.avatar;
    }
  },
  methods: {

    hasRole(allowedRoles) {


      const roles = this.$store.state.roles || [];

      return roles.some(r => allowedRoles.includes(r.name));


    },

    goToProfile() {
      const user = JSON.parse(localStorage.getItem("user"));
      if (user && user.id) {
        this.$router.push(`/userDetails/${user.id}`);
      } else {
        console.warn("لم يتم العثور على معلومات المستخدم.");
      }
    },
    async logout() {
      // localStorage.removeItem("user");
      // this.$router.push({ name: "login" });

      try {
        await axios.post("/api/auth/logout");

        localStorage.removeItem("token");
        localStorage.removeItem("user");
        delete axios.defaults.headers.common["Authorization"];

        this.$router.push({ name: "login" });
      } catch (error) {
        console.error("فشل تسجيل الخروج:", error);
      }
    },
  },
};
</script>

<style scoped>
.v-list-item {
  transition: background-color 0.2s ease;
  border-radius: 8px;
}

.hover-blue:hover {
  background-color: rgba(33, 150, 243, 0.1);
}

.hover-purple:hover {
  background-color: rgba(156, 39, 176, 0.1);
}

.hover-indigo:hover {
  background-color: rgba(63, 81, 181, 0.1);
}

.hover-green:hover {
  background-color: rgba(76, 175, 80, 0.1);
}

.hover-orange:hover {
  background-color: rgba(255, 152, 0, 0.1);
}

.hover-red:hover {
  background-color: rgba(244, 67, 54, 0.1);
}

.hover-brown:hover {
  background-color: rgba(121, 85, 72, 0.1);
}

.hover-lime:hover {
  background-color: rgba(205, 220, 57, 0.15);
}

.hover-deep-purple:hover {
  background-color: rgba(103, 58, 183, 0.1);
}

.hover-cyan:hover {
  background-color: rgba(0, 188, 212, 0.1);
}

.hover-teal:hover {
  background-color: rgba(0, 150, 136, 0.1);
}

.user-name {
  font-size: 0.85rem;
  /* حجم أصغر */
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
