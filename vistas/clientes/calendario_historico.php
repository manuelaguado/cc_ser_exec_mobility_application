
		<div style='width:100%; height:600px;'>
			<div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
				<div class="dhx_cal_navline">
					<div class="dhx_cal_prev_button">&nbsp;</div>
					<div class="dhx_cal_next_button">&nbsp;</div>
					<div class="dhx_cal_today_button"></div>
					<div class="dhx_cal_date"></div>
					<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
					<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
					<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
				</div>
				<div class="dhx_cal_header">
				</div>
				<div class="dhx_cal_data">
				</div>		
			</div>
		</div>

<script type="text/javascript" charset="utf-8">
	function init() {
		
		scheduler.config.xml_date="%Y-%m-%d %H:%i";
		scheduler.config.prevent_cache = true;
		
		scheduler.config.lightbox.sections=[	
			{name:"descripción", height:130, map_to:"text", type:"textarea" , focus:true},
			{name:"location", height:43, type:"textarea", map_to:"details" },
			{name:"time", height:72, type:"time", map_to:"auto"}
		];
		scheduler.config.first_hour = 4;
		scheduler.config.limit_time_select = true;
		scheduler.locale.labels.section_location="Location";	

		
		scheduler.init('scheduler_here',new Date(2015,09,14),"month");
		scheduler.setLoadMode("month")
		scheduler.load("clientes/calendario_historico_data/<?=$id_cliente?>/");
		
		var dp = new dataProcessor("clientes/calendario_historico_data/<?=$id_cliente?>/");
		dp.init(scheduler);

	}
	init();
</script>	