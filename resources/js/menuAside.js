import {
    mdiAccountCircle,
    mdiMonitor,
    mdiBullhorn,
    mdiDomain,
    mdiShieldCrown,
    mdiViewList,
    mdiViewModule,
    mdiLockCheckOutline,
    mdiAccountSupervisor,
    mdiAccount,
    mdiBook,
    mdiShieldLock,
    mdiFileDocumentCheck,
} from "@mdi/js";
// import Icon from '@mdi/react';

export default [
    {
        route: "dashboard",
        icon: mdiMonitor,
        label: "Inicio",
        to: "/dashboard",
    },
    {
        route: "profile.edit",
        label: "Perfil",
        icon: mdiAccountCircle,
    },
    {
        label: "Seguridad",
        icon: mdiShieldLock,
        role: "Admin",
        permission: "menu.seguridad",
        menu: [
            {
                label: "Modulos",
                route: "module.index",
                icon: mdiViewModule,
                permission: "module.index",
            },
            {
                label: "Permisos",
                route: "permission.index",
                icon: mdiLockCheckOutline,
                permission: "permission.index",
            },
            {
                label: "Roles",
                route: "role.index",
                icon: mdiAccountSupervisor,
                permission: "role.index",
            },
            {
                label: "Usuarios",
                route: "user.index",
                icon: mdiAccount,
                permission: "user.index",
            },
        ],
    },
    {
        isDivider: true,
    },
    {
        label: "Catalogos",
        icon: mdiViewList,
        permission: "menu.catalogo",
        menu: [
            {
                label: "Areas de conocimiento",
                route: "knowledgeArea.index",
                icon: mdiViewModule,
                permission: "knowledgeArea.index",
            },
            {
                label: "SubAreas de conocimiento",
                route: "knowledgeSubArea.index",
                icon: mdiViewModule,
                // permission: "knowledgeArea.index",
            },
            {
                label: "Instituciones",
                route: "institution.index",
                icon: mdiDomain,
                permission: "institution.index",
            },
            {
                route: "call.index",
                label: "Convocatorias",
                icon: mdiBullhorn,
                permission: "call.index",
            },
            {
                route: "criterion.index",
                label: "Criterios",
                icon: mdiFileDocumentCheck,
                permission: "criterion.index",
            },
        ],
    },
    {
        route: "project.index",
        label: "Proyectos",
        icon: mdiBook,
        permission: "project.index",
    },
];
