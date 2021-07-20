<template>
  <base-modal
    title="Detalhes desse desenvolvedor" 
    @close-modal="$emit('modal-closed')"
    @confirm-modal-action="$emit('modal-closed')"
  >
    <div>ID: <strong>{{this.developer.id}}</strong></div>
    <div>Nome: <strong>{{this.developer.nome}}</strong></div>
    <div>Sexo: <strong>{{this.developer.sexo_extenso}}</strong></div>
    <div>Nascimento: <strong>{{this.developer.data_nascimento_br}}</strong></div>
    <div>Idade: <strong>{{this.developer.idade}}</strong></div>
    <div>Hobby: <strong>{{this.developer.hobby}}</strong></div>
  </base-modal>
</template>

<script>
import BaseModal from './BaseModal.vue'

export default {
  components: {
    'base-modal': BaseModal,
  },

  emits: ['modal-closed'],

  props: {
    developer: {
      type: Object,
      required: true
    }
  },

}
</script>