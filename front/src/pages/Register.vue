<template>
  <div class="q-pa-md form-signin" style="max-width: 400px">

    <q-form
      @submit="onSubmit"
      class="q-gutter-md"
    >
      <h1>Registrarse</h1>
      <q-input
        filled
        v-model="nombre"
        label="Nombre *"
        lazy-rules
        :rules="[ val => val && val.length > 0 || 'Por favor entre su nombre']"
      />
      <q-input
        filled
        v-model="apellidos"
        label="Apellidos *"
        lazy-rules
        :rules="[ val => val && val.length > 0 || 'Por favor entre su apellido']"
      />
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
        <q-btn style="width: 100%; background-color: #21BA45" label="Registrarse" type="submit"/>
      </div>
    </q-form>

  </div>
</template>

<script>
import axios from 'axios'
import * as env from '../../conf'

export default {
  name: 'Register',
  data () {
    return {
      apiURl: env.API_URL,
      nombre: '',
      apellidos: '',
      email: '',
      password: ''
    }
  },
  methods: {
    onSubmit () {
      axios.post(`${this.apiURl}register`, {name: this.nombre, last: this.apellidos, email: this.email, password: this.password }).then(response => {
        if (response.status === 202){
          this.$q.notify({
            message: 'Register success',
            color: 'green'
          })
          localStorage.setItem('id', response.data.userID);
          localStorage.setItem('token', response.data.token);
          setTimeout(this.gohome(), 6000);
        } else{
          this.$q.notify({
            message: 'Uppss something went wrong',
            color: 'red'
          })
        }
      })
    },
    gohome(){
      this.$router.push('/');
    }
  }
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
