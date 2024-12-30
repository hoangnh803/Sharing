<template>
  <div class="min-h-screen bg-white flex flex-col items-center pt-12">
    <!-- Logo -->
    <router-link to="/" class="flex-shrink-0 flex items-center mb-2">
      <BookOpen class="h-8 w-8 text-black" />
      <span class="ml-2 text-xl font-bold text-black">EduShare</span>
    </router-link>

    <!-- Progress Steps -->
    <div class="flex flex-col w-full max-w-3xl mb-12 mt-2">
      <div class="flex justify-around mb-2 px-4">
        <div class="flex items-center gap-1">
          <div class="w-5 h-5 rounded-full bg-gray-200 text-gray-400 text-xs flex items-center justify-center">
            1
          </div>
          <span class="text-gray-400">Tải lên</span>
        </div>
        <div class="flex items-center gap-1">
          <div class="w-5 h-5 rounded-full bg-blue-500 text-white text-xs flex items-center justify-center">
            2
          </div>
          <span class="text-blue-500 font-medium">Chi tiết</span>
        </div>
        <div class="flex items-center gap-1">
          <div class="w-5 h-5 rounded-full bg-gray-200 text-gray-400 text-xs flex items-center justify-center">
            3
          </div>
          <span class="text-gray-400">Hoàn tất</span>
        </div>
      </div>
      <div class="flex items-center gap-1">
        <div class="h-2 flex-1 rounded-full bg-blue-500"></div>
        <div class="h-2 flex-1 rounded-full bg-blue-500"></div>
        <div class="h-2 flex-1 rounded-full bg-gray-200"></div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="w-full max-w-5xl border p-4 rounded-lg">
      <!-- File Card -->
      <div class="border-b  p-4 mb-8 flex items-center justify-between" v-if="file">
        <div class="flex items-center gap-3">
          <FileText class="w-6 h-6 text-blue-500" />
          <div>
            <div class="font-medium">{{ file.name }}</div>
            <div class="text-sm text-gray-500">{{ file.size}} </div>
          </div>
        </div>
        <div class="flex items-center gap-4">
          <button @click="removeFile" class="text-gray-400 hover:text-gray-600">
            <Trash2 class="w-5 h-5" />
          </button>
        </div>
      </div>

      <!-- Search Forms -->
      <!-- Main Content -->
      <div class="w-full max-w-5xl border p-4 rounded-lg">
        <!-- Dropdown chọn loại -->
        <div class="relative mb-4">
          <select v-model="selectedType" class="w-full border rounded-lg p-2" @change="fetchData">
            <option value="university">Đại học</option>
            <option value="class">Phổ thông</option>
          </select>
        </div>

        <!-- Input tìm kiếm trường hoặc lớp -->
        <div class="relative mb-4">
          <Building class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
          <input type="text" placeholder="Tìm kiếm tên trường hoặc lớp"
            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            v-model="searchQuery" @focus="isSearchFocused = true" @blur="isSearchFocused = false" />
          <ul v-if="isSearchFocused"
            class="absolute bg-white border mt-1 w-full z-10 rounded-lg">
            <li v-for="result in filteredResults" :key="result.id" class="p-2 hover:bg-gray-100 cursor-pointer"
              @click="() => { console.log('Clicked:', result); selectSchoolOrUniversity(result); }">
              {{ result.name }}
            </li>
            <button class="p-2 hover:bg-gray-100 cursor-pointer w-full" @click="showAddSchoolOrClassPopup = true, isSearchFocused = false" >
              Thêm mới nếu không tìm thấy tên trường hoặc lớp bạn muốn
            </button>
          </ul>
        </div>
        <!-- Popup thêm trường hoặc lớp -->
<div v-if="showAddSchoolOrClassPopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-20">
  <div class="bg-white p-6 rounded-lg shadow-lg w-96">
    <h2 class="text-lg font-bold mb-4">Thêm {{ selectedType === 'university' ? 'Trường Đại Học' : 'Lớp' }}</h2>
    <input type="text" v-model="newSchoolOrClass" placeholder="Nhập tên mới"
      class="w-full border rounded-lg p-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />
    <div class="flex justify-end gap-4">
      <button class="px-4 py-2 text-gray-500" @click="showAddSchoolOrClassPopup = false">Hủy</button>
      <button class="px-4 py-2 bg-blue-500 text-white rounded-lg" @click="addNewSchoolOrClass">Thêm</button>
    </div>
  </div>
