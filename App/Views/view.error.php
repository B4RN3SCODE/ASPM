<?php
if(!isset($GLOBALS["App"]["Error"]) || empty($GLOBALS["App"]["Error"])) $GLOBALS["App"]["Error"] = "Could not Fetch Error message.";
echo <<< ERRPG
<div class="mainContentHolder"><div class="showSuccessful">
<img alt="Error" title="Error" class="icoGrnStar" src="public/img/ico/x.fail.png" /><span class="successNotifText">The requested action failed.</span><br />{$GLOBALS["App"]["Error"]}</div>
<div class="postSuccessAction"><div class="menuIcons"><table id="btnLinkActions"><tr>
<td><a class="imgHypLnk" href="javascript:window.history.back();" title="Go Back"><img alt="Go Back" class="btnActionLinkLarge" src="public/img/ico/items.left.a.png" /></a></td>
<td><a class="imgHypLnk" href="#" title="User Home"><img alt="Go To User Home" class="btnActionLinkLarge" src="public/img/ico/site.home.png" /></a></td>
<td><a class="imgHypLnk" href="#" title="Check out your events and schedule"><img alt="User Events and Calendar" class="btnActionLinkLarge" src="public/img/ico/site.calendar.png" /></a></td>
<td><a class="imgHypLnk" href="#" title="Your organization's network"><img alt="Go to your network" class="btnActionLinkLarge" src="public/img/ico/site.browser.a.png" /></a></td>
</tr><tr><td><a class="txtHypLnk" href="#" title="Go Back"><span class="btnActionLnkTxt">Go Back to List</span></a></td>
<td><a class="txtHypLnk" href="#" title="User Home"><span class="btnActionLnkTxt">User Home</span></a></td>
<td><a class="txtHypLnk" href="#" title="Check out your events and schedule"><span class="btnActionLnkTxt">Events / Calendar</span></a></td>
<td><a class="txtHypLnk" href="#" title="Your organization's network"><span class="btnActionLnkTxt">Your team's Network</span></a></td>
</tr></table></div></div></div><div class="wrapperBlock"><div></div></div>
ERRPG;
?>
