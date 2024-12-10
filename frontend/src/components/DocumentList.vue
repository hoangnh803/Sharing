<template>
  <div class="document-list">
    <h2 class="mb-4">Recent Documents</h2>
    <div v-if="loading" class="d-flex justify-content-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <ul v-else-if="documents && documents.length" class="list-group">
      <li v-for="doc in documents" :key="doc.id" class="list-group-item">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">
            <router-link :to="{ name: 'DocumentDetail', params: { id: doc.id } }">
              {{ doc.title }}
            </router-link>
          </h5>
          <small>Uploaded by: {{ doc.user ? doc.user.name : 'Unknown' }}</small>
        </div>
        <p class="mb-1">{{ doc.description }}</p>
      </li>
    </ul>
    <p v-else class="alert alert-info">No documents available.</p>
  </div>
</template>
  
  <script>
  import { mapState, mapActions } from 'vuex'
  
  export default {
    name: 'DocumentList',
    computed: {
      ...mapState(['documents'])
    },
    methods: {
      ...mapActions(['fetchDocuments'])
    },
    created() {
      this.fetchDocuments()
    }
  }
  </script>