<template>
  <div id="app">
    <router-view ></router-view>
  </div>
</template>

<script>
import $ from 'jquery';
export default {
  created(){
    var self = this;
    $.ajax({
      url:self.$store.state.url,
      method:'POST',
      data:{
        pass:'TMC123',
        action:'getcities'
      }
    }).done((data)=>{
      var results = [];
      try{
        results = JSON.parse(data)
        if(results[0]){
          self.$store.state.cities = [];
          self.$store.state.citymap = [];
          for (let index = 0; index < results[1].length; index++) {
            var id = results[1][index].id
            console.log('id of city in city map ' + id);
            self.$store.state.citymap[id]  = results[1][index].name;
            self.$store.state.cities.push({text:results[1][index].name,value:results[1][index].id})
          }
          console.log(self.$store.state.citymap)
          console.log(self.$store.state.cities)

        }
      }catch(err){
        throw err;
      }
    }).fail((data)=>{
      console.log(data);
    })
  },
  name: 'App'
}
</script>

<style scoped>
body,html,div,p,span,a,li,ul{
  direction:rtl;
  text-align:right;
  font-size:1.1rem;
}


body,html,div,p{
  margin:0;
  padding:0;
}

</style>
