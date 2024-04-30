
<template>
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-semibold mb-4">Edit Blog</h2>
        <div class="flex justify-center mb-6">
            <router-link to="/blogs" class="text-blue-500 hover:text-blue-700 hover:underline flex items-center">
                <span class="font-semibold">Back to all blogs</span>
            </router-link>
            <button @click="logout" class="bg-red-500 p-2 mx-2 rounded" >Logout</button>
        </div>
        <form @submit.prevent="editBlog">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                <input type="text" id="title" v-model="editedBlogData.title" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p v-if="validationErrors.title" class="text-red-500 text-xs italic">{{ validationErrors.title[0] }}</p>
            </div>
            <div class="mb-4">
                <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content:</label>
                <textarea id="content" v-model="editedBlogData.content" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="5"></textarea>
                <p v-if="validationErrors.content" class="text-red-500 text-xs italic">{{ validationErrors.content[0] }}</p>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                <select id="status" v-model="editedBlogData.status" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="publishDate" class="block text-gray-700 text-sm font-bold mb-2">Publish Date:</label>
                <input type="date" id="publishDate" v-model="editedBlogData.publish_date" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p v-if="validationErrors.publish_date" class="text-red-500 text-xs italic">{{ validationErrors.publish_date[0] }}</p>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Image:</label>
                <input type="file" id="image" @change="onImageChange" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <img v-if="editedBlogData.image" :src="'/storage/'+ editedBlogData.image" alt="Selected Image" class="mt-2 max-w-full h-auto">
                <p v-if="validationErrors.image" class="text-red-500 text-xs italic">{{ validationErrors.image[0] }}</p>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save Changes</button>
        </form>
    </div>
</template>

<script>
import api from '../api';

export default {
    data() {
        return {
            editedBlogData: {
                title: '',
                content: '',
                status: '1',
                publish_date: null,
                image: null,
            },
            validationErrors: {
                title: null,
                image: null,
                publish_date: null,
                content: null,
            },
        };
    },
    computed: {
        blogId() {
            return this.$route.params.id;
        },
    },
    mounted() {
        this.fetchBlogData();
    },
    methods: {
        logout() {
            localStorage.setItem('auth_token', "");
            window.location.reload();
        },
        fetchBlogData() {
            const blogId = this.$route.params.id;
            api.get(`/blogs/${blogId}`)
                .then(response => {
                    const existingBlog = response.data.data;
                    this.editedBlogData = { ...existingBlog };
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        },
        editBlog() {
            const formData = new FormData();
            formData.append('title', this.editedBlogData.title);
            formData.append('content', this.editedBlogData.content);
            formData.append('status', this.editedBlogData.status);
            formData.append('publish_date', this.editedBlogData.publish_date);
            if (this.image) {
                formData.append('image', this.image);
            }

            api.post(`/blogs/${this.$route.params.id}`, formData)
                .then(response => {
                    this.$router.push(`/blogs/${this.$route.params.id}`);
                })
                .catch(error => {
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.validationErrors = error.response.data.errors;
                    } else {
                        console.error('Edit error:', error);
                    }
                });
        },
        onImageChange(event) {
            this.image = event.target.files[0];
        },
    },
};
</script>


