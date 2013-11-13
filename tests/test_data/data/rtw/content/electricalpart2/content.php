<!DOCTYPE html>
<html>
<head></head>
<body>
<div id="PageType">page_type</div>
	<div id="version">2.0</div>
	<div id="type">post</div>
    <div id="slug">electricalpart2</div>
	<div id="status">draft</div>
	<div id="creation_date">2012-11-03</div>
	<div id="published_date">2012-11-03</div>
	<div id="featured_image">/pics/Thumbnails/pict-1.jpg</div>
	<div id="last_modified_date">2012-11-03</div>
	<div id="trip">rtw</div>
	<div id="categories">vehicle,electrical</div>
	<div id="tags">one, two </div>
    <div id="title">Electrical, Part 2</div>
	<div id="abstract">
    <p>GXV Electrical System</p>
    </div>
<div id="main_content">
	<p>In the first post in this series I outlined the mission for the electrical system of our GXV
	expedition vehicle and described the electrical system as originally delivered.</p>
	<p>In this second post I will discuss the camper battery pack and the very important topic of
	(re)charging those batteries, and the (capacities and foibles) of the various devices that can charge
	those batteries.</p>
	<br><div class="agm-facts">
		<h2> Background - Battery Charging Regime</h2>
		<ul>
<li><p>The way a charger pushes a charge into a battery is by applying a voltage to the battery that is higher than
		the unloaded voltage the battery is exhibiting. So for example, consider a battery such that a voltmeter 
		would read 12.5 volts if applied to the battery terminals. In order to charge this battery a charger must apply a voltage
		greater that 12.5 and moreover the bigger the difference the faster the charging takes place. For
		Lifeline AGM batteries a charger must be able to apply a voltage starting at 13.3 and rising over time to 14.4.</p></li>
		<li>
<p>Most modern chargers for AGM and other types of batteries apply what is usually called a 
		three stage or three phase charging program.</p>
			<ul>
<li><p><em>Bulk</em>. The first charging stage is called <em>bulk</em>. In this stage the charger applies 
			a voltage to the battery that is high enough to lift the charge current to approximately the chargers maximum output.
			Over time the voltage has to keep rising to maintain the high charge current. 
			When the charge voltage reaches the <em>absorption level</em> 
			(14.4 volts for Lifeline batteries) the charger moves into the <em>absorption</em> stage.
			</p></li>
			<li><p><em>Absorption</em>. In this stage the charger maintains the absorption voltage on the battery. Over time 
			the charge current declines as the battery nears "fully charged". 
			In most chargers the length of absorption stage is determined by a timer. </p></li>
			<li><p><em>Float</em>.Following the absorption stage most chargers apply a float stage 
			where a periodic charging session at 13.3 volts is
			started.</p></li>
			</ul>
</li>
		</ul>
</div>
	
	<div class="agm-facts">
		<h2>AGM Battery Facts</h2>
		<p>After taking delivery of the truck I started reading up on the various electrical components 
		and AGM batteries generally. The first important facts I discovered are:</p>
		<ul>
<li><p>AGM batteries need to be fully recharge frequently. Repeated discharge and only partial recharge
		substantially reduces the life expectancy of AGM batteries.</p></li>
		<li><p>The only way to reliably determine if an AGM battery is fully charged is to measure the charging
		current when the charging devices is applying the absorption voltage to the battery. For the Lifeline 8DL
		batteries the absorption voltage is 14.4 volts and the <em>return current</em> (charge current level that
		indicates full charge) is 1% to 2% of the battery capacity. Thus our battery pack would be fully charged when
		the absorption voltage of 14.4 volts coincides with a charge current below 16.3amps (2%) or 7.65amps (1%).</p></li>
		</ul>
</div>
	<h2>Problem - Cannot tell when the batteries are "fully charged"</h2>
	<p>The Outback Mate2 device can display the voltage being applied to the battery pack <em>BUT</em> none of the
	devices measured the current going into the battery pack and hence there was no way of determining if the battery pack
	was fully charged. </p>
	<h2>Solution - Outback FLEXNet DC</h2>
	<p>Fortunately Outback sell a device called a FLEXNet that will (when suitably configured and installed)  measure the
	current going into or out of the camper battery pack. In fact it will do a lot more than that. It also keeps 
	track of AH in and AH out so that it can operate like a "gas gauge" showing both percentage charged and AH used since full.
	Further the FLEXNet can be programmed to recognize full-charged by applying the "1%-2% current" rule.</p>
	<p>With the FLEXnet installed I was able to get a much better sense of what was happening with the camper battery pack.</p>
	<div class="agm-facts">
		<h2>AGM Battery facts</h2>
		<ul>
<li><p>AGM batteries prefer to be charged fast (repeated slow recharging will somewhat shorten the battery's life). 
		The general recommendation is to select a charger that can deliver 
		a charging current level equal to between 20% and 50% of the battery capacity. For us that means an ideal
		charger would be able to deliver between 190 amps and 360 amps during the bulk charging stage.</p></li>
		<li><p>To charge a Lifeline AGM the charge must be able to deliver its charge at, at least 14.4 volts (the so called
		absorption voltage level). Indeed if the charger cannot reach 14.4 volts it will not be able to take our AGM
		batteries to a "fully charged" state.</p></li>
		</ul>
</div>
	<h2>Problem - Charger Capacities</h2>
	<p>There is obviously a problem with charger capacities, at least when compared to the "general recommendation".</p>
	<ul>
<li><p>The Outback Inverter/Charger has a maximum charge current (as per the specs) of 125 amps; that is only about 16% 
	of the battery pack capacity. However that is not the end of the issue with the Outback inverter/charger. The specs
	on their website say max continuous charging current is 125 amps. The installation manual for the FX series devices
	says that (page 47) the max AC current input to the charging circuit is 16 AC Amps but that the default is 14 AC Amps.
	Using the Mate2 I have confirmed the FX is programmed to allow a maximum AC input to the charger of 14 AC Amps (the default).
	The mate controller will not allow me to increase this to the supposed 16 Amp maximum and Outback customer service
	has confirmed that 14 Amps is really the maximum. Consequence <em>the Outback VFX2812M is really only a 100 Amp charger</em>.</p></li>
	
	<li><p>The ChargeMaster is rated at 35 Amps maximum charging current; a mere 4.5% of the battery pack capacity.</p></li>
	
	<li><p>The FLEXMax 60 has a maximum output current (according to the specs) of 60 amps, but
	based on observation the solar panels and the charge controller seem to max out at about 20 amps on a good sunny day.
	Thats a mere 2.6% of the battery bank capacity.</p></li>
	
	<li><p>The truck alternator, via the diode battery isolator, provides negligible charging current and it does that below 13.5 volts
	and is therefore effectively of no value as a charging source. This observation will be explained more fully later.</p></li>
	</ul>
<p>Recall that the battery bank was increased in size during construction without a corresponding increase in charging 
	capacity. How do the chargers rate relative to the originally spec'd 420 AH battery bank. </p>
	<ul>
<li><p>VFX2812M - 22.6%</p></li>
	<li><p>ChargeMaster  - 8.3%</p></li>
	<li><p>Solar - 4.8%</p></li>
	</ul>
<p>Result - better; but still a long way from the general recommendations I found in the literature. </p>

</div>
</body>
</html>
