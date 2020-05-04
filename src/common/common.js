import store from '../store/store.js';
import $ from 'jquery';
import router from '../router/index.js';
export default {
  isemail: (email) => {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  },
  isfullname: (name) => {
    var regexp = /^[a-zA-Z]+ [a-zA-Z]+$/;
    return true;
  },
  isstrongpassword: (password) => {
    var regexp = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})/;
    return regexp.test(password);
  },
  phonenumber: (inputtxt) => {
    var phoneno1 = /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
    var phoneno2 = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
    var phoneno3 = /([0-9]{14})/;
    return ((phoneno1.test(inputtxt)) || (phoneno2.test(inputtxt)) || (phoneno3.test(inputtxt)));
  },
  isNN: (inputtxt) => {
    var NN = /^[0-9]{12}$/;
    return NN.test(inputtxt);
  },
  isdate: (inputtxt) => {
    var NN = /^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$/;
    return NN.test(inputtxt);
  },
  short(element){
    if(element.length >200){
      return element.slice(0,175) + '...';
    }else{
      return element;
    }
  },


  checkreq(type) {
    console.log('checking request of type ' + type)
    this.checkme();
    return new Promise((res, rej) => {
      var state = 3;
        $.ajax({
          method: 'POST',
          url: store.state.url,
          data: {
            action: 'checkreq',
            pass: store.state.phrase,
            email: localStorage.email,
            req_type: type
          }
        }).done(function (data) {
          try {
            var results = JSON.parse(data);
            console.log('query succesfull and results : ' + results)
            if (results[0]) {
              state = 1;
            } else {
              state = 2;
            }
            res(state)
          } catch (err) {
            console.log('query failed and error: ' + err + ', and data is: ' + data)
            state = 3;
            res(state);
          }
        }).fail(function (data) {
          console.log('query failed and data : ' + data)
          console.log(data);
          state = 3;
          res(state)
        })
      
    })
    // return 1;
  },
  checkme() {
    console.log('!!checkme!!');
    var ls = localStorage;
    if ('email' in ls && 'password' in ls) {
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
        try {
          var results = JSON.parse(data);
          if (results[0]) {
            store.state.username = ls.username;
            store.state.usertype = ls.usertype;
          } else {
            localStorage.clear();
            router.push({ name: 'login' });
          }
        } catch (err) {
          console.log('an error occured in checkme and err is ' + err + ' and data is ' + data);
          localStorage.clear();
          router.push({ name: 'login' });
        }
      }).fail(function (data) {
        console.log('dailed in checking and routing to login, data: ' + data);
        localStorage.clear();
        router.push({ name: 'login' });
      });
    } else {
      console.log('email not in localStorage and routing to login');
      localStorage.clear();
      router.push({ name: 'login' });
    }
  },
  checknotifications() {
     return setInterval(function () {

        var ls = localStorage;
        if ('email' in ls && 'password' in ls) {
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
            try {
              var results = JSON.parse(data);
              if (results[0]) {
                store.state.username = ls.username;
                store.state.usertype = ls.usertype;
              } else {
                localStorage.clear();
                router.push({ name: 'login' });
              }
            } catch (err) {
              console.log('an error occured in checkme and err is ' + err + ' and data is ' + data);
              localStorage.clear();
              router.push({ name: 'login' });
            }
          }).fail(function (data) {
            console.log('dailed in checking and routing to login, data: ' + data);
            localStorage.clear();
            router.push({ name: 'login' });
          });
        } else {
          console.log('email not in localStorage and routing to login');
          localStorage.clear();
          router.push({ name: 'login' });
        }

        var self = this;
        $.ajax({
          method: 'POST',
          url: store.state.url,
          data: {
            pass: store.state.phrase,
            action: 'getnotifications',
            email: localStorage.email
          }
        }).done(function (data) {
          var results;
          try {
            results = JSON.parse(data)
            if (results[0]) {
              store.state.notifications = results[1];
            }
          } catch (err) {
          }
        }).fail(function (data) {
          console.log('failed query and data is ' + data);
          store.notificationstate = false;
        })
      },10000)
  },
  stopchecknotifications(x){
    try{
      clearInterval(x);
      console.log('error hapened could not sotp the notifier');
    }catch(err){console.log('error hapened could not sotp the notifier ' + err)}
  },
  dismissnotification(element) { console.log(element); }
}