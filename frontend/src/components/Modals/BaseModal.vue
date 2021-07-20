<template>
  <div class="modal">
    <div class="header">
      <h3>{{title}}</h3>
      <button class="close-button" @click.stop="$emit('close-modal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="body">
      <slot></slot>
    </div>
    <div class="confirmation">
      <button @click.stop="$emit('confirm-modal-action')">{{confirmationText || 'OK'}}</button>
    </div>
  </div>
</template>

<script>
export default {
  emits: ['close-modal', 'confirm-modal-action'],
  props: {
    title: {
      type: String,
      required: true
    },
    confirmationText: {
      type: String,
      required: false
    }
  },
}
</script>

<style scoped>
.modal {
  position: relative;
  width: 100%;
  max-width: 600px;
  padding: 0px;
  border-radius: 6px;
  background-color: white;
}
.close-button {
  position: absolute;
  top: -1.1em;
  right: 0.6em;
  line-height: 1.3;
  border-width: 2px;
  border-radius: 50%;
  border-color: rgba(255, 255, 255, 0.25);
  color: rgba(255, 255, 255, 0.7);
  background-color: red;
}
.header {
  padding: 8px 3em 8px 15px;
  border-bottom: 1px solid rgba(0,0,0,0.1);
  border-radius: 6px 6px 0 0;
  background-color: rgba(0,0,0,0.08);
}
.body {
  padding: 15px;
}
.confirmation {
  padding: 10px 15px;
  border-radius: 0 0 6px 6px;
  text-align: center;
}
.confirmation button {
  padding: 6px 15px;
  background-color: darkgreen;
  color: rgba(255, 255, 255, 0.8);
}
</style>