</div>
<!-- Thông báo thành công -->
<div
      v-if="successMessage"
      class="fixed top-4 right-4 bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg z-30"
    >
      {{ successMessage }}
    </div>


        <!-- Input tìm kiếm môn học -->
        <div class="relative" v-if="selectedSchoolOrUniversity">
          <Folder class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
          <input type="text" placeholder="Tìm kiếm môn học"
            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            v-model="courseQuery"  @focus="isSearchCourseFocused = true" @blur="isSearchCourseFocused = false"/>
          <ul v-if=" isSearchCourseFocused" class="absolute bg-white border mt-1 w-full z-10 rounded-lg">
            <li v-for="course in filteredCourses" :key="course.id" class="p-2 hover:bg-gray-100 cursor-pointer"
              @click="selectCourse(course)">
              {{ course.name }}
            </li>
            <button class="p-2 hover:bg-gray-100 cursor-pointer w-full text-blue-500" @click="showAddCoursePopup = true">
              Thêm mới môn học nếu không tìm thấy môn học bạn muốn
            </button>
          </ul>
        </div>
        <!-- Popup thêm môn học -->
<div v-if="showAddCoursePopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-20">
  <div class="bg-white p-6 rounded-lg shadow-lg w-96">
    <h2 class="text-lg font-bold mb-4">Thêm môn học</h2>
    <input type="text" v-model="newCourse" placeholder="Nhập tên môn học mới"
      class="w-full border rounded-lg p-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />
    <div class="flex justify-end gap-4">
      <button class="px-4 py-2 text-gray-500" @click="showAddCoursePopup = false">Hủy</button>
      <button class="px-4 py-2 bg-blue-500 text-white rounded-lg" @click="addNewCourse">Thêm</button>
    </div>
  </div>
</div>

        <!-- Inputs Title, Type, and Description -->
        <div v-if="selectedCourse" class="mt-6">
          <!-- Title Input -->
          <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Tiêu đề</label>
            <input id="title" type="text" v-model="title" class="w-full border rounded-lg p-2"
              :placeholder="file.name" />
          </div>

          <!-- Type Select -->
          <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Thể loại</label>
            <select id="type" v-model="selectedTypeId" class="w-full border rounded-lg p-2" placeholder="Select a type">
              <!-- Placeholder option (chọn giá trị không hợp lệ hoặc trống để làm placeholder) -->
              <option value="null" disabled selected>Chọn thể loại</option>
              <option v-for="type in documentTypes" :key="type.id" :value="type.id">
                {{ type.name }}
              </option>
            </select>
          </div>

          <!-- Description Input -->
          <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
            <textarea id="description" v-model="description" class="w-full border rounded-lg p-2" rows="4"
              placeholder="Nhập mô tả"></textarea>
          </div>
        </div>
      </div>


      <!-- Navigation -->
      <div class="flex justify-between mt-8">
        <button class="px-6 py-2 border rounded-lg hover:bg-gray-50" @click="goBack">
          Quay lại
        </button>
        <button @click="handleNext" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
          Tiếp theo
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {  ref, computed } from 'vue'
import { useRouter, useRoute  } from 'vue-router'
import { BookOpen, FileText, Trash2, Building, Folder } from 'lucide-vue-next'
import apiService from '../services/api';

const showAddCoursePopup = ref(false); // Popup cho thêm trường/lớp
const showAddSchoolOrClassPopup = ref(false); // Popup cho thêm môn học

// Reactive variable để lưu thông tin file

const route = useRoute();

const file = ref({
  name: route.query.file_name,
  size: route.query.file_size,
  path: route.query.file_path,
  preview: route.query.preview,
});



const router = useRouter(); // Sử dụng router
const newSchoolOrClass = ref(''); // Tên trường/lớp mới
const newCourse = ref(''); // Tên môn học mới
const successMessage = ref(''); // Thông báo thành công

