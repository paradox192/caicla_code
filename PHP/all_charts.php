<?php
/*
*This file is mashup of the other chart files into one
* the php code is to get the data from the DB to the charts the js script is the configs for the charts and the js funtion is to change the data of the charts when clicked
*/
  $sql_query="SELECT Weight, Material_Name, Station_Name FROM `station_container_material` AS SCM INNER JOIN smart_station AS SS ON SCM.Station_Id=SS.Station_Id WHERE SS.Customer_Id = 1 ORDER BY Material_Name DESC";
  $result=query($sql_query);//from db connect
  $rows[] = mysqli_num_rows($result);//save the number of rows from all sql requests
  $labels_material;//array that contain the material names, that gets used as labels incharts, becomes json in the script 
  $prev_name=NULL;//track prev name
  $tot_weight_material[]=0;
  
  $charts_keys;//key is the div id
  $data;//array to be converted to json for the store var in the script futher down
  $i=0;//traks array index for same name 
  $k=0;//Tacking for tot_weight so same name gets added together  

  while($info=mysqli_fetch_assoc($result)){
    if($info['Material_Name'] == $prev_name){
        $i++;
    }else{
        $i=0;
        if($prev_name != NULL){
            $tot_weight_material[]=0;
            $k++;
        }
      $prev_name = $info['Material_Name'];  
      $to_replace = $info['Material_Name'];
      $pos=strpos($to_replace,' ');
      if($pos != false){
        $material_name = substr_replace($to_replace,'<br>',$pos,0);
      }else{
        $material_name = $info['Material_Name'];
      }
      $labels_material[]=$material_name;
      $charts_keys['myChart'][] = $info['Material_Name'];
    }
      $data[$info['Material_Name']][$i][]=$info['Station_Name'];
      $data[$info['Material_Name']][$i][]=(float)$info['Weight'];//Forces int
      $tot_weight_material[$k]=round(($tot_weight_material[$k]+(float)$info['Weight']),1);
  }

 $sql_query="SELECT Material_Name, Weight, Date FROM `weight_history` ORDER BY Month(Date) DESC, Material_Name DESC";
  //$sql_query="SELECT Material_Name, Weight, Date FROM `weight_history` ORDER BY Material_Name DESC";
  $result=query($sql_query);//from db connect
  $rows[] = mysqli_num_rows($result);
  $tot_weight_date[]=0;
  $labels_date;
  $prev_date=NULL;
  $k=0;
  while($info=mysqli_fetch_assoc($result)){
      $date = substr($info['Date'],0,-3);//makes a substr with year and month
      if($date == $prev_date){
        $i++;
    }else{
        $i=0;
        if($prev_date != NULL){
        $tot_weight_date[]=0;
        $k++;
      }
      $prev_date = $date; 
      $labels_date[]=$date;
      $charts_keys['myChart2'][] = $date;
    }
      $data[$date][$i][]=$info['Material_Name'];
      $data[$date][$i][]=(float)$info['Weight'];//Forces int
      $tot_weight_date[$k]=round(($tot_weight_date[$k]+(float)$info['Weight']),1);
  }
/*
$sql_query="SELECT Weight, Material_Name, Procent FROM `station_container_material` AS SCM INNER JOIN smart_station AS SS ON SCM.Station_Id=SS.Station_Id WHERE SS.Customer_Id = 1 AND SS.Station_Name='Death Station' ORDER BY Material_Name DESC";
$result=query($sql_query);
$data_procent;//array to be converted to json for data after cliking on a colum
$labels_material;
while($info=mysqli_fetch_assoc($result)){
   // $labels_material[]=$info['Material_Name'];
    $data_procent[]=(int)$info['Procent'];
}
*/
  $max_cases=max($rows);//for switch case to get the lenght for it
  //mysqli_close;
  //print_r($data);
  //print_r($labels);
?>
<script type="text/javascript">
//var divs = ["myChart","myChart2"];    
var charts_keys = <?php echo json_encode($charts_keys);?>;//

