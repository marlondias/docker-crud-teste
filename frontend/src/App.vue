<template>
  <div class="main-container">
    <h1 class="app-title">Developers CRUD</h1>
    <List 
      ref="developerList"
      @api-feedback="triggerAlertApiFeedback" 
      @developer-create="triggerDeveloperCreate"
      @developer-show-info="triggerDeveloperShowInfo"
      @developer-edit="triggerDeveloperEdit"
      @developer-delete="triggerDeveloperDelete"
    />
  </div>

  <ModalsContainer 
    ref="modalsContainer" 
    @api-feedback="triggerAlertApiFeedback"
    @should-updated-developers="triggerUpdateDeveloperList" 
  />

  <div class="alerts-overlay">
    <AlertMessagesContainer ref="alertMessageContainer" />  
  </div>
</template>

<script>
import List from './components/DevelopersList/List.vue'
import ModalsContainer from './components/Modals/ModalsContainer.vue'
import AlertMessagesContainer from './components/Alert/AlertMessagesContainer.vue'

export default {
  name: 'App',

  components: {
    List, ModalsContainer, AlertMessagesContainer
  },

  methods: {
    triggerAlertApiFeedback(event) {
      this.$refs.alertMessageContainer.addAlertFromApiFeedBack(event.status, event.content);
    },
    triggerDeveloperCreate() {
      this.$refs.modalsContainer.showCreate();
    },
    triggerDeveloperShowInfo(event) {
      this.$refs.modalsContainer.showDetails(event.developerId);
    },
    triggerDeveloperEdit(event) {
      this.$refs.modalsContainer.showEdit(event.developerId);
    },
    triggerDeveloperDelete(event) {
      this.$refs.modalsContainer.showConfirmDelete(event.developerId);
    },
    triggerUpdateDeveloperList() {
      this.$refs.developerList.triggerListUpdate();
    }
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

  .form-input {
    margin-bottom: 13px;
  }
  .form-input label {
    display: block;
    font-weight: bold;
  }
  .form-input input,
  .form-input textarea,
  .form-input select {
    display: block;
    width: 100%;
    margin-top: 4px;
    font-size: 15px;
    padding: 5px 10px;
    border: 1px solid rgba(0, 0, 0, 0.3);
    background-color: transparent;
    color: rgba(0, 0, 0, 0.8);
  }
  .form-input input:focus,
  .form-input textarea:focus,
  .form-input select:focus {
    border-color: rgba(0, 0, 0, 0.8);
  }


</style>