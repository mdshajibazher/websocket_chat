<template>
  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Chat Component</div>

        <div class="card-body">
          <ul class="list-unstyled" style="height: 300px; overflow-y: scroll" v-if="messages.length" v-chat-scroll>
            <li class="p-2" v-for="(message,index) in messages" :key="index">
              <strong>{{message.user.name}}</strong>
                {{message.message}}
            </li>
          </ul>
          <p v-else class="alert alert-danger">No messages to display</p>
          <input
          @keyup="sendTypingEvent"
          @keyup.enter="sendMessage"
          v-model="newMessage"
          type="text"
          class="form-control"
          name="message"
          placeholder="Enter your message"
        />
        <span v-if="activeUser" class="text-muted">{{activeUser.name}} is typing..........</span>
        </div>


      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          Active Users
        </div>
        <div class="card-body">
          <ul>
            <li class="p-2" v-for="user in users" :key="user.id">
              {{user.name}} 
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ChatComponent",
  mounted() {
    console.log("Component mounted.");
  },
  created(){
      this.fetchMessages();
      Echo.join('chat')
      .listen('MessageSent',(event) => {
          this.messages.push(event.message);
      })
      .joining(user => {
        console.log('joining');
          this.users.push(user);
      })
      .leaving(user => {
          console.log('leaving');
          this.users = this.users.filter(u => u.id != user.id);
          this.endWhisper();
      })
      .here(user => {
        console.log('here');
        this.users = user;
      })
      .listenForWhisper('typing',user => {
        this.activeUser = user;

        if(this.typingTimer) {
            clearTimeout(this.typingTimer);
        }
        this.typingTimer = setTimeout(() => {
            this.activeUser = false;
        }, 3000);
      })
  },
  data(){
    return {
      messages: [],
      newMessage: "",
      users: [],
      activeUser: false,
      typingTimer: false,
    }
  },
  props: ['user'],
  methods: {
    fetchMessages(){
        axios.get('/messages')
      .then(res => {
        this.messages = res.data;
      })
      .catch(e => {
        console.log(e);
      })
    },
    sendTypingEvent(){
         Echo.join('chat')
         .whisper('typing',this.user)
    },

    endWhisper(){
          Echo.join('chat')
         .whisper('typing',false)
    },

    sendMessage(){
      axios.post('/messages',{'message': this.newMessage})
      .then( res => {
        // this.messages.push(res.data)
        this.newMessage = '';
        this.endWhisper();

      })
    }
  }
};
</script>
