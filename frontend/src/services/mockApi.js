import mockData from '../assets/mockData.json'

export const fetchDocuments = () => {
  return new Promise((resolve) => {
    setTimeout(() => {
      resolve(mockData.documents)
    }, 500)
  })
}