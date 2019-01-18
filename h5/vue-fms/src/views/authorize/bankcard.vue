<script>
import api from '../register/api'
import jssa from '../overallS/qjbl'
export default {

  name: 'registion',
  data () {
    return {
      token: jssa.token
    }
  },
  mounted () {
    this.prea()
  },
  methods: {
     prea () {
       console.log(this.$route)
       console.log(this.$route.query.taskId)
       console.log(this.$route.query.fuserid)
       api.bankcard({
         task_id: this.$route.query.taskId,
         fuserid: this.$route.query.fuserid,
         login_target: 'DEBITCARD'
       })
       console.log(1)
       this.$router.push({
         path: "/creditEval?fuserid=" + this.$route.query.fuserid
       })
     }
   }
 }
</script>
