import axios from 'axios';
import * as env from 'app/conf';
const apiUrl = env.API_URL;
const state = {
  userId: localStorage.getItem('userId'),
  token: localStorage.getItem('token'),
};

const getters = {
  getToken (state) {
    return state.token;
  },
  getuserId (state) {
    return state.userId;
  },
};

const actions = {
  login ({ commit }, { email, pass }) {
    return new Promise((resolve, reject) => {
      axios.post(`${apiUrl}login`, {email: email, password: pass }).then(response => {
        if (response.status === 202){
              localStorage.setItem('token', response.data.token);
              localStorage.setItem('id', response.data.userID);

              commit('setUserId', response.data.userID);
              commit('setToken', response.data.token);
              resolve(true);
            }
      }).catch(error => {
        reject(error);
      })
    });
  },
};

const mutations = {
  setUserId (state, id) {
    state.userId = id;
  },
  setToken (state, token) {
    state.token = token;
  },
};

export default {
  state,
  getters,
  actions,
  mutations
}
