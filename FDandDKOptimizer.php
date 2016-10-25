
    <html>
    <head>
    <script type ="text/javascript" src ="jquery.js"></script>
    <script>
    function sendData(){
        $("#myForm").submit(function(e){
                            e.preventDefault();
                            })
    }
    </script>
    </head>
    <body>
    <h2>Input DraftKing Or FanDuel Excel File</h2>
    <!--<form action="FDandDKOptimizer.php" method="post" enctype="multipart/form-data">-->
    <form id ="myForm" action="FDandDKOptimizer.php?output=true" method="post" enctype="multipart/form-data">
				<input type="file" name="fileToUpload" id="fileToUpload">
    <select name="contest">
    <option selected disabled>Choose Contest</option>
    <option value="CFLDK">Draft Kings CFL</option>
    <option value="NFLDK">Draft Kings NFL</option>
    <option value="NHLDK">Draft Kings NHL</option>
    <option value="MLBDK">Draft Kings MLB</option>
    <option value="SoccerDK">Draft Kings Soccer</option>
    <option value="GolfDK">Draft Kings Golf</option>
    <option value="NBAFD">Fan Duel NBA</option>
    <option value="NFLFD">Fan Duel NFL</option>
    <option value="NHLFD">Fan Duel NHL</option>
    </select>
				<input type="submit" value="Upload Excel File" name="submit" onclick="sendData()">
    </form>
    </body>
    </html>
