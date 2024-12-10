import axios from 'axios'

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
    },
});
// Add a request interceptor to include the token in the headers
api.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});


export default {
    login(credentials) {
        return api.post('/login', credentials)
    },
    register(userData) {
        return api.post('/register', userData)
    },
    forgotPassword(email) {
        return api.post('/forgot-password', email)
    },
    logout() {
        return api.post('/logout')
    },
    getStats() {
        return api.get('/admin/stats');
    },
    getChartData() {
        return api.get('/admin/chart-data');
    },
    getUsers() {
        return api.get('/admin/users');
    },
    updateUser(userId, userData) {
        return api.put(`/admin/users/${userId}`, userData);  // Cập nhật trạng thái khóa/mở khóa
    },
    getUniversities() {
        return api.get('/admin/universities');
    },
    getUniversityId(universityId) {
        return api.get(`/admin/universities/${universityId}`);
    },
    createUniversity(data) {
        return api.post('/admin/universities', data);
    },
    deleteUniversity(id) {
        return api.delete(`/admin/universities/${id}`);
    },
    getCourses(universityId) {
        return api.get(`/admin/universities/${universityId}/courses`);
    },
    createCourse(universityId, data) {
        return api.post(`/admin/universities/${universityId}/courses`, data);
    },
    deleteCoursefromUniversity(universityId, subjectId) {
        return api.delete(`/admin/universities/${universityId}/courses/${subjectId}`);
    },
    // Lấy danh sách môn học của lớp
    getClassSubjects(classId) {
        return api.get(`/admin/classes/${classId}/subjects`);
    },
    getClass(classId) {
        return api.get(`/admin/classes/${classId}`);
    },
    // Lấy thông tin lớp
    getClasses() {
        return api.get(`/admin/classes`);
    },
    addClass(payload) {
        return api.post('/admin/classes', payload);
    },
    // Thêm môn học vào lớp
    addSubjectToClass(classId, payload) {
        return api.post(`/admin/classes/${classId}/subjects`, payload);
    },
    // Gỡ môn học khỏi lớp
    removeSubjectFromClass(classId, subjectId) {
        return api.delete(`/admin/classes/${classId}/subjects/${subjectId}`);
    },
    deleteClass(id) {
        return api.delete(`/admin/classes/${id}`);
    },
    getDocumentTypes() {
        return api.get('/document-types');
    },
    StoreDocument(data) {
        return api.post('/upload-documents', data);
    },
    UploadTempDocument(data, onUploadProgress) {
        return api.post('/upload-file', data, {
            headers: {
                'Content-Type': 'multipart/form-data', // Ensures the data is sent as multipart form data
            },
            onUploadProgress, // Callback tiến trình tải lên
        });
    },
    deleteFile(file_path) {
        return api.delete('/delete-file', {
            params: { file_path }, // Gửi file_path qua params trong URL
        });
    }
    
}
