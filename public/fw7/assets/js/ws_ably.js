var realtime;

var apiKey = ably_api_key,
	realtime = new Ably.Realtime({
		key: apiKey,
		suspendedRetryTimeout: 5000,
		clientId:'cid' + id_operador +''
	}),
    gpsChannel 		= realtime.channels.get('gps'+id_operador+''),
    syncChannel 	= realtime.channels.get('sync'+id_operador+''),
	rideChannel 	= realtime.channels.get('ride'+id_operador+''),
	bcChannel 		= realtime.channels.get('broadcast'),
    channels = [gpsChannel, syncChannel, rideChannel,bcChannel];

realtime.connection.on('connected', function() {
	document.getElementById("infolink").innerHTML = '<span class="kkicon icon-link" style="font-weight: bold; color:#03A388 !important"></span>';
	for (var channelName in realtime.channels.all) {
		realtime.channels.get(channelName).attach();
	}
	realtime.channels.get(ably_presence).presence.enter();
});
realtime.connection.on('disconnected', function() {
	document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink" style="font-weight: bold; color:#ffd400 !important"></span>';
})
realtime.connection.on('suspended', function() {
	document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink" style="font-weight: bold; color:red !important"></span>';
})
realtime.connection.on('connecting', function() {
	document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink blink_me" style="font-weight: bold; color:#FFFFFF !important"></span>';
})
realtime.connection.on('closed', function() {
	document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink" style="font-weight: bold; color:red !important"></span>';
	realtime.connection.connect();
})

$("body").on("click", "#infolink", function() {
	realtime.connection.close();
});

////////////////////////////////////////////////////////////////////////////////
gpsChannel.subscribe(function(message) {
	gps_ok(message.data);
});
////////////////////////////////////////////////////////////////////////////////
syncChannel.subscribe(function(message) {
	sync_ok(message.data);
});
////////////////////////////////////////////////////////////////////////////////
rideChannel.subscribe(function(message) {
	ride_ok(message.data);
});
////////////////////////////////////////////////////////////////////////////////
bcChannel.subscribe(function(message) {
	broadcastPlay(message.data);
});