<script>var url_app = '<?=URL_APP?>';</script>
<script>

	var id_operador = '<?=$_SESSION['id_operador']?>';
	var serie = '<?=$_SESSION['serie']?>';
	var id_operador_unidad = '<?=$_SESSION['id_operador_unidad']?>';
	var token_session = '<?=$_SESSION['token']?>';
	var id_usuario = '<?=$_SESSION['id_usuario']?>';
	var domain = '<?=DOMAIN?>';
	
	var lat = 23.915941;
	var lon = -102.537345;
	var acc = 0;
	var globalBase = 'SB';
	
	var ably_api_key = '<?=ABLY_API_KEY?>';
	var ably_presence = '<?=ABLY_PRESENCE?>';
	
	var pusher_key= '<?=PUSHER_KEY?>';
	var pusher_presence = '<?=PUSHER_PRESENCE?>';
	
	var pubnub_publish= '<?=PUBNUB_PUBLISH?>';
	var pubnub_suscribe = '<?=PUBNUB_SUSCRIBE?>';
	var pubnub_presence = '<?=PUBNUB_PRESENCE?>';
	
	var csrf_token = 'MEAM';
	//var csrf_token = '<?=$token_cache?>';
	
</script>
<script type="text/javascript" src="<?=FW7?>libs/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="<?=FW7?>libs/framework7/dist/js/framework7.min.js"></script>
<script type="text/javascript" src="<?=FW7?>libs/swipebox/src/js/jquery.swipebox.min.js"></script>
<script type="text/javascript" src="<?=FW7?>libs/jquery-validation/dist/jquery.validate.min.js"></script>

<script type="text/javascript" src="<?=FW7?>assets/js/helpers.js?v=<?=$token_cache?>" async="async"></script>

<?php
if(SOCKET_PROVIDER == 'ABLY'){
?>
	<!--Ably-->
	<script lang="text/javascript" src="//cdn.ably.io/lib/ably.min.js"></script>
	<script type="text/javascript" src="<?=FW7?>assets/js/ws_ably.js?v=<?=$token_cache?>"></script>
<?php
}elseif(SOCKET_PROVIDER == 'PUSHER'){
?>
	<!--Pusher-->
	<script src="https://js.pusher.com/3.1/pusher.min.js"></script>
	<script type="text/javascript" src="<?=FW7?>assets/js/ws_pusher.js?v=<?=$token_cache?>"></script>
<?php
}elseif(SOCKET_PROVIDER == 'PUBNUB'){
?>
	<!--PubNub-->
	<script src="https://cdn.pubnub.com/pubnub-3.15.2.min.js"></script>
	<script type="text/javascript" src="<?=FW7?>assets/js/ws_pubnub.js?v=<?=$token_cache?>"></script>
<?php
}
?>
<script>
var mvhc = <?=$mvhc?>;
</script>


<script type="text/javascript" src="<?=FW7?>assets/js/app.js?v=<?=$token_cache?>" async="async"></script>
<script type="text/javascript" src="<?=FW7?>assets/js/indexeddb.js?v=<?=$token_cache?>" async="async"></script>
<script type="text/javascript" src="<?=FW7?>assets/js/intervals.js?v=<?=$token_cache?>" async="async"></script>



<audio id="timbre" name="timbre" src="<?=FW7?>assets/audio/timbre.mp3?v=<?=$token_cache?>" preload="auto" loop></audio>
<audio id="cordonSound" name="cordonSound" src="<?=FW7?>assets/audio/cordon.mp3?v=<?=$token_cache?>" preload="auto"></audio>
<audio id="rideSound" name="rideSound" src="<?=FW7?>assets/audio/ride.mp3?v=<?=$token_cache?>" preload="auto" loop></audio>

<img src="<?=FW7?>assets/img/driver_green.svg" style="display:none;">
<img src="<?=FW7?>assets/img/driver_black.svg" style="display:none;">
<img src="<?=FW7?>assets/img/driver_red.svg" style="display:none;">
<img src="<?=FW7?>assets/img/driver_orange.svg" style="display:none;">
<img src="<?=FW7?>assets/img/driver_blue.svg" style="display:none;">
<img src="<?=FW7?>assets/img/driver_white.svg" style="display:none;">
<script>
var timbre = document.getElementsByTagName("audio")[0];
var cordonSound = document.getElementsByTagName("audio")[1];
var rideSound = document.getElementsByTagName("audio")[2];
</script>