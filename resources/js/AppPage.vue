<template>
 <SideBar></SideBar>
</template>

 

<script>
import SideBar from './components/SideBar.vue';
import LoginPage from './pages/LoginPage.vue';
  export default {
    data: () => ({
      loggedIn : null ,
    }),
    components:{
      SideBar,
      LoginPage
    },

    beforeCreate(){

      
      

      this.loggedIn = localStorage.getItem('user'); 
      console.log(this.loggedIn);


      }
  }
</script>