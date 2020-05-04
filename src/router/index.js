// importing vue and vuerouter
import Vue from 'vue'
import Router from 'vue-router'
import $ from 'jquery'
import store from '../store/store'
import c from '../common/common.js';
// import pages and components

// login page
const login = () => import('../components/login/login.vue');

// registration pages
const register = () => import('../components/register/register.vue');
const newdoctor = () => import('../components/register/newdoctor.vue');
const newpatient = () => import('../components/register/newpatient.vue');
const newpharmacy = () => import('../components/register/newpharmacy.vue');
const newlab = () => import('../components/register/newlab.vue');
const logout = () => import('../components/workspace/logout/logout.vue');


// work area
const root = () => import('../components/workspace/root.vue');
const pharmacybody = () => import('../components/workspace/pharmacy/pharmacy.vue')

Vue.use(Router)

const routes = [
  {// main page
    name: 'root',
    path: '/',
    components: {
      default: root
    },
    beforeEnter: (from, to, next) => {
      console.log('before root')
      var ls = localStorage;
      if ('email' in ls && 'password' in ls) {
        try {
          $.ajax({
            url: store.state.url,
            method: 'POST',
            data: {
              pass: store.state.phrase,
              action: 'checkme',
              email: ls.email,
              password: ls.password
            }
          }).done(function (data) {
            console.log('succeed in checking and routing to :' + data);
            try{
              var results = JSON.parse(data);
              if (results[0]) {
                store.state.username = ls.username;
                store.state.usertype = ls.usertype;
                next();
              } else {
                localStorage.clear();
                next({ name: 'login' });
              }
            }catch(err){
              localStorage.clear();
              next({ name: 'login' });
            }
          }).fail(function (data) {
            console.log('dailed in checking and routing to login, data: ' + data );
            localStorage.clear();
            next({ name: 'login' });
          })
        } catch (err) { localStorage.clear(); console.log('an error occured in checking for authintication, error:' + err); }
      } else {
        console.log('email not in localStorage and routing to login');
        localStorage.clear();
        next({ name: 'login' });
      }
    },
    children: [
      {//patient  
        name: '0',
        path: '/patient',
        components: {
          default: () => import('../components/workspace/patient/patient.vue')
        },
        beforeEnter: (from, to, next) => {
          console.log('before patient')
          if (localStorage.usertype == 0) {
            console.log('this is a patient account, routing to next();');
            next();
          } else {
            console.log('this is not a patient account, routing to: ' + localStorage.usertype  );
            next({ name: localStorage.usertype });
          }
        },

        children: [
          {//request
            name: 'patient_request',
            path: '/request',
            components: {
              default: () => import('../components/workspace/patient/request/patient_request.vue')
            },
            children: [
              {// asking for doctor
                name: 'askdoctor',
                path: 'askdoctor',
                components: { default: () => import('../components/workspace/patient/request/askdoctor/askdoctor.vue') },
              },
              {// asking for pharmacy
                name: 'askpharmacy',
                path: 'askpharmacy',
                components: { default: () => import('../components/workspace/patient/request/askpharmacy/askpharmacy.vue') },
              },
              {// asking for lab
                name: 'asklab',
                path: 'asklab',
                components: { default: () => import('../components/workspace/patient/request/asklab/asklab.vue') },
              },
              {// home page contains alerts
                name:'requesthome',
                path:'home',
                components:{default:() => import('../components/workspace/patient/request/home/requesthome.vue')}
              }
            ]
          },
          {// showing offers and requests 
            name:'showrequests',
            path:'showrequests',
            components:{default:() => import('../components/workspace/patient/offers/showreqs/showrequests.vue')},
            children:[
              //doctor
              {//doctorreqs
                path:'doctorreqs',
                name:"doctorreqs",
                components:{default:() => import('../components/workspace/patient/offers/showreqs/reqs/doctorreqs/doctorreqs.vue')},
              },
              {//doctoroffers
                path:'doctoroffers/:req_id',
                name:"doctoroffers",
                components:{default:() => import('../components/workspace/patient/offers/showreqs/reqs/doctorreqs/doctoroffers.vue')},
              },
              {//doctrodetailedreq
                path:'doctordetailedoffer/:req_id&:offerid&:providerid',
                name:"doctordetailedoffer",
                components:{default:() => import('../components/workspace/patient/offers/showreqs/reqs/doctorreqs/doctordetailedoffer.vue')},
              },
              //pharmacy
              {//pharmacyreqs
                path:'pharmacyreqs',
                name:"pharmacyreqs",
                components:{default:() => import('../components/workspace/patient/offers/showreqs/reqs/pharmacyreqs/pharmacyreqs.vue')},
              },
              {//pharmacyoffers
                path:'pharmacyoffers/:req_id',
                name:"pharmacyoffers",
                components:{default:() => import('../components/workspace/patient/offers/showreqs/reqs/pharmacyreqs/pharmacyoffers.vue')},
              },
              {//pharmacyodetailedreq
                path:'pharmacydetailedoffer/:req_id&:offerid&:providerid',
                name:"pharmacydetailedoffer",
                components:{default:() => import('../components/workspace/patient/offers/showreqs/reqs/pharmacyreqs/pharmacydetailedoffer.vue')},
              },
              //lab
              {//labreqs
                path:'labreqs/:alert',
                name:"labreqs",
                components:{default:() => import('../components/workspace/patient/offers/showreqs/reqs/labreqs/labreqs.vue')},
              },
              {//laboffers
                path:'laboffers/:req_id',
                name:"laboffers",
                components:{default:() => import('../components/workspace/patient/offers/showreqs/reqs/labreqs/laboffers.vue')},
              },
              {//doctrodetailedreq
                path:'labdetailedoffer/:req_id&:offerid&:providerid',
                name:"labdetailedoffer",
                components:{default:() => import('../components/workspace/patient/offers/showreqs/reqs/labreqs/labdetailedoffer.vue')},
              },
            ]
          },
          {// showing appointments
            name:'showappointments',
            path:'showappointments',
            components:{default:() => import('../components/workspace/patient/appointments/showappointments.vue')},
            children:[
              //doctor
              {//مواعيد الاطباء
                path:'patientdoctorappointments',
                name:"patientdoctorappointments",
                components:{default:() => import('../components/workspace/patient/appointments/doctor/patientdoctorappointments.vue')},
              },
              {//مواعيد الاطباء
                path:'patientdetaileddoctorappointment/:req_id&:offerid&:providerid',
                name:"patientdetaileddoctorappointment",
                components:{default:() => import('../components/workspace/patient/appointments/doctor/patientdetaileddoctorappointment.vue')},
              },
              //pharmacy
              {//pharmacy appointments
                path:'patientpharmacyappointments',
                name:"patientpharmacyappointments",
                components:{default:() => import('../components/workspace/patient/appointments/pharmacy/patientpharmacyappointments.vue')},
              },
              {//pharmacy appointments
                path:'patientdetailedpharmacyappointment/:req_id&:offerid&:providerid',
                name:"patientdetailedpharmacyappointment",
                components:{default:() => import('../components/workspace/patient/appointments/pharmacy/patientdetailedpharmacyappointment.vue')},
              },
              //lab
              {//lab appointments
                path:'patientlabappointments',
                name:"patientlabappointments",
                components:{default:() => import('../components/workspace/patient/appointments/lab/patientlabappointments.vue')},
              },
              {//lab appointments
                path:'patientdetailedlabappointment/:req_id&:offerid&:providerid',
                name:"patientdetailedlabappointment",
                components:{default:() => import('../components/workspace/patient/appointments/lab/patientdetailedlabappointment.vue')},
              },
            ]
          }
        ]
      },
      {//pharmacy
        name: '2',
        path: '/pharmacy',
        components: {
          default: () => import('../components/workspace/pharmacy/pharmacy.vue')
        },
        beforeEnter: (from, to, next) => {
          store.state.sidebar.state = false;
          console.log('before pharmacy');
          if (localStorage.usertype == 2) {
            console.log('this is a pharmacy account, routing to next();');
            next();
          } else {
            console.log('this is not a doctor account, routing to: ' + localStorage.usertype  );
            next({ name: localStorage.usertype });
          }
        },
        children:[
          { // trending
            name:'pharmacy_trending',
            path:'trending',
            components:{
              default: () => import('../components/workspace/pharmacy/trending/trending.vue')
            },
          },
          { // making offer
            name:'pharmacy_offer',
            path:'offer',
            components:{
              default: () => import('../components/workspace/pharmacy/trending/offer.vue')
            },
            beforeEnter: (to,from, next) => {
              console.log('pharmacy to offer with '+store.state.doctor_offer_element.name);
              if(store.state.pharmacy_offer_element.name){
                next();
              }else{
                next(false);
              }
            }
          },
          {// offers
            name:'pharmacy_offers',
            path:'offers',
            components:{
              default: () => import('../components/workspace/pharmacy/trending/offers.vue')
            },
          },          
          {// appointments
            name:'pharmacy_appointments',
            path:'appointments',
            components:{
              default: () => import('../components/workspace/pharmacy/trending/appointments.vue')
            },
          },
        ]
      },
      {//doctor
        name: '1',
        path: '/doctor',
        components: {
          default: () => import('../components/workspace/doctor/doctor.vue')
        },
        beforeEnter: (from, to, next) => {
          store.state.sidebar.state = false;
          console.log('before doctor');
          if (localStorage.usertype == 1) {
            console.log('this is a doctor account, routing to next();');
            next();
          } else {
            console.log('this is not a doctor account, routing to: ' + localStorage.usertype  );
            next({ name: localStorage.usertype });
          }
        },
        children:[
          {// trending
            name:'doctor_trending',
            path:'trending',
            components:{
              default: () => import('../components/workspace/doctor/trending/trending.vue')
            },
          },
          {// offers
            name:'doctor_offers',
            path:'offers',
            components:{
              default: () => import('../components/workspace/doctor/trending/offers.vue')
            },
          },          
          {// appointments
            name:'doctor_appointments',
            path:'appointments',
            components:{
              default: () => import('../components/workspace/doctor/trending/appointments.vue')
            },
          },
          { // making offer
            name:'doctor_offer',
            path:'offer',
            components:{
              default: () => import('../components/workspace/doctor/trending/offer.vue')
            },
            beforeEnter: (to,from, next) => {
              console.log('routing to offer with '+store.state.doctor_offer_element.name);
              if(store.state.doctor_offer_element.name){
                next();
              }else{
                next(false);
              }
            }
          }
        ]
      },
      {//lab
        name: '3',
        path: '/lab',
        components: {
          default: () => import('../components/workspace/lab/lab.vue')
        },
        beforeEnter: (from, to, next) => {
          store.state.sidebar.state = false;
          console.log('before lab');
          if (localStorage.usertype == 3) {
            console.log('this is a lab account, routing to next();');
            next();
          } else {
            console.log('this is not a lab account, routing to: ' + localStorage.usertype  );
            next({ name: localStorage.usertype });
          }
        },
        children:[
          { //trending
            name:'lab_trending',
            path:'trending',
            components:{
              default: () => import('../components/workspace/lab/trending/trending.vue')
            },
          },
          { // offers 
            name:'lab_offer',
            path:'offer',
            components:{
              default: () => import('../components/workspace/lab/trending/offer.vue')
            },
            beforeEnter: (to,from, next) => {
              console.log('routing to offer with '+store.state.doctor_offer_element.name);
              if(store.state.lab_offer_element.name){
                next();
              }else{
                next(false);
              }
            }
          },
          {// offers
            name:'lab_offers',
            path:'offers',
            components:{
              default: () => import('../components/workspace/lab/trending/offers.vue')
            },
          },          
          {// appointments
            name:'lab_appointments',
            path:'appointments',
            components:{
              default: () => import('../components/workspace/lab/trending/appointments.vue')
            },
          },
        ]
      },
      {//logout
        name: 'logout',
        path: '/logout',
        components: {
          default: logout
        },
        beforeEnter:(to, from, next) => {
          store.state.sidebar.state = false;
          next();
        }

      }
    ]
  },
  {//login
    name: 'login',
    path: '/login',
    components: {
      default: login
    },
    beforeEnter: (from, to, next) => {
      store.state.sidebar.state = false;
      c.stopchecknotifications(store.state.notifytimer);
      if ('email' in localStorage) {
        console.log('routing to logout email ' + localStorage.email);
        next({ name: 'logout' });
      } else {
        next();
      }
    }
  },
  {//register
    name: 'register',
    path: '/register',
    components: {
      default: register
    },
    beforeEnter: (from, to, next) => {
      store.state.sidebar.state = false;
      if ('email' in localStorage) {
        next({ name: 'logout' });
      } else {
        next();
      }
    }
  },
  { name: 'newdoctor', path: '/register/doctor', components: { default: newdoctor } },
  { name: 'newpatient', path: '/register/patient', components: { default: newpatient } },
  { name: 'newpharmacy', path: '/register/pharmacy', components: { default: newpharmacy } },
  { name: 'newlab', path: '/register/lab', components: { default: newlab } },
]

const router = new Router({ routes });
router.beforeEach((to, from, next) => {

  // //testing parameter
  // localStorage.email = 'portediloce@gmail.com' ;
  // localStorage.password = 'P@ssw0rd18' ;
  // localStorage.usertype = '0' ;
  // localStorage.username = 'salem allam' ;
  // store.state.usertype = '0';
  // store.state.username = 'salem allam';

  console.log('beforeEach');
  if (store.state.alerts.type == '') {
    console.log('destroying store.state.msg');
    store.state.alerts.msg = [];
  }
  console.log(from.name != to.name);
  if (from.name != to.name) {
    console.log('routing for different page, from page name: ' + from.name + ' to page name: ' + to.name);
    next();
  } else if(from.name == to.name) {
    console.log('routing for the same page, page name: ' + to.name );
    next(false);
  }
});
export default router;