var labels_material = <?php echo json_encode($labels_material);?>;//labels for scale-x
var labels_date = <?php echo json_encode($labels_date);?>;//labels for scale-x
var data_tot_material = <?php echo json_encode($tot_weight_material);?>;//the total weight for Config_Material 
var data_tot_date = <?php echo json_encode($tot_weight_date);?>;//the total weight for series Config_Date 
var data_procent = <?php echo json_encode($data_procent);?>;//the procent for Config_Procent 

  zingchart.THEME="classic";
var initState = []; // Used later to store the chart state before changing the data
var store=<?php echo json_encode($data);?>;
var bgColors = ["#1976d2","#424242","#388e3c","#ffa000","#7b1fa2","#c2185b"];
var Config_Material = {
    "type": "bar",
    "background-color": "white",
    "title": {
        "color": "#606060",
        "background-color": "white",
        "text": "Samanlagda vikten för Materialen"
    },
    "subtitle": {
        "color": "#606060",
        "text": "Klicka på kolumenn för att se vikten på materialet för respektive station."
    },

    "scale-x": {
        "values": labels_material,
        "tick": {
            "line-width": 1,
            "line-color": "#C0D0E0"
        },
        "item": {
            "color": "#606060"
        }
    },
    "scale-y":{
    "format":"%vKg"
    },
    "plot": {
        "data-browser": [
      "<span style='font-weight:bold;color:"+bgColors[0]+";'>"+labels_material[0]+"</span>",
      "<span style='font-weight:bold;color:#"+bgColors[1]+";'>"+labels_material[1]+"</span>",
      "<span style='font-weight:bold;color:#"+bgColors[2]+";'>"+labels_material[2]+"</span>",
      "<span style='font-weight:bold;color:"+bgColors[3]+";'>"+labels_material[3]+"</span>",
      "<span style='font-weight:bold;color:"+bgColors[4]+";'>"+labels_material[4]+"</span>",
      "<span style='font-weight:bold;color:#"+bgColors[5]+";'>Unknown</span>"
        ],
    "tooltip": {
      "text": "Totalt %vKg %data-browser",
            "multiple": true,
            "font-size": "12px",
            "color": "#606060",
            "background-color": "white",
            "border-width": 3,
            "alpha": 1,
            "shadow": 0,
        "callout":false,
            "border-radius": 4,
            "padding":8,
        "rules": [
                {
                    "rule": "%i==0",
                    "border-color": bgColors[0]
                },
                {
                    "rule": "%i==1",
                    "border-color": bgColors[1]
                },
                {
                    "rule": "%i==2",
                    "border-color": bgColors[2]
                },
                {
                    "rule": "%i==3",
                    "border-color": bgColors[3]
                },
                {
                    "rule": "%i==4",
                    "border-color": bgColors[4]
                },
                {
                    "rule": "%i==5",
                    "border-color": bgColors[5]
                }
            ]
        },
        "cursor": "hand",
        "animation": {
            "effect": "7"
        },
         "rules": [
                {
                    "rule": "%i==0",
                    "background-color": bgColors[0]
                },
                {
                    "rule": "%i==1",
                    "background-color": bgColors[1]
                },
                {
                    "rule": "%i==2",
                    "background-color": bgColors[2]
                },
                {
                    "rule": "%i==3",
                    "background-color": bgColors[3]
                },
                {
                    "rule": "%i==4",
                    "background-color": bgColors[4]
                },
                {
                    "rule": "%i==5",
                    "background-color": bgColors[5]
                }
            ]
    },
    "series": [
        {
            "values":data_tot_material
        }
    ]
};

