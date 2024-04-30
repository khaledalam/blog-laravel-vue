<template>
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="flex justify-center mb-6">
            <router-link to="/blogs" class="text-blue-500 hover:text-blue-700 hover:underline flex items-center">
                <span class="font-semibold">Back to all blogs</span>
            </router-link>
            <button @click="logout" class="bg-red-500 p-2 mx-2 rounded" >Logout</button>
        </div>
        <div v-if="showConfirmation" class="fixed top-0 left-0 w-screen h-screen flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white w-1/3 p-6 shadow-lg rounded-lg">
                <p class="text-lg font-semibold mb-4">Are you sure to delete this blog?</p>
                <div class="flex justify-between">
                    <button @click="deleteBlog" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">Yes</button>
                    <button @click="cancelDelete" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded ml-2">No</button>
                </div>
            </div>
        </div>
        <div v-if="blog">
            <h2>Title: {{ blog.title }}</h2>
            <img :src="'/storage/'+blog.image" alt="Blog Image" class="cursor-pointer" @click="openImageInNewTab" style="width: 300px; height: 300px" />
            <p>Content: {{ blog.content }}</p>
            <div>
                <span>Status: </span>
                <span :class="{'text-green': blog.status === '1', 'text-red': blog.status === '0'}">{{ blog.status === '1' ? 'Active' : 'Inactive' }}</span>
            </div>
            <div class="mb-5">
                <span>Publish Date: </span>
                <span>{{ blog.publish_date }}</span>
            </div>

            <div class="space-x-2">
                <router-link :to="'/blogs/' + blog.id + '/edit'">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Edit</button>
                </router-link>
                    <button @click="showDeleteConfirmation" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded">Delete</button>
            </div>

        </div>
        <div v-else class="loading-message">
            <p>Loading...</p>
        </div>
    </div>
</template>
<script>
import api from '../api';

export default {
    data() {
        return {
            blog: null,
            showConfirmation: false,
        };
    },
    mounted() {
        const blogId = this.$route.params.id;
        api.get(`/blogs/${blogId}`)
            .then(response => {
                this.blog = response?.data?.data;
            })
            .catch(error => {
                console.error('API error:', error);
            });
    },
    methods: {
        openImageInNewTab() {
            window.open('/storage/' + this.blog.image, '_blank').focus();
        },
        logout() {
            localStorage.setItem('auth_token', "");
            window.location.reload();
        },
        showDeleteConfirmation() {
            this.showConfirmation = true;
        },
        cancelDelete() {
            this.showConfirmation = false;
        },
        deleteBlog() {

            api.delete(`/blogs/${this.$route.params.id}`)
                .then(response => {
                    this.$router.push('/blogs');
                })
                .catch(error => {
                    console.error('Delete error:', error);
                });

            this.showConfirmation = false;
        },
    },
};
</script>

