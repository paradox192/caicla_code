//bad script for live chart, needs a new way to live update chart by not have a infinity funtion call itself every second
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