<?php 

	//setting the variables
	$sid = session_id();
	$ip=$_SERVER['REMOTE_ADDR']; 
	$mytime = '1236769021';
	$sesspage = 'index.php';
	$browserdetail = $_SERVER['HTTP_USER_AGENT'];
	
	$con = sfContext::getInstance()->getDatabaseConnection('v2bb');

	$verifyQuery = "select * from phpbb_sessions where session_id='".$sid."'";
	$verifyRslt = $con->executeQuery($verifyQuery);
	$vFlag=0;
	while($verifyRslt->next()){
		$vFlag = 1;
	}
	if($vFlag == 0){
		$sessionInsQuery = "insert into phpbb_sessions (session_id, session_user_id, session_last_visit, session_start, session_time, session_ip, session_page, session_viewonline, session_browser) values ('".$sid."', '53', '1236769021', '1236769021', '1236769021', '".$ip."', 'index.php', '1', '".$browserdetail."' )";
		$sessionRslt = $con->executeQuery($sessionInsQuery);
		$cookiePrefixQuery = "select config_value as cv from phpbb_config where config_name = 'cookie_name'";
		$cPrefixRslt = $con->executeQuery($cookiePrefixQuery);
		while($cPrefixRslt->next()){
			$cPrefix = $cPrefixRslt->getString('cv'); 
		}
		$cookieExpiry = time()+60*60*24*15;
		sfContext::getInstance()->getResponse()->setCookie($cPrefix.'_sid', $sid, 0, '/');
		sfContext::getInstance()->getResponse()->setCookie($cPrefix.'_k', '.', 0, '/');
		sfContext::getInstance()->getResponse()->setCookie($cPrefix.'_u', '53', 0, '/');
		sfContext::getInstance()->getResponse()->setCookie('style_cookie', 'null', 0, '/');
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<link rel="shortcut icon" href="/favicon.ico" />
</head>


<body onload="loadmenu()">
<noscript>
<center>
<div id="noScript"><img src="/images/iconWarning.gif" /> JavaScript must
be enabled in order for you to use Voda.<br />
However, it seems JavaScript is either disabled or not supported by your
browser. <br />
To use Voda, enable JavaScript by changing your browser options.<br />
</div>
</center>
</noscript>

<div class="container">
<div class="mainDiv">
<div class="headerPart">
<div class="logo">ITBHUGlobal.org</div>


<!--<div class="nameDiv"><img src="/images/nameLogo.gif" /></div>
--><!--<div class="siteMap">
<div class="siteIcon"><img src="/images/sitemap.gif" /></div>
<div class="siteTxtDiv"><a href="/home/sitemap.html" class="siteTxt">Sitemap</a></div>
</div>
--></div>

<div style="border: 1px solid #000;">
<div id="mainMenu" class="mainMenu">
<ul>
    <li><a class="submainMenu" href="http://www.itbhuglobal.org">IBGAA</a>
        <ul>
            <li><a href="http://www.itbhuglobal.org">Home</a></li>

            <li><a href="/org/archives/2008/07/organizational_structure.php">Scope and Structure</a></li>
            <li><a href="/org/archives/2008/07/board_of_directors.php">Board of the Org</a></li>
            <li><a href="/org/archives/2008/08/organization_bylaws.php">Bylaws</a></li>
            <li><a href="/org/">The People</a></li>
        </ul>
    </li>
    <li><a>|</a></li>

    <li><a class="submainMenu" href="http://www.itbhuglobal.org/register/">Connect</a>
        <ul>
            <li><a href="https://www.itbhuglobal.org/register/">Register Yourself</a></li>
            <li><a href="https://www.itbhuglobal.org/register/profile/?action=viewProfile&amp;edit=personal ">Update Your Profile</a></li>
    <li><a href="/chapters/archives/000239.html">Feedback</a></li>
            <li style="border-bottom: 1px solid #C30;"><a href="https://www.itbhuglobal.org/register/profile/?action=database&amp;mode=search ">Search Alumni Database</a></li>

            <li style="border-bottom: 1px solid #C30;">
                <a href="/reunions/">Alumni Reunions</a></li>
             <li><a href="https://www.itbhuglobal.org/register/profile/?action=inviteAlumni&amp;edit=showForm ">Invite
Fellow Alumni</a></li>
            <li><a href="https://www.itbhuglobal.org/register/profile/?action=viewProfile&amp;edit=mailingList ">Alumni Mailing Lists</a></li>
            <li  style="border-bottom: 1px solid #C30;">
                <a href="/chapters/#YahooGroupsListing ">Alumni Yahoo! Groups</a>
            </li>

            <li><a href="/coming-soon.php?feature=Class Notes">Class Notes</a></li>
        </ul>
    </li>

    <li><a>|</a></li>

    <li><a class="submainMenu" href="http://www.itbhuglobal.org/donate/">Get Involved</a>
        <ul>

            <li><a href="/projects/">Projects</a></li>
            <li><a href="/donate/">Donate</a></li>
            <li><a href="/volunteer/">Volunteer</a></li>
            <li><a href="/coming-soon.php?feature=Help%20the%20TPO">
                      Help the TPO</a></li>
            <li><a href="/iit/">IIT Project</a></li>

        </ul>
    </li>
    <li><a>|</a></li>

    <li><a href="/chapters/" class="submainMenu">Chapters</a>
        <ul>
            <li><a href="/chapters/">Basic Information </a></li>
            <li><a href="/events/">Chapters Events</a></li>

            <li><a class="submainMenu" href="http://www.itbhuglobal.org/chapters/geo/">Geographic</a>
                <ul>
                    <li><a href="/chapters/geo/bangalore/">Bangalore</a></li>
                    <li><a href="/chapters/geo/chennai/">Chennai</a></li>
                    <li><a href="/chapters/geo/delhi/">Delhi</a></li>
                    <li><a href="/chapters/geo/hyderabad/">Hyderabad</a></li>

                    <li><a href="/chapters/geo/pune/">Pune</a></li>
                    <li><a href="/chapters/geo/newjersey/">New Jersey</a></li>
                    <li><a href="/chapters/geo/siliconvalley/">Silicon Valley</a></li>
                    <li><a href="/chapters/geo/southerncal/">Southern California</a></li>
                    <li><a href="/chapters/geo/asiapac/">Asia Pacific</a></li>
                    <li><a href="/chapters/geo/singapore/">Singapore</a></li>

                </ul>
            </li>
            <li><a class="submainMenu" href="http://www.itbhuglobal.org/chapters/dept/">Branches</a>
                <ul id="branchchapterlist">
                    <li><a href="/chapters/dept/cse/">CSE</a></li>
                    <li><a href="/chapters/dept/ece/">ECE</a></li>
                    <li><a href="/chapters/dept/eee/">EEE</a></li>

                    <li><a href="/chapters/dept/mec/">MEC</a></li>
                    <li><a href="/chapters/dept/min/">MIN</a></li>
               </ul>
             </li>
             <li><a class="submainMenu" href="http://www.itbhuglobal.org/chapters/class/">Classes</a>
                 <ul>
                     <li><a href="http://reunion61.googlepages.com/">1961</a></li>

                     <li><a href="http://www.bhuit74.net/">1974</a></li>
                 </ul>
             </li>
             <li><a href="https://www.itbhuglobal.org/register/profile/?action=database&amp;mode=chapter ">All Geographical Chapters</a></li>
        </ul>
    </li>
    <li><a>|</a></li>

    <li><a class="submainMenu" href="http://www.itbhuglobal.org/forum/viewforum.php">Career Resources</a>
        <ul>
            <li><a href="/fromit/archives/2003/09/receiving_transcripts_from_the.html">Get Transcripts</a></li>
            <li><a href="/forum/viewforum.php?f=9">Job Postings</a></li>
            <li><a
href="/coming-soon.php?feature=Higher Studies: Engineering (Outside India) ">Engineering
(Outside India) </a></li>
            <li><a href="/coming-soon.php?feature=Higher Studies: Engineering (India) ">Engineering
(India) </a></li>

            <li>
                 <a href="/forum/viewforum.php?f=30">
                      MBA (Outside India) </a></li>
             <li>
                  <a href="/coming-soon.php?feature=Higher Studies: MBA (India) ">
                      MBA (India) </a></li>
             <li><a href="/coming-soon.php?feature=Civil Services ">Civil
Services</a></li>

        </ul>
    </li>
    <li><a>|</a></li>

    <li><a class="submainMenu" href="http://www.itbhu.ac.in/">The Institute</a>
        <ul>
            <li><a href="http://itbhu.ac.in/">About the Institute</a></li>
            <li><a href="/reunions/">Alumni Reunions</a></li>

            <li><a href="/fromit/">Institute News</a></li>
            <li><a href="http://www.itbhu.ac.in/students/kashiutkarsh/ ">Kashi Utkarsh</a></li>
            <li><a href="/chapters/students/tac/ ">Technical Activity Center </a></li>
            <li><a href="/students/spicmacay/ ">SPICMACAY </a></li>
            <li><a href="/coming-soon.php?feature=Model Developement Center ">Model Developement Center</a></li>
            <li><a href="/fromit/reverberations.html" > Reverberations : College Magazine</a></li>

        </ul>
    </li>

    <li><a>|</a></li>

    <li><a href="/forum/">Forums</a></li>
    <li><a>|</a></li>

    <li><a href="/reunions/">Alumni Reunions</a></li>

    <li><a>|</a></li>

    <li><a href="/chronicle/">ITBHU Chronicle</a></li>

    <li><a>|</a></li>

    <li><a href="/gallery/">Photo Gallery</a></li>
</ul>

</div>
</div>
<!--<div class="menulist"><?php if (has_slot('header')):?><?php include_slot('header') ?><?php endif;?>
</div>-->
<div class="content">
	<div class="leftmenu">
	<?php if(!$sf_user->getAttribute('username')):

	?>
		<a href="http://www.itbhu.ac.in" target="_blank"><img src="/images/itbhu-logobig.gif"/></a>
		<div class="leftImageBottom">Constituent <a href="http://www.jee.iitb.ac.in/" target="_blank">IIT-JEE</a></div>
	<?php else: ?>
		<div class="leftmenucontent">
			<?php if (has_slot('leftmenu')):?><?php include_slot('leftmenu') ?><?php endif;?>
		</div>
	<?php endif; ?>
	</div>

	<div class="contentMain">
		<?php echo $sf_data->getRaw('sf_content') ?>
	</div>
	
</div>

<div class="footermark">
<?php echo link_to('HOME','home/admin'); ?>&nbsp;
<?php echo link_to('Search','home/searchform'); ?>
<div class="footBg"><img src="/images/spacer.gif" /></div>
</div>

</div>
<div class="footer">
<div class="footertext">Copyright &copy; 2008 - 2009 by ITBHU Global Alumni Association, Institute of Technology, Banaras Hindu University, Varanasi 221005 INDIA </div>
</div>
</div>
</div>
</body>
</html>
