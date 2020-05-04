import vue from 'vue';
import vuex from 'vuex';

vue.use(vuex);

const store = {
    state:{
        errs:{
            badquery : 'حدث خطأ يرجى المحاولة مرة اخرى'
        },
        // eesential data
        phrase:'TMC123',
        username:'',
        usertype:'',


        // registration message
        registered:false,

        // notifications list
        notifications:[],
        notificationstate:true,
        notifytimer : null,

        // side bar menu
        sidebar:{
            state:false,
            content:[
            ]
        },

        // maps and choises for lists
        cities:[
        ],        
        citymap:{},
        gendermap:{'0':'ذكر','1':'انثى'},
        genderchoice:[
            {value:1,text:'لا يهم'},
            {value:2,text:'نفس الجنس'}
        ],

        // production and testing url for the server.php
        url:'https://cors-anywhere.herokuapp.com/http://134.209.200.123/server.php', //for the server
        // url:'http://134.209.200.123/server.php', //for the server
        //  url:'../../api/server.php', // for the home testing

        // parameters for registering :(
        new_what:'',
        new_doctor:{
            username:'', // the doctor fullname,
            city:'', // the city of the doctor/
            phone:'',// the phone of the doctor 
            np:'', // the nearest point to his address
            password:'', //  the password of his account 
            password2:'', //  the password of his account 
            note:'', // the note if needed
            email:'', // email unique value
            birth:'', // the birth date of the doctor
            speciality :''// the speciality of the doctor
        },
        new_patient:{
            username:'', // the patient fullname,
            city:'', // the city of the patient/
            phone:'',// the phone of the patient 
            np:'', // the nearest point to his address
            password:'', //  the password of his account 
            password2:'', //  the password of his account 
            note:'', // the note if needed, which should contain health conserns
            email:'', // email unique value
            birth:'', // the birth date of the patient
        },
        new_pharmacy:{
            username:'', // manager fullname,
            title:'', // tha pharmacy name,
            city:'', // the city where the pharmacy exists/
            phone:'',// the phone of the pharmacy 
            np:'', // the nearest point
            password:'', //  the password 
            password2:'', //  the password 
            note:'', // the note if needed
            email:'' // email unique value
        },
        new_lab:{
            username:'', // manager fullname,
            title:'', // tha pharmacy name,
            city:'', // the city where the pharmacy exists/
            phone:'',// the phone of the pharmacy 
            np:'', // the nearest point
            password:'', //  the password 
            password2:'', //  the password 
            note:'', // the note if needed
            email:'' // email unique value
        },
        
        // probobly the detailed request to apply an offer in the العرض المقدم من قبل الطبيب element 
        doctor_offer_element :{},
        pharmacy_offer_element :{},
        lab_offer_element :{},

        // alerts data for request page
        alerts:{
            type:'',
            msg:[]
        },

        // limit of request for each type 
        patient_request_limits:{
            '1':2,
            '2':1,
            '3':1,
        },

        // user map important!
        usermap:{
            0:'مستخدم',
            1:'طبيب',
            2:'صيدلية',
            3:'تحليل'
        },

    }
}

export default new vuex.Store(store);