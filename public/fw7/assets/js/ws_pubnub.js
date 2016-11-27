var WsPubNub = PUBNUB.init({
	keepalive: 30,
	publish_key: pubnub_publish,
	subscribe_key: pubnub_suscribe,
	uuid: ''+id_operador+'',
	ssl: true,
	error: function (error) {
        console.log('Error:', error);
    }
});

WsPubNub.subscribe({
    channel: 'gps'+id_operador+'',
    message: function(m){
		var convert_json = JSON.stringify(m);
		gps_ok(convert_json);
	}
});
WsPubNub.subscribe({
    channel: 'sync'+id_operador+'',
    message: function(m){
		var convert_json = JSON.stringify(m);
		sync_ok(convert_json);
	}
});
WsPubNub.subscribe({
    channel: 'ride'+id_operador+'',
    message: function(m){
		var convert_json = JSON.stringify(m);
		ride_ok(convert_json);
	}
});
WsPubNub.subscribe({
    channel: 'broadcast',
    message: function(m){
		var convert_json = JSON.stringify(m);
		broadcastPlay(convert_json);
	}
});
WsPubNub.subscribe({
    channel: pubnub_presence,
	restore : true, 
    disconnect : function() {
		document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink" style="font-weight: bold; color:red !important"></span>';	
	},
	reconnect : function() {
		document.getElementById("infolink").innerHTML = '<span class="kkicon icon-link" style="font-weight: bold; color:#03A388 !important"></span>';
	},
    connect : function() {
		document.getElementById("infolink").innerHTML = '<span class="kkicon icon-link" style="font-weight: bold; color:#03A388 !important"></span>';
	}
});

