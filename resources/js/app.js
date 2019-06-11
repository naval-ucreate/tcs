import Vue from "vue";
import axios from "axios";
import VueAxios from "vue-axios";
import List from "./components/List.vue";
import Report from "./components/Report.vue";
import VueGoogleCharts from "vue-google-charts";

Vue.use(VueAxios, axios);
Vue.use(VueGoogleCharts);

Vue.prototype.csrf_token = document
    .querySelector("meta[name='_token']")
    .getAttribute("content");

const app = new Vue({
    el: "#app",
    components: {
        List: List,
        Report: Report
    },
    created: function() {}
});
