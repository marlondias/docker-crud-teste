<template>
  <base-modal 
    title="Deletar este desenvolvedor ?" 
    confirmation-text="Deletar"
    :disable-confirmation="isLoading"
    @close-modal="$emit('modal-closed')"
    @confirm-modal-action="deleteDeveloper"
  >
    <div>ID: <strong>{{this.developer.id}}</strong></div>
    <div>Nome: <strong>{{this.developer.nome}}</strong></div>
    <div>Sexo: <strong>{{this.developer.sexo_extenso}}</strong></div>
    <div>Nascimento: <strong>{{this.developer.data_nascimento_br}}</strong></div>
    <div>Confirma a exclusão?</div>
  </base-modal>
</template>

<script>
import axios from 'axios'
import BaseModal from './BaseModal.vue'

export default {
  components: {
    'base-modal': BaseModal,
  },

  emits: ['modal-closed', 'api-feedback', 'should-updated-developers'],

  props: {
    developer: {
      type: Object,
      required: true
    },
  },

  data() {
    return {
      api: axios.create({
        baseURL: process.env.VUE_APP_API_BASE_URL,
        timeout: 10000,
      }),
      isLoading: false,
    }
  },

  methods: {
    deleteDeveloper() {
      this.isLoading = true;
      this.api.delete(`/developers/${this.developer.id}`)
      .then((response) => {
        this.$emit('api-feedback', {status: response.status, content: {message: 'Desenvolvedor deletado!'}});
        this.$emit('should-updated-developers');
        this.$emit('modal-closed');
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



  }

}
</script>