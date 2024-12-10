<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
      <div class="flex justify-center mb-6">
        <img alt="Logo" class="h-12" src="https://storage.googleapis.com/a1aa/image/Tva8qsll56olBVqZjfvub3GKYpKRCw0fXtq2AepMsIwpq2fOB.jpg" />
      </div>
      <h2 class="text-2xl font-semibold text-center mb-2">Forgot Password</h2>
      <p class="text-center text-sm text-gray-600 mb-6">Enter your email to reset your password</p>
      <form @submit.prevent="handleForgotPassword">
        <div class="mb-4">
          <label for="email" class="block text-gray-700">Email address</label>
          <input v-model="email" type="email" class="w-full border border-gray-300 rounded-lg py-2 px-4" id="email" placeholder="Enter email" required>
        </div>
        <button type="submit" class="w-full bg-teal-600 text-white rounded-lg py-2 mb-4">Send Reset Link</button>
        <p v-if="message" class="text-green-500 mt-3">{{ message }}</p>
        <p v-if="error" class="text-red-500 mt-3">{{ error }}</p>
      </form>
      <p class="text-center text-gray-600 mb-4">
        Remembered your password? 
        <router-link to="/login" class="text-teal-600">Sign in</router-link>
      </p>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'ForgotPasswordPage',
  data() {
    return {
      email: '',
      message: '',
      error: ''
    }
  },
  methods: {
    ...mapActions(['forgotPassword']),
    async handleForgotPassword() {
      try {
        const response = await this.forgotPassword({ email: this.email })
        this.message = 'A reset link has been sent to your email.'
        this.error = ''
      } catch (error) {
        console.error(error)
        this.message = ''
        if (error.response && error.response.status === 422) {
          this.error = 'Validation error. Please check your input.'
        } else {
          this.error = 'Failed to send reset link. Please try again.'
        }
      }
    }
  }
}
</script>

<style scoped>
/* Add your styles here */
</style>