<template>
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 bg-dark min-vh-100 p-3">
          <Sidebar />
        </div>
        
        <!-- Main Content -->
        <div class="col-md-10">
          <Navbar />
          <div class="container-fluid mt-4">
            <router-view></router-view>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Sidebar from '../layout/SideBar.vue'
  import Navbar from '../layout/Navbar.vue'
  
  export default {
    components: {
      Sidebar,
      Navbar
    }
  }
  </script>
  
  <style>
  @import 'bootstrap/dist/css/bootstrap.css';
  @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css');
  
  .sidebar-link {
    color: white;
    text-decoration: none;
    display: block;
    padding: 10px;
  }
  .sidebar-link:hover {
    background: #4e5459;
  }
  </style>