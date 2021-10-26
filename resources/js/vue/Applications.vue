<template>
  <main>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="alert alert-success text-capitalize text-center show" v-if="this.message != ''">
            {{message}}
          </div>
          <div class="mt-5" v-if="this.list.length != 0">
            <div class="pb-3 row">
              <div class="col-9">
                <a href="home" class="btn btn-primary rounded-0"><i class="fa fa-plus me-1"></i> Add</a>
                <button class="btn btn-danger rounded-0 ml-2" @click="delItem" v-if="this.singleCheckbox.length != 0"><i class="fa fa-trash"></i> Delete</button>
              </div>
              <div class="col-3">
                <input type="text" class="form-control" placeholder="Search" id="search" v-model="serachItem">
              </div>
              
            </div>
            <div class="table-responsive">
              <table class="table table-bordered text-center" >
                <thead>
                  <th><input type="checkbox" class="form-control-sm" @click="select" v-model="checkAll" /></th>
                  <th width="10%" class="border py-3">S.no</th>
                  <th>User Id</th>
                  <th class="border py-3 ps-2">Name</th>
                  <th class="border py-3 ps-2">Job Title</th>
                  <th class="border py-3 ps-2">Joining Date</th>
                  <th class="border py-3 ps-2">End Date</th>
                  <!-- <th class="border py-3 ps-2">Description</th> -->
                  <th class="border py-3">Edit</th>
                </thead>
                <tbody>
                  <tr v-for="(item , index) in filteredList" :key="index">
                    <td><input type="checkbox" :id="'check-'+index" :value="item.id" v-model="singleCheckbox" class="form-control-sm" /></td>
                    <td>{{index + 1}}</td>
                    <td><a :href="'job-detail/'+item.id" style="text-decoration: underline">{{item.id}}</a></td>
                    <td>{{item.name}}</td>
                    <td>{{item.job_title}}</td>
                    <td>
                      <span :id="'start-date-'+index">{{item.start_date}}</span>
                    </td>
                    <td>
                      <span :id="'end-date-'+index" v-if="item.working_now == false">{{item.end_date}}</span>
                      <span :id="'work-'+index" :class="{'badge bg-primary text-white': item.working_now ? true : false}" v-else-if="item.working_now == true">Still Working</span>
                    </td>
                    <!-- <td>{{item.description}} <span v-if="item.description == ''">--</span></td> -->
                    <td>
                      <button class="badge bg-success btn btn-success" @click="updItem(item.id, index)"><i class="fa fa-pen"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table> 
              <div class="text-center py-4" v-if="this.filteredList.length == 0">
                <h3 class="text-danger">No match found</h3>
              </div>
            </div>
          </div>

          <div class="mt-5" v-else-if="this.list.length == 0">
              <h1 class="text-center mt-5">No Applications</h1>
              <div class="text-center py-4">
                <a href="./" class="btn btn-primary rounded-0"><i class="fa fa-plus me-1"></i> Add</a>
              </div>
          </div>

        </div>
      </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="update-modal">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Update Profile</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <UpdateModalForm :updated_profile="profile" :profile_id="id" />
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" tabindex="-1" role="dialog" id="delete-modal">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Delete Profile</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="confirm-delete">
              <h5 class="mb-4">Do you want to Delete
                 <span v-if="this.singleCheckbox.length == 1">this user ?</span>
                 <span v-else-if="this.singleCheckbox.length > 0 && this.singleCheckbox.length < this.list.length">these users ?</span>
                 <span v-else-if="this.singleCheckbox.length == this.list.length">all users ?</span>
              </h5>
              <div class="d-flex align-items-center justify-content-end">
                <button class="btn btn-danger mr-3" data-dismiss="modal">No</button>
                <button class="btn btn-primary" @click="deleteMultipleUsers">Yes</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import UpdateModalForm from './form/UpdateModalForm.vue';
import $ from 'jquery';
import apiService from './api/api';

export default {
    data() {
        return{
            list:[],
            profile:[],
            id: null,
            success_msg: '',
            del_id: null,
            checkAll: false,
            singleCheckbox: [],
            serachItem: '',

        }
    },

    components:{
      UpdateModalForm,
    },

    methods:{
      delItem(id){
        $('#delete-modal').modal('show');
        this.del_id = id;
      },

      deleteUser(id){
        apiService.deleteUser(id).then((res) => {
          $('#delete-modal').modal('hide');
          this.$store.commit('responseMsg', res.data);
        })
        .catch((err) => {
          console.log(err);
        })
      },

      updItem(id ,index){
        var profile = this.filteredList[index];
        $('#update-modal').modal('show');
        this.profile = profile;
        this.id = id;
      },

      gettedProfile(){
          apiService.getUsers().then((res) => {
          this.list = res.data;
        })
      },

      select(){
        this.singleCheckbox = [];
        if(!this.checkAll){
          for (let i = 0; i < this.filteredList.length; i++) {
            this.singleCheckbox.push(this.filteredList[i].id);
          }
        }
      },

      deleteMultipleUsers(){
        apiService.deleteMulUsers(this.singleCheckbox).then((res) => {
          this.gettedProfile();
          this.$store.commit('responseMsg', res.data);
          this.singleCheckbox = [];
          $('#delete-modal').modal('hide');
        })
        .catch((err) => {
          console.log(err);
        })
      },
    },

    created(){

        apiService.getUsers().then((res) => {
          this.list = res.data;
          this.data = this.list;

           for (let i = 0; i < this.list.length; i++) {
                var work_status = this.list[i].working_now;
                
                if(work_status == 0){
                    this.list[i].working_now = false;
                }else if(work_status == 1){
                    this.list[i].working_now = true;
                }
                
             }
          })
          .catch((err) => {
            console.log(err);
          })
    }, 

   computed:{
     message(){
       return this.$store.getters.getProfile;
     },
     filteredList(){
       return this.list.filter((item) => {
         return item.name.toLowerCase().includes(this.serachItem.toLowerCase())
         || item.id.toString().includes(this.serachItem)
         || item.job_title.toLowerCase().includes(this.serachItem.toLowerCase())
       });
     },
   },
   watch:{
     message(val){
        this.gettedProfile();

        setTimeout(() => {
            this.$store.commit('removeMsg', val);
        }, 3000);
     },
   }
  }
</script>


<style scoped>
td{
  vertical-align: middle;
}
</style>