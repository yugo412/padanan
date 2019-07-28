<template>
  <div class="loves">
    <button @click.prevent="love" class="btn btn-sm btn-outline-danger"><i class="icon icon_heart"></i> {{ total }}</button>
  </div>
</template>

<script>
  export default {
    name: 'Loveable',

    props: ['link', 'count'],

    data () {
      return {
        total: 0,
      }
    },

    mounted () {
      this.total = this.count
    },

    methods: {
      love () {
        this.total++

        axios.post(this.link)
          .then(response => {
            this.total = response.data.total_likes
          })
      }
    }
  }
</script>
