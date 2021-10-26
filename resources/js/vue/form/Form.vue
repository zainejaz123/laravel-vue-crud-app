<template>
  <div class="mb-5 container">
    <h2 class="my-4 text-center">Job Application Form</h2>
    <Loader v-show="loader" />
    <div class="alert text-center text-capitalize mb-4" :class="{'alert-success': successAlert, 'alert-danger': dangerAlert}">{{message}}</div>
    <form @submit="formSubmit">
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
          <button class="btn btn-primary ml-3">Submit</button>
        </div>
      </div>
    </form>
  </div>
</template>


<script>

import axios from 'axios';
import Loader from '../loader/loader.vue';
import apiService from '../api/api.js';

export default {

  components:{
    Loader
  },

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


    getData(){
      var name = localStorage.getItem('full_name');
      var job = localStorage.getItem('job_title');
      var start_date = localStorage.getItem('start_date');
      var end_date = localStorage.getItem('end_date');
      var description = localStorage.getItem('description');
      var working = localStorage.getItem('working_now');

        if(name){
            this.fullName = name;
        }
        
        if(job){
            this.jobTitle = job;
        }

        if(start_date){
            this.startDate = start_date;
        }
        
        if(end_date){
            this.endDate = end_date;   
        }

        if(description){
            this.description = description;   
        }

        if(working){
        this.work_now = true;
        }
    
    },

      convert_date(str) {
        var date = new Date(str),
        mnth = ("0" + (date.getMonth() + 1)).slice(-2),
        day = ("0" + date.getDate()).slice(-2);
        return [date.getFullYear(), mnth, day].join("-");
      },

      formSubmit(event){
        event.preventDefault();

        if(this.fullName == '' || this.jobTitle == '' || (this.endDate == '' && this.work_now == false) || this.startDate == ''){
            this.validation();
            return false;
        }else{
          this.loader = true;
          this.validation();

            var data = {
              "name": this.fullName,
              "job_title": this.jobTitle,
              "start_date": this.startDate,
              "end_date": this.endDate,
              "working_now": this.work_now,
              "description": this.description
            }

            this.$store.commit('setProfile', data);

            apiService.storeUser(data).then((response) => {
              if(response.data.status == 200){
                this.loader = false;
                this.successAlert = true;
                this.message = response.data.message;
              }
            })
            .catch((error) => {
              console.log(error);
            })

            localStorage.clear();

        }

      },
    },

    beforeMount(){
      this.getData();
    },

    watch:{
      description(val){
        this.words = 300;
        this.words = parseInt(this.words) - parseInt(val.length);

        if(val != ''){
          localStorage.setItem('description', val);
        }else {
          localStorage.removeItem('description');
        }
      },

      fullName(val){
        if(val != ''){
          localStorage.setItem('full_name', val);
        }else {
          localStorage.removeItem('full_name');
        }
      },

      jobTitle(val){
        if(val != ''){
          localStorage.setItem('job_title', val);
        }else {
          localStorage.removeItem('job_title');
        }
      },

      startDate(val){
        if(val != ''){
          localStorage.setItem('start_date', val);
        }else {
          localStorage.removeItem('start_date');
        }
      },

      endDate(val){
        if(val != ''){
          localStorage.setItem('end_date', val);
        }else {
          localStorage.removeItem('end_date');
        }
      },

      work_now(checked){
        if(checked == true){
          localStorage.setItem('working_now', checked);
          localStorage.removeItem('end_date');
          this.endDate = '';
          this.endErr = false;
          this.endDanger = false;
        }else if(checked == false){
          localStorage.removeItem('working_now');
        }
      }
    }
}
</script>