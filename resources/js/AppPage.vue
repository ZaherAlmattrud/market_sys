<template>
  <v-app app>
    <SideBar v-if="!isPrintMode && !isLoginPage"   class="navbar" app />

    <NavbarComponent  v-if="!isPrintMode && !isLoginPage " class="sidebar"   app  />

    
    <v-main  >
      <v-container   fluid>
        <router-view />
      </v-container>
    </v-main>
    <FooterComponent v-if="!isPrintMode && !isLoginPage" app />
  </v-app>

  <!-- <div id="app">
    <router-view></router-view>
  </div> -->
   

</template>



<script>
import SideBar from './components/SideBar.vue';
import FooterComponent from './components/FooterComponent.vue';
import LoginPage from './pages/LoginPage.vue';
import NavbarComponent from './components/NavbarComponent.vue';

export default {
  data: () => ({

    isPrintMode : false ,
    loggedIn: false,
    drawer: true,
  }),

  computed: {
    // التحقق مما إذا كانت الصفحة الحالية هي صفحة login
    isLoginPage() {
      return this.$route.path === '/';
    }
  },
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

  mounted() {

this.mediaQueryList = window.matchMedia('print');

this.mediaQueryList.addEventListener('change',this.updatePrintMode);
 


},

beforeUnmount() {
this.mediaQueryList.removeEventListener('change',this.updatePrintMode);
},

  methods: {

    updatePrintMode (event){

this.isPrintMode = event.matches ;
},


  
    toggleDrawer() {
      this.drawer = !this.drawer;
    }
  } ,

  beforeCreate() {

    const user = localStorage.getItem('user');

    if (user)
      this.loggedIn = true;

  }
}
</script>
<style scoped>

@media print{

  .navbar {
    /* display: none; */
    visibility: hidden;
    

  }
  .sidebar {
    /* display: none */
    visibility: hidden;
  }
 
}

.body{


background: orange;

}

</style>