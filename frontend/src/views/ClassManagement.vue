<template>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold mb-6">Class Management</h1>

        <!-- Tìm kiếm -->
        <input v-model="searchTerm" type="text" placeholder="Search by name"
            class="mb-4 p-2 border rounded w-80" />

        <!-- Thêm lớp -->
        <button @click="showCreateForm = true" class="ml-4 bg-blue-500 text-white px-4 py-2 rounded-md">
            Add New Class
        </button>

        <!-- Danh sách lớp -->
        <table class="min-w-full bg-white border border-gray-200 shadow-md mt-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border text-left">ID</th>
                    <th class="px-4 py-2 border text-left">Name</th>
                    <th class="px-4 py-2 border text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="classItem in filteredClasses" :key="classItem.id" class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ classItem.id }}</td>
                    <td class="px-4 py-2 border">{{ classItem.name }}</td>
                    <td class="px-4 py-2 border">
                        <button @click="goToClassSubjects(classItem.id)" class="text-blue-500">Manage</button>
                        <button @click="deleteClass(classItem.id)" class="text-red-500 pl-4">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal tạo lớp -->
        <div v-if="showCreateForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg">
                <h2 class="text-xl mb-4">Add New Class</h2>
                <input v-model="newClassName" type="text" placeholder="Class Name"
                    class="w-full mb-4 p-2 border rounded" />
                <button @click="createClass" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
                <button @click="showCreateForm = false" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
            </div>
        </div>
    </div>
</template>

<script>
import apiService from "../services/api";

export default {
    data() {
        return {
            classes: [],
            filteredClasses: [],
            searchTerm: "",
            showCreateForm: false,
            newClassName: "",
        };
    },
    methods: {
        async fetchClasses() {
            try {
                const response = await apiService.getClasses();
                this.classes = response.data;
                this.filteredClasses = this.classes;
            } catch (error) {
                console.error("Error fetching classes:", error);
            }
        },
        async createClass() {
            try {
                await apiService.addClass({ name: this.newClassName });
                this.newClassName = "";
                this.showCreateForm = false;
                this.fetchClasses();
            } catch (error) {
                console.error("Error creating class:", error);
            }
        },
        async deleteClass(classId) {
            try {
                await apiService.deleteClass(classId);
                this.fetchClasses();
            } catch (error) {
                console.error("Error deleting class:", error);
            }
        },
        goToClassSubjects(classId) {
            this.$router.push({ name: "ClassSubjects", params: { classId } });
        },
    },
    watch: {
        searchTerm(val) {
            this.filteredClasses = this.classes.filter((classItem) =>
                classItem.name.toLowerCase().includes(val.toLowerCase())
            );
        },
    },
    mounted() {
        this.fetchClasses();
    },
};
</script>
