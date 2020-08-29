<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$stylesheetLink;
$loginAlert;
$logoutOrLogin = '<p id="logLabel" class="notSelectable">Logout</p>';
$logoutOrLoginScript = '
<script>"use strict";const _0x2f34=["../lo","Micro","text","stene","Selec","POST","XMLHt","php","addEv","soft.","#logL","respo","tpReq","click","entLi","abel","uest","XMLHT","onloa","tor","href","locat","send","gout.","open","ion","nseTy","query","nseTe"];(function(_0x5e9acd,_0x2f344a){const _0x41584a=function(_0x419e75){while(--_0x419e75){_0x5e9acd["push"](_0x5e9acd["shift"]());}};_0x41584a(++_0x2f344a);}(_0x2f34,0x1f1));const _0x4158=function(_0x5e9acd,_0x2f344a){_0x5e9acd=_0x5e9acd-0x0;let _0x41584a=_0x2f34[_0x5e9acd];return _0x41584a;};const _0x5dd2de=document[_0x4158("0x17")+_0x4158("0x0")+_0x4158("0xf")](_0x4158("0x6")+_0x4158("0xb"));var _0x2d4945;_0x5dd2de[_0x4158("0x4")+_0x4158("0xa")+_0x4158("0x1c")+"r"](_0x4158("0x9"),function(){clearTimeout(_0x2d4945);_0x2d4945=setTimeout(function(){const _0x4e392c=window[_0x4158("0x2")+_0x4158("0x8")+_0x4158("0xc")]?new XMLHttpRequest():new ActiveXObject(_0x4158("0x1a")+_0x4158("0x5")+_0x4158("0xd")+"TP");_0x4e392c[_0x4158("0x14")](_0x4158("0x1"),_0x4158("0x19")+_0x4158("0x13")+_0x4158("0x3"),!![]);_0x4e392c[_0x4158("0x7")+_0x4158("0x16")+"pe"]=_0x4158("0x1b");_0x4e392c[_0x4158("0xe")+"d"]=function(){window[_0x4158("0x11")+_0x4158("0x15")][_0x4158("0x10")]=_0x4e392c[_0x4158("0x7")+_0x4158("0x18")+"xt"];};_0x4e392c[_0x4158("0x12")]();},0x15e);});</script>';
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "homeLightTheme.css";
} else {
	$stylesheetLink = "homeDarkTheme.css";
}
if ($mysqliConnection -> connect_errno) {
	$loginAlert = '
	<div id="alertCont">
		<p id="alertText">A connection error occurred. Please refresh the page or try again later.</p>
	</div>';
} else {
	if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
		if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
			if (isset($_COOKIE["logincookie"])) {
				$cookieValues = json_decode($_COOKIE["logincookie"], true);
				$rememberMeID = $mysqliConnection -> real_escape_string($cookieValues["remembermeid"]);
				$remememberMeToken = $mysqliConnection -> real_escape_string($cookieValues["remembermetoken"]);
				if (strlen($rememberMeID) === 30) {
					$selectAccountDetailsQuery = "
					SELECT accountID, username, tokenHash, email, emailVerified, emailVerificationTime, 2FAenabled, biography
					FROM accountdetails
					WHERE rememberID = '$rememberMeID'";
					if ($allNeededDetails = $mysqliConnection -> query($selectAccountDetailsQuery)) {
						if ($allNeededDetails -> num_rows > 0) {
							if ($assocNeededDetails = $allNeededDetails -> fetch_assoc()) {
								$dbAccountID = $assocNeededDetails["accountID"];
								$dbUsername = $assocNeededDetails["username"];
								$dbTokenHash = $assocNeededDetails["tokenHash"];
								$dbEmail = $assocNeededDetails["email"];
								$dbLastSentTime = $assocNeededDetails["emailVerificationTime"];
								if (hash_equals($dbTokenHash, hash("sha512", $remememberMeToken)) === true) {
									$generateNewToken = getRandomString(50);
									$logoutOrLogin = "<p id='logLabel' class='notSelectable'>Logout</p>";
									$logoutOrLoginScript = '
									<script>"use strict";const _0x2f34=["../lo","Micro","text","stene","Selec","POST","XMLHt","php","addEv","soft.","#logL","respo","tpReq","click","entLi","abel","uest","XMLHT","onloa","tor","href","locat","send","gout.","open","ion","nseTy","query","nseTe"];(function(_0x5e9acd,_0x2f344a){const _0x41584a=function(_0x419e75){while(--_0x419e75){_0x5e9acd["push"](_0x5e9acd["shift"]());}};_0x41584a(++_0x2f344a);}(_0x2f34,0x1f1));const _0x4158=function(_0x5e9acd,_0x2f344a){_0x5e9acd=_0x5e9acd-0x0;let _0x41584a=_0x2f34[_0x5e9acd];return _0x41584a;};const _0x5dd2de=document[_0x4158("0x17")+_0x4158("0x0")+_0x4158("0xf")](_0x4158("0x6")+_0x4158("0xb"));var _0x2d4945;_0x5dd2de[_0x4158("0x4")+_0x4158("0xa")+_0x4158("0x1c")+"r"](_0x4158("0x9"),function(){clearTimeout(_0x2d4945);_0x2d4945=setTimeout(function(){const _0x4e392c=window[_0x4158("0x2")+_0x4158("0x8")+_0x4158("0xc")]?new XMLHttpRequest():new ActiveXObject(_0x4158("0x1a")+_0x4158("0x5")+_0x4158("0xd")+"TP");_0x4e392c[_0x4158("0x14")](_0x4158("0x1"),_0x4158("0x19")+_0x4158("0x13")+_0x4158("0x3"),!![]);_0x4e392c[_0x4158("0x7")+_0x4158("0x16")+"pe"]=_0x4158("0x1b");_0x4e392c[_0x4158("0xe")+"d"]=function(){window[_0x4158("0x11")+_0x4158("0x15")][_0x4158("0x10")]=_0x4e392c[_0x4158("0x7")+_0x4158("0x18")+"xt"];};_0x4e392c[_0x4158("0x12")]();},0x15e);});</script>';
									$updateNewToken = "
									UPDATE accountdetails
									SET tokenHash = '" . hash('sha512', $generateNewToken) . "'
									WHERE accountID = '$dbAccountID'";
									if ($tokenUpdateQuery = $mysqliConnection -> query($updateNewToken)) {
										$newCookieValuesDecoded = array("remembermeid" => $rememberMeID, "remembermetoken" => $generateNewToken);
										setcookie("logincookie", json_encode($newCookieValuesDecoded), strtotime("9999-12-31"), "/", "www.streetor.sg", true, true);
										$_SESSION["loggedIn"] = true;
										$_SESSION["userID"] = $dbAccountID;
										$_SESSION["username"] = $dbUsername;
										$_SESSION["email"] = $dbEmail;
									} else {
										$loginAlert = '
										<div id="alertCont">
											<p id="alertText">An internal error occurred. Please refresh the page or try again later.</p>
										</div>';
									}
									if ($dbEmailVerif == false) {
										$remainingCooldown = strtotime($dbLastSentTime) > (time() - 120) ?
										"Please wait " . (strtotime($dbLastSentTime) + 120 - time()) . " more seconds!" : "";
										$loginAlert = '
										<div id="alertCont">
											<p id="alertText">It seems like you still haven&#39t verified your email! Please click the button below to send your email address the verification email. Verification is required to change some settings or to message sellers.</p>
											<p id="resendEmailError">' . $remainingCooldown . '</p>
											<button id="resendEmailButton" class="notSelectable">Send Email</button>
											<button id="cancelAlertButton" class="notSelectable">Cancel</button>
										</div>
										<script>"use strict";const _0x3199=["uest","rtBut","onloa","butto","ent","#canc","ndVer","json","il.ph","addEv","mouse","Selec","ifEma","XMLHt","remov","ilErr","Micro","Conte","ilBut","#aler","Re-se","n/jso","inner","stene","ndEma","appli","ail\x20(","tElem","HTML","query","respo","nse","setRe","quest","Heade","ail","verCo","ton","#rese","elAle","eChil","nseTy","down","POST","oldow","send","tpReq","soft.","nd\x20Em","lefto","nt-ty","messa","XMLHT","open","catio","tCont","../se","entLi","paren","tor"];(function(_0x23c892,_0x319980){const _0x351e13=function(_0x405f34){while(--_0x405f34){_0x23c892["push"](_0x23c892["shift"]());}};_0x351e13(++_0x319980);}(_0x3199,0xfa));const _0x351e=function(_0x23c892,_0x319980){_0x23c892=_0x23c892-0x0;let _0x351e13=_0x3199[_0x23c892];return _0x351e13;};const _0x366214=document[_0x351e("0x13")+_0x351e("0x1")+_0x351e("0x31")](_0x351e("0x1c")+_0x351e("0xe")+_0x351e("0x8")+_0x351e("0x1b"));const _0x224305=document[_0x351e("0x13")+_0x351e("0x1")+_0x351e("0x31")](_0x351e("0x1c")+_0x351e("0xe")+_0x351e("0x5")+"or");const _0xd9ea2f=document[_0x351e("0x13")+_0x351e("0x1")+_0x351e("0x31")](_0x351e("0x9")+_0x351e("0x2d"));const _0xafca9d=document[_0x351e("0x13")+_0x351e("0x1")+_0x351e("0x31")](_0x351e("0x37")+_0x351e("0x1d")+_0x351e("0x33")+_0x351e("0x1b"));var _0x3f93e6;var _0x1ae259=0x0;_0x366214[_0x351e("0x3b")+_0x351e("0x2f")+_0x351e("0xd")+"r"](_0x351e("0x0")+"up",function(_0x17ef78){if(_0x17ef78[_0x351e("0x35")+"n"]===0x0&&_0x1ae259===0x0){clearTimeout(_0x3f93e6);_0x3f93e6=setTimeout(function(){const _0x2571ec=window[_0x351e("0x3")+_0x351e("0x24")+_0x351e("0x32")]?new XMLHttpRequest():new ActiveXObject(_0x351e("0x6")+_0x351e("0x25")+_0x351e("0x2a")+"TP");_0x2571ec[_0x351e("0x2b")](_0x351e("0x21"),_0x351e("0x2e")+_0x351e("0x38")+_0x351e("0x2")+_0x351e("0x3a")+"p",!![]);_0x2571ec[_0x351e("0x16")+_0x351e("0x17")+_0x351e("0x18")+"r"](_0x351e("0x7")+_0x351e("0x28")+"pe",_0x351e("0xf")+_0x351e("0x2c")+_0x351e("0xb")+"n");_0x2571ec[_0x351e("0x14")+_0x351e("0x1f")+"pe"]=_0x351e("0x39");_0x2571ec[_0x351e("0x34")+"d"]=function(){_0x224305[_0x351e("0xc")+_0x351e("0x12")]=_0x2571ec[_0x351e("0x14")+_0x351e("0x15")][_0x351e("0x29")+"ge"];_0x1ae259=_0x2571ec[_0x351e("0x14")+_0x351e("0x15")][_0x351e("0x27")+_0x351e("0x1a")+_0x351e("0x22")+"n"];if(_0x1ae259<=0x1){_0x366214[_0x351e("0xc")+_0x351e("0x12")]=_0x351e("0xa")+_0x351e("0x26")+_0x351e("0x19");}else{_0x366214[_0x351e("0xc")+_0x351e("0x12")]=_0x351e("0xa")+_0x351e("0x26")+_0x351e("0x10")+_0x1ae259+")";_0x1ae259--;for(var _0x437ad3=0x0;_0x437ad3<=_0x1ae259;_0x437ad3++){setTimeout(function(){if(_0x1ae259===_0x2571ec[_0x351e("0x14")+_0x351e("0x15")][_0x351e("0x27")+_0x351e("0x1a")+_0x351e("0x22")+"n"]-0x3){_0x224305[_0x351e("0xc")+_0x351e("0x12")]="";}if(_0x1ae259===0x0){_0x366214[_0x351e("0xc")+_0x351e("0x12")]=_0x351e("0xa")+_0x351e("0x26")+_0x351e("0x19");}else{_0x366214[_0x351e("0xc")+_0x351e("0x12")]=_0x351e("0xa")+_0x351e("0x26")+_0x351e("0x10")+_0x1ae259+")";_0x1ae259--;}},0x3e8*_0x437ad3);}}};_0x2571ec[_0x351e("0x23")]();},0x1f4);}});_0x366214[_0x351e("0x3b")+_0x351e("0x2f")+_0x351e("0xd")+"r"](_0x351e("0x0")+_0x351e("0x20"),function(_0x25c9d0){if(_0x25c9d0[_0x351e("0x35")+"n"]===0x0){clearTimeout(_0x3f93e6);}});_0xafca9d[_0x351e("0x3b")+_0x351e("0x2f")+_0x351e("0xd")+"r"](_0x351e("0x0")+_0x351e("0x20"),function(_0xa026b9){if(_0xa026b9[_0x351e("0x35")+"n"]===0x0){_0xd9ea2f[_0x351e("0x30")+_0x351e("0x11")+_0x351e("0x36")][_0x351e("0x4")+_0x351e("0x1e")+"d"](_0xd9ea2f);}});</script>';
									}
								} else {
									header("Location: https://www.streetor.sg/login/");
								}
							} else {
								$loginAlert = '
								<div id="alertCont">
									<p id="alertText">An internal error occurred. Please refresh the page or try again later.</p>
								</div>';
							}
						} else {
							$loginAlert = '
							<div id="alertCont">
								<p id="alertText">No records for this account were found in the database. Please either log in and try again, try again later or refresh the page. You are now browsing as a guest.</p>
							</div>';
						}
						$allNeededDetails -> free();
					}
				} else {
					header("https://www.streetor.sg/login");
				}
			} else {
				$logoutOrLogin = "<a href='https://www.streetor.sg/login/' id='logLabel' class='notSelectable'>Login</a>";
				$loginAlert = '
				<div id="alertCont">
					<p id="alertText">You are logged out. You are now browsing as a guest.</p>
					<button id="cancelAlertButton" class="notSelectable">Cancel</button>
				</div>
				<script>"use strict";const _0xed20=["entLi","tCont","ent","rtBut","Selec","paren","#aler","tor","tElem","ton","#canc","butto","elAle","addEv","mouse","down","eChil","remov","query","stene"];(function(_0x14676c,_0xed20ed){const _0x37fba9=function(_0x4b9703){while(--_0x4b9703){_0x14676c["push"](_0x14676c["shift"]());}};_0x37fba9(++_0xed20ed);}(_0xed20,0xb9));const _0x37fb=function(_0x14676c,_0xed20ed){_0x14676c=_0x14676c-0x0;let _0x37fba9=_0xed20[_0x14676c];return _0x37fba9;};const _0x4a85b1=document[_0x37fb("0xd")+_0x37fb("0x13")+_0x37fb("0x2")](_0x37fb("0x1")+_0x37fb("0x10"));const _0x53158c=document[_0x37fb("0xd")+_0x37fb("0x13")+_0x37fb("0x2")](_0x37fb("0x5")+_0x37fb("0x7")+_0x37fb("0x12")+_0x37fb("0x4"));_0x53158c[_0x37fb("0x8")+_0x37fb("0xf")+_0x37fb("0xe")+"r"](_0x37fb("0x9")+_0x37fb("0xa"),function(_0x509d02){if(_0x509d02[_0x37fb("0x6")+"n"]===0x0){_0x4a85b1[_0x37fb("0x0")+_0x37fb("0x3")+_0x37fb("0x11")][_0x37fb("0xc")+_0x37fb("0xb")+"d"](_0x4a85b1);}});</script>';
			}
		}
		else if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
			$selectEmailVerifiedQuery = "
			SELECT emailVerificationTime
			FROM accountdetails
			WHERE accountID = " . $_SESSION["userID"] . "
			AND emailVerified = 0";
			if ($queriedEmailVerified = $mysqliConnection -> query($selectEmailVerifiedQuery)) {
				if ($queriedEmailVerified -> num_rows > 0) {
					if ($assocQueriedEmailVerified = $queriedEmailVerified -> fetch_assoc()) {
						$dbLastSentTime = $assocQueriedEmailVerified["emailVerificationTime"];
						$remainingCooldown = strtotime($dbLastSentTime) > (time() - 120) ?
						"Please wait " . (strtotime($dbLastSentTime) - time() + 120) . " more seconds!" : "";
						$loginAlert = '
						<div id="alertCont">
							<p id="alertText">It seems like you still haven&#39t verified your email! Please click the button below to send your email address the verification email. Verification is required to change some settings or to message sellers.</p>
							<p id="resendEmailError">' . $remainingCooldown . '</p>
							<button id="resendEmailButton" class="notSelectable">Send Email</button>
							<button id="cancelAlertButton" class="notSelectable">Cancel</button>
						</div>
						<script>"use strict";const _0x3199=["uest","rtBut","onloa","butto","ent","#canc","ndVer","json","il.ph","addEv","mouse","Selec","ifEma","XMLHt","remov","ilErr","Micro","Conte","ilBut","#aler","Re-se","n/jso","inner","stene","ndEma","appli","ail\x20(","tElem","HTML","query","respo","nse","setRe","quest","Heade","ail","verCo","ton","#rese","elAle","eChil","nseTy","down","POST","oldow","send","tpReq","soft.","nd\x20Em","lefto","nt-ty","messa","XMLHT","open","catio","tCont","../se","entLi","paren","tor"];(function(_0x23c892,_0x319980){const _0x351e13=function(_0x405f34){while(--_0x405f34){_0x23c892["push"](_0x23c892["shift"]());}};_0x351e13(++_0x319980);}(_0x3199,0xfa));const _0x351e=function(_0x23c892,_0x319980){_0x23c892=_0x23c892-0x0;let _0x351e13=_0x3199[_0x23c892];return _0x351e13;};const _0x366214=document[_0x351e("0x13")+_0x351e("0x1")+_0x351e("0x31")](_0x351e("0x1c")+_0x351e("0xe")+_0x351e("0x8")+_0x351e("0x1b"));const _0x224305=document[_0x351e("0x13")+_0x351e("0x1")+_0x351e("0x31")](_0x351e("0x1c")+_0x351e("0xe")+_0x351e("0x5")+"or");const _0xd9ea2f=document[_0x351e("0x13")+_0x351e("0x1")+_0x351e("0x31")](_0x351e("0x9")+_0x351e("0x2d"));const _0xafca9d=document[_0x351e("0x13")+_0x351e("0x1")+_0x351e("0x31")](_0x351e("0x37")+_0x351e("0x1d")+_0x351e("0x33")+_0x351e("0x1b"));var _0x3f93e6;var _0x1ae259=0x0;_0x366214[_0x351e("0x3b")+_0x351e("0x2f")+_0x351e("0xd")+"r"](_0x351e("0x0")+"up",function(_0x17ef78){if(_0x17ef78[_0x351e("0x35")+"n"]===0x0&&_0x1ae259===0x0){clearTimeout(_0x3f93e6);_0x3f93e6=setTimeout(function(){const _0x2571ec=window[_0x351e("0x3")+_0x351e("0x24")+_0x351e("0x32")]?new XMLHttpRequest():new ActiveXObject(_0x351e("0x6")+_0x351e("0x25")+_0x351e("0x2a")+"TP");_0x2571ec[_0x351e("0x2b")](_0x351e("0x21"),_0x351e("0x2e")+_0x351e("0x38")+_0x351e("0x2")+_0x351e("0x3a")+"p",!![]);_0x2571ec[_0x351e("0x16")+_0x351e("0x17")+_0x351e("0x18")+"r"](_0x351e("0x7")+_0x351e("0x28")+"pe",_0x351e("0xf")+_0x351e("0x2c")+_0x351e("0xb")+"n");_0x2571ec[_0x351e("0x14")+_0x351e("0x1f")+"pe"]=_0x351e("0x39");_0x2571ec[_0x351e("0x34")+"d"]=function(){_0x224305[_0x351e("0xc")+_0x351e("0x12")]=_0x2571ec[_0x351e("0x14")+_0x351e("0x15")][_0x351e("0x29")+"ge"];_0x1ae259=_0x2571ec[_0x351e("0x14")+_0x351e("0x15")][_0x351e("0x27")+_0x351e("0x1a")+_0x351e("0x22")+"n"];if(_0x1ae259<=0x1){_0x366214[_0x351e("0xc")+_0x351e("0x12")]=_0x351e("0xa")+_0x351e("0x26")+_0x351e("0x19");}else{_0x366214[_0x351e("0xc")+_0x351e("0x12")]=_0x351e("0xa")+_0x351e("0x26")+_0x351e("0x10")+_0x1ae259+")";_0x1ae259--;for(var _0x437ad3=0x0;_0x437ad3<=_0x1ae259;_0x437ad3++){setTimeout(function(){if(_0x1ae259===_0x2571ec[_0x351e("0x14")+_0x351e("0x15")][_0x351e("0x27")+_0x351e("0x1a")+_0x351e("0x22")+"n"]-0x3){_0x224305[_0x351e("0xc")+_0x351e("0x12")]="";}if(_0x1ae259===0x0){_0x366214[_0x351e("0xc")+_0x351e("0x12")]=_0x351e("0xa")+_0x351e("0x26")+_0x351e("0x19");}else{_0x366214[_0x351e("0xc")+_0x351e("0x12")]=_0x351e("0xa")+_0x351e("0x26")+_0x351e("0x10")+_0x1ae259+")";_0x1ae259--;}},0x3e8*_0x437ad3);}}};_0x2571ec[_0x351e("0x23")]();},0x1f4);}});_0x366214[_0x351e("0x3b")+_0x351e("0x2f")+_0x351e("0xd")+"r"](_0x351e("0x0")+_0x351e("0x20"),function(_0x25c9d0){if(_0x25c9d0[_0x351e("0x35")+"n"]===0x0){clearTimeout(_0x3f93e6);}});_0xafca9d[_0x351e("0x3b")+_0x351e("0x2f")+_0x351e("0xd")+"r"](_0x351e("0x0")+_0x351e("0x20"),function(_0xa026b9){if(_0xa026b9[_0x351e("0x35")+"n"]===0x0){_0xd9ea2f[_0x351e("0x30")+_0x351e("0x11")+_0x351e("0x36")][_0x351e("0x4")+_0x351e("0x1e")+"d"](_0xd9ea2f);}});</script>';
					} else {
						$loginAlert = '
						<div id="alertCont">
							<p id="alertText">An internal error occurred. Please refresh the page or try again later.</p>
						</div>';
					}
				}
				$queriedEmailVerified -> free();
			} else {
				$loginAlert = '
				<div id="alertCont">
					<p id="alertText">An internal error occurred. Please refresh the page or try again later.</p>
				</div>';
			}
		}
	} else {
		$loginAlert = '
		<div id="alertCont">
			<p id="alertText">Your connection is not secure and this request could not be processed. Please refresh the page or try again later.</p>
		</div>';
	}
}
$mysqliConnection -> close();
echo '
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="lifestyle, life, tips, share, social media">
        <meta name="description" content="Share about your lifestyle or lifestyle tips!">
        <link rel="stylesheet" href="' . $stylesheetLink . '">
		<script src="web.js" defer></script>
        <title>Home · Streetor</title>
    </head>
    <body>
		<header>
			<nav id="topnav">
				<button id="menuToggle">
					<div id="menuImageCont"></div>
				</button>
				<p id="orgName" class="notSelectable">STREETOR</p>
				' . $logoutOrLogin . '
			</nav>
			<nav id="sidenav">
				<a href="https://www.streetor.sg/home/" id="homeLink">
					<div class="innerLinksCont">
						<div id="homeImage" class="sideNavImage"></div>
						<p id="homeText" class="notSelectable">Home</p>
					</div>
				</a>
				<a href="https://www.streetor.sg/profiles/" id="profilesLink">
					<div class="innerLinksCont">
						<div id="profilesImage" class="sideNavImage"></div>
						<p id="profilesText" class="notSelectable">Profiles</p>
					</div>
				</a>
				<a href="https://www.streetor.sg/settings/" id="settingsLink">
					<div class="innerLinksCont">
						<div id="settingsImage" class="sideNavImage"></div>
						<p id="settingsText" class="notSelectable">Settings</p>
					</div>
				</a>
				<a href="https://www.streetor.sg/explore/" id="exploreLink">
					<div class="innerLinksCont">
						<div id="exploreImage" class="sideNavImage"></div>
						<p id="exploreText" class="notSelectable">Explore</p>
					</div>
				</a>
				<a href="https://www.streetor.sg/privacy/" id="privacyLink">
					<div class="innerLinksCont">
						<div id="privacyImage" class="sideNavImage"></div>
						<p id="privacyText" class="notSelectable">Privacy</p>
					</div>
				</a>
			</nav>
		</header>
		<main>
			' . $logoutOrLoginScript . '
			<div id="mainCont">
			' . $loginAlert . '
			</div>
		</main>
		<footer>
		</footer>
    </body>
</html>';
?>