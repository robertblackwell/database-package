<!DOCTYPE html>
<html>
<head></head>
<body>
<div id="PageType">page_type</div>
	<div id="version">2.0</div>
	<div id="type">post</div>
    <div id="slug">electricalpart1</div>
	<div id="status">draft</div>
	<div id="creation_date">2012-11-01</div>
	<div id="published_date">2012-11-01</div>
	<div id="featured_image">/pics/Thumbnails/pict-1.jpg</div>
	<div id="last_modified_date">2012-11-01</div>
	<div id="trip">rtw</div>
	<div id="categories">vehicle,electrical,testcategory</div>
	<div id="tags">one, two </div>
    <div id="title">Electrical, Part 1</div>
	<div id="abstract">
    <p>GXV Electrical system</p>
    </div>
<div id="main_content">

	<p>This is the first of a series of posts related to the electric system in our GXV expedition vehicle.
	My aim in this series of articles/posts is to outline the original mission of the electrical system, analyze how well
	the originally supplied equipment meet that mission, describe what modifications and upgrades I have made and what
	lessons I have learned, and what problems (if any) are still outstanding.</p> 
	<p>In this first post I will discuss the mission and original equipment.</p>
	<h2>Mission</h2>
	<p>Right from the start our GXV Expedition Vehicle was intended for an "around the world" mission. In
	terms of the electrical system this meant: </p>
	<ul>
<li><p>Operate for extended periods (measured in months) without shore power.</p></li>
	<li><p>When shore power is available be able to use that power regardless of voltage or frequency. 
	Thus the system was required to be able to use both 110V/60Hz and 230V/50Hz shore power.</p></li>
	<li><p>Selectively limit the load placed  on the shore power supply. In South America we were often plugged into shore power
	where the maximum available current draw was limited to 5-10 amps. We also understood from
	other travelers that such a shore power limit is common in Europe. Hence the system was required to
	provide a mechanism for limiting the load placed on the shore power supply.</p></li>
	</ul>
<h2>The initial solution</h2>
	<h3>Components</h3>
	<p>The electrical system as originally delivered consisted of the following elements.</p>
	<ul>
<li><p>A high capacity battery pack for powering camper electrical requirements. Initially this was spec'd as 2xLifeline 4DL
	AGM for a total capacity of 420 AH. During construction this was upgraded (at GXV's recommendation) to 3xLifeline 8DL AGMs
	for a total capacity of 3x255 or 765 AH.</p></li>
	<li><p>An OutbackPower <em>VFX28112M</em> (110V/60Hz) inverter-charger, which provided  charger, inverter and automatic AC transfer switch
	functions. 
	The Outback spec sheet claimed that the inverter would deliver 2800watts of AC (110V/60Hz) power for camper appliances,
	and 125DC Amps of charging current for the house/camper battery pack. </p></li>
	<li><p>Two 180 watt solar panels,</p></li>
	<li><p>An Outback FLEXMax 60 solar charge controller </p></li>
	<li><p>An Outback <em>Hub</em> device which connects and provides communications between all Outback products.</p></li>
	<li><p>A <em>Mate2</em> system controller from Outback that provides central control of all Outback products.</p></li>
	<li><p>A <em>ChargeMaster 12/35-3</em> universal battery charger from <a href="http://www.mastervolt.com">Mastervolt</a>. 
		This charger will accept
	any AC input in the range 90-265 Volts and 45-65Hz and hence can be plugged into a shore power source
	anywhere in the world.</p></li>
	<li><p>A diode battery isolator that provided a one-way DC charging path from the truck alternator/starting batteries
	to the camper battery pack when the truck ignition is on. The diode function prevented the truck starting process
	drawing current from the camper battery pack.</p></li>
	<li><p>Two external plugs for shore power connection; one designated for 110V/60Hz sources and the other for
	230V/50Hz.</p></li>
	<li><p>An Onan QD3200 diesel generator that could be selected as the 110V/60Hz AC source.</p></li>
	</ul>
<h2>How it works </h2>
	<p>The Outback inverter/charger is connected directly to the batteries and all DC loads (lights, refrigerator, exhaust fan, 
		12 volt accessory plugs) are connected via a DC breaker panel.</p>
	<p>All AC loads (microwave oven, coffee maker, 110 volt sockets) <em>except the camper air conditioner</em> are
	supplied by the output from the Outback inverter/transfer switch.   </p>
	<p>The camper air conditioner is powered directly by 110V/60Hz from either shore power or the onboard generator.</p>
	<p>The expectation was that the camper battery pack would be (re)charged from one of:</p>
	<ul>
<li><p>The Outback inverter charger when 110V/60Hz shore power is available</p></li>
	<li><p>The Outback inverter charger when the generator is running</p></li>
	<li><p>The solar panels via the Outback FLEXMax 60 charge controller during daylight hours</p></li>
	<li><p>The truck alternator via the diode battery isolator when the truck engine is running</p></li>
	<li><p>The ChargeMaster when 230V/50Hz (or more accurately non 110V/60Hz) shore power is available.</p></li>
	</ul>
</div>
</body>
</html>