const addNewSchoolOrClass = async () => {
  // Kiểm tra nếu trường nhập liệu không rỗng
  if (newSchoolOrClass.value.trim()) {
    try {
      if (selectedType.value === 'university') {
        // Gọi hàm tạo trường
        await apiService.createUniversity({ name: newSchoolOrClass.value.trim() });
      } else if (selectedType.value === 'class') {
        // Gọi hàm tạo lớp
        await apiService.addClass({ name: newSchoolOrClass.value.trim() });
      }
      const response = selectedType.value === 'university' ? await apiService.getUniversities() : await apiService.getClasses()
      allResults.value = response.data // Gán dữ liệu từ API vào danh sách

      // Cập nhật trạng thái và hiện thông báo
      successMessage.value = `Đã thêm mới ${selectedType.value === 'university' ? 'Trường Đại Học' : 'Lớp'} thành công! Hãy tìm kiếm lại.`;
          setTimeout(() => (successMessage.value = ''), 3000); // Xóa thông báo sau 3 giây

          // Đặt lại input và đóng popup
          newSchoolOrClass.value = '';
          showAddSchoolOrClassPopup.value = false;
    } catch (error) {
      // Xử lý lỗi
      console.error(`Error adding ${selectedType.value}:`, error);
    }
  } else {
    console.warn('Tên trường hoặc lớp không được để trống.');
  }
};

function addCourseToUniversityOrClass(type, id, payload) {
    if (type === 'university') {
        return apiService.createCourse(id, payload);
    } else if (type === 'class') {
        return apiService.addSubjectToClass(id, payload);
    }
}

const addNewCourse = async () => {
    if (newCourse.value.trim() && selectedSchoolOrUniversity.value) {
        try {
            // Lấy loại (university/class) và ID của trường/lớp
            const type = selectedType.value;
            const id = selectedSchoolOrUniversity.value.id;

            // Dữ liệu gửi lên server
            const payload = { name: newCourse.value.trim() };

            // Gọi API
            addCourseToUniversityOrClass(type, id, payload);

            // Hiển thị thông báo thành công
            successMessage.value = `Đã thêm môn học mới thành công vào ${
                type === 'university' ? 'Trường Đại Học' : 'Lớp'
            }! Hãy tìm kiếm lại.`;

            // Làm mới danh sách môn học
            if (type === 'university') {
                await fetchSubjects(id);
            } else {
                await fetchClassSubjects(id);
            }

            // Đặt lại dữ liệu và đóng popup
            newCourse.value = '';
            showAddCoursePopup.value = false;

            // Xóa thông báo sau 3 giây
            setTimeout(() => (successMessage.value = ''), 3000);
        } catch (error) {
            console.error(`Error adding course:`, error);
        }
    } else {
        console.warn('Tên môn học không được để trống hoặc chưa chọn trường/lớp.');
    }
};

const removeFile = async () => {
  if (file.value && file.value.path) {
    try {
      // Gọi API để xóa file
      await apiService.deleteFile(file.value.path, file.value.preview);
      console.log("File deleted successfully");

      // Sau khi xóa thành công, đặt lại thông tin file
      file.value = null;
      router.back(); // Quay lại trang trước
    } catch (error) {
      console.error('Failed to delete file:', error);
    }
  }
};
const goBack = () => {
  router.back(); // Quay lại trang trước đó
};
// State
const selectedType = ref('university') // Giá trị mặc định là "university"
const searchQuery = ref('') // Từ khóa tìm kiếm
const courseQuery = ref('') // Từ khóa tìm kiếm môn học
const allResults = ref([]) // Lưu danh sách tất cả các trường/lớp
const filteredResults = computed(() =>
  allResults.value.filter(item => item.name.toLowerCase().includes(searchQuery.value.toLowerCase()))
)
const selectedSchoolOrUniversity = ref(null) // Trường/lớp được chọn
const courseResults = ref([]) // Kết quả tìm kiếm môn học
const selectedCourse = ref(null) // Thêm trạng thái cho môn học đã chọn
const filteredCourses = computed(() =>
  courseResults.value.filter(course => course.name.toLowerCase().includes(courseQuery.value.toLowerCase()))
)
// Các thông tin mới cho Title, Type, và Description
const title = ref(file.value.name);
const description = ref('')
const selectedTypeId = ref()
const documentTypes = ref([]) // Lưu trữ các type lấy từ API