var Config_Date = {
    "type": "bar",
    "background-color": "white",
    "title": {
        "color": "#606060",
        "background-color": "white",
        "text": "Samanlagda vikten för materialen per månad"
    },
    "subtitle": {
        "color": "#606060",
        "text": "Klicka på kolumnen för att se vikten per månad på materialet för respektive station."
    },

    "scale-x": {
        "values": labels_date,
        "tick": {
            "line-width": 1,
            "line-color": "#C0D0E0"
        },
        "item": {
            "color": "#606060"
        }
    },
    "scale-y":{
    "format":"%vKg"
    },
    "plot": {
        "data-browser": [
          "<span style='font-weight:bold;color:"+bgColors[0]+";'>"+labels_date[0]+"</span>",
          "<span style='font-weight:bold;color:#"+bgColors[1]+";'>"+labels_date[1]+"</span>",
            "<span style='font-weight:bold;color:#"+bgColors[2]+";'>"+labels_date[2]+"</span>",
            "<span style='font-weight:bold;color:"+bgColors[3]+";'>"+labels_date[3]+"</span>",
            "<span style='font-weight:bold;color:"+bgColors[4]+";'>"+labels_date[4]+"</span>",
            "<span style='font-weight:bold;color:#"+bgColors[5]+";'>Unknown</span>"
        ],
    "tooltip": {
      "text": "Totalt %vKg %data-browser",
            "multiple": true,
            "font-size": "12px",
            "color": "#606060",
            "background-color": "white",
            "border-width": 3,
            "alpha": 1,
            "shadow": 0,
        "callout":false,
            "border-radius": 4,
            "padding":8,
        "rules": [
                {
                    "rule": "%i==0",
                    "border-color": bgColors[0]
                },
                {
                    "rule": "%i==1",
                    "border-color": bgColors[1]
                },
                {
                    "rule": "%i==2",
                    "border-color": bgColors[2]
                },
                {
                    "rule": "%i==3",
                    "border-color": bgColors[3]
                },
                {
                    "rule": "%i==4",
                    "border-color": bgColors[4]
                },
                {
                    "rule": "%i==5",
                    "border-color": bgColors[5]
                }
            ]
        },
        "cursor": "hand",
        "animation": {
            "effect": "7"
        },
         "rules": [
                {
                    "rule": "%i==0",
                    "background-color": bgColors[0]
                },
                {
                    "rule": "%i==1",
                    "background-color": bgColors[1]
                },
                {
                    "rule": "%i==2",
                    "background-color": bgColors[2]
                },
                {
                    "rule": "%i==3",
                    "background-color": bgColors[3]
                },
                {
                    "rule": "%i==4",
                    "background-color": bgColors[4]
                },
                {
                    "rule": "%i==5",
                    "background-color": bgColors[5]
                }
            ]
    },
    "series": [
        {
            "values":data_tot_date
        }
    ]
};

