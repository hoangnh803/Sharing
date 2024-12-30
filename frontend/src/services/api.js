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
    logout(token) {
        return api.post('/logout', {}, {
            headers: {
                'Authorization': `Bearer ${token}`,
            },
        });
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
    deleteFile(filePath, previewPath) {
        return api.delete('/delete-file', {
            params: {
                file_path: filePath,
                preview_path: previewPath,
            }, // Gửi file_path qua params trong URL
        });
    },
    updateUserProfile(data) {
        return api.put('/user/profile', data)
    },
    getDocuments() {
        return api.get('/user/documents');
    },
    deleteDocument(id) {
        return api
            .delete(`/user/documents/${id}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}` // Assuming you're using token-based authentication
                }
            })
    },
    adminDeleteDocument(id) {
        return api.delete(`/admin/documents/${id}`);
    },
    approveDocument(id) {
        return api.post(`/admin/documents/${id}/approve`);
    },
    rejectDocument(id, data) {
        return api.post(`/documents/${id}/reject`, data);
    },
    getReportReasons() {
        return api.get('/report-reasons');
    },
    adminGetDocuments() {
        return api.get('/admin/documents');
    },
    guestGetDocument(id) {
        return api.get(`/documents/${id}`);
    },
    userGetDocument(id) {
        return api.get(`/user/documents/${id}`);
    },
    likeDocument(id) {
        return api.post(`/documents/${id}/like`);
    },
    dislikeDocument(id) {
        return api.post(`/documents/${id}/dislike`);
    },
    unlikeDocument(id) {
        return api.post(`/documents/${id}/unlike`);
    },
    undislikeDocument(id) {
        return api.post(`/documents/${id}/undislike`);
    },
    downloadDocument(id) {
        return api.get(`/documents/${id}/download`, { responseType: 'blob' });
    },

    getDocumentsByUniversity(universityId) {
        return api.get(`/documents/university/${universityId}`);
    },
    getDocumentsByClass(classId) {
        return api.get(`/documents/class/${classId}`);
    },
    getDocumentsByUniversityAndSubject(universityId, subjectId) {
        return api.get(`/documents/university/${universityId}/subject/${subjectId}`);
    },
    getDocumentsByClassAndSubject(classId, subjectId) {
        return api.get(`/documents/class/${classId}/subject/${subjectId}`);
    },
    reportDocument(documentId, data) {
        return api.post(`/documents/${documentId}/report`, data)
    },
    removeReport(documentId) {
        return api.post(`/documents/${documentId}/remove-report`)
    },
    saveDocument(documentId) {
        return api.post(`/documents/${documentId}/save`);
    },
    unsaveDocument(documentId) {
        return api.post(`/documents/${documentId}/unsave`);
    },
    getSavedDocuments() {
        return api.get('/saved-documents')
    },
    recordView(documentId) {
        return api.post(`/documents/${documentId}/view`);
    },
    getNotifications() {
        return api.get('/notifications')
    },
    markNotificationAsRead(notificationId) {
        return api.post(`/notifications/${notificationId}/read`)
    },
    searchDocuments(query) {
        return api.get(`/documents/search/${query}`);
    },
    getRecentDocuments(userId) {
        return api.post('/documents/recent', { user_id: userId });
    },
    getUserStatistics(userId) {
        return api.get(`user/${userId}/statistics`);
    },
}
