import moment from 'moment';
import Vue from 'vue';

Vue.filter('timeformat',(arg)=>{
	return moment(arg).format("MMMM Do YYYY, h:mm:ss a");
})