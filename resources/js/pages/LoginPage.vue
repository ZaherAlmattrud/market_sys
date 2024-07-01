<template>
    <v-container>
      <v-row justify="center">
        <v-col cols="12" md="4">
          <v-card>
            <v-card-title>تسجيــــــــــــل الدخول</v-card-title>
            <v-card-text>
              <v-form @submit.prevent="login">
                <v-text-field
                  v-model="username"
                  label="أسم المستخدم"
                  required
                ></v-text-field>
                <v-text-field
                  v-model="password"
                  label="كلمة المرور"
                  type="password"
                  required
                ></v-text-field>
                <v-btn type="submit" color="primary">تسجيل الدخول</v-btn>
              </v-form>
            </v-card-text>
          </v-card>
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
      };
    },
    methods: {
      login() {
        if (this.username === 'user' && this.password === 'password') {
          localStorage.setItem('user', JSON.stringify({ username: this.username }));
          this.$router.push({ name: 'products' });
        } else {
          alert('Invalid credentials');
        }
      },
    },
  };
  </script>
  