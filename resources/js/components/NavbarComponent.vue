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
        <v-btn v-for="link in links" :key="link.id" color="white" rounded="xl" variant="text" @click="moveTo(link.page)"
          class="mx-1">
          {{ link.title }}
        </v-btn>

        <!-- زر الإشعارات مع القائمة المنبثقة -->
        <v-menu v-model="notificationsMenu" offset-y transition="scale-transition" close-on-content-click>
          <template #activator="{ on, attrs }">
            <v-badge color="red" dot overlap v-if="notifications.length > 0 && hasUnreadNotifications">
              <v-btn icon v-bind="attrs" v-on="on" @click="openNotifications" title="الإشعارات" color="white">
                <v-icon>mdi-bell-outline</v-icon>
              </v-btn>
            </v-badge>

            <v-btn v-else icon v-bind="attrs" v-on="on" @click="openNotifications" title="الإشعارات" color="white">
              <v-icon>mdi-bell-outline</v-icon>
            </v-btn>
          </template>

          <v-card style="min-width: 340px; max-height: 400px; overflow-y: auto; background-color: #f9f9f9;"
            class="pa-2">
            <v-list dense>
              <v-list-item v-if="notifications.length === 0">
                <v-list-item-title class="text-center text-grey darken-1">
                  لا توجد أحداث جديدة
                </v-list-item-title>
              </v-list-item>

              <v-list-item v-for="(audit, index) in notifications" :key="index" class="mb-2 rounded-lg"
                style="background-color: #ffffff; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);">
                <!-- أيقونة نوع العملية -->
               

                <v-list-item-content>
                  <v-list-item-title class="font-weight-bold"
                    :style="`font-size: 15px; color: ${getEventColor(audit.event)};`">
                    <v-list-item-icon>
                  <v-icon :color="getEventColor(audit.event)">
                    {{ getEventIcon(audit.event) }}
                  </v-icon>
                </v-list-item-icon>  {{ audit.user_name || 'مستخدم مجهول' }}
                  </v-list-item-title>
                  <v-list-item-subtitle style="font-size: 13px; color: #444;">
                    {{ audit.event }} على {{ shortModelName(audit.auditable_type) }} #{{ audit.auditable_id || 'N/A' }}
                  </v-list-item-subtitle>
                  <v-list-item-subtitle style="font-size: 11px; color: #888;">
                    {{ formatDate(audit.created_at) }}
                  </v-list-item-subtitle>
                </v-list-item-content>

                <!-- زر تمييز كمقروء -->
                <!-- <v-list-item-action>
          <v-tooltip bottom>
            <template #activator="{ on, attrs }">
              <v-btn icon small v-bind="attrs" v-on="on" @click="markAsRead(audit)">
                <v-icon color="grey">mdi-check</v-icon>
              </v-btn>
            </template>
            <span>تمييز كمقروء</span> 
          </v-tooltip>

               <v-list-item-title class="text-center text-grey darken-1">
                  لا توجد أحداث جديدة
                </v-list-item-title>
              </v-list-item>

        </v-list-item-action> -->


              </v-list-item>
               <v-list-item :to="{ name: 'auditsPage' }" link>

              <v-list-item-title class="text-center text-black darken-1">
                كافة العمليات
              </v-list-item-title>
              </v-list-item>



            </v-list>
          </v-card>
        </v-menu>



        <!-- صورة الملف الشخصي مع القائمة -->
        <v-menu v-model="profileMenu" offset-y transition="scale-transition" close-on-content-click>
          <template #activator="{ props, on }">
            <v-avatar v-bind="props" v-on="on" size="40" class="profile-avatar" role="button"
              aria-label="فتح قائمة المستخدم">
              <v-img :src="avatar" />
            </v-avatar>
          </template>

          <v-card style="min-width: 220px;">
            <v-list dense>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title class="font-weight-bold">
                    {{ userName|| "غير معروف" }}
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    {{ role  || "غير محدد" }}
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>

              <v-divider></v-divider>

              <v-list-item @click="logout" style="cursor: pointer;">
                <v-list-item-title>
                  <v-icon color="red">mdi-logout</v-icon> تسجيل الخروج
                </v-list-item-title>
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
      notificationsMenu: false,
      notifications: [], // قائمة الإشعارات
      hasUnreadNotifications: true, // يمكنك تعديلها حسب منطقك
      profileMenu: false,
      notificationsMenu: false,
      notifications: [],
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
        avatar: "/storage/uploads/user.png",
      },
      defaultAvatar: "/storage/uploads/profile.jpg",
    };
  },
  methods: {

    getEventIcon(event) {
      switch ((event || '').toLowerCase()) {
        case 'created':
          return 'mdi-plus-circle-outline';
        case 'updated':
          return 'mdi-pencil-outline';
        case 'deleted':
          return 'mdi-delete-outline';
        default:
          return 'mdi-information-outline';
      }
    },
    getEventColor(event) {
      switch ((event || '').toLowerCase()) {
        case 'created':
          return '#4CAF50'; // أخضر
        case 'updated':
          return '#2196F3'; // أزرق
        case 'deleted':
          return '#F44336'; // أحمر
        default:
          return '#9E9E9E'; // رمادي
      }
    },
    openNotifications() {
      this.notificationsMenu = true;
      // منطق فتح الإشعارات أو تحميلها
    },
    markAsRead(audit) {
      // منطق التمييز كمقروء، مثل تحديث حالة `audit.read = true`
      // مثلاً:
      this.hasUnreadNotifications = false;
      console.log(`تمت قراءة الإشعار: ${audit.id}`);
    },
    toggleDrawer() {
      this.$store.commit("toggleDrawer");
    },
    moveTo(page) {
      this.$router.push({ name: page });
    },
    async openNotifications() {
      try {
        const response = await axios.get("/api/getLastFiveActivityLog");
        this.notifications = response.data;
        this.notificationsMenu = true;
      } catch (error) {
        console.error("فشل جلب الأحداث:", error);
      }
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
    shortModelName(fullName) {
      if (!fullName) return "";
      return fullName.split("\\").pop();
    },
    formatDate(datetime) {
      if (!datetime) return "";
      return new Date(datetime).toLocaleString("ar-EG", {
        hour12: false,
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
      });
    },
  },
   computed: {
    userInfo() {
      return this.$store.state.user;
    },
    userName() {
      return this.userInfo?.user_name || 'مستخدم';
    },
    avatar() {
     return this.$store.state.avatar;
    },
    role(){

      return this.$store.state.roles[0]['name'];

    }

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
