<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
<div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
    <div class="mx-auto p-6 lg:p-8">

            <div style="width: 50rem;height:500px;max-height: 500px;max-width: 50rem;" class="overflow-hidden p-6 bg-white dark:bg-gradient-to-bl from-gray-700/50 dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex">
                <div class="overflow-y-scroll w-3/12 bg-gray-400 bg-opacity-25 border-b-2 border-gray-200 rounded-md">
                    <ul>
                        <li
                        v-for="user in users" :key="user.id"
                        @click="() =>{loadMessages(user.id)}"
                        :class="(userActive && userActive.id == user.id) ? 'bg-gray-400': ''"
                        class="flex flex-row p-6 text-lg text-gray-600 leading-7 font-semibold border-b border-gray-200 hover:bg-gray-400 hover:cursor-pointer">
                            <p class="flex items-center">{{ user.name }}</p>
                            <div v-if="user.notification" class="ml-2 w-2 h-2 bg-blue-500 rounded-full"></div>
                        </li>
                    </ul>
                </div>
                <div class="w-9/12 bg-white rounded-md flex flex-col justify-between">
                    <div class="w-full p-6 flex flex-col overflow-y-scroll">
                        <div 
                        v-for="message in messages" :key="message.id"
                        :class="(message.from == user) ? 'text-right': ''"
                        class="w-full mb-3 message">
                            <p
                            :class="(message.from == user) ? 'messagefromme': 'messagetome'"
                            class="inline-block p-2 rounded-md" style="max-width: 75%;">
                                {{message.content}}
                            </p>
                            <span class="block mt-1 text-xs text-gray-500">{{message.created_at}}</span>
                        </div>
                        <p v-if="isWriting" class="inline-block p-2 rounded-md messagetome text-left message">...</p>
                    </div>
                    <!-- End Chat -->

                    <!-- Footer -->
                    <div v-if="userActive" class="w-full bg-gray-200 bg-opacity-25 p-6 border-t border-gray-200">
                        <form v-on:submit.prevent="sendMessage">
                            <div class="flex rounded-md overflow-hidden border border-gray-300">
                                <input
                                v-model="message"
                                v-on:keypress="listenKeypress"
                                type="text" name="message" placeholder="Enter message..." autocomplete="off" class="flex-1 px-4 py-2 text-sm focus:outline-none"/>
                                <button type="submit" class="bg-indigo-500 hover:indigo-600 text-white px-4 py-2"></button>
                            </div>
                        </form>
                    </div>
                    <!-- End Footer -->
                </div>
            </div>
        </div>

        <div class="flex justify-center mt-16 px-6 sm:items-center sm:justify-between">
            <div class="ms-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-end sm:ms-0">
            </div>
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
.messagefromme {
    @apply bg-indigo-300 bg-opacity-25
}
.messagetome {
    @apply bg-gray-300 bg-opacity-25
}
</style>
<script>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import axios from 'axios';

    export default {
        components: {
            AppLayout,
        },
        data(){
            return {
                users:[],
                messages: [],
                userActive: null,
                message:'',
                isWriting : false
            }
        },
        methods: {
            scrollToBottom: async function(){
                if(this.messages.length){
                   document.querySelectorAll('.message:last-child')[0].scrollIntoView()
                }
            },
            loadMessages: async function(userId){
                axios.get(`api/users/${userId}`).then(response => {
                    this.userActive = response.data.user
                })

                await axios.get(`api/messages/${userId}`).then(response => {
                    this.messages = response.data.messages
                    this.scrollToBottom()
                })
                
                this.scrollToBottom()

                const user = this.users.filter((user) => {
                    if(user.id === userId){
                        return user
                    }
                })
                if(user){
                    user[0]['notification'] = false
                }
            },
            sendMessage: async function(){
                await axios.post('api/messages/send', {
                    'content': this.message,
                    'to': this.userActive.id
                }).then(response => {
                    this.messages.push({
                        'from': this.user,
                        'to': this.userActive.id,
                        'content': this.message,
                        'created_at':new Date().toISOString(),
                        'updated_at': new Date().toISOString()
                    })
                })
                console.log([this.message,this.userActive.id])
                this.scrollToBottom()
            },
            listenKeypress:  function(){
                axios.post('api/users/isWriting', {'to': this.userActive.id})
            }
        },
        mounted(){
            axios.get('api/users').then(response =>{ 
                this.users = response.data.users
            })

            Echo.private(`writingBaloon.${this.user}`).listen('.IsWriting', (data) => {
                this.isWriting = true
                this.scrollToBottom()
                setInterval(() =>{
                    this.isWriting = false
                }, 5000)
            });

            
            Echo.private(`user.${this.user}`).listen('.SendChatMessages', (data) => {
                if(this.userActive && this.userActive.id == data.message.from){
                    this.messages.push(data.message)
                    this.scrollToBottom()
                }else{
                    const user = this.users.filter((user) => {
                        if(user.id === data.message.from){
                            return user
                        }
                    })
                    if(user){
                        user[0]['notification'] = true
                    }
                }                
            })

            
        },
        computed: {
            user(){
                return this.$page.props.auth.user
            },
        }
    }
</script>
