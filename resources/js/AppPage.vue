<template>
  <SideBar></SideBar>
  
</template>



<script>
import SideBar from './components/SideBar.vue';
import LoginPage from './pages/LoginPage.vue';
export default {
  data: () => ({
    loggedIn: false,
  }),
  components: {
    SideBar,
    LoginPage
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