<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar style="background-color: #21BA45">
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="leftDrawerOpen = !leftDrawerOpen"
        />

        <q-toolbar-title>
          Manzana Verde
        </q-toolbar-title>

        <div>
          <q-btn-dropdown
            icon="record_voice_over"
          >
            <q-list>
              <q-item clickable v-close-popup @click="logout()">
                <q-item-section>
                  <q-item-label>Salir
                    <q-icon name="logout"/>
                  </q-item-label>

                </q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      content-class="bg-grey-1"
    >
      <q-list>
        <q-item-label
          header
          class="text-grey-8"
        >
          Menu
        </q-item-label>
        <EssentialLink
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import EssentialLink from 'components/EssentialLink.vue'
import * as env from 'app/conf'
import axios from 'axios'
import { mapGetters, mapMutations } from 'vuex'

const linksData = [
  {
    title: 'Home',
    caption: '',
    icon: 'home',
    link: '/'
  },
  {
    title: 'Pedidos',
    caption: '',
    icon: 'favorite',
    link: '/#/orders'
  }
]

export default {
  name: 'MainLayout',
  components: { EssentialLink },
  data () {
    return {
      apiURl: env.API_URL,
      leftDrawerOpen: false,
      essentialLinks: linksData
    }
  },
  computed: {
    ...mapMutations(['setToken', 'setUserId']),
    ...mapGetters(['getToken','getuserId'])
  },
  methods: {
    logout(){
      debugger;
      let token = this.getToken;
      let user_id = localStorage.getItem('id');
      axios.post(`${this.apiURl}logout`, JSON.stringify({Ident: user_id}), {
        headers: {
          'Authorization': `Bearer  ${token}`,
        }
      }).then(response => {
        if (response.status === 202){
          debugger;
          localStorage.clear();
          setTimeout(this.gologin(), 6000);
        } else{
          this.$q.notify({
            message: 'No se pudo desloguear el usuario',
            color: 'red'
          })
        }

      }).catch(error => {
        this.$q.notify({
          message: 'No se pudo desloguear el usuario',
          color: 'red'
        })
      });
    },

    gologin(){
      this.$router.push('/login');
    }
  }
}
</script>
