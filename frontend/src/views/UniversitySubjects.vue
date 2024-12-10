<template>
    <div class="container mx-auto p-6">
        <button @click="goBack" class="bg-gray-500 text-white px-4 py-2 rounded mb-4">
            Back
        </button>
        <h1 class="text-3xl font-semibold mb-6">Subjects for University: {{ universityName }}</h1>

        <!-- Search input -->
        <div class="mb-4">
            <input v-model="searchTerm" type="text" placeholder="Search by subject name"
                class="p-2 border border-gray-300 rounded-md w-full max-w-lg" @input="filterSubjects" />
        </div>

        <!-- Add New Subject Button -->
        <button @click="showCreateForm = true" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-6">
            Add New Subject
        </button>

        <!-- Subject Table -->
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
        <!-- Modal để thêm môn học -->
        <div v-if="showCreateForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-md">
                <div class="p-6 pb-0">
                    <h2 class="text-xl font-semibold">Add New Subject</h2>
                </div>
                <div class="p-6">
                    <input v-model="newSubjectName" type="text" placeholder="Subject Name"
                        class="w-full border-gray-300 border-b focus:border-blue-500 focus:ring-0 px-0 pb-2" />
                    <div class="flex gap-4 mt-4">
                        <button @click="createSubject" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
                        <button @click="showCreateForm = false"
                            class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import apiService from '../services/api';

export default {
    props: ['universityId'], // Nhận tham số universityId từ URL
    data() {
        return {
            universityName: "",
            searchTerm: "",
            subjects: [],
            filteredSubjects: [],
            showCreateForm: false,
            newSubjectName: "",
        };
    },
    methods: {
        async fetchSubjects() {
            try {
                const response = await apiService.getCourses(this.universityId);
                this.subjects = response.data;
                this.filteredSubjects = this.subjects; // Đặt filteredSubjects ban đầu bằng subjects
            } catch (error) {
                console.error('Error fetching subjects:', error);
            }
        },
        async fetchUniversityName() {
            try {
                const response = await apiService.getUniversityId(this.universityId);
                this.universityName = response.data.name; // Lấy tên trường đại học
            } catch (error) {
                console.error('Error fetching university name:', error);
            }
        },
        goBack() {
            this.$router.go(-1); // Quay lại trang trước đó
        },
        async createSubject() {
            // Thêm môn học mới (ví dụ môn học với ID 1)
            try {
                const payload = { name: this.newSubjectName };
                await apiService.createCourse(this.universityId, payload);
                this.newSubjectName = "";
                this.showCreateForm = false;
                this.fetchSubjects();
            } catch (error) {
                console.error('Error adding subject:', error);
            }
        },
        async removeSubject(subjectId) {
            try {
                await apiService.deleteCoursefromUniversity(this.universityId, subjectId);
                this.fetchSubjects(); // Lấy lại danh sách môn học
            } catch (error) {
                console.error('Error removing subject:', error);
            }
        },
        filterSubjects() {
            const searchTermLowerCase = this.searchTerm.toLowerCase();
            this.filteredSubjects = this.subjects.filter(subject =>
                subject.name.toLowerCase().includes(searchTermLowerCase)
            );
        },
    },
    mounted() {
        this.fetchSubjects();
        this.fetchUniversityName();
    },
};
</script>

<style scoped>
/* Tùy chỉnh các lớp css tại đây nếu cần */
</style>