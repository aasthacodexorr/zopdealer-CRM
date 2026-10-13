<template>
  <div
    class="app-content content"
    v-if="app_metadata.organization == 'zopdealer'"
  >
    <div class="content-overlay"></div>
    <div class="content-body">
      <div class="content-wrapper mt-7 pt-1 pr-0 pl-0">
        <div class="content-body">
          <div class="card shadow-none mb-0">
            <div class="row">
              <div class="col-xs-12 col-md-4 text-center input-group">
                <select v-model="operation" class="custom-select form-control m-1">
                  <option disabled value="">Please select one</option>
                  <option value="generateQueue">Generate Queue</option>
                  <option value="generateSocialQueue">Generate Social Queue</option>
                  <option value="cleanQueue">Clean Queue</option>                  
                </select>
                <button class="btn btn-info m-1" @click="getOperation(operation)">
                  SUBMIT
                </button>
              </div>
              <div class="col-xs-12 col-md-8 text-center">                                         
                <!-- <button v-if="!this.flag" class="btn-lg btn-info" @click="generateQueue">
                  Generate Q
                </button>                              -->
                <button v-if="this.flag" class="btn-lg btn-info" @click="processQueue">
                  Process Q
                </button>
              </div>        
            </div>
            <div class="row">
                <div class="table-responsive tab-wid check-bulk p-2">
                    <table
                            class="list-page-content-p mt-0 mb-0"
                    >
                        <thead>
                            <tr class="bg-grey">
                              <th class="left-set">Queue ID</th>
                              <th class="left-set">ACTION </th>
                              <th class="left-set">Feed ID </th>
                                <!-- <th class="left-set">Client ID</th> -->
                                <!-- <th class="left-set">Comment</th> -->
                                <!-- <th class="left-set">Cron</th>
                                <th class="left-set">Database Name</th> -->
                                <th class="left-set">Domain Name</th>
                                 <th class="left-set">Source</th>
                                <!-- <th class="left-set">File Name</th> -->
                                <!-- <th class="left-set">Folder</th> -->
                                <!-- <th class="left-set">FTP Host</th>
                                <th class="left-set">FTP Pass</th>
                                <th class="left-set">FTP Port</th>
                                <th class="left-set">FTP User</th> -->
                                <!-- <th class="left-set">Identity ID</th> -->
                                <!-- <th class="left-set">Message</th> -->
                                <!-- <th class="left-set">Modified By</th> -->
                                <!-- <th class="left-set">Old File</th> -->
                                <!-- <th class="left-set">Scheduled At</th> -->                               
                                <!-- <th class="left-set">Status</th> -->
                                <!-- <th class="left-set">Creation Date</th> -->
                                <!-- <th class="left-set">Modified Date</th> -->
                                <th class="left-set"> SKIP </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(single_syndication, index) in list" :key="single_syndication.index">
                              <td>{{single_syndication.syndication_queue_id}}</td>
                               <td>
                                <button class="btn btn-info" @click="processQueueById(single_syndication.syndication_queue_id)">
                                    PROCESS <!-- {{single_syndication.syndication_feed_id}} -->
                                  </button>
                              </td>
                              <td>{{single_syndication.syndication_feed_id}}</td>
                                <!-- <td>{{single_syndication.client_id}}</td> -->
                                <!-- <td>{{single_syndication.comment}}</td> -->
                                <!-- <td>{{single_syndication.cron}}</td>
                                <td>{{single_syndication.db_name}}</td> -->
                                <td>{{single_syndication.domain_name}}</td>
                                 <td>{{single_syndication.source}}</td>
                                <!-- <td>{{single_syndication.file_name}}</td> -->
                                <!-- <td>{{single_syndication.folder}}</td> -->
                                <!-- <td>{{single_syndication.ftp_host}}</td>
                                <td>{{single_syndication.ftp_pass}}</td>
                                <td>{{single_syndication.ftp_port}}</td>
                                <td>{{single_syndication.ftp_user}}</td> -->
                                <!-- <td>{{single_syndication.identity_id}}</td> -->
                                <!-- <td>{{single_syndication.message}}</td> -->
                                <!-- <td>{{single_syndication.modified_by}}</td> -->
                                <!-- <td>{{single_syndication.oldfile}}</td> -->
                                <!-- <td>{{parseDateTime(single_syndication.scheduled_at)}}</td> -->                               
                                <!-- <td>{{single_syndication.status}}</td> -->
                                <!-- <td>{{parseDateTime(single_syndication.created_at)}}</td> -->
                                <!-- <td>{{parseDateTime(single_syndication.updated_at)}}</td> -->
                                <td> 
                                  <button class="btn btn-info" @click="skipQueue(single_syndication.syndication_queue_id)">
                                   SKIP
                                  </button>
                              </td>                                
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
const moment = require("moment");
export default {
  name: "SyndicationAction",
  props: ["application", "app_metadata"],
  data(){
      return {
          list : [],
          flag : 0
      }
  },
  methods: {
    parseDateTime(date) {
      return date == null || date == ""
        ? ""
        : moment(date).format("MMMM Do YYYY hh:mm:ss");
    },   
    getOperation(o){            
      const vm = this;
      const toast = new Toast(`Executing Operation ...`);
      toast.fire(); 

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `/api/syndication/operation/`+o, 
      })
        .then(({ data }) => {
          toast.success(data.msg);
          toast.close();
          vm.listQueue();
        })
        .catch(() => {
          toast.fail(`There were some errors in completing operation.`);
          toast.close();
        });
    },
   
    processQueue() {
      const vm = this;
      const toast = new Toast(`Processing a queue...`);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `/api/syndication/queue/process`,
      })
        .then(({ data }) => {
          toast.success(data.msg);
          toast.close();
          vm.listQueue();
        })
        .catch(() => {
          toast.fail(`There were some errors in processing queue.`);
          toast.close();
        });
    },
    processQueueById(e) {
      const vm = this;
      const toast = new Toast(`Processing a queue...`);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `/api/syndication/queue/process/`+ e,
      })
        .then(({ data }) => {
          toast.success(data.msg);
          toast.close();
          vm.listQueue();
        })
        .catch(() => {
          toast.fail(`There were some errors in processing queue.`);
          toast.close();
        });
    },    
    skipQueue(e) {     
      const vm = this;
      const toast = new Toast(`Skipping a queue...`);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `/api/syndication/queue/skip/`+ e,
      })
        .then(({ data }) => {
          toast.success(data.msg);
          toast.close();
          vm.listQueue();
        })
        .catch(() => {
          toast.fail(`There were some errors in skipping queue.`);
          toast.close();
        });
    },           
    listQueue() {
      const vm = this;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `/api/syndication/queue/list`,
      })
        .then(({ data }) => {
            this.list = data.data;
            this.flag = Object.keys(this.list).length > 0 ? 1 : 0;          
        })
        .catch(() => {
         
        });
    },
  },
  created() {
      this.listQueue();
  }
};
</script>
<style scoped>
</style>