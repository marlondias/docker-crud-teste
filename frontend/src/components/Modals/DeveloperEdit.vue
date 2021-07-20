<template>
  <base-modal 
    title="Edição de desenvolvedor" 
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
      nome: '',
      sexo: '',
      nascimento: '',
      idade: '',
      hobby: '',
    }
  },

  watch: {
    developer: {
      handler(newValue, oldValue) {
        console.log('x', newValue, oldValue);
        this.nome = newValue.nome;
        this.sexo = newValue.sexo;
        this.nascimento = newValue.data_nascimento;
        this.idade = newValue.idade;
        this.hobby = newValue.hobby;
      },
      deep: true,
    }
  },

  methods: {
    updateDeveloper() {
      //
    }
  }

}
</script>