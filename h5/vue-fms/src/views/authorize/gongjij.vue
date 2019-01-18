<script>
import api from '../register/api'
import jssa from '../overallS/qjbl'
export default {

  name: 'gongjij',
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
       console.log(this.$route.query.taskId)
       console.log(this.$route.query.fuserid)
       api.gongjj({
         task_id: this.$route.query.taskId,
         fuserid: this.$route.query.fuserid
       })
       this.$router.push({
         path: "/creditEval?fuserid=" + this.$route.query.fuserid
       })
     }
   }
 }
</script>
