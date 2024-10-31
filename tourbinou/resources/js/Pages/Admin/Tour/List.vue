<script setup>
import { Head } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DataTable from 'datatables.net-vue3'
import DataTablesCore from 'datatables.net';
import TourDelete from '@/Pages/Admin/Tour/Delete.vue';
import 'datatables.net-select';
import 'datatables.net-responsive';
import Api from '@/Helpers/Api/Api.js'

DataTable.use(DataTablesCore);
</script>

<template>
    <Head title="Passeios" />

    <Modal :show="showDeleteModal" :closeable="true" @close="closeDeleteModal">
        <TourDelete v-model:show="showDeleteModal" :id="selectedId" :name="selectedName" @updateDatabase="updateDatabase"/>
    </Modal>

    <AdminLayout id="tour-list-page">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl md:text-6xl font-bold">
                Passeios
            </h1>
            <PrimaryButton
                @click-ev="goToCreatePage"
                class=""
            >
                Cadastrar passeio
            </PrimaryButton>
        </div>

        <div id="container" class="rounded-md">
            <DataTable :options="tableOptions" ref="datatable" :data="tourItems" class="display"/>
        </div>
    </AdminLayout>
</template>

<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: false,
        },
        id: {
            type: String,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            showDeleteModal: false,
            selectedId: null,
            selectedName: null,
            tourItems: [],
        };
    },
    created() {
        const self = this;

        this.updateDatabase();
        this.tableOptions = {
            searching: false,
            lengthChange: false,
            pageLength: 30,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json",
                info: "Mostrando passeios: _START_ ao _END_ (_TOTAL_ items ao todo)",
                infoEmpty: "Lista de passeios vazia (_TOTAL_ items ao todo)",
                infoFiltered: "(filtrado _MAX_ items ao todo)",
                entries: {
                    _: 'passeios',
                    1: 'passeio'
                }
            },
            responsive: true,
            order: [[ 0, 'desc' ]],
            aoColumns: [
                {bSorteable: true},
                {bSorteable: true},
                {bSorteable: true},
                {bSorteable: false},
            ],
            columnDefs: [
                {
                    width: '25%',
                    targets: [ 0 ],
                    data: 'name',
                    title: 'Nome',
                },
                {
                    width: '25%',
                    targets: [ 1 ],
                    data: 'hour',
                    title: 'Horário',
                },
                {
                    width: '25%',
                    targets: [ 2 ],
                    data: 'destination',
                    title: 'Destino',
                },
                {
                    width: '25%',
                    data: null,
                    defaultContent: '',
                    targets: -1,
                    title: '',
                    orderable: false,
                    searchable: false,
                    render: function ( data, type, row, meta ) {
                        const fontAwesomePenSvg = '<svg class="pen" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.3103 6.90307L17.1216 2.71339C16.9823 2.57406 16.8169 2.46354 16.6349 2.38814C16.4529 2.31274 16.2578 2.27393 16.0608 2.27393C15.8638 2.27393 15.6687 2.31274 15.4867 2.38814C15.3047 2.46354 15.1393 2.57406 15 2.71339L3.43969 14.2746C3.2998 14.4134 3.18889 14.5786 3.11341 14.7607C3.03792 14.9427 2.99938 15.1379 3.00001 15.3349V19.5246C3.00001 19.9225 3.15804 20.304 3.43935 20.5853C3.72065 20.8666 4.10218 21.0246 4.50001 21.0246H8.6897C8.88675 21.0253 9.08197 20.9867 9.26399 20.9112C9.44602 20.8358 9.61122 20.7248 9.75001 20.5849L21.3103 9.02464C21.4496 8.88534 21.5602 8.71997 21.6356 8.53796C21.711 8.35595 21.7498 8.16087 21.7498 7.96385C21.7498 7.76684 21.711 7.57176 21.6356 7.38975C21.5602 7.20774 21.4496 7.04237 21.3103 6.90307ZM8.6897 19.5246H4.50001V15.3349L12.75 7.08495L16.9397 11.2746L8.6897 19.5246ZM18 10.2134L13.8103 6.02464L16.0603 3.77464L20.25 7.96339L18 10.2134Z" fill="#171717"/></svg>';
                        const fontAwesomeTrashSvg = '<svg class="trash" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20.25 4.52466H3.75C3.55109 4.52466 3.36032 4.60368 3.21967 4.74433C3.07902 4.88498 3 5.07575 3 5.27466C3 5.47357 3.07902 5.66434 3.21967 5.80499C3.36032 5.94564 3.55109 6.02466 3.75 6.02466H4.5V19.5247C4.5 19.9225 4.65804 20.304 4.93934 20.5853C5.22064 20.8666 5.60218 21.0247 6 21.0247H18C18.3978 21.0247 18.7794 20.8666 19.0607 20.5853C19.342 20.304 19.5 19.9225 19.5 19.5247V6.02466H20.25C20.4489 6.02466 20.6397 5.94564 20.7803 5.80499C20.921 5.66434 21 5.47357 21 5.27466C21 5.07575 20.921 4.88498 20.7803 4.74433C20.6397 4.60368 20.4489 4.52466 20.25 4.52466ZM18 19.5247H6V6.02466H18V19.5247ZM7.5 2.27466C7.5 2.07575 7.57902 1.88498 7.71967 1.74433C7.86032 1.60368 8.05109 1.52466 8.25 1.52466H15.75C15.9489 1.52466 16.1397 1.60368 16.2803 1.74433C16.421 1.88498 16.5 2.07575 16.5 2.27466C16.5 2.47357 16.421 2.66434 16.2803 2.80499C16.1397 2.94564 15.9489 3.02466 15.75 3.02466H8.25C8.05109 3.02466 7.86032 2.94564 7.71967 2.80499C7.57902 2.66434 7.5 2.47357 7.5 2.27466Z" fill="#171717"/></svg>';

                        return '<button class="btn-edit mx-2 float-right">' + fontAwesomePenSvg + '</button>' +
                        '<button class="btn-delete mx-2 float-right">' + fontAwesomeTrashSvg + '</button>';
                    },
                    createdCell(td, items, rowData, row, col) {
                        td.addEventListener('click', (e) => {
                            if (e.target.parentNode.classList.contains('btn-edit') || e.target.classList.contains('pen-icon'))
                                self.goToEditPage(rowData.id);
                            else if (e.target.parentNode.classList.contains('btn-delete') || e.target.classList.contains('trash-icon'))
                                self.openDeleteModal(rowData.id);
                        });
                    }
                },
            ]
        };
    },
    computed:{
    },
    methods: {
        updateDatabase() {
            (async () => {
                const result = await Api.getAsync(route('api.admin.tour.index'));

                if (result.code == 200) {
                    this.tourItems = result.response.message;
                    this.$refs.datatable.dt.clear().draw();
                }
            })();
        },
        goToCreatePage() {
            window.location.href = route('pages.admin.tour.store')
        },
        goToEditPage(selectedId) {
            window.location.href = route('pages.admin.tour.update', [ selectedId ])
        },
        openDeleteModal(selectedId) {
            const tour = this.tourItems.find((obj) => obj.id == selectedId );
            this.selectedName = !!tour ? tour.name : null;
            this.selectedId = selectedId;
            this.showDeleteModal = true;
        },
        closeDeleteModal() {
            this.showDeleteModal = false;
            this.selectedName = null;
            this.selectedId = null;
        },
    }
}
</script>
