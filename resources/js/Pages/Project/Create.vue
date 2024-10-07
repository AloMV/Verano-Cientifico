<script setup>
import CardBox from "@/Components/CardBox.vue";
import LayoutMain from "@/Layouts/LayoutMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import { mdiContentSave, mdiClose, mdiPlus, mdiTrashCan } from "@mdi/js";
import NotificationBar from "@/Components/NotificationBar.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import { Link, Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import { computed, watch, ref, provide } from "vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import JetInputError from '@/Components/InputError.vue';
import { Tabs, Tab } from "flowbite-vue";
import Card from './Partials/Card.vue';
import InputError from "@/Components/InputError.vue";
import Select from '@/Components/Select.vue';
import axios from "axios";
import HeadLogo from "@/Components/HeadLogo.vue";

const props = defineProps({
    name: 'Create',
    title: {
        type: String,
        required: true
    },
    routeName: {
        type: String,
        required: true
    }
});

const activeTab = ref('general');
const form = useForm({
    title: null,
    tematic: null,
    subtematic: null,
    mode: 'Proyecto (6 hojas)', // default
    N_estudents: null,
    description: null,
    requirements: null,
    pre_requirements: null,
    keys: null
});

const saveForm = () => {
    form.post(route(`${props.routeName}store`), {
        onError: (errors) => {
            activeTab.value = 'general';
        }
    });
};

const studentOptions = Array.from({ length: 5 }, (_, i) => ({ id: i + 1, name: `${i + 1} estudiante(s)` }));

const handleFileInput = (event) => {
    form.file = event.target.files[0];
};

const getFileURL = computed(() => {
    return form.file ? URL.createObjectURL(form.file) : null;
});

</script>

<template>
    <HeadLogo :title="title" />
    <LayoutMain>
        <SectionTitleLineWithButton :icon="mdiPlus" :title="title" main>
            <Link :href="route(`${routeName}index`)">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x"
                     viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                </svg>
            </Link>
        </SectionTitleLineWithButton>

        <CardBox isForm @submit.prevent="saveForm">
            <Tabs v-model="activeTab" variant="underline" class="p-5">
                <Tab name="general" title="Datos generales" :disabled="false">
                    <FormField required label="Título de Proyecto:" :error="form.errors.title">
                        <FormControl height="h-12" v-model="form.title" placeholder="Título de Proyecto" />
                    </FormField>

                    <div class="md:flex md:space-x-4 my-5">
                        <div class="md:w-1/2 max-lg:mb-5">
                            <FormField label="Temática:">
                                <FormControl v-model="form.selectedArea" :options="areasData" />
                            </FormField>
                        </div>
                        <div class="md:w-1/2">
                            <FormField required label="SubTemática:" :error="form.errors.knowledge_area_id">
                                <FormControl v-model="form.knowledge_area_id" :options="subareasData" />
                            </FormField>
                        </div>
                    </div>

                    <FormField required label="Modalidad del Proyecto:" :error="form.errors.mode">
                        <div class="flex items-center ps-4 border border-gray-400 rounded dark:border-gray-700 h-12">
                            <input id="radio-article" type="radio" value="Virtual" v-model="form.mode"
                                   name="mode" class="w-4 h-4 text-blue-600">
                            <label for="radio-article" class="w-full ms-2 text-sm font-medium">Proyecto normal</label>
                        </div>
                        <div class="flex items-center ps-4 border border-gray-400 rounded dark:border-gray-700 h-12">
                            <input id="radio-poster" type="radio" value="Presencial"
                                   v-model="form.mode" name="mode" class="w-4 h-4 text-blue-600">
                            <label for="radio-poster" class="w-full ms-2 text-sm font-medium">Proyecto corto</label>
                        </div>
                        <div class="flex items-center ps-4 border border-gray-400 rounded dark:border-gray-700 h-12">
                            <input id="radio-poster" type="radio" value="Mixto"
                                   v-model="form.mode" name="mode" class="w-4 h-4 text-blue-600">
                            <label for="radio-poster" class="w-full ms-2 text-sm font-medium">Proyecto corto</label>
                        </div>
                    </FormField>

                    <FormField required label="Número de Estudiantes:" :error="form.errors.N_estudents">
                        <Select v-model="form.N_estudents" :options="studentOptions" placeholder="Selecciona número de estudiantes" />
                    </FormField>

                    <FormField required label="Descripción:" :error="form.errors.description">
                        <FormControl type="textarea" height="h-56" v-model="form.description" placeholder="Descripción del Proyecto" />
                    </FormField>

                    <FormField required label="Requisitos:" :error="form.errors.requirements">
                        <FormControl type="textarea" height="h-56" v-model="form.requirements" placeholder="Requisitos" />
                    </FormField>

                    <FormField required label="Pre-requisitos:" :error="form.errors.pre_requirements">
                        <FormControl type="textarea" height="h-56" v-model="form.pre_requirements" placeholder="Pre-requisitos" />
                    </FormField>

                    <FormField required label="Palabras clave:" :error="form.errors.keys">
                        <FormControl v-model="form.keys" placeholder="Palabras clave" />
                    </FormField>

                    <div class="mt-5">
                        <FormField label="Adjuntar archivo:">
                            <input type="file" @change="handleFileInput" />
                        </FormField>
                        <JetInputError :message="form.errors.file" />
                        <div v-if="getFileURL">
                            <p>Archivo adjunto: <a :href="getFileURL" target="_blank">Ver archivo</a></p>
                        </div>
                    </div>
                </Tab>
            </Tabs>

            <BaseButtons class="mt-5">
                <BaseButton type="submit" :icon="mdiContentSave">Guardar</BaseButton>
            </BaseButtons>
        </CardBox>
    </LayoutMain>
</template>

