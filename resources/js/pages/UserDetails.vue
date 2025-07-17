<template>
  <v-container class="py-8">
    <v-row justify="center">
      <v-col cols="12" md="8" lg="6">
        <v-card class="pa-6 rounded-xl elevation-3">
          <v-row class="mb-6" align="center" justify="center">
            <div class="image-wrapper">
              <v-img
                :src="user.avatar || defaultAvatar"
                class="user-img"
              />
            </div>
          </v-row>

          <v-card-title class="text-h5 text-center mb-2">
            {{ user.user_name }}
          </v-card-title>

          <v-card-subtitle class="text-center mb-4">
            {{ user.userType?.type_name || 'غير محدد' }}
          </v-card-subtitle>

          <v-divider class="mb-4" />

          <v-list dense>
            <v-list-item>
              <v-list-item-title>رقم الهاتف:</v-list-item-title>
              <v-list-item-subtitle>{{ user.mobile }}</v-list-item-subtitle>
            </v-list-item>

            <v-list-item>
              <v-list-item-title>المنطقة:</v-list-item-title>
              <v-list-item-subtitle>{{ user.area?.name || 'غير محددة' }}</v-list-item-subtitle>
            </v-list-item>

            <!-- أضف معلومات أخرى حسب الحاجة -->
          </v-list>

          <v-divider class="my-4" />
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from "@/axios"; // عدّل حسب مكان ملف axios عندك

export default {
  name: "UserDetails",
  data() {
    return {
      user: {},
      defaultAvatar: "/storage/uploads/profile.jpg",
    };
  },
  async created() {
    const userId = this.$route.params.id;
    try {
      const res = await axios.get(`/api/getUserInfo/${userId}`);
      this.user = res.data;
    } catch (err) {
      console.error("فشل تحميل معلومات المستخدم:", err);
    }
  },
};
</script>

<style scoped>
.image-wrapper {
  width: 100%;
  max-width: 300px;
  aspect-ratio: 3 / 2; /* نسبة العرض للطول */
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
  margin: 0 auto;
}

.user-img {
  width: 100%;
  height: 100%;
  object-fit: contain; /* صورة كاملة بدون قص */
  border-radius: 16px;
}

.v-list-item-title {
  font-weight: 500;
}

.v-list-item-subtitle {
  color: #555;
}
</style>
