<template>
    <nav class="navbar">
      <ul class="navbar-list">
        <li class="navbar-item" v-for="link in links" :key="link.name">
          <router-link :to="link.path" class="navbar-link" active-class="active">
            {{ link.name }}
          </router-link>
        </li>
      </ul>
    </nav>
  </template>
  
  <script lang="ts">
  import { defineComponent } from 'vue';
  
  export default defineComponent({
    name: 'Navbar',
    setup() {
      const links = [
        { path: '/', name: 'Home' },
        { path: '/dashboard', name: 'Dashboard' }
      ];
  
      return {
        links
      };
    }
  });
  </script>
  
  <style scoped>
  .navbar {
    display: flex;
    justify-content: center;
    background-color: #e0f2f1; /* Cinza claro com tom esverdeado */
    padding: 10px;
    border-bottom: 2px solid #4caf50; /* Verde mais forte para destaque */
  }
  
  .navbar-list {
    list-style: none;
    display: flex;
    gap: 20px;
    margin: 0;
    padding: 0;
  }
  
  .navbar-item {
    font-size: 16px;
  }
  
  .navbar-link {
    color: #388e3c; /* Tom de verde escuro */
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background-color 0.3s, color 0.3s;
  }
  
  .navbar-link:hover {
    background-color: #81c784; /* Tom de verde claro */
    color: white; /* Texto branco no hover */
  }
  
  .navbar-link.active {
    background-color: #4caf50; /* Verde médio para link ativo */
    color: white; /* Texto branco no link ativo */
  }
  </style>
  