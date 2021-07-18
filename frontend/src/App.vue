<template>
  <div class="main-container">
    <h1 class="app-title">Developers CRUD</h1>
    <List @apiFeedback="triggerAlertApiFeedback" />
  </div>
  <div class="alerts-overlay">
    <AlertMessagesContainer ref="alertMessageContainer" />  
  </div>
  <div class="modals-overlay">
    <span>xxx</span>
  </div>
</template>

<script>
import axios from 'axios'
import List from './components/DevelopersList/List.vue'
import AlertMessagesContainer from './components/Alert/AlertMessagesContainer.vue'

export default {
  name: 'App',

  components: {
    List, AlertMessagesContainer
  },

  data() {
    return {
      api: axios.create({
        baseURL: process.env.VUE_APP_API_BASE_URL,
        timeout: 10000,
      }),
    }
  },

  methods: {
    triggerAlertApiFeedback(event) {
      this.$refs.alertMessageContainer.addAlertFromApiFeedBack(event.status, event.content);
    },

    createDeveloper() {
      this.api.post(`/developers`);
    },

    getDeveloperInfo(id) {
      this.api.get(`/developers/${id}`);
    },

    updateDeveloper(id) {
      this.api.put(`/developers/${id}`);
    },

    deleteDeveloper(id) {
      this.api.delete(`/developers/${id}`);
    },

    openModalDeveloperInfo() {
      alert('Modal info');
    },

    confirmDeveloperDelete() {
      alert('Certeza delete?');
    },

  },

}

</script>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;600;700&display=swap');
  *{
    margin:0;
    padding:0;
    box-sizing:border-box;
  } 
  #app {
    font-family: 'Quicksand', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    min-height: 100vh;
    background-color: #def4f0;
    background-image: url('./assets/bg-dev-pattern.png');
    background-repeat: repeat;
    background-size: 200px;
    position: relative;
  }
  button {
    font-size: 15px;
    padding: 5px 10px;
    text-align: center;
    border: 1px solid rgba(0, 0, 0, 0.5);
    border-radius: 0;
    background-color: rgba(255,255,255,0.3);
    cursor: pointer;
  }
  button:hover,
  button:focus {
    border-color: rgba(0, 0, 0, 0.6);
    background-color: rgba(255,255,255,0.5);
  }
  select {
    font-size: 15px;
    padding: 4px;
    border: 1px solid rgba(0, 0, 0, 0.6);
    background-color: rgba(255,255,255,0.4);
    cursor: pointer;
  }
  select:hover,
  select:focus {
    background-color: rgba(255,255,255,0.6);
  }
  .main-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 0;
  }
  .app-title {
    margin-bottom: 35px;
    text-align: center;
  }
  ul {
    list-style-type: none;
  }
  .background-color-blend {
    background-color: rgb(222 244 241 / 80%);
  }
  .alerts-overlay {
    position: fixed;
    bottom: 20px;
    right: 15px;
    margin-left: auto;
    width: calc(100% - 30px);
    max-width: 600px;
    height: 100vh;
    display: flex;
    flex-flow: column nowrap;
    justify-content: flex-end;
    pointer-events: none;
  }
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
    padding-bottom: 50px;
    background-color: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
  }
</style>
