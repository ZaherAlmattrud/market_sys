
import { createStore } from 'vuex'

// Create a new store instance.
const store = createStore({
    state() {
        return {
            count: 0,
            loggedIn: false,
            user: null,
        }
    },
    mutations: {
        increment(state) {
            state.count++
        },

        switchLoggedInStatus(state) {

            state.loggedIn = !state.loggedIn;
        }

    },

    actions: {
        logIn({ commit }, userData) {
            
            const response = axios.post('/api/logIn', userData); // 
            const res = response.data;
            commit('switchLoggedInStatus');
        },

        logOut({ commit }) {

            const response = axios.get('/api/logOut'); // 
            const res = response.data;
            commit('switchLoggedInStatus');
        }
    }
})

export default store


