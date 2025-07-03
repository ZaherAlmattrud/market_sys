<template>
  <v-container fluid class="py-6">
    <v-row
      no-gutters
      class="outer-border mx-4"
      style="border: 2px solid #ccc;"
    >
      <!-- القسم الأيمن: تسجيل الدخول -->
      <v-col
        cols="12"
        md="6"
        class="d-flex flex-column align-center justify-center"
        style="border-left: 1px solid #ccc; padding: 40px 0;"
      >
        <v-card width="100%" max-width="400" class="pa-6" elevation="2">
          <v-card-title class="text-h6 justify-center">
         بسم الله الرحمن الرحيم
          </v-card-title>
          <v-card-text>
            <v-form @submit.prevent="login">
              <v-text-field
                v-model="username"
                label="أسم المستخدم"
                required
                variant="outlined"
                prepend-inner-icon="mdi-account"
              ></v-text-field>

              <v-text-field
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                label="كلمة المرور"
                required
                variant="outlined"
                prepend-inner-icon="mdi-lock"
                :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                @click:append-inner="togglePassword"
              ></v-text-field>

              <v-btn type="submit" color="#e6f0ff" block class="mt-4">
                تسجيل الدخول
              </v-btn>
            </v-form>

            <!-- شريط الأذكار المتحرك -->
            <div class="marquee-container mt-6">
              <div class="marquee-content">
                <span v-for="(zekr, index) in azkar" :key="index">
                  {{ zekr }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span>
              </div>
            </div>
          </v-card-text>
        </v-card>

         <!-- بطاقة معلومات المهندس -->
        <v-card width="100%" max-width="400" class="pa-4" elevation="1" outlined>
          <v-card-title class="justify-center text-h6"></v-card-title>
          <v-card-text class="text-center">
            <p><strong>زاهر محي الدين المطرود</strong></p>
              <br/>
            <p>مبرمج ومطور تطبيقات ويب وتطبيقات موبايل</p>

            <v-row justify="center" class="mt-3" dense>
              <v-col cols="auto">
                <v-btn
                  icon
                  href="https://www.linkedin.com/in/zaher-almattrud-0b097721b/"
                  target="_blank"
                  rel="noopener"
                  color="#0077b5"
                  aria-label="LinkedIn"
                >
                  <v-icon size="28">mdi-linkedin</v-icon>
                </v-btn>
              </v-col>

              <v-col cols="auto">
                <v-btn
                  icon
                  href="https://www.facebook.com/zaher.almattrud.2025"
                  target="_blank"
                  rel="noopener"
                  color="#1877f2"
                  aria-label="Facebook"
                >
                  <v-icon size="28">mdi-facebook</v-icon>
                </v-btn>
              </v-col>

              <v-col cols="auto">
                <v-btn
                  icon
                  href="https://wa.me/+963930826948"
                  target="_blank"
                  rel="noopener"
                  color="#25D366"
                  aria-label="WhatsApp"
                >
                  <v-icon size="28">mdi-whatsapp</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      

      </v-col>

      <!-- القسم الأيسر: صورة -->
      <v-col
        cols="12"
        md="6"
        style="border-right: 1px solid #ccc;padding:10px; background:#e6f0ff;"
      >
        <div
          style="
            height: 100%;
            
            min-height: 600px;
            background-image: url('/login2.png');
            background-size: 165% auto;
            background-position: center;
            
          "
        ></div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: '',
      showPassword: false,
      azkar: [

      
        "سبحان الله"
        ,
        "الحمد لله", 
        "لا إله إلا الله", 
        "الله أكبر",
          "اللهم صل وسلم وبارك على سيدنا محمد"
        
      ],
    };
  },
  methods: {
    login() {
      if (this.username === 'zaher' && this.password === '123qwe7891') {
        localStorage.setItem('user', JSON.stringify({ username: this.username }));
        this.$router.push({ name: 'products' });
      } else {
        alert('بيانات الدخول غير صحيحة');
      }
    },
    togglePassword() {
      this.showPassword = !this.showPassword;
    },
  },
};
</script>

<style scoped>
.outer-border {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  overflow: hidden;
}

/* شريط الأذكار المتحرك */
.marquee-container {
  overflow: hidden;
  white-space: nowrap;
  /* border-top: 1px solid #ccc; */
  padding-top: 12px;
  height: 30px;
  position: relative;
  /* background: #f9f9f9; */
  color: #555;
  font-weight: bold;
  font-size: 1.1rem;
}

.marquee-content {
  display: inline-block;
  padding-left: 100%;
  animation: marqueeAnim 40s linear infinite;
}

@keyframes marqueeAnim {
  0% {
    transform: translateX(0%);
  }
  100% {
    transform: translateX(-100%);
  }
}
</style>
