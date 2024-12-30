<template>
    <div class="min-h-screen bg-white">
        <div class="mt-20 top-16 bg-white z-10">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-wrap gap-4 items-center p-4">
                    <SelectButton v-model="selectedOption" :options="options" />
                </div>

            </div>
        </div>
        <!-- Course Cards -->
        <div class="max-w-7xl mx-auto px-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <CourseCard 
                    v-for="(course, index) in courses" 
                    :key="index" 
                    :course-title="course.title" 
                    :course-link="course.link" 
                    :institution="course.institution" 
                    :institution-link="course.institutionLink" 
                />
            </div>
        </div>

        
        <!-- Bộ lọc -->
    <!-- Bộ lọc -->
    <div class="max-w-7xl mx-auto card flex flex-wrap p-4 items-end gap-4">
      <FloatLabel class="w-full md:w-[600px]">
            <MultiSelect id="document-type" display="chip" v-model="selectedDocumentTypes" :options="documentTypes" optionLabel="name" filter :maxSelectedLabels="3" class="w-full" />
            <label for="document-type">Loại tài liệu</label>
        </FloatLabel>
        <FloatLabel class="w-full md:w-[600px]">
            <MultiSelect id="institution" display='chip' v-model="selectedInstitutions" :options="filteredInstitutions" optionLabel="name" filter :maxSelectedLabels="3" class="w-full" />
            <label for="institution">Trường / Lớp</label>
        </FloatLabel>
    </div>


        <!-- Search Results -->
        <div class="max-w-7xl mx-auto py-4 px-4 mt-4">
            <h1 class="text-2xl font-semibold mb-4">Search Results for "{{ searchQuery }}"</h1>
            <div class="space-y-4">
              <DocumentCard
              v-for="doc in filteredDocuments"
              :key="doc.id"
              :document="doc"
              @click="goToDocumentDetail"  
            />
            </div>
            <div v-if="filteredDocuments.length === 0 && courses.length === 0" class="text-gray-500">
      Không tìm thấy kết quả nào.
    </div> 
        </div> 
          
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import { useRoute } from 'vue-router'

import DocumentCard from '../components/DocumentCard.vue';

import MultiSelect from 'primevue/multiselect'
import FloatLabel from 'primevue/floatlabel';
import SelectButton from 'primevue/selectbutton';
import CourseCard from '../components/CourseCard.vue'
import api from '../services/api'

const route = useRoute()
const searchQuery = ref(route.query.q || '')

const options = ref(['Đại học', 'Phổ thông']);
const selectedOption = ref(options.value[0]);



const subjects = ref([]);




const selectedFilters = ref({
    Category: [],
    Length: [],
    FileType: [],
    NewRelease: [],
    Language: []
})

const documents = ref([]);
const courses = ref([]);

const fetchDocuments = async () => {
  try {
    const response = await api.searchDocuments(searchQuery.value);
    documents.value = response.data.documents;
    subjects.value = response.data.subjects;
    updateCourses();


  } catch (error) {
    console.error('Error fetching documents:', error);
  }
};
const documentTypes = ref([]);
const universities = ref([]);
const classes = ref([]);

const fetchFilters = async () => {
  try {
    const [documentTypesResponse, universitiesResponse, classesResponse] = await Promise.all([
      api.getDocumentTypes(),
      api.getUniversities(),
      api.getClasses()
    ]);
    documentTypes.value = documentTypesResponse.data.document_types || [];
    universities.value = universitiesResponse.data;
    classes.value = classesResponse.data;
  } catch (error) {
    console.error('Error fetching filters:', error);
  }
};

const updateCourses = () => {
  if (selectedOption.value === 'Đại học') {
    courses.value = subjects.value.flatMap(subject =>
      subject.universities.map(university => ({
        title: subject.name,
        link: `/university/${university.id}/courses/${subject.id}`, // URL cho từng university
        institution: university.name, // Tên của university
        institutionLink: `/institutions/${university.id}`, // Link cho từng university
      }))
    );
  } else if (selectedOption.value === 'Phổ thông') {
    courses.value = subjects.value.flatMap(subject =>
      subject.classes.map(classItem => ({
        title: subject.name,
        link: `/class/${classItem.id}/courses/${subject.id}`, // URL cho từng class
        institution: classItem.name, // Tên của class
        institutionLink: `/institutions/${classItem.id}`, // Link cho từng class
      }))
    );
  }
};

const selectedDocumentTypes = ref([]);
const selectedInstitutions = ref([]);

const filteredDocuments = computed(() => {
  let filtered = documents.value;
  console.log(filtered);

  // Lọc theo options trước
  if (selectedOption.value === 'Đại học') {
    filtered = filtered.filter(doc => doc.university);
  } else if (selectedOption.value === 'Phổ thông') {
    filtered = filtered.filter(doc => doc.class);
  }

  // Lọc theo loại tài liệu
  if (selectedDocumentTypes.value.length > 0) {
    console.log(selectedDocumentTypes.value);
    filtered = filtered.filter(doc => 
    selectedDocumentTypes.value.some(inst => inst.id === doc.document_type.id)
    );  
  }

  // Lọc theo trường hoặc lớp
  if (selectedOption.value === 'Đại học' && selectedInstitutions.value.length > 0) {
    filtered = filtered.filter(doc => 
    selectedInstitutions.value.some(inst => inst.id === doc.university.id)
    );
  } else if (selectedOption.value === 'Phổ thông' && selectedInstitutions.value.length > 0) {
    filtered = filtered.filter(doc => 
    selectedInstitutions.value.some(inst => inst.id === doc.class.id)
    );
  }

  return filtered;
});

const filteredInstitutions = computed(() => {
  if (selectedOption.value === 'Đại học') {
    return universities.value;
  } else if (selectedOption.value === 'Phổ thông') {
    return classes.value;
  }
  return [];
});

onMounted(() => {
  searchQuery.value = route.query.q || ''

  fetchDocuments();
  fetchFilters();

});

const clearAll = () => {
    selectedFilters.value = {
        Category: [],
        Length: [],
        FileType: [],
        NewRelease: [],
        Language: []
    }
}

const handleSearchQuery = (query) => {
    searchQuery.value = query
    // Trigger search based on the updated query
}



watch(() => route.query.q, (newQuery) => {
  searchQuery.value = newQuery;
  fetchDocuments();
});
watch(selectedOption, () => {
  updateCourses();
});
</script>

<style scoped>
ul {
  position: relative  !important; 
}
</style>