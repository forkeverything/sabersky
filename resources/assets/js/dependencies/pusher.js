
// listen to pusher events
var pusher = new Pusher($('meta[name="pusher-key"]').attr('content'), {
    cluster: 'ap1',
    encrypted: true
});
var pusherChannel = pusher.subscribe('user.' + $('meta[name="user-id"]').attr('content'));