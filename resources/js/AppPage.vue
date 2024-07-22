<template>
 

    

    <SideBar></SideBar>
    
    <!-- Navigation bar ends -->
 

 



  <!-- 
 
  <FooterComponent></FooterComponent> -->

</template>



<script>
import SideBar from './components/SideBar.vue';
import FooterComponent from './components/FooterComponent.vue';
import LoginPage from './pages/LoginPage.vue';
export default {
  data: () => ({
    loggedIn: false,
  }),
  components: {
    SideBar,
    LoginPage,
    FooterComponent
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