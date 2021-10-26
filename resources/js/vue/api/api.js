import axios from "axios"
import { reject } from "lodash";

const apiService = {
    storeUser(data){
        return new Promise((resolve, reject) => {
            axios.post('./api/item/store', data).then((response) => {
                resolve(response);
            })
            .catch((error) => {
                reject(error);
            })
        }); 
    },
    getUsers(){
        return new Promise((resolve, reject) => {
            axios.get('./api/items').then((response) => {
                resolve(response);
            })
            .catch((error) => {
                reject(error);
            })
        });
    },
    getSingleProfile(id){
        return new Promise((resolve, reject) => {
            axios.get(`../api/item/${id}`).then((response) => {
                resolve(response);
            })
            .catch((error) => {
                reject(error);
            })
        });
    },

    updateUser(id, data){
        return new Promise((resolve, reject) => {
            axios.put(`./api/item/${id}`, data).then((response) => {
                resolve(response);
            })
            .catch((error) => {
                reject(error);
            })
        })
    },
    deleteUser(id){
        return new Promise((resolve, reject) => {
            axios.delete(`./api/item/${id}`).then((response) => {
                resolve(response);
            })
            .catch((error) => {
                reject(error);
            })
        })
    },

    deleteMulUsers(ids){
        return new Promise((resolve, reject) => {
            axios.delete(`./api/deleteitems?ids=${ids}`).then((response) => {
                resolve(response);
            })
            .catch((error) => {
                reject(error);
            })
        })
    }
}


export default apiService;