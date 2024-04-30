<template :ref="blogsPage">
    <div>
        <div class="relative mb-5">
            <input
                type="text"
                v-model="searchQuery"
                placeholder="Search by titles"
                class="block w-64 p-4 rounded-full border border-gray-300 bg-gray-100 text-gray-700 focus:outline-none focus:border-blue-400"
            />
        </div>
    <div class="blog-list">
        <h2 class="text-2xl font-semibold my-5">All Blogs</h2>
        <div class="flex justify-center mb-6" v-if="url !='/' && url !='/home'">
            <router-link to="/" class="text-blue-500 hover:text-blue-700 hover:underline flex items-center">
                <span class="font-semibold">Back to create new blog</span>
            </router-link>
            <button @click="logout" class="bg-red-500 p-2 mx-2 rounded" >Logout</button>
        </div>
        <div v-for="blog in filtered_blogs()" :key="blog.id" class="bg-white shadow-md rounded-lg p-4 mb-4">
            <router-link :to="'/blogs/' + blog.id">
            <img :src="'/storage/' + blog.image" alt="Blog Image" class="max-w-full h-auto mb-2" style="width: 300px; height: 300px" />

            <h3 class="text-xl font-semibold mb-2">{{ blog.title }}</h3>

            <div class="text-sm text-gray-600 mb-2">
                Status: <span :class="{'text-green-500': blog.status === '1', 'text-red-500': blog.status === '0'}">{{ blog.status === '1' ? 'Active' : 'Inactive' }}</span>
            </div>

            <div class="text-sm text-gray-600 mb-2">
                Publish Date: {{ blog.publish_date }}
            </div>
            </router-link>
        </div>
    </div>
    </div>
</template>

<script>
import api from '../api';

export default {
    data() {
        return {
            blogs: [],
            searchQuery: '',
            url: window.location.pathname
        };
    },
    mounted() {
        this.fetchBlogs();
    },
    methods: {
        logout() {
            localStorage.setItem('auth_token', "");
            window.location.reload();
        },
        filtered_blogs() {
            console.log("Fsafs", this.blogs);
            return this.blogs.filter(blog => {
                return blog.title.toLowerCase().includes(this.searchQuery.toLowerCase());
            });
        },
        fetchBlogs() {
            api.get('/blogs')
                .then(response => {
                    this.blogs =  response.data?.data?.data || [];
                })
                .catch(error => {
                    console.error('API error:', error);
                });
        },
    },
};
</script>


