/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import PrimeVue from 'primevue/config';
import "primevue/resources/themes/bootstrap4-light-blue/theme.css";
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.css';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});
app.use(PrimeVue, { ripple: true });

// primevue components 
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import Tooltip from 'primevue/tooltip';
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import DataView from 'primevue/dataview';
import DataViewLayoutOptions from 'primevue/dataviewlayoutoptions'   // optional
import ConfirmationService from 'primevue/confirmationservice';
import ConfirmPopup from 'primevue/confirmpopup';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';
import Skeleton from 'primevue/skeleton';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import MultiSelect from 'primevue/multiselect';
import Chip from 'primevue/chip';
import Calendar from 'primevue/calendar';

import Sites from './pages/Sites.vue';
import SiteDashbaord from './pages/SiteDashbaord.vue';

import GlobalFilters from './components/Dashboard/GlobalFilters.vue';
import Filters from './components/Dashboard/Filters.vue';
import MainStat from './components/Dashboard/MainStat.vue';
import Visitors from './components/Dashboard/Visitors.vue';
import Pages from './components/Dashboard/Pages.vue';
import PagesDetails from './components/Dashboard/PagesDetails.vue';
import Sources from './components/Dashboard/Sources.vue';
import SourcesDetails from './components/Dashboard/SourcesDetails.vue';
import Locations from './components/Dashboard/Locations.vue';
import LocationsDetails from './components/Dashboard/LocationsDetails.vue';
import Devices from './components/Dashboard/Devices.vue';
import DevicesDetails from './components/Dashboard/DevicesDetails.vue';
import SingleCompStat from './components/Dashboard/SingleCompStat.vue';
import Loader from './components/Loader.vue';
import NewSite from './components/Site/NewSite.vue';
import Snippet from './components/Site/Snippet.vue';
import SiteManagment from './components/Site/SiteManagment.vue';

// pages
app.component('site-dashboard', SiteDashbaord);
app.component('Sites', Sites);

//components
app.component('GlobalFilters', GlobalFilters);
app.component('Filters', Filters);
app.component('MainStat', MainStat);
app.component('SingleCompStat', SingleCompStat);
app.component('Visitors', Visitors);
app.component('Pages', Pages);
app.component('PagesDetails', PagesDetails);
app.component('Sources', Sources);
app.component('SourcesDetails', SourcesDetails);
app.component('Locations', Locations);
app.component('LocationsDetails', LocationsDetails);
app.component('Devices', Devices);
app.component('DevicesDetails', DevicesDetails);
app.component('Loader', Loader);
app.component('NewSite', NewSite);
app.component('Snippet', Snippet);
app.component('SiteManagment', SiteManagment);


// primevue components 
app.component('Dropdown', Dropdown);
app.component('Button', Button);
app.component('Dialog', Dialog);
app.component('DataTable', DataTable);
app.component('Column', Column);
app.component('DataView', DataView);
app.component('InputText', InputText);
app.component('DataViewLayoutOptions', DataViewLayoutOptions);
app.component('Toast', Toast);
app.component('ConfirmPopup', ConfirmPopup);
app.component('Skeleton', Skeleton);
app.component('TabView', TabView);
app.component('TabPanel', TabPanel);
app.component('MultiSelect', MultiSelect);
app.component('Chip', Chip);
app.component('Calendar', Calendar);

app.directive('tooltip', Tooltip);
app.use(ToastService);
app.use(ConfirmationService);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
