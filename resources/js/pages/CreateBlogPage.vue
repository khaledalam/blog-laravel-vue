
<template>
    <div class="form-container">
        <div class="flex justify-between">
            <h2>Create a new blog</h2>
            <div class="flex justify-center mb-6">
                <router-link to="/blogs" class="text-blue-500 hover:text-blue-700 hover:underline flex items-center">
                    <span class="font-semibold">Back to all blogs</span>
                </router-link>
                <button @click="logout" class="bg-red-500 p-2 mx-2 rounded" >Logout</button>
            </div>
        </div>
        <form @submit.prevent="createBlog">
            <div class="mb-4">
                <label for="title" class="block text-grey-darker text-sm font-bold mb-2">Title</label>
                <input v-model="blog_data.title" type="text" id="title" required>
                <p v-if="validationErrors.title" class="text-red-500 text-xs italic">{{ validationErrors.title[0] }}</p>

            </div>
            <div class="mb-4">
                <label for="content" class="block text-grey-darker text-sm font-bold mb-2">Content</label>
                <textarea v-model="blog_data.content" id="content" required/>
                <p v-if="validationErrors.content" class="text-red-500 text-xs italic">{{ validationErrors.content[0] }}</p>

            </div>
            <div class="mb-4">
                <label for="publish_date" class="block text-grey-darker text-sm font-bold mb-2">Publish date</label>
                <VueDatePicker :model-value="publish_date_ref" @update:model-value="changePublishDate" id="publish_date" inline auto-apply :enable-time-picker="false" />
                <p v-if="validationErrors.publish_date" class="text-red-500 text-xs italic">{{ validationErrors.publish_date[0] }}</p>

            </div>

            <div class="mb-4">
                <label for="image" class="block text-grey-darker text-sm font-bold mb-2">Image</label>
                <div class="image-upload">
                    <input class="bg-yellow-200 " type="file" id="image" ref="imageInput" @change="handleImageUpload" accept="image/*">
                    <label for="image" class="upload-button">Choose Image</label>
                    <p v-if="validationErrors.image" class="text-red-500 text-xs italic">{{ validationErrors.image[0] }}</p>

                </div>
                <div v-if="imagePreview" class="image-preview">
                    <img :src="imagePreview" alt="Uploaded Image">
                </div>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-grey-darker text-sm font-bold mb-2">Status</label>
                <select v-model="blog_data.status" id="status" class="status-dropdown" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="mb-4">
                <button type="submit" class="mb-5 bg-gray-600 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded">Create Blog</button>
            </div>
        </form>
    </div>
</template>

<script>
import api from '../api';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref } from 'vue';

const publish_date_ref = ref(new Date());

export default {
    components: { VueDatePicker },
    data() {
        return {
            blog_data: {
                title: '',
                content: '',
                publish_date: '',
                status: '1',
                image: null,
            },
            imagePreview: null,

            validationErrors: {
                title: null,
                image: null,
                publish_date: null,
                content: null,
            },
        };
    },
    methods: {
        logout() {
            localStorage.setItem('auth_token', "");
            window.location.reload();
        },
        initErrors() {
            this.validationErrors = {
                title: null,
                image: null,
                publish_date: null,
                content: null,
            };
        },
        changePublishDate(modelData) {
          this.blog_data.publish_date = modelData.toISOString().split('T')[0];
        },
        createBlog() {
            this.initErrors();
            const formData = new FormData();
            formData.append('title', this.blog_data.title);
            formData.append('content', this.blog_data.content);
            formData.append('image', this.blog_data.image);
            formData.append('status', this.blog_data.status);
            formData.append('publish_date', this.blog_data.publish_date);

            api.post('/blogs/create', formData)
                .then(response => {
                    this.$emit('blog-created');
                    this.$toast.success('Good, blog created successfully!');
                })
                .catch(error => {
                        if (error.response && error.response.data && error.response.data.errors) {
                            this.validationErrors = error.response.data.errors;
                    }
                });
        },
        handleImageUpload(event) {
            const selectedImage = event.target.files[0];
            if (selectedImage) {
                this.blog_data.image = selectedImage;
                this.imagePreview = URL.createObjectURL(selectedImage);
            } else {
                this.blog_data.image = null;
                this.imagePreview = null;
            }
        },
    },
};
</script>
