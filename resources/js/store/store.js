import { createStore } from 'vuex'

const store = createStore({
    state () {
      return {
        isAuthenticated: false
      }
    },
    mutations: {
      login (state) {
        state.isAuthenticated = true;
      },
      logout (state) {
        state.isAuthenticated = false;
      }
    }
  });
  export default store;