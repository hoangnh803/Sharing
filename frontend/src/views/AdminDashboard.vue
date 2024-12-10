<template>
  <div class="dashboard p-6">
    <h1 class="text-2xl font-semibold mb-6">Admin Dashboard</h1>

    <!-- Tổng quan -->
    <div class="stats flex gap-6 mb-12">
      <div class="stat-card p-6 border border-gray-200 rounded-lg shadow-md text-center flex-1">
        <h3 class="text-lg font-medium">Total Users</h3>
        <p class="text-xl font-bold text-blue-500">{{ stats.totalUsers }}</p>
      </div>
      <div class="stat-card p-6 border border-gray-200 rounded-lg shadow-md text-center flex-1">
        <h3 class="text-lg font-medium">Total Documents</h3>
        <p class="text-xl font-bold text-green-500">{{ stats.totalDocuments }}</p>
      </div>
      <div class="stat-card p-6 border border-gray-200 rounded-lg shadow-md text-center flex-1">
        <h3 class="text-lg font-medium">Total Downloads</h3>
        <p class="text-xl font-bold text-red-500">{{ stats.totalDownloads }}</p>
      </div>
    </div>

    <!-- Biểu đồ -->
    <div class="chart">
      <canvas id="combinedChart"></canvas>
    </div>
  </div>
</template>

<script>
import apiService from '../services/api';
import Chart from 'chart.js/auto';

export default {
  name: 'AdminDashboard',
  data() {
    return {
      stats: {
        totalUsers: 0,
        totalDocuments: 0,
        totalDownloads: 0,
      },
      chartData: {
        userRegistrations: [],
        documentsUploaded: [],
        downloads: [],
      },
    };
  },
  methods: {
    async fetchStats() {
      try {
        const response = await apiService.getStats();
        this.stats = response.data;
      } catch (error) {
        console.error('Error fetching stats:', error);
      }
    },
    async fetchChartData() {
      try {
        const response = await apiService.getChartData();
        this.chartData = response.data;
        this.renderCharts();
      } catch (error) {
        console.error('Error fetching chart data:', error);
      }
    },
    renderCharts() {
      const ctx = document.getElementById('combinedChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: this.chartData.userRegistrations.map(item => item.date),
          datasets: [
            {
              label: 'User Registrations',
              data: this.chartData.userRegistrations.map(item => item.count),
              borderColor: 'blue',
              fill: false,
              tension: 0.3,
            },
            {
              label: 'Documents Uploaded',
              data: this.chartData.documentsUploaded.map(item => item.count),
              borderColor: 'green',
              fill: false,
              tension: 0.3,
            },
            {
              label: 'Downloads',
              data: this.chartData.downloads.map(item => item.count),
              borderColor: 'red',
              fill: false,
              tension: 0.3,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            x: {
              title: {
                display: true,
                text: 'Date',
              },
            },
            y: {
              title: {
                display: true,
                text: 'Count',
              },
            },
          },
        },
      });
    },
  },
  mounted() {
    this.fetchStats();
    this.fetchChartData();
  },
};
</script>

<style scoped>
.dashboard {
  max-width: 1200px;
  margin: 0 auto;
}

.stat-card {
  padding: 1.5rem;
  border-radius: 0.5rem;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.chart {
  margin-top: 2rem;
  width: 100%;
  height: 400px;
}
</style>
