<template>
    <div class="container mx-auto p-6">
        <button @click="goBack" class="bg-gray-500 text-white px-4 py-2 rounded mb-4">
            Back
        </button>
        <h1 class="text-3xl font-semibold mb-6">Subjects for Class: {{ className }}</h1>

        <!-- Tìm kiếm -->
        <div class="mb-4">
            <input v-model="searchTerm" type="text" placeholder="Search by subject name"
                class="p-2 border border-gray-300 rounded-md w-full max-w-lg" />
        </div>

        <!-- Thêm môn học -->
        <button @click="showCreateForm = true" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-6">
            Add New Subject
        </button>

        <!-- Danh sách môn học -->
        <table class="min-w-full bg-white border border-gray-200 shadow-md">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border text-left">ID</th>
                    <th class="px-4 py-2 border text-left">Name</th>
                    <th class="px-4 py-2 border text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="subject in filteredSubjects" :key="subject.id" class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ subject.id }}</td>
                    <td class="px-4 py-2 border">{{ subject.name }}</td>
                    <td class="px-4 py-2 border">
                        <button @click="removeSubject(subject.id)" class="text-red-500 hover:text-red-700">
                            Remove
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal thêm môn học -->
        <div v-if="showCreateForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-xl mb-4">Add Subject to Class</h2>
                <input v-model="newSubjectName" type="text" placeholder="Subject Name"
                        class="w-full border-gray-300 border-b focus:border-blue-500 focus:ring-0 px-0 pb-2" />
                <div class="flex mt-4 gap-4">
                    <button @click="addSubjectToClass" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
                    <button @click="showCreateForm = false" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import apiService from "../services/api";

export default {
    props: ["classId"], // Nhận classId từ router
    data() {
        return {
            className: "",
            searchTerm: "",
            subjects: [],
            filteredSubjects: [],
            showCreateForm: false,
        };
    },
    methods: {
        async fetchClassSubjects() {
            try {
                const response = await apiService.getClassSubjects(this.classId);
                this.subjects = response.data;
                this.filteredSubjects = this.subjects;
            } catch (error) {
                console.error("Error fetching class subjects:", error);
            }
        },
        async fetchClassName() {
            try {
                const response = await apiService.getClass(this.classId);
                console.log(response.data);
                this.className = response.data.name;
            } catch (error) {
                console.error("Error fetching class name:", error);
            }
        },
        async addSubjectToClass() {
            try {
                const payload = { name: this.newSubjectName };
                await apiService.addSubjectToClass(this.classId, payload);
                this.newSubjectName = "";
                this.showCreateForm = false;
                this.fetchClassSubjects();
            } catch (error) {
                console.error('Error adding subject:', error);
            }
        },
        async removeSubject(subjectId) {
            try {
                await apiService.removeSubjectFromClass(this.classId, subjectId);
                this.fetchClassSubjects(); // Refresh danh sách môn học
            } catch (error) {
                console.error("Error removing subject from class:", error);
            }
        },
        goBack() {
            this.$router.go(-1); // Quay lại trang trước đó
        },
    },
    watch: {
        searchTerm(val) {
            this.filteredSubjects = this.subjects.filter((subject) =>
                subject.name.toLowerCase().includes(val.toLowerCase())
            );
        },
    },
    mounted() {
        this.fetchClassName();
        this.fetchClassSubjects();
    },
};
</script>

<style scoped>
/* CSS tùy chỉnh nếu cần */
</style>
