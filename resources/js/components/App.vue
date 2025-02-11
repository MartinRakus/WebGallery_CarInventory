<template>
    <div class="container mt-4">
        <h1 class="mb-4">Car Inventory</h1>

        <!-- Cars and Parts Lists -->
        <div class="mt-5">
            <div class="mb-2 d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Available Cars</h2>
                <button class="btn btn-primary" @click.stop="showCarForm = !showCarForm">Add new</button>
            </div>

            <!-- CARS -->
            <div class="card p-4" v-if="showCarForm">
                <h3>Add Car</h3>
                <form @submit.prevent="submitCarForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Car Name</label>
                        <input v-model="car.name" type="text" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input v-model="car.is_registered" type="checkbox" id="is_registered" class="form-check-input">
                        <label for="is_registered" class="form-check-label">Is Registered</label>
                    </div>
                    <div class="mb-3" v-if="car.is_registered">
                        <label for="registration_number" class="form-label">Registration Number</label>
                        <input v-model="car.registration_number" type="text" id="registration_number" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Create Car</button>
                </form>
            </div>

            <ul class="list-group mb-4" @click.stop>
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

            <!-- PARTS -->
            <div class="mb-2 d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Available Parts</h2>
                <button class="btn btn-primary" @click.stop="showPartForm = !showPartForm">Add new</button>
            </div>

            <!-- Add part form -->
            <div class="card p-4" v-if="showPartForm">
                <h3>Add Part</h3>
                <form @submit.prevent="submitPartForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Part Name</label>
                        <input v-model="part.name" type="text" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="serialnumber" class="form-label">Serial</label>
                        <input v-model="part.serialnumber" type="text" id="serialnumber" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="carID" class="form-label">Car ID</label>
                        <input v-model="part.car_id" type="text" id="carID" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Create Part</button>
                </form>
            </div>

            <ul class="list-group mb-4">
                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                    <span>Name <span class="text-success">(ID)</span></span>
                    <span>Assigned to car <span class="text-success">(ID)</span></span>
                </li>
                <li v-for="part in parts" :key="part.id"
                    class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ part.name }} <span class="text-success">({{ part.serialnumber }})</span></span>
                    <span>{{ getCarName(part.car_id) }} <span class="text-success">({{ part.car_id }})</span></span>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                showCarForm: false,
                showPartForm: false,
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
            submitCarForm() { },
            submitPartForm() { },
            getCarName(carId) {
                const car = this.cars.find(c => c.id === carId);
                return car ? car.name : 'Unknown Car';
            },
        },
        mounted() {
            this.fetchCars();
            this.fetchParts();
        }
    };
</script>
