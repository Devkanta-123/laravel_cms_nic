import Dashboard from './components/Dashboard.vue';
import Admin from './pages/admin/Admin.vue';
import UpdateSetting from './pages/settings/UpdateSetting.vue';
import Menu from './pages/menu/Menu.vue';
import Website from './pages/website/Website.vue';
import Pages from './pages/pages/Pages.vue';
import Archive from './pages/pages/Archive.vue';
import ActivityLog from './pages/pages/ActivityLog.vue';
import AuditLog from './pages/pages/Audit.vue';
import PagesForm from './pages/pages/PagesForm.vue';
import Login from './pages/auth/Login.vue';
import MainComponent from './pages/main_component/MainComponent.vue';
import Theme from './pages/theme/Theme.vue';
import PageSectionMaster from './pages/page_section_master/PageSectionMaster.vue';
import CategoryMaster from './pages/category_master/CategoryMaster.vue';
import LevelMaster from './pages/level_master/LevelMaster.vue';
import CCDashboard from './components/ContentCreator/Dashboard.vue';
import CCPages from './components/ContentCreator/Pages.vue';
import CCMenu from './components/ContentCreator/Menu.vue'
import CCPagesForm from './components/ContentCreator/PagesForm.vue'
import CCFormsHandler from './components/ContentCreator/FormsHandler.vue'
import PDashboard from './components/Publisher/Dashboard.vue';
import PublisherPages from './components/Publisher/Pages.vue';
import PublisherPagesForm from './components/Publisher/PagesForm.vue'
import PublisherFormsHandler from './components/Publisher/FormsHandler.vue'

// import PageContent from './smb/layout/PageContent.vue';
// import Page from './smb/layout/Page.vue';
// import Home from './smb/Home.vue';
import Roles from './pages/admin/Roles.vue';
const folderPath = window.folderPath;
console.log(folderPath);
export default [

    {
        path: '/admin/manage',
        name: 'admin.manage',
        component: Admin,

    },
    {
        path: '/admin/roles',
        name: 'admin.roles',
        component: Roles,

    },
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard,

    },
    {
        path: '/admin/main_components',
        name: 'admin.main_components',
        component: MainComponent,

    },
    {
        path: '/admin/website',
        name: 'admin.website',
        component: Website,

    },
    {
        path: '/admin/theme',
        name: 'admin.theme',
        component: Theme,

    },
    {
        path: '/admin/menu',
        name: 'admin.menu',
        component: Menu,

    },
    {
        path: '/admin/pages',
        name: 'admin.pages',
        component: Pages,

    },
    {
        path: '/admin/page_section_master',
        name: 'admin.page_section_master',
        component: PageSectionMaster
    },
    //category_master
    {
        path: '/admin/category_master',
        name: 'admin.category_master',
        component: CategoryMaster
    },
    //level masters
    {
        path: '/admin/level_master',
        name: 'admin.level_master',
        component: LevelMaster,

    },
    {
        path: '/pages-form/:menuId/:menuName',
        name: 'PagesForm',
        component: PagesForm,
        props: true

    },
    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: UpdateSetting,

    },
    {
        path: '/app/archive',
        name: 'admin.archive',
        component: Archive,

    },

    {
        path: '/app/activitylog',
        name: 'app.activitylog',
        component: ActivityLog,

    },
    {
        path: '/app/auditlogs',
        name: 'admin.auditlogs',
        component: AuditLog,

    },


    //Content Creator  
    {
        path: '/contentcreator/dashboard',
        name: 'contentcreator.dashboard',
        component: CCDashboard,
    },
    {
        path: '/contentcreator/pages',
        name: 'contentcreator.pages',
        component: CCPages,

    },
    {
        path: '/contentcreator/menu',
        name: 'contentcreator.menu',
        component: CCMenu,

    },

    {
        path: '/contentcreator/pages-form/:menuId/:menuName',
        name: 'CCPagesForm',
        component: CCPagesForm,
        props: true

    },
    //FormsHandler for Content Creator
    {
        path: '/contentcreator/pages-form/:menuId/:menuName/:page_section_id',
        name: 'CCFormsHandler',
        component: CCFormsHandler,
        props: true

    },



    //Publisher
    {
        path: '/publisher/dashboard',
        name: 'publisher.dashboard',
        component: PDashboard,
    },
    {
        path: '/publisher/pages',
        name: 'publisher.pages',
        component: PublisherPages,

    },

    {
        path: '/publisher/pages-form/:menuId/:menuName',
        name: 'PublisherPagesForm',
        component: PublisherPagesForm,
        props: true

    },
    //FormsHandler for Publisher
    {
        path: '/publisher/pages-form/:menuId/:menuName/:page_section_id',
        name: 'PublisherFormsHandler',
        component: PublisherFormsHandler,
        props: true

    },


    // FRONT END 

    // HOMEPAGE
    {
        path: '/',
        name: 'PageContent',
        component: () =>
            import (`./${folderPath}/layout/PageContent.vue`),
        props: { id: '1' }
    },
    {
        path: '/',
        name: 'PageContent',
        component: () =>
            import (`./${folderPath}/layout/PageContent.vue`),
        props: route => ({
            id: route.params.id || '1', // Default to '1' if no ID is provided
        }),
    },
    //Other Pages
    //Other Pages
    // {
    //     path: '/page/:id',
    //     name: 'Page',
    //     component: () => import(`./${folderPath}/layout/Page.vue`),
    //     props: true,
    // },
    {
        path: '/page/:id',
        name: 'Page',
        component: () =>
            import (`./${folderPath}/layout/Page.vue`),
        props: route => ({
            id: route.params.id,
            newsId: route.query.newsid || null, // This will pass the newsid from the query parameter (e.g., 3)
            cardId: route.query.cardid || null,
            carddetailsId: route.query.carddetailsid || null,
        }),
    },

    {
        path: '/login',
        name: 'admin.login',
        component: Login,

    }
]