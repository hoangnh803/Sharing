<template>
  <div>
    <h1>Create Admin</h1>
    <form @submit.prevent="createAdmin">
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" v-model="name" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" v-model="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" v-model="password" required>
      </div>
      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation" v-model="password_confirmation" required>
      </div>
      <button type="submit" class="btn btn-primary">Create Admin</button>
      <p v-if="error" class="text-danger">{{ error }}</p>
    </form>
  </div>
</template>

<script>
import api from '../services/api';

export default {
  name: 'CreateAdmin',
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      error: ''
    };
  },
  methods: {
    async createAdmin() {
      try {
        const response = await api.createAdmin({
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        });
        console.log('Admin created successfully:', response);
        // Redirect or update UI after successful creation
      } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
          this.error = Object.values(error.response.data.errors).join(' ');
        } else {
          this.error = 'Creation failed. Please check your details and try again.';
        }
      }
    }
  }
};
</script>

<style scoped>
/* Thêm các kiểu CSS nếu cần */
</style>