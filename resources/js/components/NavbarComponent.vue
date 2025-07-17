<template>
  <v-app-bar app color="gray" image="storage/uploads/sidebar.png">
    <template v-slot:prepend>
      <v-btn @click="toggleDrawer" icon>
        <v-icon>mdi-menu</v-icon>
      </v-btn>
    </template>

    <div class="ticker-container">
      <div class="ticker-text">
        <span v-for="(zekr, index) in azkar" :key="'first-' + index">
          {{ zekr }} &nbsp; • &nbsp;
        </span>
        <span v-for="(zekr, index) in azkar" :key="'second-' + index">
          {{ zekr }} &nbsp; • &nbsp;
        </span>
      </div>
    </div>

    <template v-slot:append>
      <v-row align="center" no-gutters class="mx-3" style="gap: 12px;">
        <v-btn
          v-for="link in links"
          :key="link.id"
          color="white"
          rounded="xl"
          variant="text"
          @click="moveTo(link.page)"
          class="mx-1"
        >
          {{ link.title }}
        </v-btn>

        <v-btn icon @click="openNotifications" title="الإشعارات" color="white">
          <v-icon>mdi-bell-outline</v-icon>
        </v-btn>

        <v-btn icon @click="toggleLanguage" title="تغيير اللغة" color="white">
          <v-icon>mdi-translate</v-icon>
        </v-btn>

        <!-- Profile Avatar with dropdown -->
        <v-menu
          v-model="profileMenu"
          offset-y
          transition="scale-transition"
          close-on-content-click
        >
          <template #activator="{ props, on }">
            <v-avatar
              v-bind="props"
              v-on="on"
              size="40"
              class="profile-avatar"
              role="button"
              aria-label="فتح قائمة المستخدم"
            >
              <v-img :src="user.avatar || defaultAvatar" />
            </v-avatar>
          </template>

          <v-card style="min-width: 220px;">
            <v-list dense>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title class="font-weight-bold">
                    {{ user.user_name || "غير معروف" }}
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    {{ user.userType?.type_name || "غير محدد" }}
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>

              <v-divider></v-divider>

              <v-list-item @click="logout" style="cursor: pointer;">
               
                 
                
                <v-list-item-title>  <v-icon color="red">mdi-logout</v-icon> تسجيل الخروج</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-card>
        </v-menu>
      </v-row>
    </template>
  </v-app-bar>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {

        user: {},
      profileMenu: false,
      azkar: [
        "سبحان الله",
        "الحمد لله",
        "لا إله إلا الله",
        "الله أكبر",
        "لا حول ولا قوة إلا بالله",
        "أستغفر الله",
        "اللهم صل وسلم وبارك على سيدنا محمد",
        "الله اكبر ولله الحمد",
        "سبحان الله وبحمده سبحان الله العظيم",
      ],
      links: [
        { id: 3, title: "معدلات ليبرا", page: "LibraRatingCatalog" },
        { id: 2, title: "دليل ليبرا", page: "LibraCatalog" },
        { id: 1, title: "دليل توتال", page: "TotalCatalog" },
      ],
      user: {
        user_name: "المهندس زاهر",
        userType: { type_name: "مدير النظام" },
        avatar: "/storage/uploads/profile.jpg",
      },
      defaultAvatar: "/storage/uploads/profile.jpg",
    };
  },
  methods: {
    toggleDrawer() {
      this.$store.commit("toggleDrawer");
    },
    moveTo(page) {
      this.$router.push({ name: page });
    },
    openNotifications() {
      alert("هنا تضع منطق عرض الإشعارات");
    },
    toggleLanguage() {
      alert("هنا تضع منطق تغيير اللغة");
    },
    async logout() {
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
.ticker-container {
  width: 60%;
  overflow: hidden;
  direction: rtl;
  height: 40px;
  display: flex;
  align-items: center;
  background: transparent;
}

.ticker-text {
  display: inline-block;
  white-space: nowrap;
  animation: scroll-continuous 40s linear infinite;
}

@keyframes scroll-continuous {
  from {
    transform: translateX(-100%);
  }
  to {
    transform: translateX(100%);
  }
}

.profile-avatar {
  cursor: pointer;
  border-radius: 50%;
  border: 2px solid white;
  overflow: hidden;
}

.v-list-item-title {
  font-size: 14px !important;
}

.v-list-item-subtitle {
  font-size: 12px !important;
  opacity: 0.7;
}

.v-list-item {
  padding-top: 6px !important;
  padding-bottom: 6px !important;
}

</style>
