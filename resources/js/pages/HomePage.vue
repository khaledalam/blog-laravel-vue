
<template>
    <div class="bg-gray-200 min-h-screen p-4">
        <div class="max-w-2xl mx-auto bg-gray-100 rounded-lg shadow-lg p-8">
            <h1 class="mb-4 text-3xl font-bold text-center ">Home Page</h1>

            <create-blog-page @blog-created="refreshBlogs" class="mb-6" />

            <div class="space-y-4">
                <blogs-page :blogs="blogs" ref="blogsPage" v-show="blogs.length > 0"/>
                <h2 v-if="blogs.length < 1" class="mt-5 text-center border p-4">No blogs yet!</h2>
            </div>

        </div>
    </div>
</template>

<script>
import CreateBlogPage from '../pages/CreateBlogPage.vue';
import BlogsPage from '../pages/BlogsPage.vue';
import api from '../api';

export default {
    components: {
        CreateBlogPage,
        BlogsPage,
    },
    data() {
        return {
            blogs: [],
        };
    },
    methods: {
        refreshBlogs() {
            this.getData();
            this.$refs.blogsPage.fetchBlogs();
        },
        getData() {
            api.get('/blogs')
                .then(response => {
                    this.blogs = response.data?.data?.data || [];
                })
                .catch(error => {
                    console.error('API error:', error);
                });
        }
    },
    mounted() {
        this.getData();
    },
};
</script>

