
import { createStore } from 'vuex'

// Create a new store instance.
const store = createStore({
    state() {
        return {
            drawer: true
        }
    },
    mutations: {
     
        toggleDrawer(state) {
                  state.drawer = !state.drawer;
                }
    },

    actions: {

        toggleDrawer({ commit }) {
                  commit('toggleDrawer');
                }
  
    }

    ,
  getters: {
    drawerVisible: state => state.drawer
  }
})

export default store

 



