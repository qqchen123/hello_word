<script>
import api from '../register/api'
import jssa from '../overallS/qjbl'

export default {

  name: 'creditcards',
  data () {
    return {
      token: jssa.token
    }
  },
  mounted () {
    this.aa()
    this.prea()
  },
  methods: {
     aa () {
      console.log(this.$route.query.taskId);
     },
     prea () {
       console.log(this.$route.query.taskId)
       console.log(this.$route.query.fuserid)
       api.creditcard({
         task_id: this.$route.query.taskId,
         fuserid: this.$route.query.fuserid,
         login_target: 'CREDITCARD'
       })
       this.$router.push({
         path: "/creditEval?fuserid=" + this.$route.query.fuserid
       })
     }
   }
 }
</script>
