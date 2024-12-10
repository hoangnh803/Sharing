<template>
    <div class="p-6">
        <h1 class="text-2xl font-semibold mb-6">University Management</h1>

        <!-- Tìm kiếm trường đại học -->
        <input v-model="searchTerm" type="text" placeholder="Search by name" @input="filterUniversities"
            class="mb-4 p-2 border rounded w-80" />
        <!-- Thêm trường đại học mới -->
        <button @click="showCreateForm = true" class="mt-4 p-2 bg-blue-500 text-white rounded ml-4">Add New
            University</button>

        <!-- Danh sách trường đại học -->
        <table class="min-w-full border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border text-left">ID</th>
                    <th class="px-4 py-2 border text-left">Name</th>
                    <th class="px-4 py-2 border text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="university in filteredUniversities" :key="university.id" class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ university.id }}</td>
                    <td class="px-4 py-2 border">{{ university.name }}</td>
                    <td class="px-4 py-2 border">
                        <button @click="goToUniversity(university.id)" class="text-blue-500">Manage</button>
                        <button @click="deleteUniversity(university.id)" class="pl-4 text-red-500">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal để tạo trường đại học -->
        <div v-if="showCreateForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-md">
                <div class="p-6 pb-0">
                    <h2 class="text-xl font-semibold">
                        Create New University
                    </h2>
                </div>
                <div class="p-6">
                    <input v-model="newUniversityName" type="text" placeholder="University Name"
                        class="w-full border-gray-300 border-b focus:border-green-500 focus:ring-0 px-0 pb-2" />
                    <div class="flex gap-4 mt-4">
                        <button @click="createUniversity" class="bg-green-500 text-white p-2 rounded">Create</button>
                        <button @click="showCreateForm = false"
                            class="bg-gray-500 text-white p-2 rounded">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import apiService from '../services/api';

export default {
    data() {
        return {
            searchTerm: '',
            filteredUniversities: [],
            universities: [],
            showCreateForm: false,
            newUniversityName: '',
        };
    },
    methods: {
        async fetchUniversities() {
            try {
                const response = await apiService.getUniversities();
                this.universities = response.data;
                this.filteredUniversities = this.universities;
            } catch (error) {
                console.error('Error fetching universities:', error);
            }
        },
        filterUniversities() {
            const searchTermLowerCase = this.searchTerm.toLowerCase();
            this.filteredUniversities = this.universities.filter(university =>
                university.name.toLowerCase().includes(searchTermLowerCase)
            );
        },
        async deleteUniversity(id) {
            try {
                await apiService.deleteUniversity(id);
                this.fetchUniversities();
            } catch (error) {
                console.error('Error deleting university:', error);
            }
        },
        async createUniversity() {
            try {
                await apiService.createUniversity({ name: this.newUniversityName });
                this.showCreateForm = false;
                this.fetchUniversities();
            } catch (error) {
                console.error('Error creating university:', error);
            }
        },
        goToUniversity(id) {
            this.$router.push({ name: 'UniversitySubjects', params: { universityId: id } });
        },
    },
    mounted() {
        this.fetchUniversities();
    },
};
</script>

<style scoped>
/* Styles cho modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
}
</style>