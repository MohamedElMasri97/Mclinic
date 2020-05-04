<template>
  <div id="root">
    <hdr class="hdr"></hdr>
        <div class="holder">
            <div class="container">
                <div class="main">
                    <hdr class="hdr2"></hdr>
                    <div class="sidebar" v-if="$store.state.sidebar.state">

                        <div id="sidebar1">
                            <hr />
                            <ul>
                            <li v-for="element in $store.state.sidebar.content" :key="element.id">
                                <p @click="element.do()">
                                <a href="#" @click="$event.preventDefault();">{{element.text}}</a>
                                </p>
                                <hr/>
                            </li>
                            </ul>
                        </div>

                        <div id="sidebar2">
                            <b-navbar toggleable="lg" type="light">
                            <b-navbar-brand href="#">الخيارات</b-navbar-brand>
                            <b-navbar-toggle target="sidebar"></b-navbar-toggle>
                            <b-collapse id="sidebar" is-nav>
                                <b-navbar-nav >
                                <b-nav-item v-for="element in $store.state.sidebar.content" :key="element.id">
                                    <p @click="element.do()">
                                    <a href="#" @click="$event.preventDefault();">{{element.text}}</a>
                                    </p>
                                    <hr class="lowmrgn" />
                                </b-nav-item>
                                </b-navbar-nav>
                            </b-collapse>
                            </b-navbar>
                        </div>

                    </div>
                    <div class="notifications2" v-if="$store.state.notificationstate">
                        <b-navbar toggleable="lg" type="light">
                        <b-navbar-brand href="#">الاشعارات</b-navbar-brand>
                        <b-navbar-toggle target="notifications"></b-navbar-toggle>
                        <b-collapse id="notifications" is-nav>
                            <b-navbar-nav >
                            <b-nav-item v-for="element in $store.state.notifications" :key="element.id">
                              <p @click="dismissnotification(element)" :style='{color:mapnotificationcolor[element.type]}'>
                                {{element.note}}
                              </p>
                              <small>{{element.ts}}</small>
                              <hr class="lowmrgn" />
                            </b-nav-item>
                            </b-navbar-nav>
                        </b-collapse>
                        </b-navbar>
                    </div>
                    <div class="body">
                        <router-view class="subpage"></router-view> 
                    </div>
                </div>
            </div>

            <div class="footer1">
              <b-row>
                <b-col ></b-col>
              </b-row>
            </div>
        </div>

        <div class="notifications" v-if="$store.state.notificationstate">
            <h4>الاشعارات</h4>
            <hr />
            <ul>
            <li v-for="element in $store.state.notifications" :key="element.id">
                <p @click="dismissnotification(element)" :style='{color:mapnotificationcolor[element.type]}'>
                  {{element.note}}
                </p>
                <small>{{element.ts}}</small>
                <hr/>
            </li>
            </ul>
        </div>

  </div>
</template>
<script>
import hdr from "../workhdr";
import c from "../../common/common.js";
export default {
  data(){
    return{
      mapnotificationcolor:{
        '1':'blue',
        '0':'black',
        '2':'red',
        '3':'yellow'
      }
    }
  },
  created() {
    var self = this;
    if (self.$router.currentRoute.name == "root") {
      console.log("routing ... to  " + self.$store.state.usertype);
      self.$router.push({ name: self.$store.state.usertype });
    }
    self.$store.state.notifytimer = c.checknotifications();
  },
  components: {
    hdr
  },
  methods:{
      dismissnotification(element){
          c.dismissnotification(element);
      }
  }
};
</script>
<style scoped>


html,
body,
#root {    
  margin:0;
  padding:0;
  width:100%;
  word-wrap: break-word;
  height: 100%;
}
.hdr {
  margin:0;
  padding:0;
  position: fixed;
  width:100%;
  z-index: 5;
  height: 50px;
}.hdr2{display:none;}

.holder{
    position: absolute;
    top:50px;
    right:0;
    min-width:80%;
    height:100%;
    margin:0;
    padding:0;
}

.container{
    margin:0;
    padding:0;
    min-height: 100%;
    min-width: 100%;
}

.main{
    margin:0;
    padding:0;
    width: 100%;
    overflow: auto;
    padding-bottom: 100px;
}

.body {
    padding:1rem;
    width:70%;
    color: #000000;
    float: left;
}



#sidebar2{
    display:none;
}
.sidebar {
  padding:10px;
  border-radius: 10px;
  width: 22%;
  color: black;
  float: right;
  margin:1rem;
}
.sidebar ul,
.sidebar li,
.sidebar p,
.sidebar a {
  text-decoration: none;
  list-style: none;
  text-align: center;
  padding: 1px;
  transition: color 0.4s;
}
.sidebar p:hover {
  color: gray;
}





.notifications {
    clear:both;
    height:100%;
    position:fixed;
    left:0px;
    top:50px;
    overflow: auto;
    margin:0;
    padding:10px;
    color:white;
    font-size: 1.1rem;
    padding-bottom: 100px;
    background:rgb(118, 190, 22,0.8);
    width:20%;
}.notifications2{
    display: none;
}

.footer1{
    clear:both;
    position:relative;
    height:100px;
    width:100%;
    margin-top:-100px;
    background:rgb(158, 158, 158);
}



@media screen and (max-width: 930px) {
  .lowmrgn{margin:1px;}
  .hdr{
      display:none;
    }.hdr2{display: block;}
  .holder{
      top: 0;
      width:100%;
      min-height: 100%;
  }

  #sidebar1 {
    display: none;
  }
  #sidebar2 {
    display: block;
  }
  .sidebar {
    border-radius: 0%;
    background:white;
    position: static;
    width: 100%;
    height: auto;
    margin: 5px 0;
  }
  .notifications {
    display: none;
  }.notifications2{
    z-index: 1;
    border-radius: 0%;
    background:#eee;
    position: static;
    width: 100%;
    height: auto;
    margin: 5px 0;
    display: block;
  }
  .body {
    width:100%;
    position: static;
    min-height: 100%;
    padding:0px;
  }
  .footer1{
      background:white;
      display: none;
  }
}
</style>
