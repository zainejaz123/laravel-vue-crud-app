<template>
    <div class="container">
        <Loader v-show="loader" />
        <div class="row" v-if="this.data.status == 200">
            <div class="col-12 py-4">
                <h2><span class="badge rounded-circle badge-primary mr-2"><i class="fa fa-user"></i></span>About</h2>
            </div>
            <div class="col-6">
               <h5 class="mb-3"><span class="font-weight-bold mr-3"> ID:</span> {{profile.id}} </h5>
               <h5 class="mb-3"><span class="font-weight-bold mr-3"> Name:</span> {{profile.name}} </h5>
               <h5 class="mb-3"><span class="font-weight-bold mr-3"> Title:</span> {{profile.job_title}} </h5>
               <h5 class="mb-3"><span class="font-weight-bold mr-3"> Start Date:</span> {{profile.start_date}} </h5>
               <h5 class="mb-3"><span class="font-weight-bold mr-3"> End Date:</span>
                <span v-if="profile.working_now == false ">{{profile.end_date}}</span>
                <span v-else-if="profile.working_now == true" class="badge badge-primary">{{profile.working_now ? 'Working now' : ''}}</span>
                 </h5>
                 <h5 class="mb-3"><span class="font-weight-bold mr-3"> Description:</span> {{profile.description}} </h5>
            </div>
        </div>
        <div class="row" v-else-if="this.data.status == 400">
            <div class="col-12 text-center pt-5">
                <h1 class="text-danger text-capitalize">{{message}} !</h1>
            </div>
        </div>
    </div>
</template>


<script>
import apiService from '../api/api';
import Loader from '../loader/loader.vue';

export default {
  components: { Loader },
    data() {
        return {
            profile: [],
            data: [],
            loader: true,
        }
    },

    created(){
        var id = window.location.href.split('/').pop();
        apiService.getSingleProfile(id).then(({data}) => {
            this.data = data;
            this.loader = false;
            if(data.status == 400){
                this.message = data.message;
            }else if(data.status == 200){
                this.profile = data.profile;
            }
        })
        .catch((err) => {
            console.log(err);
        })
    }
}
</script>