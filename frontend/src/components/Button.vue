<template>
    <button
      :class="['custom-button', buttonType, { 'disabled': isDisabled }]"
      :disabled="isDisabled"
      @click="handleClick"
    >
      <slot></slot>
    </button>
  </template>
  
  <script lang ="ts">
  export default {
    name: 'CustomButton',
    props: {
      buttonType: {
        type: String,
        default: 'primary', 
      },
      isDisabled: {
        type: Boolean,
        default: false,
      },
      onClick: {
        type: Function,
        default: null,
      },
    },
    methods: {
      handleClick() {
        if (this.onClick && !this.isDisabled) {
          this.onClick();
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .custom-button {
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    border: none;
    margin:5px;
  }
  
  .custom-button.primary {
    background-color: #28a745; 
    color: white;
  }
  
  .custom-button.secondary {
    background-color: #6c757d; 
    color: white;
  }
  
  .custom-button.disabled {
    background-color: #d6d6d6;
    cursor: not-allowed;
  }
  
  .custom-button:hover:not(.disabled) {
    background-color: #218838; 
    transform: scale(1.05);
  }
  
  .custom-button.secondary:hover:not(.disabled) {
    background-color: #5a6268; 
  }
  
  .custom-button:focus {
    outline: none;
  }
  </style>
  