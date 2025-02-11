<template>
    <div class="container mt-4">
        <h1 class="mb-4">Car Inventory</h1>

        <!-- Cars and Parts Lists -->
        <div class="mt-5">
            <h2>Available Cars</h2>
            <ul class="list-group mb-4">
                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                    <span>Name</span>
                    <span>Registered</span>
                </li>
                <li v-for="car in cars" :key="car.id" class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ car.name }}</span>
                    <span v-if="car.registration_number && car.is_registered" class="badge bg-success">{{ car.registration_number }}</span>
                    <span v-else class="badge bg-danger">Not Registered</span>
                </li>
            </ul>

            <h2>Available Parts</h2>
            <ul class="list-group mb-4">
                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                    <span>Name <span class="text-success">(ID)</span></span>
                    <span>Assigned to car <span class="text-success">(ID)</span></span>
                </li>
                <li v-for="part in parts" :key="part.id"
                    class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ part.name }} <span class="text-success">({{ part.serialnumber }})</span></span>
                    <span>{{ part.car_id }}</span>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                car: {
                    name: '',
                    registration_number: '',
                    is_registered: false,
                },
                part: {
                    name: '',
                    serialnumber: '',
                    car_id: null,
                },
                cars: [],
                parts: [],
            };
        },
        methods: {
            fetchCars() {
                axios.get('/cars')
                    .then(response => {
                        this.cars = response.data;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            },
            fetchParts() {
                axios.get('/parts')
                    .then(response => {
                        this.parts = response.data;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            },
        },
        mounted() {
            this.fetchCars();
            this.fetchParts();
        }
    };
</script>
