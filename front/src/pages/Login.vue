<template>
  <div class="q-pa-md form-signin" style="max-width: 400px">

    <q-form
      @submit="onSubmit"
      class="q-gutter-md"
    >
      <div class="flex flex-center">
        <q-img src="~assets/icon.png" width="35%" />
      </div>
      <q-input
        filled
        v-model="email"
        label="Email *"
        lazy-rules
        :rules="[ val => val && val.length > 0 || 'Por favor entre su email']"
      />

      <q-input
        filled
        type="password"
        v-model="password"
        label="Contraseña *"
        lazy-rules
        :rules="[
          val => val && val.length > 0 || 'Por favor entre su contraseña'
        ]"
      />

      <div>
        <p>
          <router-link to="/register" style="color: #1D1D1D">¿No estas Registrado?</router-link>
        </p>
        <q-btn style="width: 100%; background-color: #21BA45" label="Acceder" type="submit"/>
      </div>
    </q-form>

  </div>
</template>

<script>
import axios from 'axios';
import * as env from '../../conf'
import {mapActions, mapGetters} from 'vuex'

export default {
  name: 'Login',
  data () {
    return {
      apiURl: env.API_URL,
      log: false,
      email: null,
      password: null,
    }
  },
  methods: {
    ...mapActions(['login',]),
    onSubmit () {
      this.login({email:this.email, pass:this.password}).then(response => {
        if (response === true){
              this.$q.notify({
                message: 'Login success',
                color: 'green'
              })
          this.gohome();
            } else{
              this.$q.notify({
                message: 'Bad User or Pass',
                color: 'red'
              })
            }
      }).catch(error => {
        console.log(error);  //todo tratar el error
      });
    },
    gohome(){
      this.$router.push('/');
    }
  },
}
</script>

<style scoped>
  .form-signin {
    padding-top: 100px;
    width: 100%;
    max-width: 330px;
    margin: auto;
  }

  .form-signin .checkbox {
    font-weight: 400;
  }

  .form-signin .form-floating:focus-within {
    z-index: 2;
  }
</style>
