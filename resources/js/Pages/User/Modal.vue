<template>
    <div class="modal" @click.self="closeModal">
      <div class="modal-content">
        <!-- <span class="close" @click="closeModal">&times;</span> -->
        <button class="delete-button" @click="emitDeletePhone">Delete</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  
  const props = defineProps({
    modalContent: Object,
  });
  
  const emits = defineEmits(['close', 'deletePhone']);
  
  const closeModal = () => {
    emits('close');
  };
  
  const emitDeletePhone = () => {
    emits('deletePhone');
  };
  </script>
  
  <style scoped>
  /* CSS remains the same */
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    /* width: 114px; */
    /* height: 36px; */
    /* background-color: white; */
    display: flex;
    justify-content: left;;
    align-items: top;
  }
  /* .modal-content {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    animation: slideIn .3s ease-out;
  }
   */
  @keyframes slideIn {
    from {
      transform: translateY(-20px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  } 
  /* #ff4d4f */
  .delete-button {
    background-color: white;
    box-shadow: 0 4px 8px rgba(58, 46, 46, 0.7);
    color: black;
    border: none;
    padding: 2px 68px;
    font-size: 15px;
    cursor: pointer;
    border-radius: 4px;
    border-color: black;
    margin-top: 10px;
  }
  
  .delete-button:hover {
    background-color: rgb(245, 240, 240);
  }
  </style>
  