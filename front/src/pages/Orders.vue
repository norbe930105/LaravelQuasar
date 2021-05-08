<template>
  <div class="q-pa-md row items-start q-gutter-md">
    <h5 v-if="data.length === 0">No hay elementos</h5>
    <q-card v-for="item in data" :key="item.id" class="my-card">
      <q-img src="~assets/chicken-salad.jpg" />

      <q-card-section>
        <div class="row no-wrap items-center">
          <div class="col text-h6 ellipsis">
            Mi pedido {{item.id}}
          </div>
        </div>

        <q-rating v-model="stars" :max="5" size="32px" />
      </q-card-section>

      <q-card-section class="q-pt-none">
        <div class="text-subtitle1">
          <q-select
            filled
            v-model="addfoods"
            multiple
            label="Comidas"
            :options="foods"
            style="width: 200px"
            behavior="dialog"
          />
          <q-btn round color="green" icon="add" @click="addfoodsorder(item.id)"/>

        </div>
        <div class="text-caption text-grey">
          <q-item v-for="p in item.foods" :key="p.id" clickable>
            <q-item-section avatar>
              <q-icon color="green" name="spa" />
            </q-item-section>

            <q-item-section>
              <q-item-label>{{p.name}}</q-item-label>
              <q-item-label caption>{{p.description}}</q-item-label>
            </q-item-section>

            <q-item-section avatar @click="delet(item.id, p.id)">
              <q-icon color="red" name="delete" />
            </q-item-section>
          </q-item>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
  import axios from 'axios'
  import * as env from 'app/conf'
  import { mapGetters } from 'vuex'

  export default {
    name: 'Orders',
    data () {
      return {
        apiURl: env.API_URL,
        stars: 4,
        data: [],
        foods: [],
        addfoods: [],
      }
    },
    computed: {
      ...mapGetters(['getToken','getuserId'])
    },
    methods:{
      delet(order_id, food_id){
        let token = this.getToken;
        axios.post(`${this.apiURl}order_food_delete`, {order_id: order_id, foods_id: food_id},{
          headers: {
            'Authorization': `Bearer  ${token}`,
          }
        }).then(response => {
          if (response.status === 202){
            this.$q.notify({
              message: 'Eliminado satisfactoriamente',
              color: 'green'
            })
            this.getorders();
          }
        }).catch(error => {
          this.$q.notify({
            message: 'No fue posible eliminar',
            color: 'red'
          })
        });
      },
      getorders(){
        let token = this.getToken;
        let user_id = localStorage.getItem('id')
        axios.post(`${this.apiURl}showorder`, {user_id: user_id},{
          headers: {
            'Authorization': `Bearer  ${token}`,
          }
        }).then(response => {
          if (response.status === 200){
            this.data = response.data;
            this.getfoods()
          }
        }).catch(error => {
          this.$q.notify({
            message: 'Error obteniendo datos',
            color: 'red'
          })
        });
      },
      getfoods(){
        let token = this.getToken;
        axios.get(`${this.apiURl}getfoods`, {
          headers: {
            'Authorization': `Bearer  ${token}`,
          }
        }).then(response => {
          if (response.status === 200){
            let foods = response.data;
            foods.forEach(item => {
              this.foods.push(item.name);
            });
          }
        }).catch(error => {
          this.$q.notify({
            message: 'Error obteniendo datos',
            color: 'red'
          })
        });
      },
      addfoodsorder(order_id){
        let token = this.getToken;
        axios.post(`${this.apiURl}order_food_add`, {order_id: order_id, foods: this.addfoods},{
          headers: {
            'Authorization': `Bearer  ${token}`,
          }
        }).then(response => {
          if (response.status === 202){
            this.$q.notify({
              message: 'Comidas adicionadas satisfactoriamente',
              color: 'green'
            })
            this.getorders();
          }
        }).catch(error => {
          this.$q.notify({
            message: 'Error adicionando datos',
            color: 'red'
          })
        });
      }
    },
    created () {
      this.getorders();
    }
  }
</script>

<style lang="sass" scoped>
  .my-card
    width: 100%
    max-width: 250px
</style>
