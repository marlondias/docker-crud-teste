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
      alerts: [],
    }
  },

  methods: {
    handleDismissedAlert(event)
    {
      this.alerts.splice(event.id, 1);
    },
    addAlertFromApiFeedBack(status, content) {
      let color = 'success';
      if (status >= 400) { color = 'danger'; } 
      else if (status >= 300) { color = 'warning'; }
      let message = (content.message && content.message.length > 0) 
        ? content.message : 'Algo inesperado ocorreu.';
      this.alerts.push({color: color, message: message});
    }
  },

}
</script>

<style>
ul > li {
  pointer-events: all;
}
</style>