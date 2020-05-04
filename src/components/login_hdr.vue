<template>
<div>
  <b-navbar toggleable="lg" type="dark" class='nav-background'>
    <b-navbar-brand href="#">Mclinic</b-navbar-brand>

    <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

    <b-collapse id="nav-collapse" is-nav>
      <b-navbar-nav>
        <b-nav-item><router-link :to="{name:'login'}" class="no">تسجيل الدخول</router-link></b-nav-item>
        <b-nav-item><router-link :to="{name:'register'}" class="no">انشاء حساب</router-link></b-nav-item>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</div>
</template>
<script>
export default {
    
}
</script>
<style scoped>
    .no{
        text-decoration: none;
        color:white;
    }
    .nav-background{
        background-color:#353535;
    }
</style>