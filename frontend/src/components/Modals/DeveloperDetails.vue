<template>
  <base-modal title="Detalhes do desenvolvedor">
    detalhes
  </base-modal>
</template>

<script>
import BaseModal from './BaseModal.vue'

export default {
  components: {
    'base-modal': BaseModal,
  },

  props: {
    developer: {
      type: Object,
      required: true
    }
  },
}
</script>