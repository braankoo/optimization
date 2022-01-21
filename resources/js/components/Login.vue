<template>
    <b-container fluid="true" class="whitesmoke">
        <b-row class="vh-100 text-center" align-v="center" align-h="center">
            <b-col cols="4">
                <b-card header="Login">
                    <b-form @submit.prevent="login">
                        <b-form-group
                            id="email"
                            label="Email"
                            label-for="email"
                            class="mb-2"
                            label-cols-sm="4"
                            label-cols-lg="2"
                            content-cols-lg="12"

                        >
                            <b-form-input
                                id="email-field"
                                v-model="form.email"
                                type="email"
                                required
                                placeholder="Enter email"
                                :state="form.state.email.state"
                            />
                        </b-form-group>
                        <b-form-group
                            id="password-group"
                            label="Password"
                            label-for="password-group"
                            class="mb-2"
                            label-cols-sm="4"
                            label-cols-lg="3"
                            content-cols-lg="12"
                            :invalid-feedback="form.state.email.feedback"
                        >
                            <b-form-input
                                id="current-password"
                                v-model="form.currentPassword"
                                type="password"
                                placeholder="Enter password"
                                required
                                class="mb-2"
                                :state="form.state.email.state"
                            />
                        </b-form-group>
                        <b-button type="submit" :disabled="!enableLoginButton">Submit</b-button>
                    </b-form>
                </b-card>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
export default {
    name: "login",
    data() {
        return {
            form: {
                email: '',
                currentPassword: '',
                state: {
                    email: {
                        state: null,
                        feedback: ''
                    }
                }
            }
        }
    },
    computed: {
        enableLoginButton() {
            return this.form.currentPassword.length > 0 && /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(this.form.email);
        }
    },
    methods: {
        login() {
            this.$http.get('/sanctum/csrf-cookie').then(response => {
                this.$http.post('/login', {
                        email: this.form.email,
                        password: this.form.currentPassword
                    }
                ).then((response) => {
                    localStorage.setItem('token', response.data);
                    this.$router.push({'name': 'Ad Platforms'});
                }).catch(error => {
                    const errors = error.response.data.errors;
                    for (let error in errors) {
                        if (errors.hasOwnProperty(error)) {
                            this.form.state[error].state = false;
                            this.form.state[error].feedback = errors[error].join('');
                        }
                    }
                });

            });
        },
        removeFeedback() {
            if (!this.form.state.email.state && /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(this.form.email)) {
                this.form.state.email.feedback = '';
                this.form.state.email.state = null;
            }
        }
    },
    watch: {
        'form.email': 'removeFeedback',
        'form.currentPassword': 'removeFeedback'
    }
}
</script>

<style scoped>
.whitesmoke {
    background: whitesmoke;
}
</style>
