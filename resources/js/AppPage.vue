<template>
  <v-app app>
    <SideBar app />

    <NavbarComponent app  />

    
    <v-main>
      <v-container fluid>
        <router-view />
      </v-container>
    </v-main>
    <FooterComponent app />
  </v-app>
   

</template>



<script>
import SideBar from './components/SideBar.vue';
import FooterComponent from './components/FooterComponent.vue';
import LoginPage from './pages/LoginPage.vue';
import NavbarComponent from './components/NavbarComponent.vue';

export default {
  data: () => ({
    loggedIn: false,
  }),
  components: {
    SideBar,
    LoginPage,
    FooterComponent,
    NavbarComponent
  },

  watch: {
    loggedIn(val) {
      console.log(val);
    }
  },

  beforeCreate() {

    const user = localStorage.getItem('user');

    if (user)
      this.loggedIn = true;

  }
}
</script>