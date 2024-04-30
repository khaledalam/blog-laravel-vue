<template>
    <div class="mx-auto w-5/12 mt-9 bg-gray-200 p-4 rounded-lg">
        <h2 class="text-3xl font-bold flex justify-center pb-4">Sign up</h2>
        <div
            class="bg-gray-100 shadow-lg rounded-lg px-8 p-8 flex flex-col"
        >
            <ul class="list-disc text-red-400" v-if="errors && typeof errors === 'object'">
                <li v-for="(value, key) in errors" :key="key">{{ value[0] }}</li>
            </ul>
            <p class="list-disc text-red-400" v-if="errors && typeof errors === 'string'">{{ errors }}</p>

            <form method="post" @submit.prevent="signup">
                <div class="mb-4">
                    <label
                        class="block text-grey-darker text-sm font-bold mb-2"
                        for="username"
                    >
                        Username
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                        id="username"
                        type="text"
                        v-model="form.username"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label
                        class="block text-grey-darker text-sm font-bold mb-2"
                        for="password"
                    >
                        Password
                    </label>
                    <input
                        class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3"
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                    />
                </div>
                <div class="flex flex-col items-center justify-between">
                    <button
                        class="mb-5 bg-blue-500 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded"
                        type="submit"
                    >
                        Register
                    </button>
                    <router-link
                        class="hover:text-blue-500 font-bold p-2 rounded"
                        to="/login"
                    >
                        Already have an account?
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import api from '../api';

export default {
    data() {
        return {
            form: {
                username: '',
                password: '',
            },
            errors: null,
        };
    },
    methods: {
        signup() {
            const {username, password} = this.form;

            api.post('/register', {username, password})
                .then(response => {
                    if (response.data.status === 1) {
                        const auth_token = response.data.data.token;
                        localStorage.setItem('auth_token', auth_token);
                        this.$toast.success('Good, registered successfully!');
                        setTimeout(() => {
                            this.$router.push('/login');
                        }, 3000);
                    } else {
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error(error);
                    }
                });
        },
    }
};
</script>
