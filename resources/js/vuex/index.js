import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'

const store = createStore({
  state() {
    return {
      drawer: true,
      user: null,
      token: null,
      roles: [],
      permissions: [],
      avatar: "/storage/uploads/user.jpg"
    }
  },

  mutations: {
    toggleDrawer(state) {
      state.drawer = !state.drawer
    },

    SET_AUTH(state, payload) {
      state.user = payload.user
      state.token = payload.token
      state.roles = payload.user.roles || []
      state.permissions = payload.user.permissions || []
    },

    LOGOUT(state) {
      state.user = null
      state.token = null
      state.roles = []
      state.permissions = []
    }
  },

  actions: {
    toggleDrawer({ commit }) {
      commit('toggleDrawer')
    },

    setAuth({ commit }, payload) {
      commit('SET_AUTH', payload)
    },

    logout({ commit }) {
      commit('LOGOUT')
    }
  },

  getters: {
    drawerVisible: state => state.drawer,
    isAuthenticated: state => !!state.token,
    hasRole: (state) => (role) => state.roles.some(r => r.name === role),
    hasPermission: (state) => (perm) => state.permissions.includes(perm),
  },
  plugins: [createPersistedState()]
})

  

export default store