<?php
    if($_GET['output']!="true"){
        exit();
    }
    	error_reporting(E_ERROR);
	ini_set('max_execution_time', 0);
	ini_set('auto_detect_line_endings',TRUE);
	
	$target_dir = "DKFDOptimizer/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
    $input;
	if ($_FILES["fileToUpload"]["size"] > 500000) {
    	echo "Sorry, your file is too large.";
    	$uploadOk = 0;
        exit();
	}
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	if($imageFileType != "csv") {
    	echo "Sorry, only CSV files are allowed.";
    	$uploadOk = 0;
        exit();
	}
	if ($uploadOk == 0) {
    	echo "Sorry, your file was not uploaded.";
        exit();
		// if everything is ok, try to upload file
	} else {
    	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        	//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    	} else {
        	echo "Sorry, there was an error uploading your file.";
            exit();
    }
}
    $salarylimit = 0;
    $FD = 0;
	$NFLFD = array(array($QB,array('QB'),array('QB')),array($RB,array('RB'),array('RB1','RB2')),array($WR,array('WR'),array('WR1','WR2','WR3')),array($TE,array('TE'),array('TE')),array($K,array('K'),array('K')),array($D,array('D'),array('D')));
	$CFLDK = array(array($QB,array('QB'),array('QB')),array($RB,array('RB'),array('RB')),array($WR,array('WR'),array('WR1','WR2')),array($Flex,array('WR','RB'),array('Flex1','Flex2')),array($DST,array('DST'),array('DST')));
	$NFLDK = array(array($QB,array('QB'),array('QB')),array($RB,array('RB'),array('RB1','RB2')),array($WR,array('WR'),array('WR1','WR2','WR3')),array($TE,array('TE'),array('TE')),array($Flex,array('QB','RB','WR','TE','DST'),array('K')),array($D,array('DST'),array('DST')));
	$NHLDK = array(array($C,array('C'),array('C1','C2')),array($W,array('WR','LW'),array('W1','W2','W3')),array($D,array('D'),array('D1','D2')),array($G,array('G'),array('G')),array($Util,array('C','LW','WR','D','G'),array('UTIL')));
	$MLBDK = array(array($pitcher,array('SP','RP'),array('P1','P2')),array($catcher,array('C'),array('C')),array($firstbase,array('1B'),array('1B')),array($secondbase,array('2B'),array('2B')),array($shortstop,array('SS'),array('SS')),array($thirdbase,array('3B'),array('3B')),array($outfield,array('OF'),array('OF1','OF2','OF3')));
    $soccer = array(array($pitcher,array('M'),array('M1','M2')),array($catcher,array('F'),array('F1','F2')),array($firstbase,array('D'),array('D1','D2')),array($secondbase,array('GK'),array('GK')),array($shortstop,array('F','GK','D','M'),array('U')));
    $golf = array(array($pitcher,array('G'),array('G1','G2','G3','G4','G5','G6')));
    $NBAFD = array(array($PG,array('PG'),array('PG1','PG2')),array($SG,array('SG'),array('SG1','SG2')),array($SF,array('SF'),array('SF1','SF2')),array($PF,array('PF'),array('PF1','PF2')),array($Center,array('C'),array('C')));
    $NHLFD = array(array($C,array('C'),array('C1','C2')),array($W,array('W'),array('W1','W2','W3','W4')),array($D,array('D'),array('D1','D2')),array($G,array('G'),array('G1')));
    if (isset($_POST['contest'])) {
        $contest = $_POST['contest'];
        if($contest == "CFLDK"){
            $salarylimit = 50000;
            optimizelineup($target_file,$CFLDK);
        }else if ($contest == "NFLDK"){
            $salarylimit = 50000;
            optimizelineup($target_file,$NFLDK);
        }else if ($contest == "NHLDK"){
            $salarylimit = 50000;
            optimizelineup($target_file,$NHLDK);
        }else if ($contest == "MLBDK"){
            $salarylimit = 50000;
            optimizelineup($target_file,$MLBDK);
        }else if ($contest == "SoccerDK"){
            $salarylimit = 50000;
            optimizelineup($target_file,$soccer);
        }else if ($contest == "GolfDK"){
            $salarylimit = 50000;
            optimizelineup($target_file,$golf);
        }else if ($contest == "NBAFD"){
            $FD = 1;
            $salarylimit = 60000;
            optimizelineup($target_file,$NBAFD);
        }else if ($contest == "NFLFD"){
            $FD = 1;
            $salarylimit = 60000;
            optimizelineup($target_file,$NFLFD);
        }else if ($contest == "NHLFD"){
            $FD = 1;
            $salarylimit = 55000;
            optimizelineup($target_file,$NHLFD);
        }else{
            echo "invalid contest";
        }
    }else{
        echo "invalid contest";
    }
    function optimizelineup($file,&$positions){
		$handle = fopen($file, "r");
		while (($line = fgetcsv($handle)) !== FALSE) {
  			$array[] = $line;
		}
		fclose($handler);
		for($k=0; $k<count($positions);$k++){
			for($j=0; $j<count($positions[$k][2]); $j++){
				
				$lineup[$positions[$k][2][$j]] = $j;
			}
		}
		$lineuporiginal = $lineup;
		splitarray($positions,$array);
		computelineup($positions,$lineup);
		
		for($k=0; $k<count($positions);$k++){
			for($j=0; $j<count($positions[$k][2]); $j++){
				$name = $positions[$k][0][$lineup[$positions[$k][2][$j]]]['name'];
				for($x=0; $x<count($positions);$x++){
					for($y=0; $y<count($positions[$x][2]); $y++){
						$name1 = $positions[$x][0][$lineup[$positions[$x][2][$y]]]['name'];
						if(strcmp($name,$name1)===0&&($k!=$x||$j!=$y)){
							if(count($positions[$k][1])>count($positions[$x][1])){
								array_splice($positions[$k][0], $lineup[$positions[$k][2][$j]], 1);
								$lineup = $lineuporiginal;
								computelineup($positions, $lineup);
							}else if(count($positions[$x][1])>count($positions[$k][1])){
								array_splice($positions[$x][0], $lineup[$positions[$x][2][$y]], 1);
								$lineup = $lineuporiginal;
								computelineup($positions, $lineup);
							}else{
								$player = $positions[$k][0][$lineup[$positions[$k][2][$j]]];
								array_splice($positions[$k][0], $lineup[$positions[$k][2][$j]], 1);
								$lineup = $lineuporiginal;
								computelineup($positions, $lineup);
								$points = getpoints($positions,$lineup);
								$lineup1 = $lineup;
								$positions[$k][0][] = $player;
								$player1 = $positions[$x][0][$lineup[$positions[$x][2][$y]]];
								array_splice($positions[$x][0], $lineup[$positions[$x][2][$y]], 1);
								$lineup = $lineuporiginal;
								computelineup($positions, $lineup);
								$points1 = getpoints($positions,$lineup);
								$positions[$x][0][] = $player1;
							if($points > $points1){
								$lineup = $lineup1;
							}else{
							}}
						}
					}	
				}
			}
		}
	printlineup($lineup,$positions);
    unlink($file);
	}
	function splitarray(&$positions,$array){
        global $FD;
		for($i=1; $i< count($array); $i++){
			for($j=0; $j<count($positions);$j++){
				for($k=0; $k<count($positions[$j][1]);$k++){
                    if($FD==1){
					if(strpos($array[$i][1],$positions[$j][1][$k])!==false){
                            $positions[$j][0][] = array(
                                'name' => ($array[$i][2] . ' ' . $array[$i][3]),
                                'positions' => $array[$i][1],
                                'salary' => $array[$i][6],
                                'points' => $array[$i][4],
                                'value' => ($array[$i][4]*1000)/$array[$i][6]
                            );
                        }
                    }else{
                        if (strpos($array[$i][0],$positions[$j][1][$k])!==false){
                            $positions[$j][0][] = array(
                                'name' => $array[$i][1],
                                'positions' => $array[$i][0],
                                'salary' => $array[$i][2],
                                'points' => $array[$i][4],
                                'value' => ($array[$i][4]*1000)/$array[$i][2]
                            );
                        }
					}
				}
			}
		}
	}
	function cmpvalue($a, $b){
    	if ($a['value'] == $b['value']) {
    	    return 0;
    	}
    	return ($a['value'] > $b['value']) ? -1 : 1;
	}
	function cmpincrease($a, $b){
    	if ($a['1'] == $b['1']) {
    	    return 0;
    	}
    	return ($a['1'] > $b['1']) ? -1 : 1;
	}
	function checkname($positions,$name,$lineup,&$increasearray){
		for($k=0; $k<count($positions);$k++){
			for($j=0; $j<count($positions[$k][2]); $j++){
				if(strcmp($name,$positions[$k][0][$lineup[$positions[$k][2][$j]]]['name'])===0){
					return false;
				}
			}
		}
		return true;
	}
	function helper($positions,&$increasearray,&$lineup){
        global $salarylimit;
		$salary = getsalary($positions,$lineup);
		for($i=0; $i<count($increasearray); $i++){
			$newsalary = $salary - $increasearray[$i][3][0][$lineup[$increasearray[$i][2]]]['salary'] + $increasearray[$i][3][0][$increasearray[$i][0]]['salary'];
			if(checkname($positions,$increasearray[$i][4],$lineup,$increasearray)&&$newsalary<$salarylimit&&strcmp($increasearray[$i][3][0][$lineup[$increasearray[$i][2]]]['name'],$increasearray[$i][3][0][$increasearray[$i][0]]['name'])!==0){
				$lineup[$increasearray[$i][2]] = $increasearray[$i][0];
				$increasearray[$i] = findincrease($increasearray[$i][3],$lineup,array_search($increasearray[$i][2],$increasearray[$i][3][2]),$newsalary,$increasearray,$positions);
				usort($increasearray,'cmpincrease');
				break;
			}else {
				$increasearray[$i] = findincrease($increasearray[$i][3],$lineup,array_search($increasearray[$i][2],$increasearray[$i][3][2]),$salary,$increasearray,$positions);
				usort($increasearray,'cmpincrease');
			}
		}
	}
	function getsalary($positions,$lineup){
		$salary = 0;
		for($k=0; $k<count($positions);$k++){
			for($j=0; $j<count($positions[$k][2]); $j++){
				$salary += $positions[$k][0][$lineup[$positions[$k][2][$j]]]['salary'];
			}
		}
		return $salary;
	}
	function getpoints($positions,$lineup){
		$points = 0;
		for($k=0; $k<count($positions);$k++){
			for($j=0; $j<count($positions[$k][2]); $j++){
				$points += $positions[$k][0][$lineup[$positions[$k][2][$j]]]['points'];
			}
		}
		return $points;
	}
	function checkincrease($increasearray){
		for($i=0;$i<count($increasearray);$i++){
			if($increasearray[$i][1]!==0){
				return true;
			}
		}
		return false;
	}
	function printlineup($lineup,$positions){
        $array;
        ?><table style="width:100%">
        <tr>
            <th>Name</th>
            <th>Position</th>
            <th>Salary</th>
            <th>Points</th>
        </tr>
    <?php
		for($k=0; $k<count($positions);$k++){
			for($j=0; $j<count($positions[$k][2]); $j++){
                $array[] = $positions[$k][0][$lineup[$positions[$k][2][$j]]];
                //echo $positions[$k][0][$lineup[$positions[$k][2][$j]]]['name']
                ?>
                <tr>
<td><?php echo $positions[$k][0][$lineup[$positions[$k][2][$j]]]['name']; ?></td>
<td><?php echo $positions[$k][0][$lineup[$positions[$k][2][$j]]]['positions']; ?></td>
<td><?php echo $positions[$k][0][$lineup[$positions[$k][2][$j]]]['salary']; ?></td>
<td><?php echo $positions[$k][0][$lineup[$positions[$k][2][$j]]]['points']; ?></td>
                </tr><?php
				//print_r($positions[$k][0][$lineup[$positions[$k][2][$j]]]);
			}
		}
        ?></table>
<h4>Total Points: <?php echo getpoints($positions,$lineup) ?></h4>
<h4>Total Salary: <?php echo getsalary($positions,$lineup) ?></h4><?php
        //echo json_encode($array);
	}
	function findincrease($position,$lineup,$j,$salary,&$increasearray,$positions){
        global $salarylimit;
		$maxincrease = 0;
		$output = array(0,0,0,0);
		for($i=0; $i<count($position[0]);$i++){
				$increase = ($position[0][$i]['points'] - $position[0][$lineup[$position[2][$j]]]['points'])/($position[0][$i]['salary'] - $position[0][$lineup[$position[2][$j]]]['salary']);
				
				$newsalary = $salary + $position[0][$i]['salary'] - $position[0][$lineup[$position[2][$j]]]['salary'];
				if($newsalary<$salarylimit&&$increase>$maxincrease&&$position[0][$i]['salary']>$position[0][$lineup[$position[2][$j]]]['salary']&&checkname($positions,$position[0][$i]['name'],$lineup,$increasearray,$increase)){//checkname($position,$position[0][$i]['name'],$lineup,$increasearray,$increase)
					$maxincrease = $increase;
					$output[0] = $i;
					$output[1] = $maxincrease;
					$output[2] = $position[2][$j];
					$output[3] = &$position;
					$output[4] = $position[0][$i]['name'];
				}
		}
		return $output;
	}
	function computelineup(&$positions,&$lineup){
		for($k=0; $k<count($positions);$k++){
			usort($positions[$k][0],'cmpvalue');
		}
		for($k=0; $k<count($positions);$k++){
			for($j=0; $j<count($positions[$k][2]); $j++){
				$increasearray[] = findincrease($positions[$k],$lineup,$j,getsalary($positions,$lineup),$increasearray,$positions);
			}
		}
		usort($increasearray,'cmpincrease');
		while(checkincrease($increasearray)){
			helper($positions,$increasearray,$lineup);
		}
	}
?>
