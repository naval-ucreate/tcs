import Vue from "vue";
import axios from "axios";
import VueAxios from "vue-axios";
import List from "./components/ListComponent.vue";

Vue.use(VueAxios, axios);

Vue.prototype.csrf_token = document
    .querySelector("meta[name='_token']")
    .getAttribute("content");

const app = new Vue({
    el: "#app",
    components: {
        List: List
    },
    created: function() {
        console.log("csrf token ", this.csrf_token);
        console.log("vue js is working fine");
    }
});
