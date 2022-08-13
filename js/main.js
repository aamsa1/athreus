type = ['','info','success','warning','danger'];

demo = {
	initChartlist: function(data) {
		//$("#chartPreferences").html([data.state]);
		/*
        Chartist.Pie('#chartPreferences', {
          labels: data.state,
          series: data.value
        });*/

        var data = {
          labels: data.state,
          series: [data.value]
        };

        var options = {
            seriesBarDistance: 10,
            axisX: {
                showGrid: false
            },
            height: "345px"
        };

        var responsiveOptions = [
          ['screen and (max-width: 640px)', {
            seriesBarDistance: 5,
            axisX: {
              labelInterpolationFnc: function (value) {
                return value[0];
              }
            }
          }]
        ];

        Chartist.Line('#chartActivity', data, options, responsiveOptions);
	}
}
