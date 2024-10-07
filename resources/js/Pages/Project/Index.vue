<script setup>
import CardBox from "@/Components/CardBox.vue";
import LayoutMain from "@/Layouts/LayoutMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import {
    mdiBallotOutline,
    mdiInformation,
    mdiPencil,
    mdiFilePdfBox,
    mdiBroom,
    mdiMagnify,
    mdiPlus,
    mdiEye,
    mdiCheckDecagram
} from "@mdi/js";
import NotificationBar from "@/Components/NotificationBar.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import { router, Head, usePage } from "@inertiajs/vue3";
import Pagination from "@/Shared/Pagination.vue";
import { reactive, ref, provide } from "vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import Dropdown from "@/Components/DropdownTable.vue";
import HeadLogo from "@/Components/HeadLogo.vue";
import { limitText } from "@/Hooks/useFormato";

const props =defineProps({
    name: String,
    title:{
        type: String,
        required: true,
    },
    projects: {
        type: Object,
        default: () => ({}),
        required: true,
    },
    isReviewer: {
        type: Boolean,
        required: true
    },
    routeName: {
        type: String,
        required: true,
    },
    search: {
        type: String,
        required: true,
    },
    direction: {
        type: String,
        required: true,
    },
});

// Variables reactivas para manejar el estado del componente
const search = ref(props.search);
const canPermission = usePage().props.auth.can;
const isLoading = ref(false);

// Estado reactivo para filtros y datos de la tabla
const state = reactive({
    filters: {
        search: search,
        order: props.projects.order ?? "created_at", // Ordenar por fecha de creación por defecto
        direction: props.direction,
    },
});

// Función para buscar proyectos
const filterSearch = () => {
    router.get(
        route(`${props.routeName}index`, state.filters, {
            replace: true,
        })
    );
};

// Función para limpiar filtros de búsqueda
const cleanFilters = () => {
    isLoading.value = true;
    router.get(route(`${props.routeName}index`));
};

// Función para filtrar proyectos por columna y dirección (ascendente/descendente)
const filterBy = (order, direction) => {
    state.filters.order = order;
    state.filters.direction = direction;
    isLoading.value = true;
    router.get(route(`${props.routeName}index`, state.filters));
};

// Opciones para los filtros de columnas
const opts = [
    { label: "Titulo", key: "title" },
    { label: "Tipo", key: "type" },
    { label: "Resumen", key: "abstract" },
    { label: "Área", key: "area" },
    { label: "Estatus", key: "status" },
];

// Función para verificar si el usuario tiene permiso de edición en un proyecto
const canEdit = (id) => {
    if (hasPermission('project.update')) {
        const project = props.projects.data.find(project => project?.id === id);
        return project.isPostulant && project.status.can_edit;
    }
    return false;
};

// Función para verificar si el usuario tiene un permiso específico
const hasPermission = (permission) => permission in canPermission;

// Proveer la función `filterBy` para ser usada en componentes hijos
provide("filterBy", filterBy);
</script>

<template>
    <HeadLogo :title="title" />
    <LayoutMain>
        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="title" main />

        <!-- Barra de notificaciones -->
        <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiInformation" :outline="false">
            {{ $page.props.flash.success }}
        </NotificationBar>
        <NotificationBar v-if="$page.props.flash.error" color="danger" :icon="mdiInformation" :outline="false">
            {{ $page.props.flash.error }}
        </NotificationBar>

        <!-- Formulario de búsqueda -->
        <form @submit.prevent="filterSearch" class="w-full mb-5">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="flex w-full md:w-1/2 mr-1 my-4">
                    <input type="search" id="search-dropdown"
                        class="block pr-10 md:h-11 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-l-lg md:border-l-gray-300 border-l-gray-300 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-l-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                        placeholder="Ingresa un parámetro de búsqueda" v-model="search" />
                    <BaseButton class="relative z-30 right-0 h-11 rounded-none" @click="filterSearch" :icon="mdiMagnify"
                        color="info" title="Buscar" />
                    <BaseButton class="relative right-0 h-11 rounded-l-none rounded-r-lg" @click="cleanFilters"
                        :icon="mdiBroom" color="lightDark" title="Limpiar filtro" />
                </div>

                <!-- Botón para agregar nuevo proyecto -->
                <BaseButtons>
                    <BaseButton v-if="hasPermission('project.store')" :routeName="`${routeName}create`" color="info"
                        :icon="mdiPlus" label="Agregar Proyecto" class="w-full" />
                </BaseButtons>
            </div>
        </form>

        <!-- Tabla de proyectos -->
        <CardBox v-if="projects.data.length > 0">
            <table>
                <thead>
                    <tr>
                        <th />
                        <th><Dropdown title="Titulo" :options="opts" /></th>
                        <th><Dropdown title="Tipo" :options="opts" /></th>
                        <th><Dropdown title="Resumen" :options="opts" /></th>
                        <th><Dropdown title="Área" :options="opts" /></th>
                        <th v-if="isReviewer">Estatus de evaluación</th>
                        <th v-else><Dropdown title="Estatus" :options="opts" /></th>
                        <th class="relative inline-block text-left">
                            <span class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-bold text-gray-900 dark:text-white ring-inset ring-gray-300 hover:bg-gray-100">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in projects.data" :key="item.id">
                        <td class="align-items-center">
                            <!-- Icono del proyecto -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder" viewBox="0 0 16 16">
                                <path d="M9.828 4a3 3 0 0 0-2.121-.879H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H9.828zM8 3.828a2 2 0 0 1 1.414.586L10.828 6H4a1 1 0 0 1-1-1v-1h4.828zm2 9H4a1 1 0 0 1-1-1V7h10v5a1 1 0 0 1-1 1z"/>
                            </svg>
                        </td>
                        <td data-label="Titulo">{{ limitText(item.title, 50) }}</td>
                        <td data-label="Tipo">{{ item.type }}</td>
                        <td data-label="Resumen">{{ limitText(item.abstract, 50) }}</td>
                        <td data-label="Área">{{ item.area }}</td>
                        <td v-if="isReviewer" data-label="Evaluación">{{ item.status.evaluation }}</td>
                        <td v-else data-label="Estatus">{{ item.status.name }}</td>
                        <td class="whitespace-nowrap">
                            <!-- Acciones para ver y editar -->
                            <BaseButton :routeName="`projects.show`" :routeParams="[item.id]" :icon="mdiEye"
                                title="Ver Proyecto" color="info" />
                            <BaseButton v-if="canEdit(item.id)" :routeName="`projects.edit`" :routeParams="[item.id]"
                                :icon="mdiPencil" title="Editar Proyecto" color="primary" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </CardBox>

        <!-- Mensaje si no hay proyectos -->
        <CardBoxComponentEmpty v-else title="No hay proyectos disponibles" />

        <!-- Paginación -->
        <Pagination :data="projects" class="mt-6" />
    </LayoutMain>
</template>