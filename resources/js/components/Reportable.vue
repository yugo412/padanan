<template>
  <span>
    <button :class="['btn btn-outline-warning btn-sm', {'disabled' : !auth}]" :disabled="!auth" @click="showForm = ! showForm">
      <i class="icon icon_error-triangle"></i> {{ totalReport || total }}
    </button>

    <div class="mt-2" v-if="showForm">
      <form @submit.prevent="store" action="">
        <div class="form-group">
        <label for="description">Alasan</label>
        <textarea v-model="form.description" :class="['form-control', {'is-invalid' : validations.description}]" id="" cols="30" rows="5" placeholder="Tuliskan alasan kenapa istilah dan padanan kurang sesuai..."></textarea>
        <div class="invalid-feedback" v-if="validations.description">
          {{ validations.description[0] }}
        </div>
      </div>

      <div class="form-group">
        <button class="btn btn-primary btn-sm" type="submit">Kirim</button>
        <button class="btn btn-secondary btn-sm" type="button" @click="showForm = false">Batal</button>
      </div>
      </form>
    </div>
  </span>
</template>

<script>
  export default {
    name: 'Reportable',

    props: ['total', 'link', 'auth'],

    data () {
      return {
        loading: false,
        showForm: false,
        validations: [],
        totalReport: null,
        form: {
          description: '',
        }
      }
    },

    methods: {
      store () {
        this.loading = true

        axios.post(this.link, this.form)
          .then(response => {
            this.loading = false
            this.form.description = ''
            this.showForm = false

            this.totalReport = response.data.reports_count
          })
          .catch(e => {
            if (e.response.status == 422) {
              this.validations = e.response.data.errors
            }

            this.loading = false
          })
      }
    }
  }
</script>
