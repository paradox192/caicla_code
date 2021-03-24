<?php
  // the php code is to get the data from the DB to the charts the js script is the configs for the charts and the js funtion is to change the data of the charts when clicked
  $sql_query="SELECT Material_Name, Weight, Date FROM `weight_history` ORDER BY Month(Date) DESC, Material_Name DESC";
  $result=query($sql_query);//from db connect
  $rows[] = mysqli_num_rows($result);

  /*
  * the loop is to get the array in the right format so json_encode out puts the right format
  * and to calculate the total weight. 
  *format if the chart is clickeble {"Colume_name1":[["Lable1",value1],["Lable2",value2],["Lable3",value3]],"Colume_name2":[["Lable1",value1],["Lable2",value2],["Lable3",value3]]}
  */

  $tot_weight_date[]=0;
  $labels_date;//array that contain the date, that gets used as labels incharts, becomes json in the script 
  $prev_date=NULL;//prev date
  $charts_keys;//key is the div id
  $data;//array to be converted to json for the store var in the script futher down
  $i=0;//traks array index for same colume
  $k=0;//Tacking for tot_weight so same key gets added together  
  
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

$max_cases=max($rows);
?>
<script type="text/javascript">

//var divs = ["myChart","myChart2"];    
var charts_keys = <?php echo json_encode($charts_keys);?>;//

var labels_date = <?php echo json_encode($labels_date);?>;//labels for scale-x
var data_tot_date = <?php echo json_encode($tot_weight_date);?>;//the total weight for series Config_Date 

  zingchart.THEME="classic";
var initState = []; // Used later to store the chart state before changing the data
var store=<?php echo json_encode($data);?>;
var bgColors = ["#1976d2","#424242","#388e3c","#ffa000","#7b1fa2","#c2185b"];
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



var updateChart = function(p){
    initState[p.id] = zingchart.exec(p.id,'getdata'); // Gets the state of the chart when the node was clicked
    var newValues = null;
    var update = null;
    <?php
    //make the switch case dynamic to the columne that are clickable can be as manny and litlle you like
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