<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CustomInput from '@/Components/CustomInput.vue';
import CustomSelect from '@/Components/CustomSelect.vue';
import CustomTextArea from '@/Components/CustomTextArea.vue';
import Api from '@/Helpers/Api/Api.js'
</script>

<template>
    <Head :title="headTitle" />

    <AdminLayout id="tour-save-page">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl md:text-6xl font-bold">
                Passeios
            </h1>
            <PrimaryButton
                @click-ev="backToLastPage"
                class="flex flex-row"
            >
                <div class="flex items-center">
                    <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.75 9.52473C15.75 9.67391 15.6907 9.81699 15.5852 9.92247C15.4797 10.028 15.3366 10.0872 15.1875 10.0872H4.17019L8.27292 14.1893C8.32519 14.2415 8.36664 14.3036 8.39493 14.3718C8.42321 14.4401 8.43777 14.5133 8.43777 14.5872C8.43777 14.6611 8.42321 14.7343 8.39493 14.8026C8.36664 14.8709 8.32519 14.9329 8.27292 14.9852C8.22066 15.0375 8.15862 15.0789 8.09033 15.1072C8.02205 15.1355 7.94886 15.15 7.87495 15.15C7.80104 15.15 7.72786 15.1355 7.65957 15.1072C7.59129 15.0789 7.52925 15.0375 7.47699 14.9852L2.41449 9.9227C2.36219 9.87045 2.3207 9.80842 2.29239 9.74013C2.26408 9.67184 2.24951 9.59865 2.24951 9.52473C2.24951 9.45081 2.26408 9.37761 2.29239 9.30932C2.3207 9.24104 2.36219 9.179 2.41449 9.12676L7.47699 4.06426C7.58253 3.95871 7.72569 3.89941 7.87495 3.89941C8.02422 3.89941 8.16738 3.95871 8.27292 4.06426C8.37847 4.16981 8.43777 4.31296 8.43777 4.46223C8.43777 4.61149 8.37847 4.75465 8.27292 4.8602L4.17019 8.96223H15.1875C15.3366 8.96223 15.4797 9.02149 15.5852 9.12698C15.6907 9.23247 15.75 9.37554 15.75 9.52473Z" fill="#F5F5F5"/>
                    </svg>
                    <span class="ml-2">
                        Voltar
                    </span>
                </div>
            </PrimaryButton>
        </div>

        <div id="container" class="rounded-md">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <CustomInput v-model="form.name"
                        placeholder="Digite o nome do seu passeio"
                        label="Nome"
                        type="text"
                        :errorMessage="nameError"
                        :successMessage="nameSuccess"
                        @checkValue="checkNameMessage"
                        :disabled="!onComplete"
                    />
                </div>
                <div>
                    <CustomSelect v-model="form.hour"
                        placeholder="Selecione um horário"
                        label="Horário"
                        :data="hourItems"
                        :errorMessage="hourError"
                        :successMessage="hourSuccess"
                        @checkValue="checkHourMessage"
                        :disabled="!onComplete"
                    />
                </div>
                <div>
                    <CustomSelect v-model="form.destination"
                        placeholder="Selecione um destino"
                        label="Destino"
                        :data="destinationItems"
                        :errorMessage="destinationError"
                        :successMessage="destinationSuccess"
                        @checkValue="checkDestinationMessage"
                        :disabled="!onComplete"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 mt-5">
                <div>
                    <CustomTextArea v-model="form.description"
                        placeholder="Digite sua descrição"
                        label="Descrição"
                        limit="260"
                        :errorMessage="descriptionError"
                        :successMessage="descriptionSuccess"
                        @checkValue="checkDescriptionMessage"
                        :disabled="!onComplete"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 mt-5">
                <div>
                    <PrimaryButton class="float-right mr-2" colorClass="save" @click-ev="save" :disabled="!onComplete">
                        Salvar
                    </PrimaryButton>
                    <PrimaryButton class="float-right mr-2" colorClass="cancel" @click-ev="backToLastPage">
                        Cancelar
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
export default {
    props: {
    },
    data() {
        return {
            form: {
                id: null,
                name: null,
                hour: '',
                destination: '',
                description: null
            },
            nameError: null,
            nameSuccess: null,
            hourError: null,
            hourSuccess: null,
            destinationError: null,
            destinationSuccess: null,
            descriptionError: null,
            descriptionSuccess: null,
            hourItems: [],
            destinationItems: [],
            tourId: route().params?.id,
            onComplete: false,
        };
    },
    async created() {
        const resultDestination = await Api.getAsync(route('api.admin.destination.list'));
        const resultHour = await Api.getAsync(route('api.admin.hour.index'));


        if (resultDestination.code == 200) {
            this.destinationItems = resultDestination.response.message;
        }

        if (resultHour.code == 200) {
            this.hourItems = resultHour.response.message;
        }

        if (this.tourId != null) {
            this.form.id = this.tourId;
            await this.getData();
        }

        this.onComplete = true;
    },
    computed:{
        headTitle() {
            return (this.form.id != null ? 'Editar' : 'Criar') + ' Passeio';
        }
    },
    methods: {
        checkNameMessage(value) {
            this.nameSuccess = null;
            this.nameError = null;
            this.form.name = value;

            if (value != null && !!value.toString().length) {
                this.nameSuccess = true
            } else {
                this.nameError = 'O campo Nome é obrigatório';
            }

            return this.nameSuccess;
        },
        checkHourMessage(value) {
            this.hourSuccess = null;
            this.hourError = null;
            this.form.hour = value;

            if (value != null && !!value.toString().length) {
                this.hourSuccess = true
            } else {
                this.hourError = 'O campo Horário é obrigatório';
            }

            return this.hourSuccess;
        },
        checkDestinationMessage(value) {
            this.destinationSuccess = null;
            this.destinationError = null;
            this.form.destination = value;

            if (value != null && !!value.toString().length) {
                this.destinationSuccess = true
            } else {
                this.destinationError = 'O campo Destino é obrigatório';
            }

            return this.destinationSuccess;
        },
        checkDescriptionMessage(value) {
            this.descriptionSuccess = true;
            this.descriptionError = null;
            this.form.description = value;

            if (value != null && value.toString().length > 260) {
                this.descriptionSuccess = false;
                this.descriptionError = 'Limite de caracteres ultrapassado';
            }

            return this.descriptionSuccess;
        },
        async getData() {
            const result = await Api.getAsync(route('api.admin.tour.show', this.tourId));

            if (result.code == 200) {
                this.form.id = result.response.message.id;
                this.form.name = result.response.message.name;
                this.form.destination = result.response.message.destination_id;
                this.form.hour = result.response.message.hour_id;
                this.form.description = result.response.message.description;
            }
        },
        async save() {
            this.onComplete = false;
            const nameResult = this.checkNameMessage(this.form.name);
            const hourResult = this.checkHourMessage(this.form.hour);
            const destinationResult = this.checkDestinationMessage(this.form.destination);
            const descriptionResult = this.checkDescriptionMessage(this.form.description);

            if (!(nameResult && hourResult && destinationResult && descriptionResult)) {
                this.onComplete = true;
                return;
            }

            const dataToSend = {
                id: this.form.id,
                name: this.form.name,
                destination_id: this.form.destination,
                hour_id: this.form.hour,
                description: this.form.description,
            }

            const resultState = !!this.form.id ?
                await Api.putAsync(route('api.admin.tour.update', this.form.id), dataToSend) :
                await Api.postAsync(route('api.admin.tour.store'), dataToSend);

            if (resultState.code == 200) {
                this.backToLastPage();
            }
        },
        backToLastPage() {
            window.location.href = route('pages.admin.tour.index');
        },
    }
}
</script>
