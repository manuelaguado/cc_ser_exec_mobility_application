<script>
	
	var url_app = '<?=URL_APP?>';
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
	var mvhc = <?=$mvhc?>;
	
	var ably_api_key = '<?=ABLY_API_KEY?>';
	var ably_presence = '<?=ABLY_PRESENCE?>';
	
	var pusher_key= '<?=PUSHER_KEY?>';
	var pusher_presence = '<?=PUSHER_PRESENCE?>';
	
	var pubnub_publish= '<?=PUBNUB_PUBLISH?>';
	var pubnub_suscribe = '<?=PUBNUB_SUSCRIBE?>';
	var pubnub_presence = '<?=PUBNUB_PRESENCE?>';
	var csrf_token = '<?=$token_cache?>';
	
	<?php $new_version = '?v='.$token_cache; ?>
	
</script>
<script type="text/javascript" src="<?=FW7?>libs/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="<?=FW7?>libs/framework7/dist/js/framework7.min.js"></script>
<script type="text/javascript" src="<?=FW7?>libs/swipebox/src/js/jquery.swipebox.min.js"></script>
<script type="text/javascript" src="<?=FW7?>libs/jquery-validation/dist/jquery.validate.min.js"></script>

<--<script type="text/javascript" src="<?=FW7?>assets/js/helpers.js<?=$new_version?>" ></script>
<script type="text/javascript" src="<?=FW7?>assets/js/app.js<?=$new_version?>" ></script>
<script type="text/javascript" src="<?=FW7?>assets/js/indexeddb.js<?=$new_version?>" ></script>
<script type="text/javascript" src="<?=FW7?>assets/js/intervals.js<?=$new_version?>" ></script>-->

<audio id="timbre" name="timbre" src="<?=FW7?>assets/audio/timbre.mp3<?=$new_version?>" preload="auto" loop></audio>
<audio id="cordonSound" name="cordonSound" src="<?=FW7?>assets/audio/cordon.mp3<?=$new_version?>" preload="auto"></audio>
<audio id="rideSound" name="rideSound" src="<?=FW7?>assets/audio/ride.mp3<?=$new_version?>" preload="auto" loop></audio>

<img src="<?=FW7?>assets/img/driver-green.svg" style="display:none;">
<img src="<?=FW7?>assets/img/driver-black.svg" style="display:none;">
<img src="<?=FW7?>assets/img/driver-red.svg" style="display:none;">
<img src="<?=FW7?>assets/img/driver-orange.svg" style="display:none;">
<img src="<?=FW7?>assets/img/driver-blue.svg" style="display:none;">
<img src="<?=FW7?>assets/img/driver-white.svg" style="display:none;">
<script>
var timbre = document.getElementsByTagName("audio")[0];
var cordonSound = document.getElementsByTagName("audio")[1];
var rideSound = document.getElementsByTagName("audio")[2];
</script>

<?php
if(SOCKET_PROVIDER == 'ABLY'){
?>
	<!--Ably-->
	<script lang="text/javascript" src="//cdn.ably.io/lib/ably.min.js"></script>
	<script type="text/javascript" src="<?=FW7?>assets/js/ws_ably.js<?=$new_version?>"></script>
<?php
}elseif(SOCKET_PROVIDER == 'PUSHER'){
?>
	<!--Pusher-->
	<script src="https://js.pusher.com/3.1/pusher.min.js" ></script>
	<script type="text/javascript" src="<?=FW7?>assets/js/ws_pusher.js<?=$new_version?>" ></script>
<?php
}elseif(SOCKET_PROVIDER == 'PUBNUB'){
?>
	<!--PubNub-->
	<script src="https://cdn.pubnub.com/pubnub-3.15.2.min.js"></script>
	<script type="text/javascript" src="<?=FW7?>assets/js/ws_pubnub.js<?=$new_version?>"></script>
<?php
}
?>
<script id="libsper"></script>
<script>
function fhelperslib(){
	var helperslib = document.createElement("script");
	helperslib.src = '<?=FW7?>assets/js/helpers.js<?=$new_version?>';
	(document.body ? document.body : document.getElementsByTagName("head")[0]).appendChild(helperslib);
}
function fapplib(){
	var applib = document.createElement("script");
	applib.src = '<?=FW7?>assets/js/app.js<?=$new_version?>';
	(document.body ? document.body : document.getElementsByTagName("head")[0]).appendChild(applib);
}
function findexeddblib(){
	var indexeddblib = document.createElement("script");
	indexeddblib.src = '<?=FW7?>assets/js/indexeddb.js<?=$new_version?>';
	(document.body ? document.body : document.getElementsByTagName("head")[0]).appendChild(indexeddblib);
}
function fintervalslib(){
	var intervalslib = document.createElement("script");
	intervalslib.src = '<?=FW7?>assets/js/intervals.js<?=$new_version?>';
	(document.body ? document.body : document.getElementsByTagName("head")[0]).appendChild(intervalslib);
}
if(typeof  helpersLoaded == undefined){fhelperslib();}
if(typeof  appLoaded == undefined){fapplib();}
if(typeof  indexeddbLoaded == undefined){findexeddblib();}
if(typeof  intervalsLoaded == undefined){fintervalslib();}
</script>