function liveChart(){
  var xmlhttp = new XMLHttpRequest();
  var url = "/prodjects/sims/PHP/live_chart_proc_weight.php";
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          var myArr = JSON.parse(this.responseText);
          var bgColors = ["#1976d2","#424242","#388e3c","#ffa000","#7b1fa2","#c2185b"];
          //var labels_material = myArr['labels'];//the total weight for series    
          var data_procent = myArr['percent'];
          var data_weight = myArr['weight'];
          var Config_Procent = {
            "type": "hbar",
            "background-color": "white",
            "title": {
                "color": "#606060",
                "background-color": "white",
                "text": "Fyllnadsgraden av Material för Death Station"
            },
            "subtitle": {
                "color": "#606060",
                "text": ""
            },

            "scale-x": {
                "values": labels_material,
                "tick": {
                    "line-width": 1,
                    "line-color": "#C0D0E0"
                },
                "item": {
                    "color": "#606060"
                }
            },
            "scale-y":{
                "format":"%v%",
                "values":"0:100:10"
            },
            "plot": {
                "data-browser": [
                    "<span style='font-weight:bold;color:"+bgColors[0]+";'>"+labels_material[0]+"</span>",
                    "<span style='font-weight:bold;color:#"+bgColors[1]+";'>"+labels_material[1]+"</span>",
                    "<span style='font-weight:bold;color:#"+bgColors[2]+";'>"+labels_material[2]+"</span>",
                    "<span style='font-weight:bold;color:"+bgColors[3]+";'>"+labels_material[3]+"</span>",
                    "<span style='font-weight:bold;color:"+bgColors[4]+";'>"+labels_material[4]+"</span>",
                    "<span style='font-weight:bold;color:#"+bgColors[5]+";'>Unknown</span>"
                ],
            "tooltip": {
                "text": "%data-browser är %v% full",
                "multiple": true,
                "font-size": "12px",
                "color": "#606060",
                "background-color": "white",
                "border-width": 3,
                "alpha": 1,
                "shadow": 0,
                "callout":false,
                "border-radius": 4,
                "padding":8,
                "rules": [
                        {
                            "rule": "%i==0",
                            "border-color": bgColors[0]
                        },
                        {
                            "rule": "%i==1",
                            "border-color": bgColors[1]
                        },
                        {
                            "rule": "%i==2",
                            "border-color": bgColors[2]
                        },
                        {
                            "rule": "%i==3",
                            "border-color": bgColors[3]
                        },
                        {
                            "rule": "%i==4",
                            "border-color": bgColors[4]
                        },
                        {
                            "rule": "%i==5",
                            "border-color": bgColors[5]
                        }
                    ]
                },
                "cursor": "hand",
                 "rules": [
                        {
                            "rule": "%i==0",
                            "background-color": bgColors[0]
                        },
                        {
                            "rule": "%i==1",
                            "background-color": bgColors[1]
                        },
                        {
                            "rule": "%i==2",
                            "background-color": bgColors[2]
                        },
                        {
                            "rule": "%i==3",
                            "background-color": bgColors[3]
                        },
                        {
                            "rule": "%i==4",
                            "background-color": bgColors[4]
                        },
                        {
                            "rule": "%i==5",
                            "background-color": bgColors[5]
                        },
                        {
                            "rule": "%v>85",
                            "background-color": "red"
                        }
                    ]
            },
            "series": [
                {
                    "values":data_procent
                }
            ]
          };

          var Config_Weight = {
            "type": "hbar",
            "background-color": "white",
            "title": {
                "color": "#606060",
                "background-color": "white",
                "text": "Vikten av Material för Death Station"
            },
            "subtitle": {
                "color": "#606060",
                "text": ""
            },

            "scale-x": {
                "values": labels_material,
                "tick": {
                    "line-width": 1,
                    "line-color": "#C0D0E0"
                },
                "item": {
                    "color": "#606060"
                }
            },
            "scale-y":{
                "format":"%vKg",
            },
            "plot": {
                "data-browser": [
                    "<span style='font-weight:bold;color:"+bgColors[0]+";'>"+labels_material[0]+"</span>",
                    "<span style='font-weight:bold;color:#"+bgColors[1]+";'>"+labels_material[1]+"</span>",
                    "<span style='font-weight:bold;color:#"+bgColors[2]+";'>"+labels_material[2]+"</span>",
                    "<span style='font-weight:bold;color:"+bgColors[3]+";'>"+labels_material[3]+"</span>",
                    "<span style='font-weight:bold;color:"+bgColors[4]+";'>"+labels_material[4]+"</span>",
                    "<span style='font-weight:bold;color:#"+bgColors[5]+";'>Unknown</span>"
                ],
            "tooltip": {
                "text": "%data-browser väger %vKg",
                "multiple": true,
                "font-size": "12px",
                "color": "#606060",
                "background-color": "white",
                "border-width": 3,
                "alpha": 1,
                "shadow": 0,
                "callout":false,
                "border-radius": 4,
                "padding":8,
                "rules": [
                        {
                            "rule": "%i==0",
                            "border-color": bgColors[0]
                        },
                        {
                            "rule": "%i==1",
                            "border-color": bgColors[1]
                        },
                        {
                            "rule": "%i==2",
                            "border-color": bgColors[2]
                        },
                        {
                            "rule": "%i==3",
                            "border-color": bgColors[3]
                        },
                        {
                            "rule": "%i==4",
                            "border-color": bgColors[4]
                        },
                        {
                            "rule": "%i==5",
                            "border-color": bgColors[5]
                        }
                    ]
                },
                "cursor": "hand",
                 "rules": [
                        {
                            "rule": "%i==0",
                            "background-color": bgColors[0]
                        },
                        {
                            "rule": "%i==1",
                            "background-color": bgColors[1]
                        },
                        {
                            "rule": "%i==2",
                            "background-color": bgColors[2]
                        },
                        {
                            "rule": "%i==3",
                            "background-color": bgColors[3]
                        },
                        {
                            "rule": "%i==4",
                            "background-color": bgColors[4]
                        },
                        {
                            "rule": "%i==5",
                            "background-color": bgColors[5]
                        },
                    ]
            },
            "series": [
                {
                    "values":data_weight
                }
            ]
          };
          
          zingchart.render({
            id : 'myChart3', 
            data : Config_Procent,
          });
          zingchart.render({
            id : 'myChart4', 
            data : Config_Weight,
          });
          setTimeout("liveChart();",1000);   
      }
  };
  xmlhttp.open("GET", url, true);
  xmlhttp.send();
}
window.onload=liveChart;

