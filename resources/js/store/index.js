import Axios from 'axios';
export default{
	state:{ 
		userList:[],
		userMessage:[],
		//selectedUser : []
	},
	mutations: {
		userList(state, payload){
			return state.userList = payload;
		},
		userMessage(state,payload){
			return state.userMessage = payload
		}
	},
	actions: {

		userList({commit}){
			Axios.get('/userlist')
			.then((response)=>{
				commit("userList", response.data);
				
			})
		},
		userMessage(context,payload){
			Axios.get('/usermessage/'+payload)
			.then((response)=>{
				context.commit('userMessage',response.data)
			})
		}
	},
	getters: {
		userList(state){
			return state.userList
		},
		userMessage(state){
			return state.userMessage
		}
	}
}