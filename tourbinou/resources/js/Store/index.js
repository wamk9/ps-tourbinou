import VueCookies from 'vue-cookies'
import { createStore } from 'vuex';

const Auth =  {
    state: {
      token: null,
    },
    mutations: {
      SET_TOKEN(state, payload) {
        if (payload === null)
          VueCookies.remove('token')
        else
          VueCookies.set('token', payload);

        state.token = payload;
      }
    },
    actions: {
      setToken(context, payload) {
        context.commit('SET_TOKEN', payload)
      },
      removeToken(context) {
        context.commit('SET_TOKEN', null)
      }
    },
    getters: {
      getToken: state => state.token || VueCookies.get('token'),
    },
  };

const Store = createStore({
    modules: {
    Auth : Auth
  }
})

export default Store;