var updateChart = function(p){
  initState[p.id] = zingchart.exec(p.id,'getdata'); // Gets the state of the chart when the node was clicked
  var newValues = null;
  var update = null;
  <?php
  $case_nr=0;
  echo'switch(p.nodeindex){';
    while ($case_nr <= $max_cases) {
      echo'case '.$case_nr.':';
        echo'newValues = store[charts_keys[p.id]['.$case_nr.']];';
        echo'update = true;';
        echo'break;';
        $case_nr++; 
    }
    echo'case'.$case_nr.':';
      echo'update = true;';
      echo'break;';
  echo'}';
  ?>
  
  if(update){
    if (p.id == 'myChart') {
    data = {
      "plot":{
      "cursor":null,
      "rules":[],
      "background-color": bgColors[p.nodeindex],
      "tooltip": {
        "text":"%vKg av totalen",
          "rules":[],
          "border-color": bgColors[p.nodeindex]
      }
        },
        "scale-x":{
        "values":[]
        }
      }
    }else{
    data = {
        "plot":{
         "cursor":null,
          "tooltip": {
           "text":"%vKg av totalen"
          }
        },
        "scale-x":{
          "values":[]
        }
      }
    }
    zingchart.unbind(p.id, 'node_click'); // Disable node_click for second level
    zingchart.exec(p.id, 'modify', {
      update:false, // Making multiple changes, queue these changes
      data
    });
    zingchart.exec(p.id, 'setseriesvalues',{ // Replaces all values at plotindex 0
      update:false, // Queue these, too
      plotindex:0,
      values:newValues
    });
    zingchart.exec(p.id,'update'); // Push queued changes
    zingchart.bind(p.id, 'animation_end', function(){ // When the animation ends...
      zingchart.exec(p.id, 'addobject',{ // ...add a back button
        type:'shape',
        data:{
          "type":"rectangle",
          "id":"back_btn",
          "height":20,
          "width":70,
          "background-color":"#ffffff #f6f6f6",
          "x":"80%",
          "y":"16%",
          "border-width":1,
          "border-color":"#888",
          "cursor":"hand",
          "label":{
            "text":"< Back",
            "color": "#606060"
          },
          "hover-state":{
            "background-color":"#1976D2 #ffffff",
            "border-color":"#57a2ff",
            "fill-angle": -180
          }
        }
      });
    });
  }
};

zingchart.render({
  id : 'myChart', 
  data : Config_Material,
});

zingchart.bind('myChart','node_click',updateChart);

zingchart.bind('myChart', 'shape_click', function(p){ // Listen for back button click
  zingchart.unbind(p.id,'animation_end');
  if (p.shapeid == "back_btn"){
    zingchart.exec(p.id,'setdata',{ // Set the data back to the state it was in when the node was clicked
      data:initState[p.id]
    });
    zingchart.bind(p.id,'node_click',updateChart);
  }
});

zingchart.render({
  id : 'myChart2', 
  data : Config_Date,
});

zingchart.bind('myChart2','node_click',updateChart);

zingchart.bind('myChart2', 'shape_click', function(p){ // Listen for back button click
  zingchart.unbind(p.id,'animation_end');
  if (p.shapeid == "back_btn"){
    zingchart.exec(p.id,'setdata',{ // Set the data back to the state it was in when the node was clicked
      data:initState[p.id]
    });
    zingchart.bind(p.id,'node_click',updateChart);
  }
});
</script>