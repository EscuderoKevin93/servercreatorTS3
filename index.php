<?php
	date_default_timezone_set('America/Argentina/Buenos_Aires'); //Change Here!
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	include 'data/config.php';
	
	
    $connect = "serverquery://".$USER_QUERY.":".$PASS_QUERY."@".$HOST_QUERY.":".$PORT_QUERY."";
    $ts3 = TeamSpeak3::factory($connect);
	
	if (isset($_POST["create"])) {
		
		$servername = $_POST['servername'];
		$slots = $_POST['slots'];
		$port = $_POST['port'];
		$unixTime = time();
		$realTime = date('[Y-m-d]-[H:i]',$unixTime);

        $create_array = [
            "virtualserver_name" => $servername,
            "virtualserver_maxclients" => $slots,
            "virtualserver_name_phonetic" => $realTime,
            "virtualserver_hostbutton_tooltip" => "My Company",
            "virtualserver_hostbutton_url" => "http://www.example.com",
            "virtualserver_hostbutton_gfx_url" => "http://www.example.com/buttons/button01_24x24.jpg",
        ];
		
		if(!empty($port)) {
            array_merge($create_array, ["virtualserver_port" => $port]);
        }

        $curl = curl_init("https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($curl, CURLOPT_POST, 2);
        curl_setopt($curl, CURLOPT_POSTFIELDS, ['secret' => $GOOGLE_CAPTCHA_PRIVATEKEY, 'response' => $_POST['g-recaptcha-response']]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec ($curl);
        curl_close ($curl);

        if(json_decode($response, TRUE)['success']) {

            try {
                $new_ts3 = $ts3->serverCreate($create_array);

                $token = $new_ts3['token'];
                $createdport = $new_ts3['virtualserver_port'];

            } catch (Exception $e) {
                echo "Error (ID " . $e->getCode() . ") <b>" . $e->getMessage() . "</b>";
            }

        }else {
            die;
        }
		
	}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <meta charset="UTF-8" />
        <title>Simple Server Create</title>
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
        <script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
    <body>
        <div class="container">
            <header>
				<h1>Simple Server<span> Creator</span></h1>
			</header>
            <section>				
                <div id="container_demo" >
                    <div id="wrapper">
                        <div id="login" class="animate form">
							<?php if (isset($_POST["create"])): ?>
								<form  method="post" autocomplete="off"> 
									
									<h1>Server Created!</h1> 
									
									<p> 
										<label  class="uname" data-icon="u" > Server Name</label>
										<input readonly type="text" value="<?php echo $servername; ?>"/>
									</p>
									
									<p> 
										<label  class="uname" data-icon="u" > Token</label>
										<input readonly type="text" value="<?php echo $token; ?>"/>
									</p>
									
									<p> 
										<label  class="uname" data-icon="u" > Server Port</label>
										<input readonly type="text" value="<?=$createdport?>"/>
									</p>
									
								</form>
                            <?php else: ?>
								<form  method="post" autocomplete="off"> 
									<h1>Settings</h1> 
									<p> 
										<label  class="uname" data-icon="u" > Server Name</label>
										<input  name="servername" required="required" type="text" placeholder="Server Name"/>
									</p>
									
									<p> 
										<label class="youpasswd" data-icon="p"> Slots</label>
										<input name="slots" required="required" type="text" placeholder="100" /> 
									</p>
									
									<p> 
										<label class="youpasswd" data-icon="p"> Port</label>
										<input name="port" type="text" placeholder="9987 or blank for random" /> 
									</p>

                                    <div class="g-recaptcha" data-sitekey="<?=$GOOGLE_CAPTCHA_PUBLICKEY?>"></div>
									
									<p class="login button"> 
										<input type="submit" name="create" value="Create!" /> 
									</p>
								</form>
							<?php endif; ?>
						</div>
						
					</div>
				</div>  
			</section>
			<footer>
				<h1>Created By<span> EscuderoKevin</span> For <span> R4P3.NET </span></h1>
			</footer>
		</div>
	</body>
</html>																							