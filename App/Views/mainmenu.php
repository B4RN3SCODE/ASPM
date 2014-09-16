<?php
$MENUUSERNAME = (isset($_SESSION) && isset($_SESSION["User"])) ? $_SESSION["User"]->getUserName() : "Home...";
echo <<< MNMENU
<div class="navbar"><div class="linkUser">
<a href="#" id="lnkUsrHomeIco"><img alt="Site Home" src="http://localhost:8081/ArborSolutionsProjectManagement/public/img/ico/site.home.png" id="homeIco" />${MENUUSERNAME}</a>
</div><div class="userMenu"><ul class="menuList"><li class="menuListTitle"><span class="menuGroupTitle">Links</span></li>
<li class="menuListItem"><a href="#" id="lnkLinksMsg">Messages</a></li>
<li class="menuListItem"><a href="#" id="lnkLinksTs">TimeSheets</a></li>
<li class="menuListItem"><a href="#" id="lnkLinksCompFeed">Company Feed</a></li>
<li class="menuListItem"><a href="#" id="lnkLinksUTools">User Tools</a></li>
<li class="menuListTitle"><span class="menuGroupTitle">Create</span></li>
<li class="menuListItem"><a href="#" id="lnkCreateEvent">Event</a></li>
<li class="menuListItem"><a href="#" id="lnkCreateTs">TimeSheets</a></li>
<li class="menuListItem"><a href="#" id="lnkCreateTaskProj">Task / Project</a></li>
<li class="menuListTitle"><span class="menuGroupTitle">Update</span></li>
<li class="menuListItem"><a href="#" id="lnkUpdateProfile">Profile</a></li>
<li class="menuListItem"><a href="#" id="lnkUpdateStat">Status</a></li>
<li class="menuListItem"><a href="#" id="lnkUpdateLoc">Location</a></li>
<li class="menuListItem"><a href="#" id="lnkUpdateTs">TimeSheet</a></li>
<li class="menuListItem"><a href="#" id="lnkUpdateTaskProj">Task / Project</a></li>
<li class="menuListTitle"><span class="menuGroupTitle">View</span></li>
<li class="menuListItem"><a href="#" id="lnkViewDoc">Documents</a></li>
<li class="menuListItem"><a href="#" id="lnkViewUsr">Users</a></li>
<li class="menuListItem"><a href="#" id="lnkViewSched">Calendar</a></li>
<li class="menuListItem"><a href="#" id="lnkViewEvent">Events</a></li></ul></div></div>
MNMENU;
?>
