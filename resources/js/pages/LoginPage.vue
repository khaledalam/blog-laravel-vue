<template>
    <div class="mx-auto w-5/12 mt-9 bg-gray-200 p-4 rounded-lg">
        <h2 class="text-3xl font-bold flex justify-center pb-4">Sign in</h2>
        <div
            class="bg-gray-100 shadow-lg rounded-lg px-8 p-8 flex flex-col"
        >
            <ul class="list-disc text-red-300" v-if="errors && typeof errors === 'object'">
                <li v-for="(value, key) in errors" :key="key">{{ value[0] }}</li>
            </ul>
            <p class="list-disc text-red-400" v-if="errors && typeof errors === 'string'">{{ errors }}</p>
            <form method="post" @submit.prevent="login">
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
                        class="mb-5 bg-white hover:bg-blue-200 hover:text-white font-bold p-2 rounded"
                        type="submit"
                    >
                        Login
                    </button>
                    <router-link
                        class="hover:text-blue-500 font-bold p-2 rounded"
                        to="/register"
                    >
                        Don't have account?
                    </router-link>



                    <span class="mt-10">
                        Seed Demo:<br/>
                        username: admin<br/>
                        password: admin
                    </span>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import api from '../api';

export default {
    setup(){

    },
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
        login() {
            const { username, password } = this.form;

            api.post('/login', { username, password })
                .then(response => {
                    if (response.data.status === 1 && response.data.message === "User Login Successfully.") {
                        const auth_token = response.data.data.token;
                        localStorage.setItem('auth_token', auth_token);
                        this.$router.push('/home');
                    } else {
                        this.errors = 'Wrong username or password';
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 401) {
                        this.errors = 'Wrong username or password';
                    } else {
                        console.error('Login error:', error);
                    }
                });
        },
        },
};

</script>
