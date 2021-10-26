<template>
    <div class="site-loader">
        <img :src="image" alt="loader" class="img-fluid" />
    </div>
</template>

<script>

export default {
    data() {
        return{
            image: 'public/img/loader.gif',
        }
    },
}
</script>

<style scoped>
    .site-loader{
        position: fixed;
        inset: 0;
        height: 100%;
        width: 100%;
        z-index: 9999;
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .site-loader img{
        max-width: 300px;
    }
</style>