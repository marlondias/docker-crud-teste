<template>
  <base-modal 
    title="Edição de desenvolvedor"
    confirmation-text="Modificar"
    :disable-confirmation="isLoading"
    @close-modal="$emit('modal-closed')"
    @confirm-modal-action="updateDeveloper"
  >
    <div class="form-input">
      <label for="inputNome">Nome</label>
      <input type="text" id="inputNome" maxlength="100" v-model="nome">
    </div>
    <div class="form-input">
      <label for="inputSexo">Sexo</label>
      <select id="inputSexo" v-model="sexo">
        <option value="">Não informado</option>
        <option value="M">Masculino</option>
        <option value="F">Feminino</option>
      </select>
    </div>
    <div class="form-input">
      <label for="inputNasc">Data de nascimento</label>
      <input type="date" id="inputNasc" min="1900-01-01" v-model="nascimento">
    </div>
    <div class="form-input">
      <label for="inputHobby">Hobby</label>
      <textarea id="inputHobby" maxlength="500" rows="3" v-model="hobby"></textarea>
    </div>
  </base-modal>
</template>

<script>
import axios from 'axios'
import BaseModal from './BaseModal.vue'

export default {
  components: {
    'base-modal': BaseModal,
  },

  emits: ['modal-closed', 'should-updated-developers'],

  props: {
    developer: {
      type: Object,
      required: true
    }
  },
  
  data() {
    return {
      api: axios.create({
        baseURL: process.env.VUE_APP_API_BASE_URL,
        timeout: 10000,
      }),
      isLoading: false,
      nome: this.developer.nome || '',
      sexo:  this.developer.sexo || '',
      nascimento: this.developer.data_nascimento || '',
      hobby: this.developer.hobby || '',
    }
  },

  methods: {
    updateDeveloper() {
      let formData = {
        nome: this.nome,
        sexo: this.sexo,
        data_nascimento: this.nascimento,
        hobby: this.hobby,
      };
      this.isLoading = true;
      this.api.put(`/developers/${this.developer.id}`, formData)
      .then((response) => {
        this.$emit('api-feedback', {status: response.status, content: {message: 'Desenvolvedor modificado!'}});
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
  },

}
</script>