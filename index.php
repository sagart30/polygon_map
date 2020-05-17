<?php

$jsonData   = file_get_contents("validJson.json");
$arrData    = json_decode($jsonData, true);

$polygon    = array();

foreach($arrData as $key => $val){
    $polygon[] = array($val['lat'], $val['lon']);    
}

function inside($point, $polygonArr, $accuracy = 0) {

    if($accuracy != 0){
        foreach($polygonArr as $key => $val){
            $vs[$key][0] = ($val[0] < 0) ? $val[0] - $accuracy : $val[0] + $accuracy;    
            $vs[$key][1] = ($val[1] < 0) ? $val[1] - $accuracy : $val[1] + $accuracy;    
        }
    }else{
        $vs = $polygonArr;
    }

    $x = $point[0];
    $y = $point[1];

    $inside = false;

    for ($i = 0, $j = count($vs) - 1; $i < count($vs); $j = $i++) {
        $xi = $vs[$i][0];
        $yi = $vs[$i][1];
        $xj = $vs[$j][0];
        $yj = $vs[$j][1];

        $intersect = (($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
        if ($intersect) $inside = !$inside;
    }

    return $inside;
}



if(isset($_REQUEST['latitude']) && isset($_REQUEST['longitude'])){

    $latitude   = trim($_REQUEST['latitude']);
    $longitude  = trim($_REQUEST['longitude']);
    $accuracy   = isset($_REQUEST['accuracy']) ? trim($_REQUEST['accuracy']) : 0;

    if(is_numeric($latitude) && is_numeric($longitude)){
        if(inside(array($latitude, $longitude), $polygon, $accuracy)){
            echo "True : inside origin";
        }else{
            echo "False : outside origin";
        }
    }else{
        echo "Please insert valid input";
    }
}
?>
<html>
	<head><title>Check pointer</title></head>
	<body>
		<form method='post'>
            <table>
                <tr>
                    <td>Latitude</td>
                    <td><input type='text' name='latitude' /></td>
                </tr>
                <tr>
                    <td>Longitude</td>
                    <td><input type='text' name='longitude' /></td>
                </tr>
                <tr>
                    <td>Accuracy</td>
                    <td><input type='text' name='accuracy' value='0' disabled /></td>
                </tr>
                <tr>
                    <td colspan='2'><input type='submit' /></td>
                </tr>
            </table>
		</form>
	</body>
</html>