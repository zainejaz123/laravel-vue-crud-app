<template>
    <main>
        <div class="row">
        <div class="col-6 mb-3">
          <div class="form-group">
            <label for="name" class="mb-1"
              >Full Name: <span class="text-danger">*</span></label
            >
            <input
              type="text"
              name="full-name"
              class="form-control"
              :class="{ 'border-danger': nameDanger }"
              v-model="fullName"
            />
            <small class="text-danger d-inilne-block mt-1" v-show="nameErr"
              >Frist name required</small
            >
          </div>
        </div>
        <div class="col-6 mb-3">
          <div class="form-group">
            <label for="name" class="mb-1"
              >Job Title: <span class="text-danger">*</span></label
            >
            <input
              type="text"
              name="job-title"
              class="form-control"
              :class="{ 'border-danger': jobDanger }"
              v-model="jobTitle"
            />
            <small class="text-danger d-inilne-block mt-1" v-show="jobErr"
              >Job Title required</small
            >
          </div>
        </div>
        <div class="col-6 mb-3">
          <div class="form-group">
            <label for="name" class="mb-1"
              >Start Date: <span class="text-danger">*</span></label
            >
            <input
              type="date"
              name="start-date"
              class="form-control"
              :class="{ 'border-danger': startDanger }"
              v-model="startDate"
            />
            <small class="text-danger d-inilne-block mt-1" v-show="startErr"
              >Start date required</small
            >
          </div>
        </div>
        <div class="col-6 mb-3">
          <div class="form-group">
            <label for="name" class="mb-1"
              >End Date: <span class="text-danger">*</span></label
            >
            <input
              type="date"
              name="start-date"
              class="form-control"
              :class="{ 'border-danger': endDanger }"
              v-model="endDate"
              :disabled="this.work_now == true"
            />
            <div class="d-flex align-items-center justify-content-between mt-1">
              <small class="text-danger" v-show="endErr"
                >End date required</small
              >
              <label for="working_now" class="ml-auto">
                <input
                  type="checkbox"
                  id="working_now"
                  class="form-check-input me-2"
                  v-model="work_now"
                />Working Now
              </label>
            </div>
          </div>
        </div>
        <div class="col-12 mb-3">
          <label for="Description" class="mb-1">Description (optional):</label>
          <textarea
            class="form-control"
            maxlength="300"
            rows="4"
            v-model="description"
            style="resize: none"
          ></textarea>
          <small class="text-danger" v-if="this.words > 0"
            >Remaining word: {{ words }}</small
          >
          <small class="text-danger" v-else-if="this.words == 0"
            >You Cant add more text</small
          >
        </div>
        <div class="col-12 text-right">
          <button class="btn btn-danger mr-3" data-dismiss="modal">Cancel</button>
          <button class="btn btn-primary" @click="updateProfile()">Update</button>
        </div>
      </div>
    </main>
</template>


<script>
import apiService from '../api/api';
export default {

    props: ['updated_profile', 'profile_id'],

    data() {
        return{
            words: 300,
            inputDisable: false,
            fullName: '',
            jobTitle: '',
            startDate: '',
            endDate: '',
            description: '',
            work_now: false,
            profile_array: [],
            pro_id: null,
            
            nameErr: false,
            jobErr: false,
            startErr: false,
            endErr: false,

            nameDanger: false,
            jobDanger: false,
            startDanger: false,
            endDanger: false,

            loader: false,

            message: "",
            successAlert: false,
            dangerAlert: false,
        }
  },
  methods:{

      validation(){
      if(this.fullName == ''){
        this.nameErr = true;
        this.nameDanger = true;
      }else{
       this.nameErr = false;
       this.nameDanger = false;
      }

      if(this.jobTitle == ''){
        this.jobErr = true;
        this.jobDanger = true;
      }else{
       this.jobErr = false;
       this.jobDanger = false;
      }

      if(this.startDate == ''){
        this.startErr = true;
        this.startDanger = true;
      }else{
       this.startErr = false;
       this.startDanger = false;
      }

      if(this.endDate == '' && this.work_now == false){
        this.endErr = true;
        this.endDanger = true;
      }else{
       this.endErr = false;
       this.endDanger = false;
      }
    },

    updateProfile(){
        if(this.fullName == '' || this.jobTitle == '' || (this.endDate == '' && this.work_now == false) || this.startDate == ''){
            this.validation();
            return false;
        }else{
            var data = {
              "name": this.fullName,
              "job_title": this.jobTitle,
              "start_date": this.startDate,
              "end_date": this.endDate,
              "working_now": this.work_now,
              "description": this.description
            }

            apiService.updateUser(this.pro_id, data).then((res) => {
              if(res.data.status == 200){

                $('#update-modal').modal('hide');
                this.$store.commit('responseMsg', res.data);

              }else if(res.data.status == 400){
                this.dangerAlert = true;
                this.message = res.data.message;
              }
            })
            .catch((err) => {
              console.log(err);
            })
        }
        
    }
  },

  watch:{
      profile_id(id){
        this.pro_id = id;
      },

      updated_profile(val){
            this.profile_array = val;
            this.fullName = this.profile_array.name;
            this.jobTitle = this.profile_array.job_title;
            this.startDate = this.profile_array.start_date;
            this.endDate = this.profile_array.end_date;
            this.description = this.profile_array.description;
            this.work_now = this.profile_array.working_now;
      },

       description(val){
        this.words = 300;
        if(val != null){
          this.words = parseInt(this.words) - parseInt(val.length);
        }
       },

      work_now(checked){
        if(checked == true){
          this.endDate = '';
          this.endErr = false;
          this.endDanger = false;
        }
      }
  },

}
</script>