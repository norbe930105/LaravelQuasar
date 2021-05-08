<template>
  <q-page>
    <div class="q-pa-md">
      <q-toolbar class="bg-green">
        <q-btn
          round
          flat
          color="white"
          icon="add"
          key="add"
          :disable="(selected.length===0)?false:true"
          @click.prevent="openModal(true, 'add')"
        >
          <q-tooltip>Crear Comida</q-tooltip>
        </q-btn>
        <q-btn
          round
          key="updated"
          flat
          color="white"
          icon="edit"
          @click.prevent="openModal(true,'update')"
          :disable="(selected.length!==0)?false:true"
        >
          <q-tooltip>Actualizar Comida</q-tooltip>
        </q-btn>
        <q-btn
          round
          key="delete"
          flat
          color="white"
          icon="delete"
          @click.prevent="openModalDelete(true, 'delete')"
          :disable="(selected.length!==0)?false:true"
        >
          <q-tooltip>Eliminar Comida</q-tooltip>
        </q-btn>
        <q-btn
          flat
          color="white"
          icon="check"
          @click.prevent="addorder()"
          :disable="(selected.length!==0)?false:true"
        >
          <q-tooltip>Adicionar a mi pedido</q-tooltip>
        </q-btn>
      </q-toolbar>
        <q-table
          title="Comidas"
          :data="data"
          :columns="columns"
          row-key="id"
          selection="multiple"
          :selected.sync="selected"
          no-data-label="No se encontraron elementos"
          :visible-columns="visibleColumns"
        >
          <template v-slot:body-cell-img="props">
            <q-td :props="props">
              <div>
                <img
                  :src="`${apiURl}getfile/${ props.row.id }`"
                  spinner-color="white"
                  style="height: 140px; max-width: 150px"
                />
              </div>
            </q-td>
          </template>
        </q-table>
      <q-dialog
        v-model="modalDialogDelete"
        persistent
        transition-show="scale"
        transition-hide="scale"
      >
        <q-card style="width: 280px">
          <q-card-section class="bg-green">
            <div class="text-h6 text-white">
              {{ 'Esta seguro que desea eliminar' }}
            </div>
          </q-card-section>
          <q-card-section class="q-pa-lg">

          </q-card-section>
          <q-separator inset/>
          <q-card-actions align="right">
            <q-btn type="button" :icon="'delete'"
                   @click="dele()" label="Eliminar"
                   color="secondary" flat />
            <q-btn type="button" icon="close" @click="closeModalDelete()" label="Cancelar" v-close-popup
                   color="secondary" flat/>
          </q-card-actions>
        </q-card>
      </q-dialog>
      <q-dialog
        v-model="modalDialog"
        persistent
        transition-show="scale"
        transition-hide="scale"
      >
        <q-card style="width: 500px">
          <q-card-section class="bg-green">
            <div class="text-h6 text-white">
              {{ this.action == 'add' ? 'Adicionar comida' : 'Actualizar comida' }}
            </div>
          </q-card-section>
          <q-card-section class="q-pa-lg">
            <div class="row">
              <div class="col" style="padding: 0px 20px 20px 0px">
                <q-uploader
                  :factory="factoryFn"
                  label="Imagen"
                  color="green"
                  text-color="black"
                  field-name="file"
                  style="max-width: 500px; width: 100%"
                />
              </div>
            </div>
            <div class="row">
              <div class="col" style="padding: 0px 20px 20px 0px">
                <q-input v-model="name" type="text" label="Nombre *" dense
                         name="name"
                         lazy-rules
                         :rules="[val => val && val.length > 0 || 'Este campo es obligatorio']"
                />
              </div>
            </div>
            <div class="row">
              <div class="col" style="padding: 0px 20px 20px 0px">
                <q-input v-model="description" type="text" label="Descripcion *" dense
                         name="description"
                         lazy-rules
                         :rules="[val => val && val.length > 0 || 'Este campo es obligatorio']"
                />
              </div>
            </div>
          </q-card-section>
          <q-separator inset/>
          <q-card-actions align="right">
            <q-btn type="button" :icon="action === 'add' ? 'save' : 'edit'"
                   @click="action != 'add' ? update() : save()" label="Guardar"
                   color="secondary" flat />
            <q-btn type="button" icon="close" @click="closeModal()" label="Cancelar" v-close-popup
                   color="secondary" flat/>
          </q-card-actions>
        </q-card>
      </q-dialog>
    </div>
  </q-page>
</template>

<script>
  import axios from 'axios';
  import * as env from 'app/conf';
  import {mapActions, mapGetters} from 'vuex';

