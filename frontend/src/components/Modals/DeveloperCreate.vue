<template>
  <base-modal 
    title="Novo desenvolvedor" 
    confirmation-text="Salvar"
    @close-modal="$emit('modal-closed')"
    @confirm-modal-action="createDeveloper"
  >
    <div class="form-input">
      <label for="inputNome">Nome</label>
      <input type="text" id="inputNome" maxlength="100" v-model="nome">
    </div>
    <div class="form-input">
      <label for="inputSexo">Sexo</label>
      <select id="inputSexo" v-model="sexo">
        <option value="">Não informado</option>
        <option value="m">Masculino</option>
        <option value="f">Feminino</option>
      </select>
    </div>
    <div class="form-input">
      <label for="inputNasc">Data de nascimento</label>
      <input type="date" id="inputNasc" min="1900-01-01" v-model="nascimento">
    </div>
    <div class="form-input">
      <label for="inputIdade">Idade</label>
      <input type="number" id="inputIdade" min="1" max="200" v-model="idade">
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

  emits: ['api-feedback', 'modal-closed', 'should-updated-developers'],

  data() {
    return {
      api: axios.create({
        baseURL: process.env.VUE_APP_API_BASE_URL,
        timeout: 10000,
      }),
      isLoading: false,
      nome: '',
      sexo: '',
      nascimento: '',
      idade: '',
      hobby: '',
    }
  },

  methods: {
    createDeveloper() {
      let formData = {
        nome: this.nome,
        sexo: this.sexo,
        data_nascimento: this.nascimento,
        idade: this.idade,
        hobby: this.hobby,
      }
      this.isLoading = true;
      this.api.post('/developers', formData)
      .then((response) => {
        this.$emit('api-feedback', {status: response.status, content: {message: 'Desenvolvedor criado!'}});
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
    }
  },

}
</script>