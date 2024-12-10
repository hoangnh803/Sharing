import { createStore } from 'vuex'
import api from '../services/api'
import Cookies from 'js-cookie'

export default createStore({
    state: {
        user: null,
        documents: [],
        notifications: [] // Thêm trạng thái thông báo
    },
    mutations: {
        setUser(state, user) {
            state.user = user
            Cookies.set('user', JSON.stringify(user), { expires: 1 }) // Lưu trữ user trong cookie
        },
        setDocuments(state, documents) {
            state.documents = documents
        },
        setNotifications(state, notifications) {
            state.notifications = notifications
        },
        logout(state) {
            state.user = null
            Cookies.remove('user')
            Cookies.remove('token')
        },
        initializeStore(state) {
            const user = Cookies.get('user')
            if (user) {
                state.user = JSON.parse(user)
            }
        }
    },
    actions: {
        async login({ commit }, credentials) {
            try {
                const response = await api.login(credentials)
                commit('setUser', response.data.user)
                Cookies.set('token', response.data.authorization.token, { expires: 1 }) // Lưu trữ token trong cookie
                return response
            } catch (error) {
                console.error('Login error:', error)
                throw error
            }
        },
        async logout({ commit }) {
            await api.logout()
            commit('logout')
        },
        async fetchDocuments({ commit }) {
            try {
                const response = await api.getDocuments()
                commit('setDocuments', response.data.data)
            } catch (error) {
                console.error('Fetch documents error:', error)
                throw error
            }
        },
        async fetchNotifications({ commit }) {
            try {
                const response = await api.getNotifications()
                commit('setNotifications', response.data.notifications)
            } catch (error) {
                console.error('Fetch notifications error:', error)
                throw error
            }
        },
        async downloadDocument({ commit }, id) {
            try {
                const response = await api.downloadDocument(id)
                const url = window.URL.createObjectURL(new Blob([response.data]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', 'document.pdf') // You might want to get the actual filename from the backend
                document.body.appendChild(link)
                link.click()
            } catch (error) {
                console.error('Download document error:', error)
                throw error
            }
        },
        async addComment({ commit }, { documentId, content }) {
            try {
                const response = await api.addComment(documentId, content)
                return response.data
            } catch (error) {
                console.error('Add comment error:', error)
                throw error
            }
        },
        async addRating({ commit }, { documentId, rating }) {
            try {
                const response = await api.addRating(documentId, rating)
                return response.data
            } catch (error) {
                console.error('Add rating error:', error)
                throw error
            }
        },
        async uploadDocument({ commit }, formData) {
            try {
                const response = await api.uploadDocument(formData)
                return response.data
            } catch (error) {
                console.error('Upload document error:', error)
                throw error
            }
        }
    },
    getters: {
        isLoggedIn: state => !!state.user,
        user: state => state.user,
        notifications: state => state.notifications,
        unreadNotificationsCount: state => state.notifications.filter(notification => !notification.read).length
    }
})