export default {
  name: 'PageIndex',
  data () {
    return {
      apiURl: env.API_URL,
      selected: [],
      data: [],
      action: '',
      name: '',
      previewImage: '',
      image: '',
      file: {},
      description: '',
      id: '',
      modalDialog: false,
      modalDialogDelete: false,
      visibleColumns: ['img', 'name', 'description'],
      columns: [
        {
          name: 'id',
          label: 'Id',
          align: 'left',
          field: row => row.id,
          sortable: true
        },
        { name: 'img', label: 'Imagen', field: 'file'},
        { name: 'name', align: 'center', label: 'Nombre', field: 'name', sortable: true },
        { name: 'description', label: 'Descripcion', field: 'description'},
      ],
    }
  },
  computed: {
    ...mapGetters(['getToken','getuserId'])
  },
  methods: {
    openModal(data, action){
      this.action = action;
      if (action === 'add'){
        this.modalDialog = data;
      } else
        {
          this.id = this.selected[0].id;
          this.name = this.selected[0].name;
          this.description = this.selected[0].description;
          this.modalDialog = data;
        }
    },

    openModalDelete(data, action){
      this.id = this.selected[0].id;
      this.modalDialogDelete = data;
    },

    addorder(){
      this.id = this.selected[0].id;
      let token = this.getToken;
      let user_id = localStorage.getItem('id')
      axios.post(`${this.apiURl}addorder`,{id: this.id, user_id: user_id}, {
        headers: {
          'Authorization': `Bearer  ${token}`,
        }
      }).then(response => {
        if (response.status === 202){
          this.id = '';
          this.$q.notify({
            message: 'Comida adicionada a su pedido',
            color: 'green'
          })
        }
      }).catch(error => {
        this.$q.notify({
          message: 'No se pudo adicionar a su pedido',
          color: 'red'
        })
      });
    },

    update(){
      let token = this.getToken;
      axios.post(`${this.apiURl}updatefoods`,{id: this.id, name: this.name, description: this.description, file: this.file}, {
        headers: {
          'Authorization': `Bearer  ${token}`,
        }
      }).then(response => {
        if (response.status === 202){
          this.$q.notify({
            message: 'Comida Editada',
            color: 'green'
          })
          this.getfoods();
          this.closeModal();
        }
      }).catch(error => {
        this.$q.notify({
          message: 'No se pudo editar',
          color: 'red'
        })
      });
    },

    dele(){
      let token = this.getToken;
      axios.post(`${this.apiURl}deletefoods`,{id: this.id}, {
        headers: {
          'Authorization': `Bearer  ${token}`,
        }
      }).then(response => {
        if (response.status === 202){
          this.$q.notify({
            message: 'Comida Eliminada',
            color: 'green'
          })
          this.getfoods();
          this.closeModalDelete();
        }
      }).catch(error => {
        this.$q.notify({
          message: 'No se pudo eliminar',
          color: 'red'
        })
      });
    },

    factoryFn (file) {
      const fd = new FormData()
      fd.append('file', file[0])
      return new Promise((resolve, reject) =>{
        this.$axios.post(`${this.apiURl}upload`,
          fd,
          {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
          .then(res => {
            this.file = res.data;
            resolve(file)
            this.$q.notify({
              message: 'Imagen subida satisfactoriamente',
              color: 'green'
            })
          })
          .catch(err => reject(err))
      })
    },

    save(){
      let token = this.getToken;
      axios.post(`${this.apiURl}addfoods`,{name: this.name, description: this.description, file: this.file}, {
        headers: {
          'Authorization': `Bearer  ${token}`,
        }
      }).then(response => {
        if (response.status === 202){
          this.$q.notify({
            message: 'Comida adicionada',
            color: 'green'
          })
          this.getfoods();
          this.closeModal();
        }
      }).catch(error => {
        this.$q.notify({
          message: 'Error obteniendo datos',
          color: 'red'
        })
      });
    },

    closeModal(){
      this.modalDialog = false;
      this.selected = [];
      this.id = '';
      this.name = '';
      this.description = '';
    },
    closeModalDelete(){
      this.modalDialogDelete = false;
      this.selected = [];
      this.id = '';
    },

    getfoods(){
      let token = this.getToken;
      axios.get(`${this.apiURl}getfoods`, {
        headers: {
          'Authorization': `Bearer  ${token}`,
        }
      }).then(response => {
        if (response.status === 200){
         this.data = response.data;
        }
      }).catch(error => {
        this.$q.notify({
          message: 'Error obteniendo datos',
          color: 'red'
        })
      });
    }
  },
  created () {
    this.getfoods();
  }
}
</script>
