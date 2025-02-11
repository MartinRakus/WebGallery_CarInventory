<template>
    <div class="mt-4 ml-6 mr-6">
        <div class="mb-2 d-flex justify-content-between align-items-center">
            <h1>Car Inventory</h1>
            <div class="form-check">
                <input v-model="search" type="text" id="searchBar" class="form-control" placeholder="Hľadať">
            </div>
        </div>

        <hr>

        <!-- Cars and Parts Lists -->
        <div id="container" class="mt-4 columns-container">
            <div class="flex-grow-1 mx-2">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Dostupné vozidlá</h2>
                    <button class="btn btn-primary" @click.stop="showCarForm || isEditingCar !== null ? resetCarForm() : showCarForm = true">{{showCarForm || isEditingCar !== null ? 'Zatvoriť' : 'Pridať'}}</button>
                </div>

                <!-- CARS -->
                <div class="card p-4" v-if="showCarForm || isEditingCar !== null">
                    <h3>{{ isEditingCar !== null ? 'Upraviť vozidlo' : 'Pridať vozidlo' }}</h3>
                    <form @submit.prevent="submitCarForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Názov vozidla</label>
                            <input v-model="car.name" type="text" id="name" class="form-control" placeholder="Značka vozidla" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input v-model="car.is_registered" :checked="car.is_registered" type="checkbox" id="is_registered" class="form-check-input">
                            <label for="is_registered" class="form-check-label">Je registrované?</label>
                        </div>
                        <div class="mb-3" v-if="car.is_registered">
                            <label for="registration_number" class="form-label">Registračné číslo</label>
                            <input v-model="car.registration_number" type="text" id="registration_number" class="form-control" placeholder="Sériové číslo / EČV" :required="car.is_registered">
                        </div>
                        <button type="submit" class="btn btn-success">{{ isEditingCar !== null ? 'Upraviť vozidlo' : 'Pridať vozidlo' }}</button>
                    </form>
                    <div v-if="statusMessage" class="mt-3 alert alert-danger">{{ statusMessage }}</div>
                </div>

                <ul class="list-group mb-4" @click.stop>
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                        <span>Názov</span>
                        <span>Registračné číslo</span>
                    </li>
                    <li v-if="filteredCars.length" v-for="car in filteredCars" :key="car.id" :id="'c' + car.id" class="list-group-item d-flex justify-content-between align-items-center selectable" :class="{ 'bg-secondary text-white': selectedCars.includes(car.id) }" @click="selectCar(car)">
                        <span class="first pr-2">{{ car.name }}</span>
                        <div class="d-flex justify-content-between align-items-center">
                            <span v-if="car.registration_number && car.is_registered" class="badge bg-success second">{{ car.registration_number }}</span>
                            <span v-else class="badge bg-danger">Neregistrované</span>
                            <i class="fa fa fa-pencil-square-o btn btn-success ml-5 mr-2" @click.stop="editCar(car)"></i>
                            <i class="fa fa-times btn btn-danger" @click.stop="removeCar(car)"></i>
                        </div>
                    </li>
                    <li v-else class="list-group-item d-flex justify-content-center align-items-center">
                        <span class="text-danger">Žiadne vozidlá neboli nájdené</span>
                    </li>
                </ul>
            </div>

            <!-- PARTS -->
            <div class="flex-grow-1 mx-2">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Dostupné diely</h2>
                    <button class="btn btn-primary" @click.stop="showPartForm || isEditingPart !== null ? resetPartForm() : showPartForm = true">{{ showPartForm || isEditingPart !== null ? 'Zatvoriť' : 'Pridať' }}</button>
                </div>

                <!-- Add part form -->
                <div class="card p-4" v-if="showPartForm || isEditingPart !== null">
                    <h3>{{ isEditingPart !== null ? 'Upraviť diel' : 'Pridať diel' }}</h3>
                    <form @submit.prevent="submitPartForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Názov dielu</label>
                            <input v-model="part.name" type="text" id="name" class="form-control" placeholder="Značka / typ dielu" required>
                        </div>
                        <div class="mb-3">
                            <label for="serialnumber" class="form-label">Sériové číslo</label>
                            <input v-model="part.serialnumber" type="text" id="serialnumber" class="form-control"  placeholder="Výrobné číslo dielu" required>
                        </div>
                        <div class="mb-3" v-if="isEditingPart === null">
                            <label for="carID" class="form-label">Vozidlo</label>
                            <select v-model="part.car_id" id="carID" class="form-control" required>
                                <option value="" :selected="part.car_id === ''" disabled>--Prosím vyberte vozidlo--</option>
                                <option v-for="car in cars" :value="car.id">{{ car.name }} ({{ car.id }})</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">{{ isEditingPart !== null ? 'Upraviť diel' : 'Pridať diel' }}</button>
                    </form>
                    <div v-if="statusMessage" class="mt-3 alert alert-danger">{{ statusMessage }}</div>
                </div>

                <ul class="list-group overflow-auto mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                        <span>Názov <span class="text-success">(Sériové číslo)</span></span>
                        <span>Priradené k vozidlu <span class="text-success">(ID)</span></span>
                    </li>
                    <li v-if="filteredParts.length" v-for="part in filteredParts" :key="part.id" :id="'p' + part.id" class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="first pr-2">{{ part.name }} <span class="text-success">({{ part.serialnumber }})</span></span>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="second">{{ getCarName(part.car_id) }} <span class="text-success">({{ part.car_id }})</span></span>
                            <i class="fa fa fa-pencil-square-o btn btn-success ml-5 mr-2" @click.stop="editPart(part)"></i>
                            <i class="fa fa-times btn btn-danger" @click.stop="removePart(part)"></i>
                        </div>
                    </li>
                    <li v-else class="list-group-item d-flex justify-content-center align-items-center">
                        <span class="text-danger">Žiadne diely neboli nájdené</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                showCarForm: false,
                isEditingCar: null,
                showPartForm: false,
                isEditingPart: null,
                car: {
                    name: '',
                    registration_number: '',
                    is_registered: false,
                },
                part: {
                    name: '',
                    serialnumber: '',
                    car_id: '',
                },
                cars: [],
                parts: [],
                selectedCars: [],
                statusMessage: '',
                search: '',
            };
        },
        computed: {
            filteredCars() {
                if (!this.search.trim()) return this.cars;
                return this.cars.filter(car =>
                    car.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    (car.registration_number && car.registration_number.toLowerCase().includes(this.search.toLowerCase()))
                );
            },
            filteredParts() {
                if (!this.search.trim() && this.selectedCars.length === 0) return this.parts;

                return this.parts.filter(part =>
                    (this.selectedCars.length === 0 || this.selectedCars.includes(part.car_id)) &&
                    (part.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    part.serialnumber.toLowerCase().includes(this.search.toLowerCase()) ||
                    this.getCarName(part.car_id).toLowerCase().includes(this.search.toLowerCase()) ||
                    part.car_id.toString().includes(this.search.trim()))
                );
            }
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
            editCar(car) {
                if (this.isEditingCar === car) {
                    this.resetCarForm();
                } else {
                    this.car = { ...car, is_registered: Boolean(car.is_registered) };
                    this.isEditingCar = car;
                    this.showCarForm = true;
                }
            },
            removeCar(val) {
                if (confirm(`Are you sure you want to delete ${val.name}?`)) {
                    axios.delete(`/cars/${val.id}`)
                        .then(() => {
                            this.fetchCars();
                            this.fetchParts();
                        })
                        .catch(error => {
                            console.error(error);
                            this.statusMessage = 'Chyba pri mazaní vozidla. Skontrolujte prosím zadané údaje.';
                        });
                }
            },
            openCarForm() {
                this.resetCarForm();
                this.showCarForm = true;
            },
            resetCarForm() {
                this.car = { name: '', registration_number: '', is_registered: false };
                this.statusMessage = '';
                this.isEditingCar = null;
                this.showCarForm = false;
            },
            submitCarForm() {
                this.car.registration_number = this.car.is_registered ? this.car.registration_number : '';
                axios.post('/cars', this.car)
                    .then(response => {
                        this.fetchCars();
                        this.resetCarForm();
                    })
                    .catch(error => {
                        console.error(error);
                        this.statusMessage = 'Chyba pri ukladaní vozidla. Skontrolujte prosím zadané údaje.';
                    });
            },
            editPart(part) {
                if (this.isEditingPart === part) {
                    this.resetPartForm();
                } else {
                    this.part = { ...part };
                    this.isEditingPart = part;
                    this.showPartForm = true;
                }
            },
            removePart(val) {
                if (confirm(`Are you sure you want to delete ${val.name}?`)) {
                    axios.delete(`/parts/${val.id}`)
                        .then(() => {
                            this.fetchParts();
                        })
                        .catch(error => {
                            console.error(error);
                            this.statusMessage = 'Chyba pri mazaní dielu. Skontrolujte prosím zadané údaje.';
                        });
                }
            },
            openPartForm() {
                this.resetPartForm();
                this.showPartForm = true;
            },
            resetPartForm() {
                this.part = { name: '', serialnumber: '', car_id: '' };
                this.statusMessage = '';
                this.isEditingPart = null;
                this.showPartForm = false;
            },
            submitPartForm() {
                axios.post('/parts', this.part)
                    .then(response => {
                        this.fetchParts();
                        this.resetPartForm();
                    })
                    .catch(error => {
                        console.error(error);
                        this.statusMessage = 'Chyba pri ukladaní dielu. Skontrolujte prosím zadané údaje.';
                    });
            },
            getCarName(carId) {
                const car = this.cars.find(c => c.id === carId);
                return car ? car.name : 'Unknown Car';
            },
            // Select car as a filter
            selectCar(car) {
                const index = this.selectedCars.indexOf(car.id);
                if (index === -1) {
                    this.selectedCars.push(car.id);
                } else {
                    this.selectedCars.splice(index, 1);
                }
            },
        },
        mounted() {
            this.fetchCars();
            this.fetchParts();
        }
    };
</script>
<style>
  @import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css';
</style>
<style scoped>
    .first {
        word-break: break-word;
        overflow-wrap: break-word;
        white-space: normal;
    }

    .second {
        line-height: normal;
        word-break: break-word;
        overflow-wrap: break-word;
        white-space: normal;
    }

    .columns-container {
        display: flex;
        gap: 25px;
    }

    .columns-container > div {
        flex: 1;
        min-width: 0;
    }

    .selectable {
        cursor: pointer;
    }

    @media (max-width: 1024px) {
        #container {
            flex-direction: column;
        }
    }
</style>