// Gọi API để lấy dữ liệu
const fetchData = async () => {
  allResults.value = [] // Xóa dữ liệu cũ
  searchQuery.value = '' // Reset từ khóa tìm kiếm
  try {
    const response = selectedType.value === 'university' ? await apiService.getUniversities() : await apiService.getClasses()
    allResults.value = response.data // Gán dữ liệu từ API vào danh sách
    fetchDocumentTypes() // Lấy danh sách loại tài liệu
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}
// Lấy danh sách các loại tài liệu từ API
const fetchDocumentTypes = async () => {
  try {
    const response = await apiService.getDocumentTypes()
    documentTypes.value = response.data.document_types; // Cập nhật danh sách các loại tài liệu
  } catch (error) {
    console.error('Error fetching document types:', error)
  }
}
// Khi người dùng chọn trường/lớp
const selectSchoolOrUniversity = (item) => {
  selectedSchoolOrUniversity.value = item;
  courseResults.value = []; // Reset danh sách môn học
  courseQuery.value = ''; // Reset từ khóa tìm kiếm môn học
  searchQuery.value = item.name; // Đặt tên trường/lớp vào ô tìm kiếm
  // Gọi hàm lấy danh sách môn học
  if (selectedType.value === 'university') {
    fetchSubjects(item.id) // Gọi API lấy môn học của trường
  } else {
    fetchClassSubjects(item.id) // Gọi API lấy môn học của lớp
  }
}

const fetchSubjects = async (universityId) => {
  try {
    const response = await apiService.getCourses(universityId);
    courseResults.value = response.data; // Cập nhật danh sách môn học cho trường
  } catch (error) {
    console.error('Error fetching subjects:', error);
  }
}

const fetchClassSubjects = async (classId) => {
  try {
    const response = await apiService.getClassSubjects(classId);
    courseResults.value = response.data; // Cập nhật danh sách môn học cho lớp
  } catch (error) {
    console.error('Error fetching class subjects:', error);
  }
}
const selectCourse = (course) => {
  selectedCourse.value = course
  courseQuery.value = course.name // Hiển thị tên môn học trong ô input
}
// Khởi tạo dữ liệu ban đầu
fetchData()
// Hàm kiểm tra tất cả các trường
const checkFormValidity = () => {
  if (!file.value) {
    console.error("File is required.");
    return false;
  }
  if (!selectedSchoolOrUniversity.value) {
    console.error("School or University is required.");
    return false;
  }
  if (!selectedCourse.value) {
    console.error("Course is required.");
    return false;
  }
  if (!title.value) {
    console.error("Title is required.");
    return false;
  }
  if (!selectedTypeId.value) {
    console.error("Type is required.");
    return false;
  }
  if (!description.value) {
    console.error("Description is required.");
    return false;
  }
  return true;
};
const handleNext = async () => {
  if (checkFormValidity()) {
    console.log({
      file: file.value,
      university_or_class_id: selectedSchoolOrUniversity.value.id,
      subject_id: selectedCourse.value.id,
      title: title.value,
      type_id: selectedTypeId.value,
      description: description.value,
    });

    try {
      // Kiểm tra nếu có file_path được truyền qua route
      const filePath = file.value?.path;  // Đảm bảo file.path chứa file_path

      if (!filePath) {
        console.error("File path is missing.");
        return;
      }

      // Chuẩn bị dữ liệu gửi lên API
      const data = {
        title: title.value,
        description: description.value,
        file_path: filePath, // Gửi file_path từ query params
        preview: file.value.preview, // Gửi thông tin ảnh preview
        document_type_id: selectedTypeId.value, // Gửi ID loại tài liệu
        subject_id: selectedCourse.value.id, // Gửi ID môn học
      };

      // Gửi dữ liệu tùy theo loại đã chọn
      if (selectedType.value === 'university') {
        data.type = 'university'; // Gửi type nếu chọn "university"
        data.university_id = selectedSchoolOrUniversity.value.id; // Gửi university_id nếu chọn "university"
      } else if (selectedType.value === 'class') {
        data.type = 'school'; // Gửi type nếu chọn "school"
        data.class_id = selectedSchoolOrUniversity.value.id; // Gửi class_id nếu chọn "class"
      }

      // Gọi API để lưu tài liệu
      const response = await apiService.StoreDocument(data); // Gọi API phương thức store

      if (response.status === 200) {
        console.log("Upload successful");

        // Chuyển hướng
        router.push("/congartulations");
      } else {
        console.error("Upload failed", response.data.errors);
      }
    } catch (error) {
      console.error("Error during upload:", error);
    }
  }
};


</script>

<style>

</style>