<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {ref} from 'vue';

const props = defineProps(['time_logs', 'projects', 'current_tracker', 'dailyStats', 'monthlyStats'])
let current_project = ref(props.current_tracker?.project_id)
let timer = ref(props.current_tracker)
let start_disabled = ref(props.current_tracker ? true : false)
let editProject = ref()
let editStart = ref()
let editEnd = ref()
let editTimer = ref()

function startTimer() {
    if (!current_project.value)
        alert('No project selected')
    else {
        axios.post('/time-logs/', {
            'project': current_project.value
        }).then((res) => {
            timer.value = res.data.timer
            start_disabled.value = true
        })
    }
}

function stopTimer() {
    axios.put('/time-logs/stop/' + timer.value.id, {}).then((res) => {
        start_disabled.value = false
    })
}

function deleteTime(id) {
    axios.delete('/time-logs/' + id, {}).then((res) => {
        console.log(res.data)
    })
}

function editTime(timer) {
    editProject.value = timer.project
    editStart.value = timer.start
    editEnd.value = timer.end
    editTimer.value = timer
}

function submitTimeLog(timer) {
    axios.put('/time-logs/' + timer.id, {
        'project': editProject.value.id,
        'start': editStart.value,
        'end': editEnd.value
    }).then((res) => {
        if(res.message)
            alert(res.message)
        console.log(res)
    })
}
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <select v-model="current_project"
                            class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                        <option>choose the project</option>
                        <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
                    </select>
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                            :disabled="start_disabled"
                            @click="startTimer()">
                        Start timer
                    </button>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                            :disabled="!start_disabled"
                            @click="stopTimer()">
                        Stop timer
                    </button>
                </div>
            </div>
        </div>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg  p-6">
                    <h1>Time logs</h1>
                    <table>
                        <tr>
                            <th>Project</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Time spent (s.)</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <tr v-for="time_log in time_logs">
                            <td>{{ time_log.project.name }}</td>
                            <td>{{ time_log.start }}</td>
                            <td>{{ time_log.end }}</td>
                            <td>{{ time_log.time_spent }}</td>
                            <td>
                                <button @click="editTime(time_log)">Edit</button>
                            </td>
                            <td>
                                <button @click="deleteTime(time_log.id)">Delete</button>
                            </td>
                        </tr>
                    </table>
                    <div>

                        <div class="form-group">
                            <label>Project</label>
                            <select name="project"
                                    v-model="editProject"
                                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Start time:</label>
                            <input type="text"
                                   name="start"
                                   class="form-control"
                                   placeholder="Start time"
                                   v-model="editStart">
                        </div>

                        <div class="form-group">
                            <strong>End time:</strong>
                            <input type="text"
                                   name="end"
                                   class="form-control"
                                   placeholder="End time"
                                   v-model="editEnd">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success btn-submit" @click="submitTimeLog(editTimer)">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg  p-6">
                    <h2>Time per day</h2>
                    <div id="daily">
                        {{ dailyStats }}
                    </div>
                    <h2>Time per month</h2>
                    <div id="monthly">
                        {{ monthlyStats }}
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg  p-6">
                    <a href="/logs/export/">Time Logs export</a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
