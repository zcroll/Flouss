<template>
    <div class="p-6 bg-gray-100 min-h-screen">
        <h1 class="text-3xl font-bold mb-6">Job Fields</h1>
        <div v-for="(jobs, field) in jobsWithFields" :key="field" class="field-group mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">{{ field }}</h2>
            <ul class="list-none pl-0">
                <li v-for="job in jobs" :key="job.id" class="mb-4 p-4 bg-white shadow rounded">
                    <strong class="text-lg">{{ job.name }}</strong>
                    <ul class="list-disc pl-5 mt-2">
                        <li v-for="(score, trait) in job.scores" :key="trait" class="text-gray-700">
                            {{ trait }}: {{ score }}
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        jobsWithFields: Object,
    },
};
</script>

<style scoped>
.field-group {
    margin-bottom: 20px;
}
.field-group h2 {
    margin-bottom: 10px;
}
.field-group ul {
    list-style-type: none;
    padding-left: 0;
}
.field-group ul ul {
    padding-left: 20px;
}
</style>