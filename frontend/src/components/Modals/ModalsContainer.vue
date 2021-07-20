<template>
  <div class="modals-overlay" v-if="isAnyModalVisible || isLoading">
    <div v-show="isLoading" class="loading">
      <img src="../../assets/loading.gif">
    </div>

    <ModalCreate 
      v-if="showModalCreate && !isLoading" 
      @modal-closed="resetModals" 
      @api-feedback="$emit('api-feedback', $event)"
      @should-updated-developers="$emit('should-updated-developers', $event)" 
    />

    <ModalDetails 
      v-if="showModalDetails && !isLoading" 
      :developer="developer" 
      @modal-closed="resetModals" 
      @api-feedback="$emit('api-feedback', $event)"
    />

    <ModalEdit 
      v-if="showModalEdit && !isLoading" 
      :developer="developer" 
      @modal-closed="resetModals" 
      @api-feedback="$emit('api-feedback', $event)"
      @should-updated-developers="$emit('should-updated-developers', $event)" 
    />

    <ModalConfirmDelete 
      v-if="showModalConfirmDelete && !isLoading" 
      :developer="developer" 
      @modal-closed="resetModals" 
      @api-feedback="$emit('api-feedback', $event)"
      @should-updated-developers="$emit('should-updated-developers', $event)" 
    />

  </div>
</template>

<script>
import axios from 'axios'
import ModalCreate from './DeveloperCreate.vue'
import ModalDetails from './DeveloperDetails.vue'
import ModalEdit from './DeveloperEdit.vue'
import ModalConfirmDelete from './DeveloperConfirmDelete.vue'

export default {
  components: {
    ModalCreate, ModalDetails, ModalEdit, ModalConfirmDelete
  },

  emits: ['api-feedback', 'should-updated-developers'],

  data() {
    return {
      api: axios.create({
        baseURL: process.env.VUE_APP_API_BASE_URL,
        timeout: 10000,
      }),
      developer: null,
      showModalCreate: false,
      showModalDetails: false,
      showModalEdit: false,
      showModalConfirmDelete: false,
      isLoading: false,
    }
  },

  computed: {
    isAnyModalVisible() {
      return this.showModalCreate 
      || this.showModalDetails 
      || this.showModalEdit 
      || this.showModalConfirmDelete;
    }
  },

  methods: {
    resetModals() {
      this.showModalCreate = false;
      this.showModalDetails = false;
      this.showModalEdit = false;
      this.showModalConfirmDelete = false;
      this.developer = null;
    },
    getDeveloperInfo(id) {
      this.isLoading = true;
      this.api.get(`/developers/${id}`)
      .then((response) => {
        this.developer = response.data.data;
      })
      .catch((error) => {
        let statusCode = 500;
        let errorContent = null;
        if (error.response) {
          statusCode = error.response.status;
          errorContent = (error.response.data) ? error.response.data.error : null;
        }
        this.$emit('api-feedback', {status: statusCode, content: errorContent});
      })
      .then(() => {
        this.isLoading = false;
      });
    },
    showCreate() {
      this.resetModals();
      this.showModalCreate = true;
    },
    showDetails(id) {
      this.resetModals();
      this.getDeveloperInfo(id);
      this.showModalDetails = true;
    },
    showEdit(id) {
      this.resetModals();
      this.getDeveloperInfo(id);
      this.showModalEdit = true;
    },
    showConfirmDelete(id) {
      this.resetModals();
      this.getDeveloperInfo(id);
      this.showModalConfirmDelete = true;
    },
  }
}
</script>

<style scoped>
  .modals-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    display: flex;
    flex-flow: column nowrap;
    justify-content: center;
    align-items: center;
    padding: 0 20px 50px 20px;
    background-color: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
  }
  .loading img {
    width: 200px;
    height: auto;
  }

</style>