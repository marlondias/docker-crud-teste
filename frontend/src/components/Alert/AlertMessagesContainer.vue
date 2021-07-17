<template>
  <ul class="alert-list">
    <li v-for="(alert, index) in alerts" :key="index">
        <AlertMessage 
          :id="index" 
          :message="alert.message" 
          :color="alert.color" 
          @dismissed="handleDismissedAlert" />
    </li>
  </ul>
</template>

<script>
import AlertMessage from './AlertMessage.vue'

export default {
  components: {
    AlertMessage
  },

  data() {
    return {
      'alerts': [
        {
          'message': 'Um alerta 0',
        },
        {
          'message': 'Um alerta 1',
          'color': 'warning',
        },
        {
          'message': 'Um alerta 2',
          'color': 'danger',
        },
        {
          'message': 'Um alerta 3',
          'color': 'success',
        },
      ],
    }
  },

  methods: {
    handleDismissedAlert(event)
    {
      this.alerts.splice(event.id, 1);
    }
  },

}
</script>

<style>
ul {
  list-style-type: none;
}
</style>