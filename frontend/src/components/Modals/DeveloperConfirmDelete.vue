<template>
  <base-modal title="Deletar este desenvolvedor ?">
    del {{developerId}} ?
  </base-modal>
</template>

<script>
import BaseModal from './BaseModal.vue'

export default {
  components: {
    'base-modal': BaseModal,
  },

  props: {
    developerId: {
      type: Number
    }
  },
}
</script>